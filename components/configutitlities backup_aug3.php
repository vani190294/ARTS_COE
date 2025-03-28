<?php
namespace app\components;

use Yii;
use yii\db\Query;
use app\models\Configuration;
use app\components\ConfigConstants;
use app\models\Categorytype;
use app\models\Categories;
use app\models\Batch;
use app\models\Degree;
use app\models\Subjects;
use app\models\AbsentEntry;
use app\models\Regulation;
use app\models\Revaluation;
use app\models\SubjectsMapping;
use app\models\Student;
use app\models\StudentMapping;
use app\models\SubInfo;
use app\models\StuInfo;
use app\models\Programme;
use app\models\ExamTimetable;
use app\models\CoeBatDegReg;
use app\models\MarkEntryMaster;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use app\models\UpdateTracker;
class ConfigUtilities
{
  
  public  function getConfigValue($name)
    {
        $config_value = Configuration::find(['config_desc'])->where(['config_name' => $name])->one();       
        return $config_value->config_value;
    }

    public function UpdateConfigValue($name,$value)
    {
        $updated = date("Y-m-d H:i:s");
        $updateBy = Yii::$app->user->getId();        
        $value = ucwords($value);


        $updateQuery = "UPDATE coe_configuration SET 
                        config_value=:value ,
                        updated_at=:updated ,
                        updated_by=:updateBy 
                        WHERE config_desc='".$name."'";
                        
        $updateCofig = Yii::$app->db->createCommand($updateQuery)
                        ->bindValue(':value', $value)
                        ->bindValue(':updateBy', $updateBy)
                        ->bindValue(':updated', $updated)->execute();

        return isset($updateCofig)&&$updateCofig!=""?1:0;
    }

    public function getResultPublishStatus($exam_year,$exam_month)
    {
        $check_status = MarkEntryMaster::find()->where(['year'=>$exam_year,'month'=>$exam_month,'status_id'=>1])->one();
        $return_val = count($check_status)>0?'1':'0';
        return $return_val;
    }
    public function getConfigDesc($name)
    {
        $config_value = Configuration::find(['config_desc'])->where(['config_name' => $name])->one();       
         return $config_value->config_desc;
    }
    

    public function UpdateBatchLocking($name,$locking_start,$locking_end)
    {
        $updated = date("Y-m-d H:i:s");
        $updateBy = Yii::$app->user->getId();  

        $updateStartDate = "UPDATE coe_configuration    
                        SET config_value =:locking_start, updated_at=:updated, updated_by=:updateBy WHERE config_name='".ConfigConstants::CONFIG_BATCH_LOCKING_START."' and config_desc like '%Batch Locking%'";
                        
        $updateEndDate = "UPDATE coe_configuration    
                        SET config_value =:locking_end, updated_at=:updated, updated_by=:updateBy WHERE config_name='".ConfigConstants::CONFIG_BATCH_LOCKING_END."' and config_desc like '%Batch Locking%'";

        $UpdateBatchLocking = Yii::$app->db->createCommand($updateStartDate)
                        ->bindValue(':locking_start', $locking_start)
                        ->bindValue(':updateBy', $updateBy)
                        ->bindValue(':updated', $updated)->execute();
       
        $UpdateBatchLocking = Yii::$app->db->createCommand($updateEndDate)
                        ->bindValue(':locking_end', $locking_end)
                        ->bindValue(':updateBy', $updateBy)
                        ->bindValue(':updated', $updated)->execute();

        return isset($UpdateBatchLocking)&&$UpdateBatchLocking!=""?1:0;
        

    } 
    /***
     * 
     * @return array|Batch[]|mixed|object
     */
    public function getBatchDetails()
    {
        $batch = Batch::find()->orderBy(['batch_name'=>SORT_ASC])->all();
        return  $batch_list = ArrayHelper::map($batch,'coe_batch_id','batch_name');
    }
    /**
     *  @return array|Degree[]|mixed|object
     */
    public function getDegreedetails()
    {
       
        $query = "SELECT a.coe_bat_deg_reg_id,concat(b.degree_code, ' ' , c.programme_code) as degree_name FROM coe_bat_deg_reg as a LEFT JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id LEFT JOIN coe_programme c ON c.coe_programme_id = a.coe_programme_id  order by a.coe_bat_deg_reg_id";
        $degreeInfo = Yii::$app->db->createCommand($query)->queryAll();        
        return ArrayHelper::map($degreeInfo,'coe_bat_deg_reg_id','degree_name');
    }
    public function getManDegreedetails($batch_id,$batch_map_id)
    {
        if($batch_id!='' && $batch_map_id!='')
        {
          $query = "SELECT a.coe_bat_deg_reg_id,concat(b.degree_code, ' ' , c.programme_code) as degree_name FROM coe_bat_deg_reg as a LEFT JOIN coe_degree b ON b.coe_degree_id = a.coe_degree_id LEFT JOIN coe_programme c ON c.coe_programme_id = a.coe_programme_id JOIN coe_batch as d ON d.coe_batch_id=a.coe_batch_id and a.coe_batch_id='".$batch_id."' and d.coe_batch_id='".$batch_id."' and coe_bat_deg_reg_id!='".$batch_map_id."'  order by a.coe_bat_deg_reg_id";
          $degreeInfo = Yii::$app->db->createCommand($query)->queryAll();     
           
          return ArrayHelper::map($degreeInfo,'coe_bat_deg_reg_id','degree_name');
        }
        
    }
    public function getDegree()
    {
        $degree_list = Degree::find()->all();
        return ArrayHelper::map($degree_list,'coe_degree_id','degree_code');
        
    }
    /**
     * @return \yii\db\ActiveQuery
     */

    public function getSectionnames()
    {        
        $section_list = CoeBatDegReg::find()->max('no_of_section');        
        $stu_dropdown = ['All'=>'All (View Only)'];
        $section_list = !empty($section_list) && isset($section_list)?$section_list:4;
        
        for ($char = 65; $char < 65+$section_list; $char++) {
           // $stu_dropdown .= "<option value='".chr($char)."' > ".chr($char)."</option>";
            $stu_dropdown[chr($char)]= chr($char);
        }
        return $stu_dropdown;
    }

