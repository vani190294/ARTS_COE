<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\CoeBatDegReg;
use app\models\Degree;
use app\models\Student;
use app\models\StudentMapping;

?>

<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

<?php

    if(isset($student_mark) && !empty($student_mark))
    {
        $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII','13'=>'XIII','14'=>'XIV','15'=>'XIII','16'=>'XIII','17'=>'XIII','18'=>'XIII','19'=>'XIII','20'=>'XIII','21'=>'XIII','22'=>'XIII','23'=>'XIII','24'=>'XIII','25'=>'XIII','26'=>'XIII','27'=>'XIII','28'=>'XIII','29'=>'XIII','30'=>'XIII','31'=>'XIII','32'=>'XIII','33'=>'XIII','34'=>'XIII','35'=>'XIII','36'=>'XIII','37'=>'XIII','38'=>'XIII','39'=>'XIII','40'=>'XIII'];
        $old_year_month = '';
        $cgpa_calc = $total_credits_cgpa = $total_gpa_cgpa = $total_credits = $gpa = $total_gpa= $sgpa = $total_marks = 0;
?>
<div class="col-xs-12 pull-right">
        <div class="col-xs-12 col-sm-9 col-lg-9">
        </div>    
        <div class="col-xs-12 col-sm-1 col-lg-1">
            <?php 
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-studentmarkview','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));

            ?>
        </div>
        <div class="col-xs-3 col-sm-1 col-lg-1">
            <?php 

                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/studentmark-view-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);

            ?>
        </div>
        <div class="col-xs-3 col-sm-1 col-lg-1">
            <?php 
               
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Arrear List', ['/mark-entry-master/student-arrear-export'], [
                'class'=>'pull-right btn btn-danger', 
                'target'=>'_blank', 
                'onclick'=>"ShowStudentArrears($('#mark_view_reg_no').val())",
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
</div>

<div class="col-xs-12">
    <div class="col-xs-3 col-sm-1 col-lg-1">
        &nbsp;
    </div>
    <div class="col-xs-3 col-sm-10 col-lg-10">
