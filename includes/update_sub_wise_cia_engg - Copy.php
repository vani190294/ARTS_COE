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
use app\models\AbsentEntry;
use app\models\Programme;
use yii\db\Query;
use app\models\MarkEntry;
use app\models\MarkEntryMaster;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\UpdateTracker;

$interate = 1; // Check only 1 time for Sheet Columns
foreach($sheetData as $k => $line)
{ 
    $exam_columns=['A'=>'YEAR','B'=>'MONTH','C'=>'MARK TYPE','D'=>'TERM','E'=>'SUBJECT CODE','F'=>'REGISTER NUMBER','G'=>'CIA (OUT OF MAXIMUM)'];

    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F'],'G'=>$line['G']];

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

$det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
$updated_from = ucwords( str_replace('-', ' ', Yii::$app->controller->action->controller->id.' '.Yii::$app->controller->action->id) );
$det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
foreach($sheetData as $k => $line)
{    

    $line = array_map('trim', $line);
    $subjects = new Subjects();
    $MarkEntry = new MarkEntry();
    $MarkEntryMaster = new MarkEntryMaster();
    $subject_mapping = new SubjectsMapping();
    
    $year = isset($line['A'])?$line['A']:""; 
    $month = isset($line['B'])?$this->valueReplace($line['B'], Categorytype::getCategoryId()):"";
    $mark_type = isset($line['C'])?$this->valueReplace($line['C'], Categorytype::getCategoryId()):"";
    $term = isset($line['D'])?$this->valueReplace($line['D'], Categorytype::getCategoryId()):"";
    $subject_code = isset($line['E'])?Subjects::find()->where(['subject_code'=>$line['E']])->all():"";
    $reg_num_12 = isset($line['F'])?Student::find()->where(['register_number'=>$line['F']])->one():"";
    $reg_num_stu = '';
    if(!empty($reg_num_12))
    {
        $stu_map_id_che = Yii::$app->db->createCommand('SELECT * FROM coe_student_mapping WHERE status_category_type_id NOT IN ("'.$det_disc_type.'") and student_rel_id="'.$reg_num_12->coe_student_id.'" ')->queryOne();
        if(!empty($stu_map_id_che))
        {
          $reg_num_stu = StudentMapping::find()->where(['student_rel_id'=>$reg_num_12['coe_student_id']])->one();
          $reg_num = $line['F'];
        }
    }
   
    if(!empty($year) && !empty($month) && !empty($mark_type) && !empty($reg_num_stu) && !empty($subject_code) && !in_array(null, $line, true) )
    {      
        $inserted_res = 0;
        $student = Student::find()->where(['register_number'=>$reg_num])->one();
        $stu_mapping = StudentMapping::find()->where(['student_rel_id'=>$student->coe_student_id])->one();
        $batchMapping = CoeBatDegReg::findOne($stu_mapping->course_batch_mapping_id);
        $student_map_id = $stu_mapping->coe_student_mapping_id;

        $sem_verify = ConfigUtilities::SemCaluclation($year,$month,$stu_mapping->course_batch_mapping_id);
        $connection = Yii::$app->db;

            if(count($subject_code>0))
            {
                foreach ($subject_code as $sub_map) 
                {
                    $sub_map_id_get = SubjectsMapping::find()->where(['subject_id'=>$sub_map['coe_subjects_id'],'batch_mapping_id'=>$stu_mapping->course_batch_mapping_id])->one();
                    if(!empty($sub_map_id_get))
                    {
                        $subject_map_id = $sub_map_id_get['coe_subjects_mapping_id'];
                        break;
                    }
                }
                
            }
            else
            {
                $subject_map_id = $this->valueReplace($line['E'], SubjectsMapping::getSubjectMappingId($stu_mapping->course_batch_mapping_id,$sem_verify));
            }
            
            $cia_cat_id = Categorytype::find()->where(['description'=>'CIA'])->orWhere(['category_type'=>'Internal'])->one();
            $internal_id = $cia_cat_id->coe_category_type_id;

            $exe_cat_id = Categorytype::find()->where(['description'=>'External'])->orWhere(['category_type'=>'ESE'])->one();
           
            $exe_cat_id_2 = Categorytype::find()->where(['description'=>'ESE(Dummy)'])->orWhere(['category_type'=>'ESE(Dummy)'])->one();

            $check_cia_marks = MarkEntry::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'category_type_id'=>$internal_id])->orderBy('coe_mark_entry_id DESC')->one();

            $check_ese_marks_dum = MarkEntry::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'category_type_id'=>$exe_cat_id_2->coe_category_type_id,'year'=>$year,'month'=>$month,'mark_type'=>$mark_type,'term'=>$term])->orderBy('coe_mark_entry_id DESC')->one();
            
            $check_ese_marks = MarkEntry::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'category_type_id'=>$exe_cat_id->coe_category_type_id,'year'=>$year,'month'=>$month,'mark_type'=>$mark_type,'term'=>$term])->orderBy('coe_mark_entry_id DESC')->one();

             $check_ese_marks = !empty($check_ese_marks) ? $check_ese_marks['category_type_id_marks']: ( !empty($check_ese_marks_dum) ? $check_ese_marks_dum['category_type_id_marks'] : 0); 

            $get_sub_info = Yii::$app->db->createCommand('SELECT A.* FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id WHERE coe_subjects_mapping_id="'.$subject_map_id.'" and A.subject_code="'.$line['E'].'" ')->queryOne();
            
            if($line['G']<=$get_sub_info['CIA_max'])
            {
                $status_check = 'YES';                
            }
            else
            {
                $status_check = 'NO';
            }
            $get_result_publish_status = MarkEntry::find()->where(['subject_map_id'=>$subject_map_id,'year'=>$year,'month'=>$month,'status_id'=>1])->one();

            $get_result_publish_mas_status = MarkEntryMaster::find()->where(['subject_map_id'=>$subject_map_id,'year'=>$year,'month'=>$month,'status_id'=>1])->one();

            
                if(count($check_cia_marks)>0 && !empty($check_cia_marks) && $status_check=='YES')
                {

                    $prev_cia_marks = $check_cia_marks['category_type_id_marks'];
                    $command1 = $connection->createCommand('UPDATE coe_mark_entry SET category_type_id_marks="'.$line['G'].'",updated_by="'.$created_by.'",updated_at="'.$created_at.'",month="'.$month.'",mark_type="'.$mark_type.'",term="'.$term.'" WHERE coe_mark_entry_id="'.$check_cia_marks->coe_mark_entry_id.'" ');
                    if($command1->execute())
                     { 
                        
                        $check_mark_entry_master = MarkEntryMaster::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'mark_type'=>$mark_type,'term'=>$term,'year'=>$year,'month'=>$month])->one();
                        $attempt = 1;
                        if(!empty($check_mark_entry_master))
                        {
                            $ciaMArks = $line['G']<=0 ? '0': $line['G'];
                            $ese_marks = $check_ese_marks;

                            $student_res_data = ConfigUtilities::StudentResult($student_map_id, $subject_map_id, $ciaMArks, $ese_marks,$year,$month);
                         
                            $year_of_passing = $student_res_data['result'] == "Pass" || $student_res_data['result'] == "pass" || $student_res_data['result'] == "PASS" ? $month. "-" . $year : '';

                            $getAbent = AbsentEntry::find()->where(['absent_student_reg'=>$student_map_id,'exam_subject_id'=>$subject_map_id,'exam_year'=>$year,'exam_month'=>$month])->one();
                            if(!empty($getAbent))
                            {
                                $year_of_passing = '';
                                $student_res_data['result']='Absent';
                                $student_res_data['grade_name'] = 'AB';
                                $student_res_data['grade_point'] = 0;
                            }
                              $data_updated = 'PREVIOUS CIA MARKS '.$prev_cia_marks.' NEW CIA MARKS '.$ciaMArks.' Prev Result '.$check_mark_entry_master['result'].' NEW RESULT '.$student_res_data['result'];
                              $data_array = ['subject_map_id'=>$subject_map_id,'updated_link_from'=>$updated_from,'data_updated'=>nl2br($data_updated),'exam_month'=>$month,'exam_year'=>$year,'student_map_id'=>$student_map_id];            
                              $update_track = ConfigUtilities::updateTracker($data_array);

                            $command2 = $connection->createCommand('UPDATE coe_mark_entry_master SET CIA="'.$ciaMArks.'",ESE="'.$student_res_data['ese_marks'].'",total="'.$student_res_data['total_marks'].'",result="'.$student_res_data['result'].'",grade_point="'.$student_res_data['grade_point'].'",grade_name="'.$student_res_data['grade_name'].'",updated_by="'.$created_by.'",updated_at="'.$created_at.'",year_of_passing="'.$year_of_passing.'" WHERE coe_mark_entry_master_id="'.$check_mark_entry_master->coe_mark_entry_master_id.'" ');
                            if($command2->execute())
                            {

                                $totalSuccess+=1;
                                $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Success']);
                            }
                            else{ 
                                $dispResults[] = array_merge($line, ['type' => 'E', 'message' => 'Unable to Update Result']);
                             }
                        }
                        else
                        {
                            $totalSuccess+=1;
                            $dispResults[] = array_merge($line, ['type' => 'S',  'message' => 'Cia Marks Updated']);
                        }
                     }
                     else{ 
                        $dispResults[] = array_merge($line, ['type' => 'E', 'message' => 'Unable to Update CIA Marks']);
                     }

                }
                else{
                    $dispResults[] = array_merge($line, ['type' => 'E', 'message' => 'Marks Not Available to Update Or MAXIMUM Crosssed']);
                }  
            
    } // Not empty of Batch & Other related ids
    else
    {                    
        $dispResults[] = array_merge($line, 
            ['type' => 'E',  'message' => 'Data submision is worng']);
        Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to fetch the data with your submision.");
    }           
} // Foreach Ends Here  

$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>'Internal_update_engg'];
?>