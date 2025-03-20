<?php 
use yii\helpers\Url;
use app\models\Batch;
use app\models\Student;
use app\models\StudentMapping;
use app\models\BatDegReg;
use app\models\CoeBatDegReg;
use app\models\Regulation;
use app\models\Categorytype;
use app\models\Degree;
use app\models\Subjects;
use app\models\SubjectsMapping;
use app\models\Programme;
use yii\db\Query;
use app\models\MarkEntry;
use app\models\MarkEntryMaster;
use app\models\MarkEntryMasterTemp;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

$interate = 1; // Check only 1 time for Sheet Columns
foreach($sheetData as $k => $line)
{ 
    $exam_columns=['A'=>'YEAR','B'=>'MONTH','C'=>'MARK TYPE','D'=>'TERM','E'=>'SEMESTER','F'=>'SUBJECT CODE','G'=>'REGISTER NUMBER','H'=>'ESE 100'];

    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F'],'G'=>$line['G'],'H'=>$line['H']];

    $mis_match=array_diff_assoc($exam_columns,$template_clumns);
    if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
    {
        $misMatchingColumns = '';
        foreach ($mis_match as $key => $value) {
            $misMatchingColumns .= $key.", ";
        }
        $misMatchingColumns = trim($misMatchingColumns,', ');
        $misMatchingColumns = wordwrap($misMatchingColumns, 10, "<br />\n");
        Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Template </b> Please use the Original Sample Template from the Download Link!!");
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

$det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
foreach($sheetData as $k => $line)
{    

    $line = array_map('trim', $line);
    $subjects = new Subjects();
    $MarkEntry = new MarkEntry();
   
    $subject_mapping = new SubjectsMapping();

    $year = isset($line['A'])?$line['A']:""; 
    
    $month = isset($line['B'])?$this->valueReplace($line['B'], Categorytype::getCategoryId()):"";

    $mark_type = isset($line['C'])?$this->valueReplace($line['C'], Categorytype::getCategoryId()):"";

     $term = isset($line['D'])?$this->valueReplace($line['D'], Categorytype::getCategoryId()):"";
    $subject_code = isset($line['F'])?Subjects::find()->where(['subject_code'=>$line['F']])->all():"";
    $reg_num_12 = isset($line['G'])?Student::find()->where(['register_number'=>$line['G'],'student_status'=>'Active'])->one():"";
    $reg_num_stu = '';
    if(isset($reg_num_12) && !empty($reg_num_12))
    { 
        $stu_map_id_che = Yii::$app->db->createCommand('SELECT * FROM coe_student_mapping WHERE status_category_type_id NOT IN ("'.$det_disc_type.'") and student_rel_id="'.$reg_num_12->coe_student_id.'" ')->queryOne();

        if(!empty($stu_map_id_che))
        {
          $reg_num_stu = StudentMapping::find()->where(['student_rel_id'=>$reg_num_12['coe_student_id']])->one();
          $reg_num = $line['G'];
        }
    }
    if(!empty($year) && !empty($month) && !empty($mark_type) && !empty($reg_num_stu) && !empty($subject_code) && !in_array(null, $line, true))
    { 
        $inserted_res = 0;
        $student = Student::find()->where(['register_number'=>$reg_num,'student_status'=>'Active'])->one();
        $stu_mapping = StudentMapping::find()->where(['student_rel_id'=>$student->coe_student_id])->one();
        $batchMapping = CoeBatDegReg::findOne($stu_mapping->course_batch_mapping_id);
        $student_map_id = $stu_mapping->coe_student_mapping_id;

        $sem_verify = ConfigUtilities::SemCaluclation($year,$month,$stu_mapping->course_batch_mapping_id);
       
        if($sem_verify==0)
        {

        }
        else
        {
            $subject_map_id = '';
            if(count($subject_code>0))
            {
                foreach ($subject_code as $sub_map) 
                {
                    $sub_map_id_get = SubjectsMapping::find()->where(['subject_id'=>$sub_map['coe_subjects_id'],'batch_mapping_id'=>$stu_mapping->course_batch_mapping_id,'semester'=>$line['E']])->one();
                    if(!empty($sub_map_id_get))
                    {
                        $subject_map_id = $sub_map_id_get['coe_subjects_mapping_id'];
                        break;
                    }
                }
                
            }
            else
            {
                $subject_map_id = $this->valueReplace($line['F'], SubjectsMapping::getSubjectMappingId($stu_mapping->course_batch_mapping_id,$line['E']));
            }
            
            $check_mark_entrytemp = MarkEntryMasterTemp::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'year'=>$year,'month'=>$month,'mark_type'=>$mark_type,'term'=>$term,])->one();
           
            if(!empty($check_mark_entrytemp))
            {  
                
                if((!empty($line['H']) && $line['H']!=0))
                {
                    
                    $check_mark_entry = MarkEntry::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'mark_type'=>$mark_type,'category_type_id'=>46,'term'=>$term,'year'=>$year,'month'=>$month])->one();
                    $subject_code1 = isset($line['F'])?Subjects::find()->where(['subject_code'=>$line['F']])->one():"";
                     $convert_ese=round($line['H']*($subject_code1->ESE_max/100));

                    $stu_result_data = ConfigUtilities::StudentResult($student_map_id,$subject_map_id,$check_mark_entry->category_type_id_marks,$line['H'],$year,$month);

                    if(empty($check_mark_entry) && $check_mark_entry->category_type_id_marks=='')
                    {                         
                        $status =0;   
                        $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'CIA Mark Not Available']);
                    }
                    else if(!empty($check_mark_entry) && count($check_mark_entry)>0)
                    {   
                        $created_by = Yii::$app->user->getId();
                         $created_at = date("Y-m-d H:i:s");
                        $MarkEntryMaster = MarkEntryMasterTemp::findOne($check_mark_entrytemp['coe_mark_entry_master_id']);
                        $year_of_passing =  $month. "-" . $year;
                        $MarkEntryMaster->student_map_id = $student_map_id;
                        $MarkEntryMaster->subject_map_id = $subject_map_id;
                        $MarkEntryMaster->CIA = $check_mark_entry->category_type_id_marks;
                        $MarkEntryMaster->ESE = $convert_ese;
                        $MarkEntryMaster->total = ($convert_ese+$check_mark_entry->category_type_id_marks);
                        $MarkEntryMaster->result = $stu_result_data['result'];
                        $MarkEntryMaster->grade_point = $stu_result_data['grade_point'];
                        $MarkEntryMaster->grade_name = $stu_result_data['grade_name'];
                        $MarkEntryMaster->year = $year;
                        $MarkEntryMaster->month = $month; 
                        $MarkEntryMaster->term = $term;
                        $MarkEntryMaster->mark_type = $mark_type;
                        $MarkEntryMaster->status_id = 0; // For Data Migration only
                        $MarkEntryMaster->year_of_passing = $stu_result_data['attempt']; 
                        $MarkEntryMaster->attempt = $year_of_passing;
                        $MarkEntryMaster->updated_by = $created_by;
                        $MarkEntryMaster->updated_at = $created_at;                              
                       
                        if($MarkEntryMaster->save(false))
                        {
                            $totalSuccess+=1;
                            $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Successfully Updated']);
                        }
                        else
                        {
                            $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Something Wrong Contact SKIT']);
                        }
                               
                           
                    }
                }
                else
                {
                   
                    $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Wrong Entry']);
                }                   
            }
            else
            {
                 $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'NO DATA FOUND IN TEMP TABLE']);
                
            }
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


$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>'Master Grade Import Update'];
?>