<?php
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $supported_extensions = ConfigUtilities::ValidFileExtension(); 
        $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
        $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";

        $REVAl_val = Yii::$app->db->createCommand('SELECT coe_category_type_id FROM coe_category_type WHERE description="Revaluation" ')->queryScalar();
        $moderation_val = Yii::$app->db->createCommand('SELECT coe_category_type_id FROM coe_category_type WHERE description like "%moderation%" ')->queryScalar();

    $data ='<table border=1 id="checkAllFeet" align="center" ><tbody align="center">'; 

    $data.='<tr>
                <td colspan=2> 
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan=8 align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                </td>
                <td  colspan=2 align="center">  
                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>   
            </tr>';
                if(isset($_SESSION['stu_arrear_subject']))
                {
                    unset($_SESSION['stu_arrear_subject']);
                }
            $_SESSION['stu_arrear_subject'] = $_POST['mark_view_reg_no'];
    $stu_dob_display = Student::find()->where(['register_number'=>$_POST['mark_view_reg_no']])->one();
    $data.='<tr style="background: #194d33;color: #fff;">
                <td colspan="12" align="center"><h3 style="background: #194d33;color: #fff;"><b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Mark View </b></h3>
                </td>
            </tr>
            <tr style="background: #0000ff;color: #fff;">
                <td colspan="12" align="center"><h4 style="background: #0000ff;color: #fff;"><b>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' DATE OF BIRTH : '.DATE('d-m-Y',strtotime($stu_dob_display['dob'])).' </b></h3>
                </td>
            </tr>';

    $files = glob($absolute_dire.$_POST['mark_view_reg_no'].".*");
     if (count($files) > 0)
        { 
            foreach ($files as $file)
             {
                $info = pathinfo($file);
                $extension = ".".$info["extension"];
             }
        }
         else
         {
            $extension="";
         }
        
    $photo_extension = ConfigUtilities::match($supported_extensions,$_POST['mark_view_reg_no'].$extension); 
    $stu_photo = $photo_extension!="" ? $stu_directory.$_POST['mark_view_reg_no'].".".$photo_extension:$stu_directory."stu_sample.jpg";
    $prev_reg_num =$student_detail['previous_reg_number'];

    if(!empty($student_detail['previous_reg_number']))
    {
        $get_loop_1 = Yii::$app->db->createCommand('SELECT year,month,student_map_id, course_batch_mapping_id,mark_type FROM coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id WHERE C.register_number="'.$student_detail['previous_reg_number'].'" and mark_type=27 group by year,month,student_map_id order by year,month')->queryAll();
    }
    $get_loop = Yii::$app->db->createCommand('SELECT year,month,student_map_id, course_batch_mapping_id,mark_type FROM coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id WHERE C.register_number="'.$_POST['mark_view_reg_no'].'" and mark_type=27 group by year,month,student_map_id order by year,month')->queryAll();
    
    if(!empty($student_detail['previous_reg_number']))
    {
        $get_loop = array_merge($get_loop_1,$get_loop);
    } 

    $table_insert ='<table border=1 style="font-weight: bold;" width="100%">
    <tr>
       <td><b>Part</td>
       <td><b>Credits</td>
       <td><b>Marks</td>
       <td><b>Percentage</td>
       <td><b>CGPA</td>
        <td><b>Grade</td>
          <td><b>Class</td>
        <td><b>Add Credits</td>
    </tr>';

    $sem_loop=1;
    $color = dechex(rand(0x000000, 0xFFFFFF)); 
    $last_reg_year = $last_reg_month =  $last_attempt = '';

    for ($i=1; $i <= 5 ; $i++) 
      { 
         $part_info = ConfigUtilities::getPartDetails($_POST['mark_view_reg_no'],$i);
         $display_marks = $part_info['part_marks']=='-'?'-':$part_info['part_marks']."/".$part_info['part_total_marks'];
         $display_creds = $part_info['part_credits']=='-'?'-':$part_info['part_credits']."/".$part_info['part_total_credits'];
        $table_insert .="
        <tr>
      <td valign='top'  class='line_height print_cgpa_size'  width='100px;' > ".$semester_array[$i]." </td>
      <td valign='top'  class='line_height print_cgpa_size'  width='100px;' > ".$display_creds." </td>
      <td valign='top'  class='line_height print_cgpa_size' width='160px;' > ".$display_marks." </td>
      <td valign='top'  class='line_height print_cgpa_size'  width='190px;' > ".$part_info['part_percentage']." </td>
      <td valign='top'  class='line_height print_cgpa_size'  width='110px;' > ".$part_info['part_cgpa']." </td>
      <td valign='top'  class='line_height print_cgpa_size'  width='80px;' > ".$part_info['part_grade_point']."  </td>
      <td valign='top'   class='line_height print_cgpa_size print_class' width='200px'  > ".$part_info['part_class']." </td>
      <td valign='top'   class='line_height print_cgpa_size'  width='60px;'  >".$part_info['part_additional_cred']." </td>                                                             
  </tr>
        ";
      }

    if(!empty($student_detail['previous_reg_number']))
    {
        $last_attempt = $last_attempt." <br /><br /> Previous Reg Number : <b style='color: #1a0363'>".$student_detail['previous_reg_number']."</b>";
    }

    $table_insert .='</table>';
    $data_1 = '';

    $data_1.="<tr><td colspan=3><center>".$student_detail['name']."</center></td>";
    $data_1.="<td style='background: #158e39; color: #fff; padding:2px;' colspan=2><center><b>".$student_detail['register_number']."</b></center></td>";
    $data_1.="<td colspan=2><center>".$student_detail['batch_name']."</center></td>";
    $data_1.="<td colspan=2><center>".$student_detail['degree_code']." ".$student_detail['programme_code']."</center></td>";
    
    $add_css_stu = $student_detail['student_status']=='Detain' || $student_detail['student_status']=='Detain/Debar' || $student_detail['student_status']=='Discontinued' ? "style='background: #5C0099; padding: 5px; color: #fff;'" : "style='background: #158e39; color: #fff; padding:2px;'";
    $data_1.="<td ".$add_css_stu." colspan=3 ><center>".$student_detail['student_status']."</center></td></tr>";
    

    $data.='<tr>
                <td colspan="3" align="left">
                    <img class="img-responsive" width=150 height=150 src="'.$stu_photo.'" alt="'.$stu_photo.'" Photo >
                </td>
                <td colspan="3" align="left">
                    <h3 class="stu_mark_head">LAST ATTEMPT : '.$last_attempt.'</h3>
                </td>
                <td colspan="6" align="left"><h4><b>Semester Wise Performance</b></h4>'.$table_insert.'
                </td>
            </tr>';

   
    if($student_detail['student_status']=='Transfer')
    {
        if(isset($student_detail["stu_map_id"]) && !empty($student_detail["stu_map_id"]))
        {
            $get_loop_2 = Yii::$app->db->createCommand('SELECT * FROM coe_student_category_details WHERE student_map_id="'.$student_detail["stu_map_id"].'"')->queryAll();
        }    
        $data.="<tr>                    
                <td colspan=12 style='text-align: center;'><h3> Transfer Information</h3>
                </td>
            </tr>";
       $data.="<tr>
                <th width='40px'><center>Semester</center></th>
                <th colspan=2><center>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code</center></th>
                <th><center>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Name</center></th>
                <th><center>CREDITS</center></th>
                <th><center>CIA</center></th>
                <th><center>ESE</center></th>
                <th><center>Total</center></th>
                <th><center>Result</center></th>
                <th><center>Grade Point</center></th>                   
                <th><center>Grade Name</center></th>
                <th><center>Year of Passing</center></th>
            <tr>";        

        foreach ($get_loop_2 as $key => $Transfer) 
        {
            $data .= "
                <tr>
                    <td width='40px' align='left'>" . $semester_array[$Transfer['semester']]. "</td>
                    <td width='80px' name='".$Transfer['subject_map_id']."'  align='left'>" . strtoupper($Transfer['subject_code']) . "</td>
                    <td width='450px' colspan='5'  align='left'>" . strtoupper(wordwrap($Transfer['subject_name'],6)) . "</td>
                    <td width='37px'  align='left'>" . $Transfer['credit_point']  . "</td>
                    <td width='37px'  align='left'>" . $Transfer['total']  . "</td>
                    <td width='37px'  align='left'> " . strtoupper($Transfer['grade_name']) . " </td>
                    <td width='37px'  align='left'>" . $Transfer['grade_point'] . "</td>
                    <td width='65px'  align='right'>" . $Transfer['result']  . "</td>
                </tr>
                ";
        }    
        $data .='<tr><td height=30 colspan=12>&nbsp;</td></tr>';
    }

 $data.="<tr>                   
                <td colspan=12 style='text-align: center;'><h3>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Information</h3>
                </td>
            </tr>";


    $data.="<tr colspan=12>             
                <th colspan=3><center>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Name</center>
                </th>
                <th colspan=2><center>Register Number </center></th>
                <th colspan=2><center>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)."</center></th>
                <th colspan=2><center>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)."</center></th>
                <th colspan=3><center>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Status</center></th>
            </tr>".$data_1;             

    $data.="<tr>                    
                <td colspan=12 style='text-align: center;'><h3>Mark Information</h3></td>
            </tr>";

        $data.="<tr>
                    <th width='40px'><center>Semester</center></th>
                    <th><center>PART NO</center></th>
                    <th colspan=2><center>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code</center></th>
                    <th><center>".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Name</center></th>
                    
                    <th><center>CREDITS</center></th>
                    <th><center>CIA</center></th>
                    <th><center>ESE</center></th>
                    <th><center>Total</center></th>
                    <th><center>Result</center></th>
                    <th><center>Year</center></th>                   
                    <th><center>Month</center></th>
                    <th><center>Year of Passing</center></th>
                <tr>";
                $sem_inc = 1;

                if(!empty($student_detail['previous_reg_number']))
                {
                    $fetch_data_1 = 'SELECT year,month,mark_type,student_map_id,course_batch_mapping_id as bat_map_val FROM coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id where C.register_number="'.$student_detail['previous_reg_number'].'" and mark_type=27 group by year,month,mark_type order by year,month,mark_type';
                    $fetch_month_1 = Yii::$app->db->createCommand($fetch_data_1)->queryAll();


                    $man_fetch_data_1 = 'SELECT year,month,mark_type, student_map_id, course_batch_mapping_id as bat_map_val FROM coe_mandatory_stu_marks as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id where C.register_number="'.$student_detail['previous_reg_number'].'" and mark_type=27 group by year,month,mark_type order by year,month,mark_type';
                    $man_fetch_month_1 = Yii::$app->db->createCommand($man_fetch_data_1)->queryAll();

                    $fetch_month_1 = array_filter($fetch_month_1);
                    $man_fetch_month_1 = array_filter($man_fetch_month_1);

                    if(!empty($man_fetch_month_1) && !empty($fetch_month_1))
                    {
                        $fetch_month_1 = array_merge($fetch_month_1,$man_fetch_month_1);
                    }

                    $fetch_all_data = 'SELECT distinct month as month,year FROM coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id where register_number="'.$student_detail['previous_reg_number'].'" ';
                    $fetch_month_all = Yii::$app->db->createCommand($fetch_all_data)->queryAll();
                    $fetch_month_arr_aa = array_filter([]);
                    if(count($fetch_month_all)>0)
                    {
                        for ($aaa=0; $aaa <count($fetch_month_all); $aaa++) 
                        { 
                             $fetch_arr_data_1 = 'SELECT year,month,mark_type,student_map_id,course_batch_mapping_id as bat_map_val FROM coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id where C.register_number="'.$student_detail['previous_reg_number'].'" and year="'.$fetch_month_all[$aaa]['year'].'" and month="'.$fetch_month_all[$aaa]['month'].'" group by year,month,mark_type  order by year,month,mark_type ';
                            $fetch_month_arr_aa[]= Yii::$app->db->createCommand($fetch_arr_data_1)->queryOne();
                        }
                        
                    }
                    if(!empty($fetch_month_arr_aa))
                    {
                        $fetch_month_1 = array_merge($fetch_month_1,$fetch_month_arr_aa);
                        $fetch_month_1 = array_map('unserialize',array_unique(array_map('serialize',$fetch_month_1)));
                    }

                }

                $fetch_data = 'SELECT year,month,mark_type,student_map_id,course_batch_mapping_id as bat_map_val FROM coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id where C.register_number="'.$reg_num.'" and mark_type=27 group by year,month order by year,month,mark_type';
                $fetch_month = Yii::$app->db->createCommand($fetch_data)->queryAll();
                $old_year_month = $new_year_month = '';

                $stureDe = Student::find()->where(['register_number'=>$reg_num])->one();
                $stuMapIdS = StudentMapping::find()->where(['student_rel_id'=>$stureDe->coe_student_id])->one();
                $get_mapping = CoeBatDegReg::findOne($stuMapIdS->course_batch_mapping_id);
                $get_degree = Degree::findOne($get_mapping->coe_degree_id);


                $fetch_supp_data = 'SELECT distinct month as month,year FROM coe_mark_entry_master where student_map_id="'.$stuMapIdS->coe_student_mapping_id.'" ';
                $fetch_month_supp = Yii::$app->db->createCommand($fetch_supp_data)->queryAll();
                $stu_map_hide_id = $stuMapIdS->coe_student_mapping_id;
                $fetch_month_arr = array_filter([]);
                if(count($fetch_month_supp)>0)
                {
                    for ($abc=0; $abc <count($fetch_month_supp); $abc++) 
                    { 
                        $fetch_arr_data = 'SELECT year,month,mark_type,student_map_id,course_batch_mapping_id as bat_map_val FROM coe_mark_entry_master as A JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id JOIN coe_student as C ON C.coe_student_id=B.student_rel_id where C.register_number="'.$reg_num.'" and year="'.$fetch_month_supp[$abc]['year'].'" and month="'.$fetch_month_supp[$abc]['month'].'" group by year,month,mark_type  order by year,month,mark_type ';
                        $fetch_month_arr[]= Yii::$app->db->createCommand($fetch_arr_data)->queryOne();
                    }
                    
                }
               
                if(!empty($student_detail['previous_reg_number']))
                {
                    $fetch_month = array_merge($fetch_month_1,$fetch_month);
                    $fetch_month = array_map('unserialize',array_unique(array_map('serialize',$fetch_month)));
                }
                if(isset($fetch_month_arr) && !empty($fetch_month_arr))
                {
                    $fetch_month = array_merge($fetch_month, $fetch_month_arr);
                    $fetch_month = array_map('unserialize',array_unique(array_map('serialize',$fetch_month)));
                }
                array_multisort(array_column($fetch_month, 'year'),  SORT_ASC, $fetch_month);
                $fetch_month = array_map('unserialize',array_unique(array_map('serialize',$fetch_month)));
                $pre_stu_map_id = '';
                foreach ($fetch_month as $value_data) 
                {

                    $semester_name = ConfigUtilities::getSemesterName($value_data['bat_map_val']);
                    $print_sem = ConfigUtilities::SemCaluclation($value_data['year'],$value_data['month'],$value_data['bat_map_val']);

                    $query_get = 'SELECT A.subject_code,A.subject_name,B.semester,A.CIA_max,A.credit_points,C.year,A.ESE_max,A.total_minimum_pass as min_pass,C.CIA,C.ESE,C.total,C.year_of_passing,C.grade_name,C.grade_point,part_no,C.mark_type,C.result,C.withheld,F.description,C.subject_map_id,C.student_map_id FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id JOIN coe_mark_entry_master as C ON C.subject_map_id=B.coe_subjects_mapping_id JOIN coe_category_type as F ON F.coe_category_type_id=C.month WHERE C.year="'.$value_data['year'].'" AND C.month="'.$value_data['month'].'" AND C.student_map_id="'.$value_data['student_map_id'].'" group by year,month,subject_map_id order by B.semester,paper_type_id';
                    $get_all_details = Yii::$app->db->createCommand($query_get)->queryAll();


                    $man_query_get = 'SELECT CONCAT(A.subject_code,"-",B.sub_cat_code) as subject_code,A.subject_name,C.semester,A.CIA_max,credit_points,C.year,A.ESE_max,A.total_minimum_pass as min_pass,C.CIA,C.ESE,C.total,C.year_of_passing,C.grade_name,C.grade_point,C.mark_type,C.result,C.withheld,part_no,F.description,B.is_additional,C.subject_map_id,C.student_map_id FROM coe_mandatory_subjects as A JOIN coe_mandatory_subcat_subjects as B ON B.man_subject_id=A.coe_mandatory_subjects_id JOIN coe_mandatory_stu_marks as C ON C.subject_map_id=B.coe_mandatory_subcat_subjects_id JOIN coe_category_type as F ON F.coe_category_type_id=C.month WHERE C.year="'.$value_data['year'].'" AND C.month="'.$value_data['month'].'" AND C.student_map_id="'.$value_data['student_map_id'].'" group by year,month,subject_map_id order by C.semester';
                    $get_man_all_details = Yii::$app->db->createCommand($man_query_get)->queryAll();
                    if(!empty($get_man_all_details))
                    {
                        $get_all_details = array_merge($get_all_details,$get_man_all_details);
                    }
                    
                    $mont_name_display = Categorytype::findOne($value_data['month']);
                    $print_mark_type = $value_data['mark_type']=='27' ? 'REGULAR' : 'ARREAR';
                    $text_add = !empty($pre_stu_map_id) && $pre_stu_map_id!=$value_data['student_map_id'] ?'PREV REG NUM ATTEMPT':'';
                    $print_sem = $value_data['year']." - ".strtoupper($mont_name_display->description)." ".$text_add;

                    $data.='<tr><td align="center" colspan=12> <span style="display: none;">'.$stu_map_hide_id.'</span> <h3 style="color: #138711; font-weight: bold;" > <b style="color: #0d4fba;">'.$print_mark_type." </b> ".$semester_name.'  <b style="color: #b34700;">'.$print_sem.'</b>  DETAILS</h3> </td></tr>';
                    $pre_stu_map_id = $value_data['student_map_id'];
                    foreach ($get_all_details as $stu_mark) 
                    {
                        $check_reval = Yii::$app->db->createCommand('SELECT * FROM coe_mark_entry WHERE category_type_id="'.$REVAl_val.'" and student_map_id="'.$stu_mark['student_map_id'].'" AND subject_map_id="'.$stu_mark['subject_map_id'].'" AND year="'.$value_data['year'].'" and month="'.$value_data['month'].'"')->queryOne();

                        $check_moderation = Yii::$app->db->createCommand('SELECT * FROM coe_mark_entry WHERE category_type_id="'.$moderation_val.'" and student_map_id="'.$stu_mark['student_map_id'].'" AND subject_map_id="'.$stu_mark['subject_map_id'].'" AND year="'.$value_data['year'].'" and month="'.$value_data['month'].'"')->queryOne();

                        $grade_name = $stu_mark['CIA']==0 && $stu_mark['ESE']==0 && $stu_mark['total']==0 && $stu_mark['min_pass']==0 ? "" : $stu_mark['grade_name'];
                        
                        $add_css =$grade_name=='WD' ?'style="background: #38007a !important; color: #FFF !important;"':'';
                        
                        $add_css =$stu_mark['withheld']=='w' || $stu_mark['withheld']=='W' ?'style="background: #c14603 !important; color: #FFF !important;"':$add_css;

                        $add_css =isset($stu_mark['is_additional']) ?'style="background: #890c01 !important; color: #FFF !important;"':$add_css;
                        $add_css =!empty($check_reval) ?'style="background: #0182a3 !important; color: #FFF !important;"':$add_css;
                        $add_css =!empty($check_moderation) ?'style="background: #cc00ff !important; color: #FFF !important;"':$add_css;
                       
                        $year_of_passing = $stu_mark['year_of_passing']=='' || empty($stu_mark['year_of_passing']) ? '---' : $stu_mark['description']."-".$stu_mark['year'];

                        $data.='<tr '.$add_css.' ><td width="40px">'.$semester_array[$stu_mark['semester']].'</td>';
                        $data.='<td align="left">'.$semester_array[$stu_mark['part_no']].'</td>';
                        $data.='<td align="left" style= "student_map_id= '.$stu_mark['student_map_id'].' and subject_map_id='.$stu_mark['subject_map_id'].' " colspan=2>'.$stu_mark['subject_code'].'</td>';
                        $data.='<td align="left">'.$stu_mark['subject_name'].'</td>';
                        
                        if($stu_mark['CIA']==0 && $stu_mark['ESE']==0 && $stu_mark['total']==0 && $stu_mark['min_pass']==0)
                        {
                            $data.='<td colspan=4> &nbsp; </td>';
                        }
                        else
                        {
                            $data.='<td>'.$stu_mark['credit_points'].'</td>';
                            $data.='<td>'.$stu_mark['CIA'].'</td>';
                            $data.='<td>'.$stu_mark['ESE'].'</td>';
                            $data.='<td>'.$stu_mark['total'].'</td>';
                        }

                        if($stu_mark['withheld']=='w' || $stu_mark['withheld']=='W')
                        {
                            $data.='<td>'.$stu_mark['result'].' ('.$stu_mark['withheld'].') </td>';
                        }else if($stu_mark['CIA']==0 && $stu_mark['ESE']==0 && $stu_mark['total']==0 && $stu_mark['min_pass']==0)
                        {
                            
                            //$comp_status = $stu_mark['result']=='Pass' || $stu_mark['result']=='PASS' || $stu_mark['result']=='pass' ? 'COMPLETED' : 'NOT COMPLETED';
                            $comp_status = $stu_mark['result']=='Pass' || $stu_mark['result']=='PASS' || $stu_mark['result']=='pass' ? 'Pass' : 'Fail';
                        
                            $data.='<td colspan=2>'.$comp_status.'</td>';
                        } 
                        else{
                            $data.='<td>'.$stu_mark['result'].'</td>';
                        } 

                        if($stu_mark['CIA']==0 && $stu_mark['ESE']==0 && $stu_mark['total']==0 && $stu_mark['min_pass']==0)
                        {
                            $data.='<td colspan=3> &nbsp; </td>';
                        }
                        else
                        {
                            $data.= '<td>'.$stu_mark['year'].'</td>'; 
                            $data.='<td>'.strtoupper($mont_name_display->description).'</td>';
                            $data.='<td>'.$year_of_passing.'</td>'; 
                        }
                        
                    }
                    if($value_data['mark_type']=='27')
                    {
                        $data.='<tr> <td align="right" colspan=12> <h4 style="color: #2157c4; font-weight: bold;" > SGPA : '.$cgpa_calc['gpa'].' </h4> </td></tr>';    
                        $sem_inc++;
                    }
                    $print_student_map_id = $value_data['student_map_id'];
                    $batch_mapping_id = $value_data['bat_map_val'];
                }

                $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE  student_map_id="'.$print_student_map_id.'"')->queryAll();
                        
                if(!empty($check_add))
                {
                    $data.='<tr><td align="center" colspan=12> <h3 style="color: #138711; font-weight: bold;" > <b style="color: #0d4fba;"> ADDITIONAL CREDITS SUBJECT DETAILS</h3> </td></tr>';

                    foreach ($check_add as $valuess) 
                    {
                        $grade_point_print = $valuess['grade_point']==0?"-": (strlen($valuess['grade_point'])==1 ? $valuess['grade_point'].'.0' : $valuess['grade_point']) ;
                        $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                        
                        $sem_nu = ConfigUtilities::semCaluclation($valuess['exam_year'],$valuess['exam_month'],$batch_mapping_id);
                        $mont_name_display = Categorytype::findOne($valuess['exam_month']);
                        $data .= "
                        <tr>
                            <td width='40px' align='left'>" . $semester_array[$sem_nu]. "</td>
                            <td width='80px'  align='left'>" . strtoupper($valuess['subject_code']) . "</td>
                            <td width='450px' colspan='6'  align='left'>" . strtoupper(wordwrap($valuess['subject_name'],6)) . "</td>
                            <td width='37px'  align='left'>" . $valuess['credits']  . "</td>
                            <td width='37px'  align='left'>" . $valuess['total']  . "</td>
                            <td width='37px'  align='left'>" . $valuess['exam_year']  . "</td>
                            <td width='37px'  align='left'>" . $mont_name_display['description']  . "</td>
                            
                            <td width='65px'  align='right'>" . $result_stu . "</td>
                        </tr>
                        ";
                    }
                   
                }
               

    $data.='<tr>
                <td colspan=12 ><h2>#### END OF STATEMENT ###</h2></td>
                </tr>
                </tbody>';        
    $data.='</table><br /><br />';
    $data.='<table class="color_identification"  width="40%" border=1>
                <tr>
                    <th colspan=2 ><h2>COLOR IDENTIFICATION</h2></th>
                </tr>
                <tr>
                    <td class="maroon_identification" > &nbsp; </td>
                    <td style="font-weight: bold;">MANDATORY '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' </td>
                </tr>
                <tr>
                    <td class="withheld_iden" > &nbsp; </td>
                    <td style="font-weight: bold;">WITHHELD</td>
                </tr>
                <tr>
                    <td class="moderation_IDEN" > &nbsp; </td>
                    <td style="font-weight: bold;">MODERATION</td>
                </tr>
                <tr>
                    <td class="withdraw_IDEN" > &nbsp; </td>
                    <td style="font-weight: bold;">WITHDRAW</td>
                </tr>
                <tr>
                    <td class="reval_drwaw" > &nbsp; </td>
                    <td style="font-weight: bold;">REVALUATION</td>
                </tr>
            </table>';
    if(isset($_SESSION['studentmarkview_print'])){ unset($_SESSION['studentmarkview_print']);}
    $_SESSION['studentmarkview_print'] = $data;
    echo $data;


    }
?>
</div>
<div class="col-xs-3 col-sm-1 col-lg-1">
        &nbsp;
    </div>
</div>