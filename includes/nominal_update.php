<?php 
use yii\helpers\Url;
use app\models\Batch;
use app\models\Student;
use app\models\StudentMapping;
use app\models\BatDegReg;
use app\models\Categorytype;
use app\models\MarkEntryMaster;
use app\models\ExamTimeTable;
use app\models\HallAllocate;
use app\models\MarkEntry;
use app\models\Degree;
use app\models\Subjects;
use app\models\Nominal;
use app\models\SubjectsMapping;
use app\models\Programme;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

$interate = 1; // Check only 1 time for Sheet Columns

foreach($sheetData as $k => $line)
{    
     $subject_columns=['A'=>'Register Number','B'=>'Semester','C'=>'Prev Subject Code','D'=>'New Subject Code'];

        $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D']];

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
foreach($sheetData as $k => $line)
{    
    $created_at = date("Y-m-d H:i:s");
    $createdBy = Yii::$app->user->getId(); 
    $line = array_map('trim', $line);
    $nominal = new Nominal();    
    $stu_data = isset($line['A'])?Student::findOne(['register_number'=>$line['A']]):"";
    $semester = isset($line['B'])?$line['B']:"";
    $sub_code_1 = isset($line['C'])?Subjects::findOne(['subject_code'=>$line['C']]):"";
    $sub_code_2 = isset($line['D'])?Subjects::findOne(['subject_code'=>$line['D']]):"";
    $year = date('Y');
    $connection = Yii::$app->db;
    if(!empty($stu_data) &&  !empty($semester) &&  !empty($sub_code_1) &&  !empty($sub_code_2) && !in_array(null, $line, true))
    { 
        $stuMapp = StudentMapping::findOne(['student_rel_id'=>$stu_data->coe_student_id]);        
        $subjects_1 = SubjectsMapping::find()->where(['batch_mapping_id'=>$stuMapp->course_batch_mapping_id,'semester'=>$line['B'],'subject_id'=>$sub_code_1->coe_subjects_id])->one();
        $subjects_2 = SubjectsMapping::find()->where(['batch_mapping_id'=>$stuMapp->course_batch_mapping_id,'semester'=>$line['B'],'subject_id'=>$sub_code_2->coe_subjects_id])->one();
        
        $check_nominal = [];
        $check_nominal = array_filter($check_nominal);
        if(!empty($subjects_1))
        {
            $check_nominal = Nominal::find()->where(['coe_student_id'=>$stu_data->coe_student_id,'coe_subjects_id'=>$subjects_1->subject_id,'semester'=>$line['B']])->one();
        }
        
        if(!empty($stuMapp)  && !empty($subjects_1) && !empty($subjects_2) && !empty($check_nominal))
        {
            $check_int_marks = MarkEntry::find()->where(['student_map_id'=>$stuMapp->coe_student_mapping_id,'subject_map_id'=>$subjects_1->coe_subjects_mapping_id])->all();
            
            $exam_check = ExamTimeTable::find()->where(['subject_mapping_id'=>$subjects_1->coe_subjects_mapping_id,'exam_year'=>$year])->orderBY('coe_exam_timetable_id DESC')->one();

            if(!empty($check_int_marks))
            {
                $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Mark Entry Available']);
            }
            else 
            {
                $get_exam = ExamTimeTable::find()->where(['subject_mapping_id'=>$subjects_2->coe_subjects_mapping_id,'exam_year'=>$year])->orderBY('coe_exam_timetable_id DESC')->one();
                if(empty($get_exam))
                {
                    
                   // $update_status = Yii::$app->db->createCommand($hall_update_query)->execute();
                   if($check_nominal=1)
                    {  
                        $status = 1;
                        if(!empty($status))
                        {
                            $totalSuccess+=1;
                            $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Updated Successfully!!!']);
                        }
                        else{
                            $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Something Worng']);
                        } 
                    }
                    else{
                            $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Unable to Update Galley']);
                        }
                }
                else
                {
                    
                    $connection = Yii::$app->db;
                    $command = $connection->createCommand('UPDATE coe_nominal SET coe_subjects_id="'.$sub_code_2->coe_subjects_id.'",updated_by="'.$createdBy.'",updated_at="'.$created_at.'" WHERE  coe_nominal_id="'.$check_nominal->coe_nominal_id.'" ');
                    if($command->execute())
                    {
                        $totalSuccess+=1;
                        $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Updated Successfully!!!']);
                    }
                    else
                    {
                        $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Unable to Update']);
                    }
                }

            }
        }
        else
        {                    
            $dispResults[] = array_merge($line, 
                ['type' => 'E',  'message' => 'No Data Found']);
            Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to fetch the data with your submision.");
        }  
    } // Not empty of Batch & Other related ids
    else
    {                    
        $dispResults[] = array_merge($line, 
            ['type' => 'E',  'message' => 'No Data Found']);
        Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to fetch the data with your submision.");
    }           
} // Foreach Ends Here  

$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>'Nominal_update'];
?>