    public function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }

    public function ValidFileExtension(){
        return array('png','jpg','jpeg','gif','JPG');
    }

    public function match($needles, $haystack)
    {
        foreach($needles as $needle){
            if (stristr($haystack, $needle) !== false) {
                return $needle;
            }
        }
        return false;
    }

    public function StudentResult($student_map_id,$subject_map_id,$cia_marks,$ese_marks,$year_exam=null,$month_exam=null)
    {
      $student_res_data = ['result'=>'NA','total_marks'=>'-1','grade_name'=>'NA','grade_point'=>'-1','attempt'=>"-1",'ese_marks'=>'-1','year_of_passing'=>'NA'];
     
      $get_id_details_data = ['exam_year'=>$year_exam,'exam_month'=>$month_exam];;
      if(isset($get_id_details_data) && !empty($get_id_details_data))
      {
          $get_id_details = ExamTimetable::find()->where(['exam_year'=>$get_id_details_data['exam_year'],'exam_month'=>$get_id_details_data['exam_month']])->andWhere(['IN','subject_mapping_id',$subject_map_id])->one();
      }
      
      if(is_array($subject_map_id))
        {
            $sub_code = '';
            sort($subject_map_id);
            for ($che=0; $che <count($subject_map_id) ; $che++) 
            { 
                $find_records = MarkEntryMaster::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_map_id[$che]])->all();                
                if(!empty($find_records))
                {
                    $sub_code = $subject_map_id[$che];
                    $get_exam_info = ExamTimetable::find()->where(['subject_mapping_id'=>$subject_map_id[$che],'exam_year'=>$get_id_details_data['exam_year'],'exam_month'=>$get_id_details_data['exam_month']])->one();
                    $exam_type = $get_exam_info->exam_type;
                    $exam_term = $get_exam_info->exam_term;
                    break;
                }
                else
                {
                    $sub_code = $subject_map_id[$che];
                }
            }
        }
        else if(isset($get_id_details))
        {
            $sub_code = $subject_map_id;
            $exam_type = $get_id_details['exam_type'];
            $exam_term = $get_id_details['exam_term'];
        }
        else
        {
            $max_exam_id = Yii::$app->db->createCommand('SELECT max(coe_exam_timetable_id) from coe_exam_timetable where exam_year="'.$get_id_details_data['exam_year'].'" and exam_month="'.$get_id_details_data['exam_month'].'"')->queryScalar();
            $exam_data_show = ExamTimetable::findOne($max_exam_id);
            $sub_code = $subject_map_id;
            if(!empty($exam_data_show))
            {
                $exam_type = $exam_data_show->exam_type;
                $exam_term = $exam_data_show->exam_term;
            }
            else
            {
                $exam_data_show_xam_type = Categorytype::find()->where(['category_type'=>'Regular'])->one();
                $exam_data_show_exam_term = Categorytype::find()->where(['category_type'=>'End'])->one();
                $exam_type = $exam_data_show_xam_type['coe_category_type_id'];
                $exam_term = $exam_data_show_exam_term['coe_category_type_id'];
            }
            
        }

      $subject_id = SubjectsMapping::findOne($sub_code);
      $subject_details = Subjects::findOne($subject_id->subject_id);
      $coe_batch_id = CoeBatDegReg::findOne($subject_id->batch_mapping_id);
      $regulation = CoeBatDegReg::find()->where(['coe_batch_id'=>$coe_batch_id->coe_batch_id,'coe_bat_deg_reg_id'=>$subject_id->batch_mapping_id])->one();
      $grade_details = Regulation::find()->where(['regulation_year'=>$regulation->regulation_year])->all();

      $re_appear = Regulation::find()->where(['coe_batch_id'=>$coe_batch_id->coe_batch_id,'regulation_year'=>$regulation->regulation_year])->andWhere(['IN','grade_name',['RA'=>'RA','U'=>'U']])->one();
      $k=0;
      $ese_max = $subject_details->ESE_max;
      $convert_ese_marks =  round( ($ese_marks*$ese_max)/100 );
      $total_marks = $insert_total = $cia_marks+$convert_ese_marks;

      $check_attempt_reg = Yii::$app->db->createCommand('SELECT count(*) FROM coe_mark_entry_master WHERE subject_map_id="'.$sub_code.'" AND student_map_id="'.$student_map_id.'" AND result not like "%pass%"')->queryScalar();

      if(!empty($check_attempt_reg)) 
      {
        $check_attempt_wd = Yii::$app->db->createCommand('SELECT count(*) FROM coe_mark_entry_master WHERE subject_map_id="'.$sub_code.'" AND student_map_id="'.$student_map_id.'" AND result not like "%pass%" and grade_name like "WD%" ')->queryScalar();
      }

      if(isset($get_id_details))
      {
         $check_reval = Revaluation::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$sub_code,'year'=>$get_id_details['exam_year'],'month'=>$get_id_details['exam_month'],'mark_type'=>$exam_type,'reval_status'=>'YES'])->one(); 
      }      
      $check_attempt = isset($check_attempt_wd) && !empty($check_attempt_wd) ? 0 : $check_attempt_reg;
      $config_attempt = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CIA_ZEO);
      $attempt = isset($check_attempt) && $check_attempt!="" ? ($check_attempt+1) : 0;
      $attempt = isset($check_reval) && !empty($check_reval) ? $attempt-1:$attempt;
      if($attempt>$config_attempt) // For SKCT
      {      
          if($subject_details->ESE_max==0 && $subject_details->ESE_min==0)
          {
            $total_marks = $insert_total = $cia_marks;
            $convert_ese_marks = 0;
          }
          else
          {
              $total_marks = $insert_total = $ese_marks;
              $convert_ese_marks =  $ese_marks;
          }
            
      } 
      require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
      $final_sub_total = $subject_details->ESE_max+$subject_details->CIA_max;
      $arts_college_grade = 'NO';
      if($org_email=='coe@skasc.ac.in')
      {
        $convert_ese_marks =  $ese_marks;
        $insert_total = $ese_marks+$cia_marks;
        if($final_sub_total<100)
        {
          $total_marks = round(round((($insert_total/$final_sub_total)*10),1)*10);
        }
        else
        {
          $total_marks = $ese_marks+$cia_marks;
        }
        $arts_college_grade = round(($insert_total/$final_sub_total)*10,1);

      }
      foreach ($grade_details as $value) 
      {
          if($value['grade_point_to']!='')
          {              
              if($total_marks >= $value['grade_point_from'] &&  $total_marks <= $value['grade_point_to'] )
              {
               
                  if($cia_marks<$subject_details->CIA_min || $convert_ese_marks<$subject_details->ESE_min || $total_marks<$subject_details->total_minimum_pass)
                  {
                    $result_stu = 'Fail';
                    if($total_marks==0 || $convert_ese_marks==0)
                    {
                      $merge_condition = array_filter(array());
                      if(isset($get_id_details))
                      {
                       
                         $exam_year = $get_id_details_data['exam_year'];
                         $exam_month = $get_id_details_data['exam_month'];
                         $check_ab = 'SELECT * FROM coe_absent_entry WHERE absent_student_reg="'.$student_map_id.'" AND exam_subject_id="'.$sub_code.'" AND exam_type="'.$exam_type.'" AND exam_month="'.$exam_month.'" and exam_year="'.$exam_year.'"'; 
                         $check_data = Yii::$app->db->createCommand($check_ab)->queryAll(); 
                      }
                      else
                      {
                          $check_data = AbsentEntry::find()->where(['absent_student_reg'=>$student_map_id,'exam_subject_id'=>$sub_code,'exam_type'=>$exam_type])->all();
                      }   
                      if( isset($check_data) && !empty($check_data))
                      {
                          $result_stu = 'Absent';
                      }
                      else
                      {
                          $result_stu = 'Fail';
                      }
                      
                    }
                    $grade_name_ins = 'U';
                    $student_res_data = ['result'=>$result_stu,'total_marks'=>$insert_total,'grade_name'=>$grade_name_ins,'grade_point'=>0,'attempt'=>$attempt,'year_of_passing'=>'','ese_marks'=>$convert_ese_marks];        
                  }      
                  else
                  {
                    $grade_name_prit = $subject_details->ESE_max==0 && $subject_details->CIA_min==0 && $subject_details->ESE_min==0 && $subject_details->CIA_max==0 ? 'COMPLETED' : $value['grade_name'];
                    $grade_point_arts = $org_email=='coe@skasc.ac.in' ? $arts_college_grade : $value['grade_point'];
                    if(isset($get_id_details))
                    {
                        $student_res_data = ['result'=>'Pass','total_marks'=>$insert_total,'grade_name'=>$grade_name_prit,'grade_point'=>$grade_point_arts,'attempt'=>$attempt,'year_of_passing'=>$get_id_details['exam_month']."-".$get_id_details['exam_year'],'ese_marks'=>$convert_ese_marks];
                    }
                    else if(!empty($year_exam) && !empty($month_exam))
                    {
                        $student_res_data = ['result'=>'Pass','total_marks'=>$insert_total,'grade_name'=>$grade_name_prit,'grade_point'=>$grade_point_arts,'attempt'=>$attempt,'ese_marks'=>$convert_ese_marks,'year_of_passing'=>$month_exam."-".$year_exam];
                    }
                    else
                    {
                        $student_res_data = ['result'=>'Pass','total_marks'=>$insert_total,'grade_name'=>$grade_name_prit,'grade_point'=>$grade_point_arts,'attempt'=>$attempt,'ese_marks'=>$convert_ese_marks,'year_of_passing'=>''];
                    }
                    
                    
                  }
              } // Grade Point Caluclation
          } // Not Empty of the Grade Point 
          
      }   
      
      return $student_res_data;      
    }
    public function HasSuperAccess($id)
    {
       $query = "select distinct parent from auth_assignment as A JOIN auth_item_child as B ON B.parent=A.item_name where A.user_id='".$id."' and A.item_name IN ('SuperAdminAccess')";
      $data_access = Yii::$app->db->createCommand($query)->queryAll();
      $access = count($data_access)>0 && !empty($data_access) ?"Yes":"No";
      return $access;
    }
    public function HasAccess($id)
    {
      $query = "select distinct parent from auth_assignment as A JOIN auth_item_child as B ON B.parent=A.item_name where A.user_id='".$id."' and A.item_name NOT IN ('ReadAccess', 'ReadingRole', 'PartiallyUpdateAccess', 'PartiallyUpdateRole', 'MarkEntryAccess', 'MarkEntryRole', 'InternalEntryAccess', 'InternalEntryRole', 'InternalAccess', 'InternalRole', 'InternalMarkEntryRole', 'InternalMarkEntryAccess')";
      $data_access = Yii::$app->db->createCommand($query)->queryAll();
      $access = count($data_access)>0 && !empty($data_access)?"Yes":"No";
      return $access;
    }
    public function RevalHasAccess($id)
    {
      $query = "select distinct parent from auth_assignment as A JOIN auth_item_child as B ON B.parent=A.item_name where A.user_id='".$id."' and A.item_name IN ('RevalAccess', 'RevaluationAccess', 'RevaluationApplication', 'RevalApplicationAccess', 'RevalApplication')";
      $data_access = Yii::$app->db->createCommand($query)->queryAll();
      $access = count($data_access)>0 && !empty($data_access)?"Yes":"No";
      return $access;
    }
    function isInArray($needle, $haystack) 
    {
        foreach ($needle as $stack) {
            if (in_array($stack, $haystack)) {
                return true;
            }
        }
        return false;
    }

    public function getMonth(){
        $month = Yii::$app->db->createCommand("select distinct(b.description),b.coe_category_type_id from coe_categories a,coe_category_type b where a.coe_category_id=b.category_id and a.category_name in('Bisem','Trisem')")->queryAll();
        return  $all_month = ArrayHelper::map($month,'coe_category_type_id','description');
    }
    public function getCgpaCaluclation($exam_year,$exam_month,$batch_mapping_id,$student_map_id,$se=null)
    {
        $get_month = is_numeric($exam_month)?$exam_month:Categorytype::find()->where(['description'=>$exam_month])->one();
        
        $exam_month_fin = !empty($get_month) && is_numeric($get_month)?$get_month:$get_month->coe_category_type_id;
        $exam_month = $exam_month_fin;
        $get_mapping = CoeBatDegReg::findOne($batch_mapping_id);
        $get_degree = Degree::findOne($get_mapping->coe_degree_id);
        $deg_info = $get_degree->degree_total_semesters/$get_degree->degree_total_years;
        $sem_calc = ConfigUtilities::SemCaluclation($exam_year,$exam_month,$batch_mapping_id);
        if($deg_info==2)
        {
            $category_id = Categories::find()->where(['category_name'=>'Bisem'])->one();
            $category_ids = Categorytype::find()->where(['category_id'=>$category_id->coe_category_id])->all();
            $month_ids = '';
            foreach ($category_ids as  $value) 
            {
              $month_ids .= '"'.$value['coe_category_type_id'].'",';
            }
            $trim_months_trim= trim($month_ids,',');
            $trim_months = 'and month IN('.$trim_months_trim.') ';
            $add_exam_month = ' and exam_month IN('.$trim_months_trim.')';
            //$trim_months = '';
        }
        else
        {
          $month_ids = '"'.$exam_month.'",';
          $trim_months = ' and month <='.$exam_month;
          $add_exam_month = ' and exam_month <='.$exam_month;
          
        }
        $config_elect = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER); 
        $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$student_map_id.'" and year="'.$exam_year.'" and month="'.$exam_month_fin.'"';
        $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();

        $sem_man_add = !empty($se)?"AND A.semester<='".$se."' ":'AND A.semester<="'.$sem_calc.'"';
        
        $getWithDrawStat = 'SELECT * FROM coe_mark_entry_master WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and  (withdraw="wd" OR grade_name="WD") ';
        $getWithDrawStatus = Yii::$app->db->createCommand($getWithDrawStat)->queryAll();
        $add_withdraw_cond = '';
        if(!empty($getWithDrawStatus) && count($getWithDrawStatus)>0)
        {
          $remoVeSubs = '';
          foreach ($getWithDrawStatus as $key => $remove_subs) 
          {
              $remoVeSubs .=$remove_subs['subject_map_id'].", ";
          }
          $removeSubsCodes = trim($remoVeSubs,', '); 
          $add_withdraw_cond = ' AND subject_map_id NOT IN( '.$removeSubsCodes.' ) ';      
        }


        $sem_add = !empty($se)?"AND B.semester<='".$se."' ":'AND B.semester<="'.$sem_calc.'"';
        
        $total_credits_earn_part_1 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=1';
        $total_earn_reg_part_1= Yii::$app->db->createCommand($total_credits_earn_part_1)->queryScalar();

        $total_credits_earn_part_2 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=2';
        $total_earn_reg_part_2= Yii::$app->db->createCommand($total_credits_earn_part_2)->queryScalar();

        $total_credits_earn_part_3 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=3';
        $total_earn_reg_part_3= Yii::$app->db->createCommand($total_credits_earn_part_3)->queryScalar();
       /** $total_credits_earn_part_3 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year=2020 and month=29 and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=3  and  term=35  and mark_type=27';
        $total_earn_reg_part_3= Yii::$app->db->createCommand($total_credits_earn_part_3)->queryScalar();**/

        $total_credits_earn_part_4 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=4';
        $total_earn_reg_part_4= Yii::$app->db->createCommand($total_credits_earn_part_4)->queryScalar();

        
        $total_add_credits_earn = 'SELECT sum(credits) FROM  coe_additional_credits  WHERE student_map_id="'.$student_map_id.'" and exam_year<="'.$exam_year.'" and exam_month<="'.$exam_month_fin.'" and result like "%pass%" ';
        $total_ad_earn_reg= Yii::$app->db->createCommand($total_add_credits_earn)->queryScalar();


       $get_final_cgpa = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),5) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE student_map_id="'.$student_map_id.'" and result like "%pass%"'; 
        $final_cgpa_result = Yii::$app->db->createCommand($get_final_cgpa)->queryScalar();
        $final_cgpa_result = round($final_cgpa_result,2);

        $total_credits_cons = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE student_map_id="'.$student_map_id.'" and result like "%pass%" ';
        $consolidate_tot = Yii::$app->db->createCommand($total_credits_cons)->queryScalar();
        
        $total_add_credits_earn_cum = 'SELECT sum(credits) FROM  coe_additional_credits  WHERE student_map_id="'.$student_map_id.'" and result like "%pass%" ';
        $cumulative_total_addition_cred = Yii::$app->db->createCommand($total_add_credits_earn_cum)->queryScalar();

        if($sem_calc%2==0)
          
        {
          $total_credits_earn_part_1 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .' AND subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where year="'.$exam_year.'" and student_map_id="'.$student_map_id.'" and month=30 and result like "%pass%" '.$sem_add .' ) and part_no=1';
        $total_earn_reg_part_1= Yii::$app->db->createCommand($total_credits_earn_part_1)->queryScalar();

        $total_credits_earn_part_2 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .' AND subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where year="'.$exam_year.'" and student_map_id="'.$student_map_id.'" and month=30 and result like "%pass%" '.$sem_add .' ) and part_no=2';
        $total_earn_reg_part_2= Yii::$app->db->createCommand($total_credits_earn_part_2)->queryScalar();

        $total_credits_earn_part_3 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .' AND subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where year="'.$exam_year.'" and student_map_id="'.$student_map_id.'" and month=30 and result like "%pass%" '.$sem_add .' ) and part_no=3 ';
        $total_earn_reg_part_3= Yii::$app->db->createCommand($total_credits_earn_part_3)->queryScalar();
        
         /**$total_credits_earn_part_3 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .' AND subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where year="'.$exam_year.'" and student_map_id="'.$student_map_id.'" and month=30 and result like "%pass%" '.$sem_add .' ) and part_no=3  and term=35';
        $total_earn_reg_part_3= Yii::$app->db->createCommand($total_credits_earn_part_3)->queryScalar();***/
        $total_credits_earn_part_4 = 'SELECT sum(D.credit_points) as total_credits FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .' AND subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where year="'.$exam_year.'" and student_map_id="'.$student_map_id.'" and month=30 and result like "%pass%" '.$sem_add .' ) and part_no=4';
        $total_earn_reg_part_4= Yii::$app->db->createCommand($total_credits_earn_part_4)->queryScalar();

       $total_add_credits_earn = 'SELECT sum(credits) FROM  coe_additional_credits  WHERE student_map_id="'.$student_map_id.'" and exam_year<="'.$exam_year.'" '.$add_exam_month.' and result like "%pass%" '; 

       $total_ad_earn_reg= Yii::$app->db->createCommand($total_add_credits_earn)->queryScalar();
          //$cumulative_total_credits = $cumulative_total_credits+$cumulative_add_total_credits;

        }
        
        $get_gpa_part_1 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=1'; 
        $gpa_result_part_1 = Yii::$app->db->createCommand($get_gpa_part_1)->queryScalar();

        $get_gpa_part_2 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=2'; 
        $gpa_result_part_2 = Yii::$app->db->createCommand($get_gpa_part_2)->queryScalar();
        
      /**  $get_gpa_part_3 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=3 and term=35'; 
        $gpa_result_part_3 = Yii::$app->db->createCommand($get_gpa_part_3)->queryScalar();**/
        $get_gpa_part_3 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=3 '; 
        $gpa_result_part_3 = Yii::$app->db->createCommand($get_gpa_part_3)->queryScalar();

        $get_gpa_part_4 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year="'.$exam_year.'" and month="'.$exam_month_fin.'" and student_map_id="'.$student_map_id.'" and result like "%pass%" and  part_no=4'; 
        $gpa_result_part_4 = Yii::$app->db->createCommand($get_gpa_part_4)->queryScalar();

        $part_1 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year<="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .'  and part_no=1   '; 
        $part_1_cgpa = Yii::$app->db->createCommand($part_1)->queryScalar();

        $part_2 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year<="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .'  and part_no=2   '; 
        $part_2_cgpa = Yii::$app->db->createCommand($part_2)->queryScalar();

        $part_3 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year<="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .'  and part_no=3   '; 
        $part_3_cgpa = Yii::$app->db->createCommand($part_3)->queryScalar();

        $part_4 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year<="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .'  and part_no=4   '; 
        $part_4_cgpa = Yii::$app->db->createCommand($part_4)->queryScalar();
        
        if($sem_calc%2==0)
        {
          $part_1 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year<="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .' AND subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where year="'.$exam_year.'" and student_map_id="'.$student_map_id.'" and month=30 and result like "%pass%" '.$sem_add .' ) and part_no=1  ';
          
          $part_1_cgpa = Yii::$app->db->createCommand($part_1)->queryScalar();

          $part_2 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year<="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .' AND subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where year="'.$exam_year.'" and student_map_id="'.$student_map_id.'" and month=30 and result like "%pass%" '.$sem_add .' ) and part_no=2 ';
          
          $part_2_cgpa = Yii::$app->db->createCommand($part_2)->queryScalar();

          $part_3 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year<="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .' AND subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where year="'.$exam_year.'" and student_map_id="'.$student_map_id.'" and month=30 and result like "%pass%" '.$sem_add .' ) and part_no=3 ';
          
          $part_3_cgpa = Yii::$app->db->createCommand($part_3)->queryScalar();

          $part_4 = 'SELECT round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),2) as cgpa FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id WHERE year<="'.$exam_year.'" '.$trim_months.' and student_map_id="'.$student_map_id.'" and result like "%pass%" '.$sem_add .' AND subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where year="'.$exam_year.'" and student_map_id="'.$student_map_id.'" and month=30 and result like "%pass%" '.$sem_add .' ) and part_no=4  ';
          
          $part_4_cgpa = Yii::$app->db->createCommand($part_4)->queryScalar();

        }

        $cumulative_total_addition_cred = empty($cumulative_total_addition_cred)?"0":$cumulative_total_addition_cred;
        $total_registered = empty($total_reg)?"-":$total_reg;
        $credits_earned_part4 = empty($total_earn_reg_part_4)?"--":$total_earn_reg_part_4;
        $cgpa_result_sen_part4 = empty($part_4_cgpa)?"--":round($part_4_cgpa,1);
        $gpa_result_send_part4 = empty($gpa_result_part_4)?"--":round($gpa_result_part_4,1);

        $credits_earned_part3 = empty($total_earn_reg_part_3)?"--":$total_earn_reg_part_3;
        $cgpa_result_sen_part3 = empty($part_3_cgpa)?"--":round($part_3_cgpa,1);
        $gpa_result_send_part3 = empty($gpa_result_part_3)?"--":round($gpa_result_part_3,1);

        $credits_earned_part2 = empty($total_earn_reg_part_2)?"--":$total_earn_reg_part_2;
        $cgpa_result_sen_part2 = empty($part_2_cgpa)?"--":round($part_2_cgpa,1);
        $gpa_result_send_part2 = empty($gpa_result_part_2)?"--":round($gpa_result_part_2,1);

        $credits_earned_part1 = empty($total_earn_reg_part_1)?"--":$total_earn_reg_part_1;
        $cgpa_result_sen_part1 = empty($part_1_cgpa)?"--":round($part_1_cgpa,1);
        $gpa_result_send_part1 = empty($gpa_result_part_1)?"--":round($gpa_result_part_1,1);

        $gpa_result_send_part1 = (strlen($gpa_result_send_part1)==1 || strlen($gpa_result_send_part1)==2)  && $gpa_result_send_part1!='--'?$gpa_result_send_part1.".0":$gpa_result_send_part1;
        
        $cgpa_result_sen_part1 = (strlen($cgpa_result_sen_part1)==1 || strlen($cgpa_result_sen_part1)==2) && $cgpa_result_sen_part1!='--'?$cgpa_result_sen_part1.".0":$cgpa_result_sen_part1;
        $final_cgpa_result = empty($final_cgpa_result)?"-":round($final_cgpa_result,2);

        $gpa_result_send_part2 = (strlen($gpa_result_send_part2)==1 || strlen($gpa_result_send_part2)==2 ) && $gpa_result_send_part2!='--' ?$gpa_result_send_part2.".0":$gpa_result_send_part2;
    
         $cgpa_result_sen_part2 = (strlen($cgpa_result_sen_part2)==1 || strlen($cgpa_result_sen_part2)==2) && $cgpa_result_sen_part2!='--' ?$cgpa_result_sen_part2.".0":$cgpa_result_sen_part2;
         $consolidate_total_cred = $consolidate_tot+$cumulative_total_addition_cred;
      
        $gpa_result_send_part3 = (strlen($gpa_result_send_part3)==1 || strlen($gpa_result_send_part3)==2) && $gpa_result_send_part3!='--' ?$gpa_result_send_part3.".0":$gpa_result_send_part3;
       
         $cgpa_result_sen_part3 = (strlen($cgpa_result_sen_part3)==1  || strlen($cgpa_result_sen_part3)==2)  && $cgpa_result_sen_part3!='--' ?$cgpa_result_sen_part3.".0":$cgpa_result_sen_part3;   
       
        $gpa_result_send_part4 = (strlen($gpa_result_send_part4)==2 || strlen($gpa_result_send_part4)==1  ) && $gpa_result_send_part4!='--'  ?$gpa_result_send_part4.".0":$gpa_result_send_part4;
      
         $cgpa_result_sen_part4 = (strlen($cgpa_result_sen_part4)==1 || strlen($cgpa_result_sen_part4)==2) && $cgpa_result_sen_part4!='--' ?$cgpa_result_sen_part4.".0":$cgpa_result_sen_part4;
         
         $final_cgpa_result = strlen($final_cgpa_result)<2?$final_cgpa_result.".00":$final_cgpa_result;
        $final_cgpa_result = strlen($final_cgpa_result)<4 && strlen($final_cgpa_result)>2?$final_cgpa_result."0":$final_cgpa_result; 

        return $arra = ['part_1_gpa'=>$gpa_result_send_part1,'part_2_gpa'=>$gpa_result_send_part2,'part_3_gpa'=>$gpa_result_send_part3,'part_4_gpa'=>$gpa_result_send_part4,'part_1_earned'=>$credits_earned_part1,'part_2_earned'=>$credits_earned_part2,'part_3_earned'=>$credits_earned_part3,'part_4_earned'=>$credits_earned_part4,'part_1_cgpa'=>$cgpa_result_sen_part1,'part_2_cgpa'=>$cgpa_result_sen_part2,'part_3_cgpa'=>$cgpa_result_sen_part3,'part_4_cgpa'=>$cgpa_result_sen_part4,'final_cgpa'=>$final_cgpa_result,'consolidate_cre' =>$consolidate_total_cred,'registered'=>$total_registered];

    }
    public function SemCaluclation($exam_year,$exam_month,$batch_mapping_id)
    {
        if(empty($exam_year) || empty($exam_month) || empty($batch_mapping_id))
        {
            return 0;
        }
        $get_month = is_numeric($exam_month)?Categorytype::findOne($exam_month):$exam_month;
        
        $month_name = is_numeric($exam_month)?$get_month->description:$get_month;
        $coe_batch_id = CoeBatDegReg::findOne($batch_mapping_id);

        $batch_year = Batch::findOne($coe_batch_id->coe_batch_id);
        $batch_name = $batch_year->batch_name;
        $deg_id = Degree::findOne($coe_batch_id->coe_degree_id); 
        
        if(stristr($month_name, "Oct/Nov") && ($deg_id->degree_total_semesters/$deg_id->degree_total_years)==2) // Condition for Bi Semester 
        {
            $semester = (($exam_year-$batch_name)*2)+1; // (2017-2016)*2+1 = (1*2)+1 = 3;
        }
        else if(($deg_id->degree_total_semesters/$deg_id->degree_total_years)==3) // For Tri Semester
        {
            $sem_type = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_TRISEM);
            $config_list = Categories::find()->where(['category_name' => $sem_type])->one();
            $listAllMonths = Categorytype::find()->where(['category_id'=>$config_list->coe_category_id])->orderBy('coe_category_type_id')->all();
            foreach ($listAllMonths as $key => $value) {
                $sem_types [$key] = $value['description']; 
            }
            $semester = array_search($month_name,$sem_types)+1; // +1 is for array starting from 0
        }
        else
        {
            $semester = ($exam_year-$batch_name)*2;
        }
       
        return $semester;
        // $sem_type = $degree_total_semesters/$deg_id->degree_total_years)==3 ? ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_TRISEM):
    }

    public function getSemExamYearMonth($batch_mapping_id,$semester)
    {
        if(empty($batch_mapping_id) || empty($semester))
        {
            return 0;
        } 
        $coe_batch_id = CoeBatDegReg::findOne($batch_mapping_id);
        $exam_year = date('Y');
        $batch_id = Batch::findOne($coe_batch_id->coe_batch_id);
        $batch_name = $batch_id->batch_name;
        $deg_id = Degree::findOne($coe_batch_id->coe_degree_id); 
        
        if(($deg_id->degree_total_semesters/$deg_id->degree_total_years)==2) // Condition for Bi Semester 
        {
            echo $semester = (($exam_year-$batch_name)*2)+1;  
            // (2017-2016)*2+1 = (1*2)+1 = 3;
        }
        else if(($deg_id->degree_total_semesters/$deg_id->degree_total_years)==3) // For Tri Semester
        {
            $sem_type = ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_TRISEM);
            $config_list = Categories::find()->where(['category_name' => $sem_type])->one();
            $listAllMonths = Categorytype::find()->where(['category_id'=>$config_list->coe_category_id])->orderBy('coe_category_type_id')->all();
            foreach ($listAllMonths as $key => $value) 
            {
                $sem_types [] =  $value['coe_category_type_id']; 
            }
            $getReguLar = Categorytype::find()->where(['description'=>'Regular'])->one();
          
            $loop_cout = 1;
            $semInfoMation='';
            for( $sem_val=0; $sem_val<count($sem_types); $sem_val++) 
            {
                if($loop_cout==$semester)
                {
                   $get_mark_det = MarkEntryMaster::find()->where(['month'=>$sem_types[$sem_val],'mark_type'=>$getReguLar['coe_category_type_id']])->one();
                   $semInfoMation = $get_mark_det;                  
                   break;
                }
                $loop_cout++;
            }
            $semester = ['exam_year'=>$semInfoMation['year'],'exam_month'=>$semInfoMation['month'],'semester'=>$loop_cout];
            
             // +1 is for array starting from 0
        }
        else
        {
            $semester = ($exam_year-$batch_name)*2;
        }
       
        return $semester;
        // $sem_type = $degree_total_semesters/$deg_id->degree_total_years)==3 ? ConfigUtilities::getConfigDesc(ConfigConstants::CONFIG_TRISEM):
    }
 

    public function SingleArray($arr){
        foreach($arr as $key){
            if(is_array($key)){
                $arr1=ConfigUtilities::SingleArray($key);
                foreach($arr1 as $k){
                    $new_arr[]=$k;
                }
            }
            else{
                $new_arr[]=$key;
            }
        }
        return $new_arr;
    }
   
    public function TransferStudents($student_map_id)
    {
        $get_details = Categorytype::find()->where(['description'=>'Transfer'])->one();
        
        $add_condition = $student_map_id!=''?'':'NOT IN(SELECT DISTINCT student_map_id as student_map_id FROM coe_student_category_details WHERE stu_status_id="'.$get_details->coe_category_type_id.'") ';
        
        $query = 'SELECT A.coe_student_mapping_id,B.register_number FROM coe_student_mapping AS A JOIN coe_student AS B ON B.coe_student_id =A.student_rel_id WHERE A.status_category_type_id = "'.$get_details->coe_category_type_id.'" And A.coe_student_mapping_id '.$add_condition." Order by B.register_number";
        $stu_categories = Yii::$app->db->createCommand($query)->queryAll();
        return  $stu_categories = ArrayHelper::map($stu_categories,'coe_student_mapping_id','register_number');
    }
    public function getYearOfPassing($year_of_passing)
    {
        $year_of_passing_text='';
        if($year_of_passing!='')
        {
            $split = explode('-', $year_of_passing);
            $month = Categorytype::findOne($split[0]);
            if(strpos($month->description, "/")!==FALSE)
            {
                $month_explode = explode("/", $month->description);
                $month_name = strtoupper($month_explode[1]);
                if($month_name=='MAY')
                {
                  $month_name = 'APR';
                }
            }
            else
            {
                $month_name = strtoupper($month->description);   
            }
            $year_of_passing_text = $month_name."-".$split[1];
        }
        return $year_of_passing_text; 

    }
    public function getLastYearOfPassing($reg_num)
    {
       $getStuInfo = Yii::$app->db->createCommand('SELECT * FROM stu_info where reg_num="'.$reg_num.'"')->queryOne();   
        $getLastRec = Yii::$app->db->createCommand('SELECT * FROM coe_mark_entry_master where student_map_id="'.$getStuInfo['stu_map_id'].'" and result like "%Pass%" order by coe_mark_entry_master_id desc limit 20')->queryAll();
        if(!empty($getLastRec))
        {
          $max_year = array_filter(['']);
          $max_month = array_filter(['']);
          foreach ($getLastRec as $value) {
            $max_year[] = $value['year'];
          }
          $max_year = max($max_year);
          $getLastRec = Yii::$app->db->createCommand('SELECT * FROM coe_mark_entry_master where student_map_id="'.$getStuInfo['stu_map_id'].'" and year="'.$max_year.'" and result like "%Pass%" order by coe_mark_entry_master_id desc')->queryOne();
        }
        $year_of_passing_text='';
        if(!empty($getLastRec))
        {
          $year_of_passing =  $getLastRec['year_of_passing'];          
          $split = explode('-', $year_of_passing);
          $month = Categorytype::findOne($split[0]);
          if(strpos($month->description, "/")!==FALSE)
          {
              $month_explode = explode("/", $month->description);
              $month_name = strtoupper($month_explode[1]);
              if($month_name=='MAY')
              {
                $month_name = 'APR';
              }
          }
          else
          {
              $month_name = strtoupper($month->description);   
          }
          $year_of_passing_text = $month_name."-".$split[1];
          
        }
        return $year_of_passing_text; 

    }
    public function getMonthName($month)
    {
        $month_text='';
        if($month!='')
        {            
            $month = Categorytype::findOne($month);
            $month_text = $month->description; 
        }
        return $month_text; 

    }
    public function getClassification($percentage,$regulation_year,$reg_num,$part_no=null)
    {        
        $stuInfo = StuInfo::findOne(['reg_num'=>$reg_num]);
        $getYearOfPassing = Yii::$app->db->createCommand('select * from coe_mark_entry_master as B where result not like  "%pass%" and result not like  "%COMPLETED%" and  result not like  "%EXEMPLARY%"  and   result not like  "%VERY GOOD%"  and result not like "%GOOD%" and student_map_id="'.$stuInfo['stu_map_id'].'" limit 3')->queryAll();
      // print_r($getYearOfPassing );exit;
      
        $classification_text='NO CLASS SPECIFIED';
        if($part_no==3)
        {
            if($percentage >= 9.0 && $percentage <= 10.0 && empty($getYearOfPassing))
            {
                $classification_text="FIRST CLASS - EXEMPLARY";
            }
            else if($percentage >= 7.5 && $percentage <= 8.9 && empty($getYearOfPassing))
            {
              $classification_text="FIRST CLASS WITH DISTINCTION";
            }
            else if($percentage >= 7.5 && $percentage <= 10.0 )
            {
              $classification_text="FIRST CLASS";
            }
            else if($percentage >= 6.0 && $percentage <= 7.4)
            {
              $classification_text="FIRST CLASS";
            }
            else if($percentage >= 5.0 && $percentage <= 5.9)
            {
              $classification_text="SECOND CLASS";
            }
          else if($percentage <= 4.9)
            {
              $classification_text="THIRD CLASS";
            }                 
        }
        else
        {
          
          if($percentage >= 6.0 && $percentage <= 10.0)
          {
            $classification_text="FIRST CLASS";
          }
          else if($percentage >= 5.0 && $percentage <= 5.9)
          {
            $classification_text="SECOND CLASS";
          }
          else if( $percentage <= 4.9)
          {
            $classification_text="THIRD CLASS";
          }                 
        }
        
        return $classification_text;
    }

    public function getSemesterName($coe_bat_deg_reg_id)
    {        
        $section_list = CoeBatDegReg::findOne($coe_bat_deg_reg_id); 
        $degree_type = Degree::findOne($section_list->coe_degree_id); 
        $data = round($degree_type->degree_total_semesters/$degree_type->degree_total_years);
        return $sem_type = $data==3?"TRIMESTER":'SEMESTER';
    }
    public function getCateName($category_id)
    {        
        $cat_name = Categorytype::findOne($category_id); 
        return $cat_name->description;
    }
    
    public function getSemesterRoman($semester_number)
    {        
        $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII'];        
        return $semester_array[$semester_number];
        
    }
    public function getSemesterDetails($exam_year,$batch_map_id,$semester_number)
    {        
       
        $get_batch_details = CoeBatDegReg::findOne($batch_map_id);


        return $get_batch_details;
        
    }
    public function getSubjectMappingIds($subject_id,$exam_year,$exam_month,$seme=null,$batch_map_id=null)
    {
      $add_in_theQur='';
      if(!empty($seme) && $seme!='')
      {
        $add_in_theQur = ' and A.semester="'.$seme.'" ';
      }
      if(!empty($batch_map_id) && $batch_map_id!='')
      {
        $add_in_theQur = ' and A.batch_mapping_id="'.$batch_map_id.'" ';
      }
        $subject_map_ids = Yii::$app->db->createCommand('SELECT A.* FROM coe_subjects_mapping as A Join coe_exam_timetable as B ON B.subject_mapping_id=A.coe_subjects_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=A.subject_id WHERE A.subject_id="'.$subject_id.'" and B.exam_year="'.$exam_year.'" and B.exam_month="'.$exam_month.'" '.$add_in_theQur)->queryAll();
        $subj_ids = '';
        foreach($subject_map_ids as $value)
        {
            $subj_ids .= $value['coe_subjects_mapping_id'].",";
        }
        $subj_ids = trim($subj_ids,",");

        if(strpos($subj_ids,','))
        {
            $subject_ids = array_filter(array(''=>''));
            $explode = explode(',', $subj_ids);
            for ($i=0; $i <count($explode) ; $i++) 
            { 
                $subject_ids[$explode[$i]] = $explode[$i];
            }
        }
        else
        {
            $subject_ids = $subj_ids;
        }
        
        return $subject_ids;
    }
    public function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    public function random_color() {
        return ConfigUtilities::random_color_part() . ConfigUtilities::random_color_part() . ConfigUtilities::random_color_part();
    }

    public function array_flatten($array) { 
      if (!is_array($array)) { 
        return false; 
      } 
      $result = array(); 
      foreach ($array as $key => $value) { 
        if (is_array($value)) { 
          $result = array_merge($result, ConfigUtilities::array_flatten($value)); 
        } else { 
          $result[$key] = $value; 
        } 
      } 
      return $result; 
    }
    public function getSemesterMonthName($semester_number)
    {        
        $semester_array = ['1'=>'30','2'=>'29','3'=>'30','4'=>'29','5'=>'30','6'=>'29','7'=>'30','8'=>'29'];        
        return $semester_array[$semester_number];        
    }
    /**
     *  @return array|Degree[]|mixed|object
     */
    public function getProgrammeDetails()
    {       
        $query = "SELECT coe_programme_id,programme_code FROM coe_programme";
        $degreeInfo = Yii::$app->db->createCommand($query)->queryAll();        
        return ArrayHelper::map($degreeInfo,'coe_programme_id','programme_code');
    }
    public function checkWithDrawStatus($student_map_id,$subject_mapping_id)
    {       
        $getStatus = MarkEntryMaster::find()->where(['student_map_id'=>$student_map_id,'subject_map_id'=>$subject_mapping_id,'withdraw'=>'wd'])->one();
        return !empty($getStatus) && count($getStatus)>0 ?'YES':'NO';
    }
    public function semesterNumberSend($number)
    {       
        return $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII','13'=>'XIII','14'=>'XIV','15'=>'XV','16'=>'XVI','17'=>'XVII','18'=>'XVIII','19'=>'XIX','20'=>'XX','21'=>'XXI','22'=>'XXII'];
    }
    public function getPracExamSessions()
    {       
        $pracId = Categories::find()->where(['category_name'=>'Practical Exam Session'])->one();
        $batch = Categorytype::find()->where(['category_id'=>$pracId['coe_category_id']])->all();
        return  $batch_list = ArrayHelper::map($batch,'coe_category_type_id','description');
    }
    
   public function createColumnsArray($end_column, $first_letters = '')
      {
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');
        foreach ($letters as $letter) {
            $column = $first_letters . $letter;
            $columns[] = $column;
            if ($column == $end_column)
                return $columns;
        }
        foreach ($columns as $column) {
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = createColumnsArray($end_column, $column);
                $columns = array_merge($columns, $new_columns);
            }
        }
        return $columns;
      }
     public function getTcreasons()
     {
        $Cate = Categories::find()->where(['description'=>'Transfer Reason'])->one();
        $categories = Categorytype::find()->where(['category_id'=>$Cate->coe_category_id])->all();
        return  $reason_list = ArrayHelper::map($categories,'coe_category_type_id','description');
     }
     public function getConduct()
     {
        $Cate = Categories::find()->where(['description'=>'Conduct'])->one();
        $categories = Categorytype::find()->where(['category_id'=>$Cate->coe_category_id])->all();
        return  $reason_list = ArrayHelper::map($categories,'coe_category_type_id','description');
     }
     public function getCreatedTime()
      {  
          return date("Y-m-d H:i:s");
      }
      public function getCreatedUser()
      {  
          return Yii::$app->user->getId();
      }
      public function getIpAddress()
      {  
          return Yii::$app->params['ipAddress'];
      }
     public function getAttempts($stu_map_id,$exam_year,$exam_month)
     {
        $categories = MarkEntryMaster::find()->where(['student_map_id'=>$stu_map_id])->groupBy('year,month')->all();
        return count($categories);
     }
      public function getPartDetails($register_number,$part_no)
      {
        $getStu = StuInfo::findOne(['reg_num'=>$register_number]);
        $coe_batch_id = CoeBatDegReg::findOne($getStu['batch_map_id']);

        $part_marks = 'SELECT sum(D.credit_points) as total_credits, sum(A.ESE+A.CIA) as total, ((sum(A.ESE+A.CIA)/sum(D.ESE_max+D.CIA_max))*100) as percentage, round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),5) as cgpa, sum(A.grade_point) as grade_points , sum(A.grade_point) as sec_grade_points  FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id  WHERE student_map_id="'.$getStu['stu_map_id'].'" and part_no="'.$part_no.'" and result like "%pass%" ';
        $sum = Yii::$app->db->createCommand($part_marks)->queryOne();

        $part_total_marks = 'SELECT sum(D.credit_points) as total_credits, sum(D.ESE_max+D.CIA_max) as total_max FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id  WHERE student_map_id="'.$getStu['stu_map_id'].'" and part_no="'.$part_no.'" ';
        $part_total_credits = Yii::$app->db->createCommand($part_total_marks)->queryOne();

        $part_5 = 'SELECT grade_name FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id  WHERE student_map_id="'.$getStu['stu_map_id'].'" and part_no="'.$part_no.'" ';
        $part_5_details = Yii::$app->db->createCommand($part_5)->queryOne();

        $total_add_credits_earn_cum = 'SELECT sum(credits) FROM  coe_additional_credits  WHERE student_map_id="'.$getStu['stu_map_id'].'" and part_no='.$part_no.' and result like "%pass%" group by subject_code';
        $part_adds = Yii::$app->db->createCommand($total_add_credits_earn_cum)->queryScalar();
        $part_adds = $part_adds=='' || empty($part_adds) ? 0: $part_adds;
        $total_cumulative = ($sum['total_credits']+$part_adds);
        $total_cumulative = (!empty($total_cumulative) || $total_cumulative!=0 || $total_cumulative!='') ? $total_cumulative:'--';
        $part_adds = $part_adds=='' || empty($part_adds) ? '--': $part_adds;
        $classification = ConfigUtilities::getClassification(round($sum['cgpa'],1),$coe_batch_id['regulation_year'] ,$register_number,$part_no);

        $classification = $part_no==5 ? $part_5_details['grade_name'] : $classification;

        $part_total_credits_send = (!empty($part_total_credits['total_credits']) || $part_total_credits['total_credits']!="" || $part_total_credits['total_credits']!=0) ? $part_total_credits['total_credits']:'--';

        $sum_of_tota_creds = ($sum['total_credits']==0 || empty($sum['total_credits'])) ?'--':$sum['total_credits'];
        $part_marks = ($sum['total']==0 || empty($sum['total'])) ? '--':$sum['total'];
        $percentage = (!empty($sum['percentage']) || $sum['percentage']!=0 )?round($sum['percentage'],1):'--';
        $part_cgpa = (!empty($sum['cgpa']) || $sum['cgpa']!=0) ? round($sum['cgpa'],1):'--';

        $part_total_marks = (!empty($part_total_credits['total_max']) || $part_total_credits['total_max']!="" || $part_total_credits['total_max']!=0) ? $part_total_credits['total_max']:'--';

        $part_grade_point = ConfigUtilities::getPartGradePonts($part_cgpa,$register_number);
         return $sendDetails = ['part_no'=>$part_no,'part_credits'=>$sum_of_tota_creds,'part_marks'=>$part_marks,'part_percentage'=>$percentage,'part_cgpa'=>$part_cgpa,'part_grade_point'=>$part_grade_point['grade_name'],'part_class'=>$classification,'part_additional_cred'=>$part_adds,'part_total_credits'=>$part_total_credits_send,'total_cumulative'=>$total_cumulative,'part_total_marks'=>$part_total_marks ];

      }
      public function getPartGradePonts($grade_point,$reg_num)
      {        
          $getStu = StuInfo::findOne(['reg_num'=>$reg_num]);
          $coe_batch_id = CoeBatDegReg::findOne($getStu['batch_map_id']);
          $grade_details = Regulation::find()->where(['regulation_year'=>$coe_batch_id->regulation_year])->all();
          $total_check = $grade_point*10;
          $grade = '--';
          foreach ($grade_details as $value) 
          {
              if($value['grade_point_to']!='' && $value['grade_point_from']!='')
              { 
                  if($total_check >= $value['grade_point_from'] &&  $total_check <= $value['grade_point_to'] )
                  {
                    $grade = $value['grade_name'];
                  } // Grade Point Caluclation
              } // Not Empty of the Grade Point 
          }
          return $semester_array = [ 'grade_name'=>$grade, 'grade_point'=>$grade_point ];
          
      }
      public function getCreditDetails($register_number)
      {
        $getStu = StuInfo::findOne(['reg_num'=>$register_number]);
        $coe_batch_id = CoeBatDegReg::findOne($getStu['batch_map_id']);

        $part_marks = 'SELECT sum(D.credit_points) as total_credits, sum(A.ESE+A.CIA) as total, sum(D.ESE_max+D.CIA_max) as total_max, ((sum(A.ESE+A.CIA)/sum(D.ESE_max+D.CIA_max))*100) as percentage, round (sum(A.grade_point*D.credit_points)/sum(D.credit_points),5) as cgpa, sum(A.grade_point) as grade_points , sum(A.grade_point) as sec_grade_points  FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as C ON C.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as D ON D.coe_subjects_id=B.subject_id  WHERE student_map_id="'.$getStu['stu_map_id'].'" and result like "%pass%" ';
        $sum = Yii::$app->db->createCommand($part_marks)->queryOne();

         return $sendDetails = ['part_credits'=>$sum['total_credits'],'part_marks'=>$sum['total'],'part_percentage'=>round($sum['percentage'],2),'part_cgpa'=>round($sum['cgpa'],2),'part_grade_point'=>round($sum['sec_grade_points'],1),'part_total_marks'=>$sum['total_max'] ];

      }
      public function updateTracker($data_receive)
     {  
          $UpdateTracker = New UpdateTracker();
          $UpdateTracker->subject_map_id = $data_receive['subject_map_id'];
          $UpdateTracker->exam_month = $data_receive['exam_month'];
          $UpdateTracker->exam_year = $data_receive['exam_year'];
          $UpdateTracker->student_map_id = $data_receive['student_map_id'];
          $UpdateTracker->updated_link_from = $data_receive['updated_link_from'];
          $UpdateTracker->data_updated =  $data_receive['data_updated'];
          $UpdateTracker->updated_ip_address = ConfigUtilities::getIpAddress();
          $UpdateTracker->updated_by = ConfigUtilities::getCreatedUser();
          $UpdateTracker->updated_at = ConfigUtilities::getCreatedTime();
          if($UpdateTracker->save(false))
          {
            return 1;
          }
          else{
            return 0;
          }
        }
       /**
     *  @return array|Degree[]|mixed|object
     */
    public function getPartFiveDetails($grade_name)
    {        
        $semester_array = ['A'=>'EXEMPLARY','B'=>'VERY GOOD','C'=>'GOOD','D'=>'FAIR','E'=>'SATISFACTORY',''=>'CHECK DATA']; 
     //  $semester_array = ['A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E',''=>'CHECK DATA'];  
        return $semester_array[$grade_name];
    }
    public function getStudentInfo($reg_num)
    {        
        return $stu_info = StuInfo::findOne(['reg_num'=>$reg_num]);
    }

}



?>
