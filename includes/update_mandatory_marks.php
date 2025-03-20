<?php 
use yii\helpers\Url;
use app\models\Batch;
use app\models\Student;
use app\models\StudentMapping;
use app\models\BatDegReg;
use app\models\CoeBatDegReg;
use app\models\Categorytype;
use app\models\MandatoryStuMarks;
use app\models\MandatorySubjects;
use app\models\MandatorySubcatSubjects;
use app\models\Degree;
use app\models\Subjects;
use app\models\Regulation;
use app\models\SubjectsMapping;
use app\models\Programme;
use yii\db\Query;
use app\models\MarkEntry;
use app\models\MarkEntryMaster;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

$interate = 1; // Check only 1 time for Sheet Columns

foreach($sheetData as $k => $line)
{ 
    $exam_columns=['A'=>'YEAR','B'=>'MONTH','C'=>'MARK TYPE','D'=>'TERM','E'=>'SEMESTER','F'=>'SUBJECT CODE','G'=>'SUB CATEGORY CODE', 'I'=>'REGISTER NUMBER','I'=>'CIA (OUT OF MAXIMUM)'];

    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F'],'G'=>$line['G'],'H'=>$line['H'],'I'=>$line['I']];

    $mis_match=array_diff_assoc($exam_columns,$template_clumns);
    if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
    {
        $misMatchingColumns = '';
        foreach ($mis_match as $key => $value) {
            $misMatchingColumns .= $key.", ";
        }
        $misMatchingColumns = trim($misMatchingColumns,', ');
        $misMatchingColumns = wordwrap($misMatchingColumns, 10, "<br />\n");
        Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original Template </b> Please use the Original Sample Template from the Download Link!!");
        return Yii::$app->response->redirect(Url::to(['import/index']));
    }
    else
    {
        break;
    }
    $interate +=7;
    
}
unset($sheetData[1]);
$transaction = Yii::$app->db->beginTransaction();
$det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
$regulAr = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Regular%'")->queryScalar();
$det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
foreach($sheetData as $k => $line)
{
    $line = array_map('trim', $line);
    $subjects = new MandatorySubjects();
    $MarkEntry = new MarkEntry();
    $MarkEntryMaster = new MarkEntryMaster();
    $subject_mapping = new MandatorySubcatSubjects();

    $year = isset($line['A'])?$line['A']:""; 
    $month = isset($line['B'])?$this->valueReplace($line['B'], Categorytype::getCategoryId()):"";
    $mark_type = isset($line['C'])?$this->valueReplace($line['C'], Categorytype::getCategoryId()):"";
    $term = isset($line['D'])?$this->valueReplace($line['D'], Categorytype::getCategoryId()):"";
    $reg_num_12 = isset($line['H'])?Student::find()->where(['register_number'=>$line['H']])->one():"";
    $reg_num_stu = '';
    if(!empty($reg_num_12))
    {
        $stu_map_id_che = Yii::$app->db->createCommand('SELECT * FROM coe_student_mapping where student_rel_id="'.$reg_num_12->coe_student_id.'" ')->queryOne();
        if(!empty($stu_map_id_che))
        {
          $reg_num_stu = StudentMapping::find()->where(['student_rel_id'=>$reg_num_12['coe_student_id']])->one();
          $reg_num = $line['H'];
        }
    }

    $subject_code = isset($line['F']) && !empty($reg_num_stu) ?MandatorySubjects::find()->where(['subject_code'=>$line['F'],'batch_mapping_id'=>$reg_num_stu->course_batch_mapping_id,'semester'=>$line['E']])->one():"";
    
    if(isset($line['G']))
    {
        $string_lenght = strlen($line['G']);
        if($string_lenght<=1)
        {
            $sub_cat_code = '00'.$line['G'];
        }
        else if($string_lenght>1 && $string_lenght<=2)
        {
            $sub_cat_code = '0'.$line['G'];
        }
        else
        {
            $sub_cat_code = $line['G'];
        }
    }
   
    $subject_cat_code = isset($line['G']) && !empty($subject_code) && !empty($reg_num_stu) ?MandatorySubcatSubjects::find()->where(['sub_cat_code'=>$sub_cat_code,'man_subject_id'=>$subject_code->coe_mandatory_subjects_id,'batch_map_id'=>$reg_num_stu->course_batch_mapping_id])->all():"";
   
    if(!empty($year) && !empty($month) && !empty($mark_type) && !empty($reg_num_stu) && !empty($subject_code) && !empty($subject_cat_code) && !in_array(null, $line, true))
    {      
        $inserted_res = 0;
        $student = Student::find()->where(['register_number'=>$reg_num])->one();
        $stu_mapping = StudentMapping::find()->where(['student_rel_id'=>$student->coe_student_id])->one();
        $batchMapping = CoeBatDegReg::findOne($stu_mapping->course_batch_mapping_id);

        $subject_cat_code = MandatorySubcatSubjects::find()->where(['sub_cat_code'=>$sub_cat_code,'man_subject_id'=>$subject_code->coe_mandatory_subjects_id,'batch_map_id'=>$batchMapping->coe_bat_deg_reg_id,'coe_batch_id'=>$batchMapping->coe_batch_id])->one();

        $student_map_id = $stu_mapping->coe_student_mapping_id;
        $subject_map_id = $subject_cat_code['coe_mandatory_subcat_subjects_id'];        
        $get_sub_info = MandatorySubjects::find()->where(['subject_code'=>$line['F'],'batch_mapping_id'=>$stu_mapping->course_batch_mapping_id,'semester'=>$line['E'],'man_batch_id'=>$batchMapping->coe_batch_id])->one();

        $check_cia_marks = MandatoryStuMarks::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'mark_type'=>$mark_type,'year'=>$year,'month'=>$month])->one();
        
        $condition_check = 0;
        if(!empty($check_cia_marks) && !empty($line['I']) && $line['I']<=$get_sub_info['CIA_max'])
        {   
            
            $ciaMArks = $line['I']<=0 ? '0': $line['I'];

            $total_marks = $ciaMArks ; 
            $grade_details = Regulation::find()->where(['regulation_year'=>$batchMapping->regulation_year])->all();
            $stu_attempet =  MandatoryStuMarks::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id])->all();
            $stuAttemop = count($stu_attempet);

            foreach ($grade_details as $value) 
            {
                if(!empty($value['grade_point_to']))
                {
                     if($total_marks >= $value['grade_point_from'] &&  $total_marks <= $value['grade_point_to'] )
                      {
                          if($total_marks<$get_sub_info['total_minimum_pass'])
                          {                    
                            $student_res_data = ['result'=>'Fail','total_marks'=>$total_marks,'grade_name'=>"RA",'grade_point'=>'0','year_of_passing'=>''];        
                          }      
                          else
                          {
                            $student_res_data = ['result'=>'Pass','total_marks'=>$total_marks,'grade_name'=>$value['grade_name'],'grade_point'=>$value['grade_point'],'year_of_passing'=>$month."-".$year];                    
                          }
                      }
                }
            } 
            $connection = Yii::$app->db;
            $getUpdate = "Update coe_mandatory_stu_marks set CIA='".$ciaMArks."', total='".$student_res_data['total_marks']."', result='".$student_res_data['result']."',grade_point='".$student_res_data['grade_point']."', grade_name='".$student_res_data['grade_name']."',year_of_passing='".$student_res_data['year_of_passing']."' where  coe_mandatory_stu_marks_id='".$check_cia_marks['coe_mandatory_stu_marks_id']."'";      

           if($connection->createCommand($getUpdate)->execute() )
           {
                $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Record Updated Successfully!!!']);
           }
           else{
                $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Unable to Update record']);
           }
                              
        }
        else
        {
            $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'No Data Found or Maximum Crossed']);
        }
    } // Not empty of Batch & Other related ids
    else
    {                    
        $dispResults[] = array_merge($line, 
            ['type' => 'E',  'message' => 'Data submision is worng']);
        Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to fetch the data with your submision.");
    }           
} // Foreach Ends Here  
try
{
    $transaction->commit();
}
catch(\Exception $e)
{
   if($e->getCode()=='23000')
   {
       $message = "Duplicate Entry";
   }
   else
   {
      $transaction->rollback(); 
       $message = "Something Wrong";
   }
   $dispResults[] = array_merge($line, ['type' => 'E',  'message' => $message]);
}
$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>'Mandatory Marks'];
?>