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
use app\models\PracticalEntry;
use app\models\PracticalExamTimetable;

$interate = 1; // Check only 1 time for Sheet Columns
$updated_from = ucwords( str_replace('-', ' ', Yii::$app->controller->action->controller->id.' '.Yii::$app->controller->action->id) );
foreach($sheetData as $k => $line)
{ 
    $exam_columns=['A'=>'YEAR','B'=>'MONTH','C'=>'MARK TYPE','D'=>'SUBJECT CODE','E'=>'REGISTER NUMBER','F'=>'ESE (OUT OF 100)'];

    $template_clumns=['A'=>$line['A'],'B'=>$line['B'],'C'=>$line['C'],'D'=>$line['D'],'E'=>$line['E'],'F'=>$line['F']];

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
    $subject_code = isset($line['D'])?Subjects::find()->where(['subject_code'=>$line['D']])->all():"";
    $reg_num_12 = isset($line['E'])?Student::find()->where(['register_number'=>$line['E']])->one():"";
    $reg_num_stu = '';
    if(!empty($reg_num_12))
    {
        $stu_map_id_che = Yii::$app->db->createCommand('SELECT * FROM coe_student_mapping WHERE status_category_type_id NOT IN ("'.$det_disc_type.'","'.$det_cat_type.'") and student_rel_id="'.$reg_num_12->coe_student_id.'" ')->queryOne();
        if(!empty($stu_map_id_che))
        {
          $reg_num_stu = StudentMapping::find()->where(['student_rel_id'=>$reg_num_12['coe_student_id']])->one();
          $reg_num = $line['E'];
        }
    }

    //print_r($reg_num_stu); exit();
    if(!empty($year) && !empty($month) && !empty($mark_type) && !empty($reg_num_stu) && !empty($subject_code) && !in_array(null, $line, true))
    {      
        $inserted_res = 0;
        $student = Student::find()->where(['register_number'=>$reg_num])->one();
        $stu_mapping = StudentMapping::find()->where(['student_rel_id'=>$student['coe_student_id']])->one();
        $batchMapping = CoeBatDegReg::findOne($stu_mapping['course_batch_mapping_id']);
        $student_map_id = $stu_mapping['coe_student_mapping_id'];
        $batch_id=$batchMapping['coe_batch_id'];

        $sem_verify = ConfigUtilities::SemCaluclation($year,$month,$stu_mapping['course_batch_mapping_id']);
        $connection = Yii::$app->db;
        //print_r($student); exit;

        $subject_map_id='';
            if(count($subject_code>0))
            {
                foreach ($subject_code as $sub_map) 
                {
                    $sub_map_id_get = SubjectsMapping::find()->where(['subject_id'=>$sub_map['coe_subjects_id'],'batch_mapping_id'=>$stu_mapping['course_batch_mapping_id']])->one();
                    if(!empty($sub_map_id_get))
                    {
                        $subject_map_id = $sub_map_id_get['coe_subjects_mapping_id'];
                        break;
                    }
                }
                
            }
            else
            {
                $subject_map_id = $this->valueReplace($line['D'], SubjectsMapping::getSubjectMappingId($stu_mapping['course_batch_mapping_id'],$sem_verify));
            }
           


        if($subject_map_id!='')
        {
            //echo $student_map_id;
           $check_cia_marks = MarkEntry::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'category_type_id'=>46])->one();
            //echo $check_cia_marks->createCommand()->getRawSql(); exit;
            
            $check_ese_marks = MarkEntry::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'category_type_id'=>89,'year'=>$line['A'],'month'=>$month,'mark_type'=>$mark_type])->one();

            if(empty($check_ese_marks))
            {
                $check_ese_marks = MarkEntry::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'category_type_id'=>49,'year'=>$line['A'],'month'=>$month,'mark_type'=>$mark_type])->one();
            }
            
            $PracticalEntry = PracticalEntry::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'year'=>$line['A'],'month'=>$month,'mark_type'=>$mark_type])->one();
            
            $PracticalEntrytable = PracticalExamTimetable::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'exam_year'=>$line['A'],'exam_month'=>$month,'mark_type'=>$mark_type])->one();

            $get_sub_info = Yii::$app->db->createCommand('SELECT A.* FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id WHERE coe_subjects_mapping_id="'.$subject_map_id.'" and A.subject_code="'.$line['D'].'" ')->queryOne();

            $out_of_100 = !empty($line['F'])?$line['F']:0;
            
            $updated_at = date("Y-m-d H:i:s");
            $updated_by = Yii::$app->user->getId();
            //print_r($PracticalEntry); exit;
            if(!empty($check_cia_marks) && !empty($check_ese_marks) && $out_of_100<=100)
            {
                $check_mark_entry_master = MarkEntryMasterTemp::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id,'mark_type'=>$mark_type,'year'=>$year,'month'=>$month])->one();    

                $old_result=$check_mark_entry_master['result'];
                $old_ese=$check_mark_entry_master['ESE'];
                $old_total=$check_mark_entry_master['total'];                         

                //print_r($check_mark_entry_master); exit;
               
                if(!empty($check_mark_entry_master))
                {
                    $sub_info = SubjectsMapping::findOne($subject_map_id);
                    //print_r($sub_info); exit();
                    if($sub_info['type_id']==144 || $sub_info['type_id']==143) //type123
                    {
                        

                        $ese100 =$out_of_100;      // for theory tp                     
                             $Practicalmark=$PracticalEntry['out_of_100'];   // for theory tp
                             //echo "P".$Practicalmark;// exit;

                            if($sub_info['type_id']=='140') //type1
                            {
                               $ese_disp = ceil(($ese100*0.25) + ($Practicalmark*0.25)); //exit;
                                $total=$check_cia_marks['category_type_id_marks']+$ese_disp;
                            }
                            else if($sub_info['type_id']=='141') //type2
                            {
                                
                                $ese_disp = ceil(($ese100*0.35) + ($Practicalmark*0.15));
                                $total=$check_cia_marks['category_type_id_marks']+$ese_disp;
                            }
                            else if($sub_info['type_id']=='142') //type3
                            {
                              
                                $ese_disp = ceil(($ese100*0.15) + ($Practicalmark*0.35));

                               $total=$check_cia_marks['category_type_id_marks']+$ese_disp;
                            } 
                            else if($sub_info['type_id']=='143') //type3
                            {
                                
                                 $ese100=$out_of_100;
                                 $ese_disp = ceil($ese100*0.40);

                               $total=$check_cia_marks['category_type_id_marks']+$ese_disp;
                            } 
                            else if($sub_info['type_id']=='144')
                            {
                                $ese100=$out_of_100;
                                $ese_disp = ceil($ese100*0.50);
                                $total=$check_cia_marks['category_type_id_marks']+$ese_disp;
                            }
                        
                        //$total=$CIA+$ese_disp;
                        //echo $total; exit;    
                        $sub_info = Yii::$app->db->createCommand('SELECT * FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id WHERE B.coe_subjects_mapping_id="'.$subject_map_id.'"')->queryOne();

                        if(($total>=$sub_info["total_minimum_pass"]) && ($ese_disp>=$sub_info["ESE_min"]))
                        {
                            $getgradename = Yii::$app->db->createCommand("SELECT grade_name,grade_point FROM `coe_regulation` WHERE '".$total."' between `grade_point_from` and `grade_point_to` and `coe_batch_id`='".$batch_id."' ")->queryOne();
                        
                            $grade_point=$getgradename['grade_point'];
                            $getgradename=$getgradename['grade_name'];
                            $result='Pass';
                            $year_of_passing=$month.'-'.$year;
                        }
                        else
                        {
                            $grade_point=0;
                            $getgradename='U';
                            $result='Fail'; 
                            $year_of_passing='';
                        }

                        $update='';
                        
                        if($sub_info['type_id']=='144')
                        {

                            $gruy= 'UPDATE coe_mark_entry_master_temp SET  ESE="'.$ese_disp.'",total="'.$total.'",result="'.$result.'",grade_point="'.$grade_point.'",grade_name="'.$getgradename.'",year_of_passing="'.$year_of_passing.'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE student_map_id ="'.$student_map_id.'"  AND subject_map_id ="'.$subject_map_id.'"  AND year="'.$year.'" AND month="'.$month.'" AND mark_type='.$mark_type.' AND (total!="'.$total.'" || ESE!="'.$ese_disp.'") AND result!="Absent"'; 
                            $update=Yii::$app->db->createCommand($gruy)->execute();
                        }

                        if($sub_info['type_id']=='143')
                        {

                            $gruy1= 'UPDATE coe_mark_entry_master SET ESE="'.$ese_disp.'",total="'.$total.'",result="'.$result.'",grade_point="'.$grade_point.'",grade_name="'.$getgradename.'",year_of_passing="'.$year_of_passing.'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE student_map_id ="'.$student_map_id.'"  AND subject_map_id ="'.$subject_map_id.'"  AND year="'.$year.'" AND month="'.$month.'" AND mark_type='.$mark_type.' AND (total!="'.$total.'" || ESE!="'.$ese_disp.'") AND result!="Absent"'; 
                            $update1=Yii::$app->db->createCommand($gruy1)->execute();
                            
                            $gruy= 'UPDATE coe_mark_entry_master_temp SET  ESE="'.$ese_disp.'",total="'.$total.'",result="'.$result.'",grade_point="'.$grade_point.'",grade_name="'.$getgradename.'",year_of_passing="'.$year_of_passing.'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE student_map_id ="'.$student_map_id.'"  AND subject_map_id ="'.$subject_map_id.'"  AND year="'.$year.'" AND month="'.$month.'" AND mark_type='.$mark_type.' AND (total!="'.$total.'" || ESE!="'.$ese_disp.'") AND result!="Absent"'; 

                            $update=Yii::$app->db->createCommand($gruy)->execute();
                        }
                        
                        $msg='';
                        if($update)
                        {
                            
                            $data_updated = 'TP Practical Entry PREVIOUS ESE out of 100 MARKS '.$PracticalEntry['out_of_100'].' NEW ESE out of 100 MARKS '.$Practicalmark;

                            $data_array = ['subject_map_id'=>$check_mark_entry_master['subject_map_id'],'updated_link_from'=>$updated_from,'data_updated'=>nl2br($data_updated),'exam_month'=>$month,'exam_year'=>$year,'student_map_id'=>$check_mark_entry_master['student_map_id']];            
                            $update_track = ConfigUtilities::updateTracker($data_array);

                            $command1 = $connection->createCommand('UPDATE coe_practical_entry SET out_of_100="'.$Practicalmark.'",ESE="'.$ese_disp.'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_practical_entry_id="'.$PracticalEntry['coe_practical_entry_id'].'" ');

                            $command1->execute();

                            $command3 = $connection->createCommand('UPDATE coe_prac_exam_ttable SET out_of_100="'.$Practicalmark.'",ESE="'.$ese_disp.'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_prac_exam_ttable_id="'.$PracticalEntrytable['coe_prac_exam_ttable_id'].'" ');

                            $command3->execute();
                           

                            $command2 = $connection->createCommand('UPDATE coe_mark_entry SET category_type_id_marks="'.($ese_disp*2).'",updated_by="'.$updated_by.'",updated_at="'.$updated_at.'" WHERE coe_mark_entry_id="'.$check_ese_marks->coe_mark_entry_id.'" ');
                            
                            $command2->execute();
                        

                           $data_updated1 = 'PREVIOUS ESE MARKS '.$old_ese.' NEW ESE MARKS '.$ese_disp.' Prev Result '.$old_result.' NEW RESULT '.$result;

                            $data_array = ['subject_map_id'=>$check_mark_entry_master['subject_map_id'],'updated_link_from'=>$updated_from,'data_updated'=>nl2br($data_updated1),'exam_month'=>$month,'exam_year'=>$year,'student_map_id'=>$check_mark_entry_master['student_map_id']];            
                            $update_track = ConfigUtilities::updateTracker($data_array);

                       
                            $totalSuccess+=1;
                            $dispResults[] = array_merge($line, ['type' => 'S',  'message' => $data_updated1]);
                        }
                        else
                        { 
                            $dispResults[] = array_merge($line, ['type' => 'E', 'message' => 'Unable to Update Result or Same Result']);
                        }
                    }
                    else
                    { 
                        
                        $dispResults[] = array_merge($line, ['type' => 'E', 'message' => 'Subject type mismatch']);
                     }
                }
                else
                {
                    $dispResults[] = array_merge($line, ['type' => 'E',  'message' => 'Marks Not Found in Master entry']);
                   
                }
            }
            else
            {
                $dispResults[] = array_merge($line, ['type' => 'E', 'message' => 'Marks Not Available to Update Or MAXIMUM Crosssed']);
                
            } 
        }
         else
         {
                $dispResults[] = array_merge($line, ['type' => 'E', 'message' => 'Subject Not found']);
            } 
    } // Not empty of Batch & Other related ids
    else
    {                    
        $dispResults[] = array_merge($line, 
            ['type' => 'E',  'message' => 'Data submision is worng']);
        Yii::$app->ShowFlashMessages->setMsg('Error',"We are Unable to fetch the data with your submision.");
    }           
} // Foreach Ends Here  

$_SESSION['importResults'] = ['dispResults'=>$dispResults,'totalSuccess'=>$totalSuccess,'result_for'=>'update_practical_mark_100'];
?>