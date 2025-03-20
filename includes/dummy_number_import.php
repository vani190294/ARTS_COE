<?php 
use yii\helpers\Url;
use app\models\Batch;
use app\models\Student;
use app\models\AbsentEntry;
use app\models\ExamTimetable;
use app\models\StudentMapping;
use app\models\BatDegReg;
use app\models\Categorytype;
use app\models\Degree;
use app\models\Subjects;
use app\models\Nominal;
use app\models\DummyNumbers;
use app\models\StoreDummyMapping;
use app\models\SubjectsMapping;
use app\models\Programme;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

$interate = 1; // Check only 1 time for Sheet Columns

foreach($sheetData as $k => $line)
{    
     $subject_columns=['A'=>'Year','B'=>'Month','C'=>'Subject Code','D'=>'Register Number','E'=>'Dummy Number'];

      $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E']];

        $mis_match=array_diff_assoc($subject_columns,$template_clumns);

        if($interate==1 && count($mis_match)!=0 && count($mis_match)>0)
        {
            $misMatchingColumns = '';
            foreach ($mis_match as $key => $value) {
                $misMatchingColumns .= $key.", ";
            }
            
            $misMatchingColumns = trim($misMatchingColumns,', ');
            $misMatchingColumns = wordwrap($misMatchingColumns, 40, "<br />\n");
            Yii::$app->ShowFlashMessages->setMsg('Error',"<b style='font-size: 17px;'> Column (s) ".$misMatchingColumns." Are Not matching with our Original ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL)." Template </b> Please use the Original Sample Template from the Download Link!!");
            return Yii::$app->response->redirect(Url::to(['import/index']));
        }
        else
        {
            break;
        }
    $interate +=5;        
}

unset($sheetData[1]);
$det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

$det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
$transaction = Yii::$app->db->beginTransaction();
$elective_id_get = Categorytype::find()->where(['description'=>'Elective'])->orWhere(['category_type'=>'Elective'])->one();
foreach($sheetData as $k => $line)
{    
    $subject_mapping_id = $stu_map_id = $reg_dum_num = '';
    $line = array_map('trim', $line);
    $dummyNumber = new DummyNumbers();  
    $reg_dum_num = isset($line['E'])?$line['E']:"";
    $stu_data = isset($line['D'])?Student::find()->where(['register_number'=>$line['D']])->one():"";
    $stu_map_id = '';
    if(!empty($stu_data))
    {   
        $stu_map_id_che = Yii::$app->db->createCommand('SELECT * FROM coe_student_mapping WHERE status_category_type_id NOT IN ("'.$det_disc_type.'") and student_rel_id="'.$stu_data->coe_student_id.'" ')->queryOne();
        if(!empty($stu_map_id_che))
        {
          $stu_map_id = StudentMapping::find()->where(['student_rel_id'=>$stu_data['coe_student_id']])->one();
        }
    }
    $subject_mapping_id = array_filter([]);
    $is_nominal = 1;
    $subject = isset($line['C'])?Subjects::find()->where(['subject_code'=>$line['C']])->one():"";  
    if(!empty($subject) && !empty($stu_map_id))
    {
        $month_id_get=0;
        if(!empty($line['B']))
        {
            $month_id_get = $this->valueReplace($line['B'], Categorytype::getCategoryId());
        }
        $getExam_dertails = Yii::$app->db->createCommand('SELECT A.* FROM coe_subjects_mapping as A Join coe_exam_timetable as B ON B.subject_mapping_id=A.coe_subjects_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=A.subject_id WHERE C.subject_code="'.$line['C'].'" and A.subject_id="'.$subject['coe_subjects_id'].'" and B.exam_year="'.$line['A'].'" and B.exam_month="'.$month_id_get.'" and A.batch_mapping_id="'.$stu_map_id->course_batch_mapping_id.'"')->queryOne();
          if(!empty($getExam_dertails))
          {
            $subject_mapping_id = SubjectsMapping::findOne($getExam_dertails['coe_subjects_mapping_id']); 
            
            if($subject_mapping_id->subject_type_id==$elective_id_get['coe_category_type_id'])
            {
              $getNominal = Nominal::find()->where(['coe_student_id'=>$stu_data['coe_student_id'],'coe_subjects_id'=>$subject['coe_subjects_id'],'semester'=>$subject_mapping_id->semester])->one();
              if(empty($getNominal))
              {
                $is_nominal = 0;
              }
            }
            else
            {
              $is_nominal = 1;
            }
          }
        
    }
    if($is_nominal==1 && !empty($subject_mapping_id) &&  !empty($stu_map_id) && !empty($reg_dum_num) && !in_array(null, $line, true))
    {     
            $month_id = $this->valueReplace($line['B'], Categorytype::getCategoryId());

            $check_dum = DummyNumbers::find()->where(['year'=>$line['A'],'month'=>$month_id,'dummy_number'=>$reg_dum_num])->all();
            
            if(empty($check_dum))
            {
                $dummyNumber->student_map_id = $stu_map_id->coe_student_mapping_id;
                $dummyNumber->subject_map_id = $subject_mapping_id->coe_subjects_mapping_id;
                $dummyNumber->dummy_number = $reg_dum_num;
                $dummyNumber->year = $line['A'];
                $dummyNumber->month = $month_id;
                $dummyNumber->created_by = $created_by;
                $dummyNumber->updated_by = $created_by;
                $dummyNumber->created_at = $created_at;
                $dummyNumber->updated_at = $created_at;

                $get_exam_details = ExamTimetable::find()->where(['exam_year'=>$line['A'],'exam_month'=>$month_id,'subject_mapping_id'=>$subject_mapping_id->coe_subjects_mapping_id])->one();
                $check_stu_absent = array_filter([]);
               if(!empty($get_exam_details))
               {
                   $check_stu_absent = AbsentEntry::find()->where(['absent_student_reg'=>$stu_map_id->coe_student_mapping_id,'exam_subject_id'=>$subject_mapping_id->coe_subjects_mapping_id,'exam_year'=>$line['A'],'exam_month'=>$month_id,'exam_type'=>$get_exam_details->exam_type,'absent_term'=>$get_exam_details->exam_term])->one();
               }
                $getStroreInfo = StoreDummyMapping::find()->where(['year'=>$line['A'],'month'=>$month_id,'subject_map_id'=>$subject_mapping_id->coe_subjects_mapping_id])->one();
	             


                if(empty($check_stu_absent) && isset($getStroreInfo) && $reg_dum_num >= $getStroreInfo->dummy_from && $reg_dum_num <= $getStroreInfo->dummy_to && !empty($get_exam_details) )
                {
                  $check_dum_assign = DummyNumbers::find()->where(['year'=>$line['A'],'month'=>$month_id,'student_map_id'=>$stu_map_id->coe_student_mapping_id,'subject_map_id'=>$subject_mapping_id->coe_subjects_mapping_id])->all();

                  if(empty($check_dum_assign))
                  {
                       if($dummyNumber->save(false))
                       {  
                                       
                         $totalSuccess+=1;
                         $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Success']);
                                             
                       }
                       else
                       {  
                            $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Sequence is Missing in Template']);
                       }
                  }
                  else
                  {
                     $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Already '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Assigned']);
                  }
                  
                }
        				else if(empty($getStroreInfo))
        				{
        					$dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'No Sequence Found']);
        				}
                else
                {
                    $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Wrong Entry']);
                }
                
          }
          else
          {
            $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Duplicate Number']);
          }
    } // Not empty of Batch & Other related ids
    else
    {                    
        $dispResults[] = array_merge($line, 
            ['type' => 'E',  'message' => 'No Data Found']);
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
       $message = "Error";
   }
   $transaction->rollback(); 
   $dispResults[] = array_merge($line, ['type' => 'E',  'message' => $message]);
}

$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)];
?>