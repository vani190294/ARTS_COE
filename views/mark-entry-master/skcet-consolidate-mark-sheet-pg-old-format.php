<?php 
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\CoeBatDegReg;
use app\models\Degree;
use app\models\Programme;
$this->registerCssFile("@web/css/skcet_non_cbcs_pg_newmarkstatement.css");
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?> 

<?php


if(isset($get_console_list) && !empty($get_console_list))
{       
        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
        /* 
        *   Already Defined Variables from the above included file
        *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
        *   use these variables for application
        *   use $file_content_available="Yes" for Content Status of the Organisation
        */
       
        $supported_extensions = ConfigUtilities::ValidFileExtension(); 
        $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
        $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";

        $add_tr_starting = $print_trimester == 1?"<table border='0' width='100%' ><tr><td valign='top' class='print_tri_sem' colspan='11' > &nbsp; </td></tr></table>":"<table border='0' width='100%' ><tr><td valign='top' class='no_print_tri_sem' colspan='11' >&nbsp; </td></tr></table>";
        
            $html = "";
            $previous_subject_code= $splitted_body = "";
            $previous_reg_number = $old_sem = "";
            $header = "";
            $body ="";
            $footer = "";
            $print_register_number="";
            $new_stu_flag = $total_subs_count  = $is_additional_printed = 0;
            $print_stu_data="";
            $print_student_map_id = "";
            $exam_year='';
            $app_month_name='';
            $batch_mapping_id='';
            $cgpa_calc = [];
            $html = "";
            $previous_subject_code = "";
            $previous_reg_number = "";
            $header = "";
            $body = "";
            $footer = "";
            $print_register_number = "";
            $new_stu_flag = 0;
            $print_stu_data = "";
            $exam_year = '';
            $app_month = '';
            $batch_mapping_id = '';
            $first_reg_num = 0;
            $date_print = isset($date_print) ? date('d-m-Y',strtotime($date_print)) : date('d-m-Y');
            $date = "<b>".$date_print."</b>";
        $date_insert = "<tr><td  align='left' valign='bottom' class='date_style' colspan='11' width='300px' style='font-size: 13px;' >".$date."</td></tr> ";

    $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
            echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry-master/consolidate-skcet-old-non-cbcs-pdf'], [
        'class' => 'pull-right btn btn-primary',
        'target' => '_blank',
        'data-toggle' => 'tooltip',
        'title' => 'Will open the generated PDF file in a new window'
    ]);

            echo "<br /><br />";
            $open_div ='<div class="row"><div class="col-xs-12"><div class="col-xs-12 col-sm-1 col-lg-1">&nbsp;</div><div class="col-xs-12 col-sm-10 col-lg-10"><div class="col-xs-12 col-sm-1 col-lg-1"></div>';
            $close_div = "<br /><br /></div></div>";
            $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X'];

            foreach ($get_console_list as  $value) 
            {
                    
                if($previous_reg_number!=$value['register_number'])
                {                    
                    $new_stu_flag=$new_stu_flag + 1;
                    if($new_stu_flag > 1) 
                    {
                        $semester_number = ConfigUtilities::semCaluclation($exam_year,$app_month,$batch_mapping_id);
                        $course_batch_mapping_id =  CoeBatDegReg::findOne($batch_mapping_id);
                        $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
                        $total_semesters = $degree_years->degree_total_semesters;
                        $colspan_merge = (11-$total_semesters);
                        $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$print_student_map_id,$semester_number);
                        $final_cgpa = $cgpa_calc['final_cgpa'];
                        $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
                    $cumulative_row = "<tr height='15px'>
                            <td class='make_bold_font font_bigger'  width='20px' height='15px' valign='top' align='center' >  <b>" . $cgpa_calc['consolidate_cre'] . "</b> </td>
                            
                            <td class='make_bold_font font_bigger'  width='60px'  height='15px' colspan='6' align='center' valign='top' > <b>" . $cgpa_calc['final_cgpa'] . "</b> </td>
                            <td colspan='4' width='20px' align='center' ><b>" . $classification . " </td>
                           
                            </tr>";

                        $footer .=$cumulative_row . $date_insert."<tr><td colspan='11' width='30px' height='69px' >&nbsp;</td></tr></table>";

                       
                            
                            $body .= "
                                <table border=0   >
                                    <tr>
                                        <td  class='make_bold_font font_bigger' width='400px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                        
                                        <td colspan='4' width='170px'  align='right'>&nbsp;</td>
                                    </tr>
                                </table>";
                        
                            $merge_body = "<tr><td width='100%' valign='top' colspan='11' height='625px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='58px' >&nbsp;</td></tr>";
                        

                       
                        $html = $header . $merge_body .  $footer."<pagebreak />";
                        $print_stu_data .= $html;
                        $header = "";
                        $body ="";
                        $footer = "";
                        $header = "";
                        $body = "";
                        $footer = ""; $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
                        $new_stu_flag = 1;
                        unset($cgpa_calc); $cgpa_calc = [];
                    }   
                    $files = glob($absolute_dire.$value['register_number'].".*"); // Will find 2.JPG, 2.php, 2.gif
                    // Process through each file in the list
                    // and output its extension

                    if (count($files) > 0)
                    foreach ($files as $file)
                     {
                        $info = pathinfo($file);
                        $extension = ".".$info["extension"];
                     }
                     else
                     {
                        $extension="";
                     }
                     $exam_year = $value['year'];
                    $app_month = $value['month'];
                    $add_month = $value['month'];
                    $batch_mapping_id = $value['course_batch_mapping_id'];
                    $semester_number = ConfigUtilities::semCaluclation($exam_year, $app_month, $batch_mapping_id);
                    $course_batch_mapping_id = CoeBatDegReg::findOne($batch_mapping_id);
                    $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
                    $total_semesters = $degree_years->degree_total_semesters;
                    $changeCssClass = 'print_credit_points';
                    $photo_extension = ConfigUtilities::match($supported_extensions,$value['register_number'].$extension);
                
                    $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg"; 

                    $header .="<table width='100%'  autosize='1' >";
                    $header .= '
                        <tr>
                            <td height="85px" class="print_mba" colspan="4" >&nbsp;
                            </td>
                           <td height="100px" style="border: none; text-align: right; margin-top: 0px;" colspan="7" >
                                <img class="img-responsive" width="100" height="100" src="' . $stu_photo . '" alt="' . $stu_photo . '" Photo >
                                
                            </td>
                        </tr>';
                    $print_gender = $value['gender']=='F'?'FEMALE':'MALE';
                    $exam_year=$value['year'];
                    $app_month = $value['month'];
                    $app_month_name = $value['month']=='Oct/Nov' ||  $value['month']=='OCT/NOV' ||  $value['month']=='oct/nov' ?'NOV':'APR';
                    if($value['month']=='Oct/Nov' ||  $value['month']=='OCT/NOV' ||  $value['month']=='oct/nov')
                    {
                        $app_month_name ="NOV";
                    }
                    else if($value['month']=='April/May' ||  $value['month']=='APRIL/MAY' ||  $value['month']=='april/may')
                    {
                        $app_month_name ="APR";
                    }
                    else
                    {
                        $app_month_name =strtoupper($value['month']);
                    }
                    $batch_mapping_id=$value['course_batch_mapping_id'];
                    $stu_last_exam = "SELECT CONCAT(month,'-',year) FROM coe_mark_entry_master AS a join coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id where A.student_map_id='".$value['student_map_id']."' order by coe_mark_entry_master_id desc";
                    $last_appe = Yii::$app->db->createCommand($stu_last_exam)->queryScalar();
                    $last_appearance =  ConfigUtilities::getLastYearOfPassing($value['register_number']) ;
                    $dob= date('d/m/Y',strtotime($value['dob']));
                    if($value['degree_code']=="MBABISEM" )
                    {
                        $deg =strtoupper($value['programme_name']);
                    }
                    else
                    {
                        $deg =strtoupper($value['degree_code']) . ". ". strtoupper($value['programme_name']);
                    }
                    
                    $header .="
                    <tr class='take_top' >
                        <td class='header_print_stu' colspan='11' width='100%' >
                            <table width='100%' align='left' style='border: none !important;   padding-top: 30px;'  border='0'  >
                                <tr>
                                    <td class='line_height line_height_reduce' colspan='4' width='50px;' style='width: 40px;' >&nbsp;</td>
                                    <td class='line_height line_height_reduce' colspan='5' align='left' style='padding-left: 62px;' > <b>" . strtoupper($value['name']) . "</b> </td>
                                    <td class='line_height line_height_reduce' style='padding-right: -5px;' colspan='2' align='left' ><b>".$last_appearance."</b></td>
                                </tr>
                            
                                <tr>
                                    <td class='line_height line_height_reduce' colspan='4' style='width: 50px;'>&nbsp;</td>
                                    <td class='line_height line_height_reduce' align='left'  colspan='2' style='padding-left: 62px;'><b>" . strtoupper($value['register_number']) . "</b> </td>
                                    <td class='line_height line_height_reduce' style='padding-left: 75px;' align='left' colspan='3' ><b>" . $dob . "</b></td>
                                    <td class='line_height line_height_reduce' style='padding-right: -5px;' align='left' colspan='2' > <b>" . strtoupper($print_gender) . "</b></td>
                                </tr>
                                <tr>
                                    <td class='line_height line_height_reduce' colspan='4' width='45px;' style='width: 45px;'>&nbsp;</td>
                                    <td class='line_height line_height_reduce' align='left' colspan='5' style='padding-left: 62px;'> <b>" . strtoupper($deg) . "</b></td>
                                    <td class='line_height line_height_reduce' style='padding-right: -5px;' colspan='2' align='left' ><b>".$value['regulation_year']."</b></td>
                                </tr>
                               <tr>
                                    <td class='line_height' colspan='11' class='stu_sub_gap' >&nbsp; </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                    
                    ";
                    
         $total_credits ='';
         $total_earned_credits ='';
         $passed_grade_points ='';
         $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);

           if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) 
            {
                $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = strtoupper(nl2br($sub_na));
                $subject_name_print = str_replace("&AMP;","&",$subject_name_print);

                $result_stu = $value['result'] == "Pass" || $value['result'] == "PASS" || $value['result'] == "pass"? "COMPLETED" : "NOT COMPLETED";

                $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left' >" . $semester_array[$value['semester']]. "</td>
                            <td width='67px'  align='left'>" . $value['subject_code'] . "</td>
                            <td width='180px' class='put_margin' colspan='5'  align='left'>" . $subject_name_print . "</td>
                            <td colspan='4' width='105px'  align='center'> ".$result_stu." </td>
                        </tr>
                    </table>";
                $total_subs_count++;
            } 
            else 
            {
                $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "RA" : $value['result'];
                
                $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;
    
                $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;

                $result_stu = ($value['grade_name'] == "WD" || $value['grade_name'] == "wd") ? "RA" : $result_stu;
               

                $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = strtoupper(nl2br($sub_na));
                $subject_name_print = str_replace("&AMP;","&",$subject_name_print);

                $grade_name = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? 'AB' : $value['grade_name'];

                $grade_name = ($value['grade_name'] == "WH" || $value['grade_name'] == "wh") ? 'RA' : $grade_name;
                $grade_name = ($value['grade_name'] == "WD" || $value['grade_name'] == "wd") ? 'W' : $grade_name;

                

                $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
                $grade_point_print = $grade_name=='RA'?"-":$grade_point_print;
               

                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];
                $disp_ese_max = ($value['ESE_max']+$value['CIA_max'])!=0?($value['ESE_max']+$value['CIA_max']):'-';
                $disp_ese_marks = ($value['CIA']+$value['ESE'])!=0?($value['CIA']+$value['ESE']):'-';
                $disp_credit = $value['credit_points']==0?'-':$value['credit_points'];
                $result_stu = $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
                $body .= "
                <table style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                        <td width='67px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                        <td width='180px' class='put_margin'  colspan='4'  align='left'>" . $subject_name_print . "</td>
                        <td width='40px'  align='center'>" . $disp_credit . "</td>
                        <td width='40px'  align='center'>" . $disp_ese_max . "</td>
                        <td width='40px'  align='center'>" . $disp_ese_marks. "</td>
                        <td width='55px'  align='center' style='padding-right: 12px;' > " . strtoupper($grade_name) . " </td>
                        <td width='60px'  align='left'>" . $result_stu . "</td></tr></table>"; 
                $total_subs_count++; 
                
            } // If subject not contains the ESE_max==0  Condition
            $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id'],$semester_number);
        } // If not the same registration number
        else 
        {
            
           $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id'],$semester_number);
            if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) 
            {
                $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = strtoupper(nl2br($sub_na));
                $subject_name_print = str_replace("&AMP;","&",$subject_name_print);
                $result_stu = $value['result'] == "Pass" || $value['result'] == "PASS" || $value['result'] == "pass"? "COMPLETED" : "NOT COMPLETED";
                $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left' ><b>" . $semester_array[$value['semester']]. "</td>
                            <td width='67px'  align='left'>" . $value['subject_code'] . "</td>
                            <td width='180px' class='put_margin'   colspan='5'  align='left'>" . $subject_name_print . "</td>
                            <td colspan='4' width='105px'  align='center'> ".$result_stu."</b> </td>
                        </tr>
                    </table>";
                $total_subs_count++;
            } 
            else
            {
                $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "RA" : $value['result'];
                
                $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;
    
                $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;

                $result_stu = $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);


                $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = strtoupper(nl2br($sub_na));
                $subject_name_print = str_replace("&AMP;","&",$subject_name_print);

                $grade_name = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? 'AB' : $value['grade_name'];

                $grade_name = $value['grade_name'] == "WH" || $value['grade_name'] == "wh" ? 'RA' : $value['grade_name'];
                $grade_name = $value['grade_name'] == "WD" || $value['grade_name'] == "wd" ? 'W' : $value['grade_name'];

               
                $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
                $grade_point_print = $grade_name=='RA'?"-":$grade_point_print;

                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];
                $disp_ese_max = ($value['ESE_max']+$value['CIA_max'])!=0?($value['ESE_max']+$value['CIA_max']):'-';
                $disp_ese_marks = ($value['CIA']+$value['ESE'])!=0?($value['CIA']+$value['ESE']):'-';
                $disp_credit = $value['credit_points']==0?'-':$value['credit_points'];
                $result_stu = $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
                $body .= "
                <table style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                        <td width='67px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                        <td width='180px' class='put_margin'   colspan='4'  align='left'>" . $subject_name_print . "</td>
                        <td width='40px'  align='center'>" . $disp_credit . "</td>
                        <td width='40px'  align='center'>" . $disp_ese_max . "</td>
                        <td width='40px'  align='center'>" . $disp_ese_marks. "</td>
                        <td width='55px'  align='center' style='padding-right: 12px;' > " . strtoupper($grade_name) . " </td>
                        <td width='60px'  align='left'>" . $result_stu . "</td></tr></table>";  
                $total_subs_count++;
                
            }
        }
        $previous_subject_code = $value['subject_code'];
        $previous_reg_number = $value['register_number'];
        $print_student_map_id = $value['student_map_id'];
        $semester_last_print = $value['semester'];
    }// End the foreach variable here
      
      $body .= "
                        <table border=0   >
                            <tr>
                                <td  class='make_bold_font font_bigger' width='400px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
      
        $merge_body = "<tr><td width='100%' valign='top' colspan='11' height='625px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='58px' >&nbsp;</td></tr>";
    


            $semester_number = ConfigUtilities::semCaluclation($exam_year,$app_month,$batch_mapping_id);
            $course_batch_mapping_id =  CoeBatDegReg::findOne($batch_mapping_id);
            $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
            $total_semesters = $degree_years->degree_total_semesters;
            $colspan_merge = (11-$total_semesters);

            $final_cgpa = $cgpa_calc['final_cgpa'];
            $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
            $cumulative_row = "<tr height='15px'>
                            <td class='make_bold_font font_bigger'  width='20px' height='15px' valign='top' align='center' >  <b>" . $cgpa_calc['consolidate_cre'] . "</b> </td>
                            
                            <td class='make_bold_font font_bigger'  width='60px'  height='15px' colspan='6' align='center' valign='top' > <b>" . $cgpa_calc['final_cgpa'] . "</b> </td>
                            <td  class='make_bold_font font_bigger'  width='200px' style='padding-left: 20px;' colspan='4' align='center' ><b>" . $classification . " </td>
                           
                            </tr>";
            $footer .=$cumulative_row . $date_insert."<tr><td colspan='11' width='30px' height='69px' >&nbsp;</td></tr></table>";
            
            $html = $header .$merge_body.$footer;
            $print_stu_data .=$html;
            
            if(isset($_SESSION['consolidate_pg_mark_statement_pdf'])){ unset($_SESSION['consolidate_pg_mark_statement_pdf']);}
            $_SESSION['consolidate_pg_mark_statement_pdf'] = $print_stu_data;
            echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-12" >'.$print_stu_data.'</div></div></div></div></div>'; 
        
}
else
{ 
    Yii::$app->ShowFlashMessages->setMsg('Error','No data Found');            
}

?>