<?php 
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\CoeBatDegReg;
use app\models\Degree;
use app\models\Programme;
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
        
        //print_r($get_console_list);exit;
        $supported_extensions = ConfigUtilities::ValidFileExtension(); 
        $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
        $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";

        $add_tr_starting = $print_trimester == 1?"<table border='0' width='100%' ><tr><td valign='top' class='print_tri_sem' colspan='11' > TRIMESTER PATTERN </td></tr></table>":'';
        
            $html = "";
            $previous_subject_code= "";
            $previous_reg_number = $old_sem = "";
            $header = "";
            $body ="";
            $footer = "";
            $print_register_number="";
            $new_stu_flag=0;
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
            echo Html::a('<i class="fa fa-file-pdf-o"></i> Print Pdf', ['/mark-entry-master/consolidate-mark-sheet-pg-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
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

                        $final_cgpa = $cgpa_calc['final_cgpa'];
                        $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
                        
                       $cumulative_row = "<tr height='15px'>
                            <td  width='25px' height='15px' valign='top' align='right' > &nbsp; </td>
                            <td class='cumulative_credits' colspan='3' height='15px' valign='top' align='left'  > <b>" . $cgpa_calc['consolidate_cre'] . "</b> </td>
                            <td width='50px' class='cumulative_cgpa'  height='15px' align='right' valign='top' > <b>" . $cgpa_calc['final_cgpa'] . "</b> </td>
                            <td colspan='7' class='print_classification'  width='150px' align='center' ><b>" . $classification . " </td>
                           
                            </tr>";

                        $footer .=$cumulative_row . $date_insert."</table>";



                        $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE  student_map_id="'.$print_student_map_id.'"')->queryAll();
                        
                        if(!empty($check_add))
                        {
                            $body .= "
                            <table class='subjects_tables' style='padding-top: 5px' border='0' width='100%' >
                            <tr>
                                <td colspan='4' width='113px' >&nbsp;</td>
                                <td width='626px' colspan='7'  align='left'><b>ADDITIONAL CREDIT ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))."S </b></td>
                            </tr>
                            </table>";
                            foreach ($check_add as $valuess) 
                            {
                                $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                                $year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
                                $sem_nu = ConfigUtilities::semCaluclation($valuess['exam_year'],$valuess['exam_month'],$batch_mapping_id);

                                $add_style_font = strlen($valuess['subject_name']) >63 ?"style='font-size: 10px !important;'":'';
                                $sub_remove_one = str_replace('ADDITIONAL CREDIT '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).': ', '', $valuess['subject_name']);
                                /*$sub_na = wordwrap(strtoupper($valuess['subject_name']), 60, "\n", true);
                                $sub_na = htmlentities($sub_na);
                                $subject_name_print = nl2br($sub_na);*/
                                $with_add = $value['subject_code']=='15PMA103'?'30px':'100px';
                                $change_class = $value['subject_code']=='15PMA103'?'change_margin_ma10':'put_margin';
                                $subject_name_print = ltrim($subject_name_print);
                                $subject_name_print = $sub_remove_one;

                                $body .= "
                                <table class='subjects_tables' border='0' width='100%' >
                                <tr>
                                    <td class='sem_print' width='50px' align='left'>" . $semester_array[$sem_nu]. "</td>
                                    <td  class='subject_code' width='95px'  align='left'>" . strtoupper($valuess['subject_code']) . "</td>
                                    <td width='450px' class='".$change_class."' colspan='5'  align='left'>" . $subject_name_print . "</td>
                                    <td width='37px'  align='left'>" . $valuess['credits']  . "</td>
                                    <td width='37px'  align='left'> " . strtoupper($valuess['grade_name']) . " </td>
                                    <td width='37px'  align='left'>" . $grade_point_print. "</td>
                                    <td width='65px'  align='right'>" . $year_of_passing . "</td>
                                </tr>
                                </table>";
                            }
                            $body .= "
                            <table border=0  >
                                <tr>
                                    <td width='130px' align='left'>&nbsp;</td>
                                    <td class='additional_credits_print' colspan='6' > # ADDITIONAL CREDIT ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))."S WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                                    <td colspan='4' width='100px'  align='right'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class='end_of_statement' width='600px' colspan='7'> ~ END OF STATEMENT ~ </td>
                                    
                                    <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                                </tr>
                            </table>";                       
                           
                        }
                        else{
                            $body .= "
                                <table border=0   >
                                    <tr>
                                        <td class='end_of_statement' width='600px' colspan='7'> ~ END OF STATEMENT ~ </td>
                                        
                                        <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                                    </tr>
                                </table>";
                        }

                        $merge_body = "<tr><td width='100%' valign='top' colspan='11' height='749px' >" . $body . "</td></tr>";
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

                    $photo_extension = ConfigUtilities::match($supported_extensions,$value['register_number'].$extension);
                
                    $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg"; 
                   // $deg="MBA";
                   if($value['degree_code']=="MBA" || $value['degree_code']=="mba" || $value['degree_code']=="Mba")
                    {
                       // $deg =strtoupper($value['programme_name']);
                          $deg="MBA";
                    }
                    else
                    {
                       // $deg =strtoupper($value['degree_code']) . ". ". strtoupper($value['programme_name']);
                        //$deg =strtoupper($value['degree_code']);
                        $deg="MBA";

                    }

                    $header .="<table width='100%'  autosize='1' >";
                    $header .= '
                        <tr>
                            <td height="85px" class="print_mba" colspan="4" >
                                '.$deg.'
                            </td>
                           <td height="85px" style="border: none; text-align: right; margin-top: 0px;" colspan="7" >
                                <img class="img-responsive" width="85" height="85" src="' . $stu_photo . '" alt="' . $stu_photo . '" Photo >
                                
                            </td>
                        </tr>';
                    $print_gender = $value['gender']=='F'?'FEMALE':'MALE';
                    $exam_year=$value['year'];
                    $app_month_name = ConfigUtilities::getMonthName($value['month']);
                    $batch_mapping_id=$value['course_batch_mapping_id'];
                    $stu_last_exam = "SELECT CONCAT(month,'-',year) FROM coe_mark_entry_master AS a join coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id where A.student_map_id='".$value['student_map_id']."' order by coe_mark_entry_master_id desc";
                    $last_appe = Yii::$app->db->createCommand($stu_last_exam)->queryScalar();
                    $last_appearance = $last_appearance = ConfigUtilities::getLastYearOfPassing($value['register_number']) ;
                    $dob= date('d/m/Y',strtotime($value['dob']));
                    if($value['degree_code']=="MBA" || $value['degree_code']=="mba" || $value['degree_code']=="Mba")
                    {
                        $deg =strtoupper($value['programme_name']);
                    }
                    else
                    {
                        //$deg =strtoupper($value['degree_code']) . ". ". strtoupper($value['programme_name']);
                       //$deg =strtoupper($value['degree_code']);
                        $deg =strtoupper($value['programme_name']);

                    }
                    $header .="
                    <tr class='take_top' >
                        <td class='header_print_stu' colspan='11' width='100%' >
                            <table width='100%' align='left' style='border: none !important;   padding-top: 7px;'  border='0'  >
                                <tr>
                                    <td class='line_height line_height_reduce' colspan='4' width='50px;' style='width: 40px;' >&nbsp;</td>
                                    <td class='line_height line_height_reduce' colspan='5' align='left' style='padding-left: 64px;' > <b>" . strtoupper($value['name']) . "</b> </td>
                                    <td class='line_height line_height_reduce' style='padding-right: -5px;' colspan='2' align='center' ><b>".$last_appearance."</b></td>
                                </tr>
                            
                                <tr>
                                    <td class='line_height line_height_reduce' colspan='4' style='width: 50px;'>&nbsp;</td>
                                    <td class='line_height line_height_reduce deg' align='left'  colspan='2' style='padding-left: 64px;'><b>" . strtoupper($value['register_number']) . "</b> </td>
                                    <td class='line_height line_height_reduce dob' style='padding-left: 75px;' align='left' colspan='3' ><b>" . $dob . "</b></td>
                                    <td class='line_height line_height_reduce deg' style='padding-right: -5px;' align='center' colspan='2' > <b>" . strtoupper($print_gender) . "</b></td>
                                </tr>
                                <tr>
                                    <td class='line_height line_height_reduce' colspan='4' width='45px;' style='width: 45px;'>&nbsp;</td>
                                    <td class='line_height line_height_reduce deg' align='left' colspan='5' style='padding-left: 64px;'> <b>" . strtoupper($deg) . "</b></td>
                                    <td class='line_height line_height_reduce' style='padding-right: -5px;' colspan='2' align='center' ><b>".$value['regulation_year']."</b></td>
                                </tr>
                               <tr>
                                    <td class='line_height line_height_reduce' colspan='11' height='25px;' style='width: 30px;'>&nbsp; </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                    
                    ";
                    
         $total_credits ='';
         $total_earned_credits ='';
         $passed_grade_points ='';
         $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);

           if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) {
                $sub_na = wordwrap(strtoupper($value['subject_name']), 85, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = nl2br($sub_na);
                $body .= "
                    <table class='subjects_tables' border='0' width='100%' >
                        <tr>
                            <td  class='sem_print'  width='50px' align='left' >" . $semester_array[$value['semester']]. "</td>
                            <td class='subject_code' width='95px'  align='left' >" . $value['subject_code'] . "</td>
                            <td width='450px' colspan='5'  align='left'>" . $subject_name_print . "</td>
                            <td colspan='4' width='245px'  align='center'> COMPLETED </td>
                        </tr>
                    </table>";
            }
           else
           {
                //$result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
                $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
                
                $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;
    
                $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;

                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];

                $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
                $body .= "
                    <table border='0' width='100%' >
                        <tr>
                            <td height='20px;' colspan ='11' width='500px' >&nbsp;</td>
                        </tr>
                    </table>";
                $sub_na = wordwrap(strtoupper($value['subject_name']), 65, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = nl2br($sub_na);
                $line_height_sub = strlen($subject_name_print) >61 ?'line-height: 1em !important;':'';
                $body .= $add_tr_starting."
                    <table class='subjects_tables' border='0' width='100%' >
                    <tr>
                        <td  class='sem_print'  width='50px' align=''>" . $semester_array[$value['semester']]. "</td>
                        <td  class='subject_code' width='95px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                        <td width='450px' style='".$line_height_sub."' colspan='5'  align='left'>" . $subject_name_print. "</td>
                        <td width='37px'  align='left'>" . $value['credit_points'] . "</td>
                        <td width='37px'  align='left'> " . strtoupper($value['grade_name']) . " </td>
                        <td width='37px'  align='left'>" . $grade_point_print . "</td>
                        <td width='65px'  align='right'>" . $year_of_passing . "</td>
                    </tr>
                    </table>";
                /* <td width='50px'  align='left'> ".$value['sub_total_marks']." </td>
                  <td width='50px'  align='left'> ".$value['total']." </td> */
           }

           $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id']);

            $cgpa_calc []= ['gpa'=> $value['credit_points']*$value['grade_point'],'credits'=>$value['credit_points']];
            $total_earned_credits += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? $value['credit_points'] : 0;

            $passed_grade_points += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? ($value['credit_points']*$value['grade_point']) : 0;

            $total_credits +=$value['credit_points'];
            $old_sem =$value['semester']; 
        // Closing the Main Header Table
                
    }
    else{
         $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']); 

         if($old_sem!=$value['semester'])
         {
          if($old_sem=='')
          {

          }
          else
          {
            $body .= "
            <table border='0' width='100%' >
                <tr>
                    <td class='gap_subjects_tables' colspan ='11' width='500px' >&nbsp;</td>
                </tr>
            </table>";
          }
           
            $old_sem =$value['semester']; 
         }

         if($value['ESE_max']==0 && $value['CIA_max']==0)
           {
                $sub_na = wordwrap(strtoupper($value['subject_name']), 85, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = nl2br($sub_na);
                $body .= "
                <table class='subjects_tables' border='0' width='100%' >
                    <tr>
                        <td  class='sem_print'  width='50px' align='left'>" . $semester_array[$value['semester']]. "</td>
                        <td  class='subject_code' width='95px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                        <td width='450px' colspan='4' align='left'>" . $subject_name_print . "</td>
                        <td colspan='4' width='245px' align='center'> COMPLETED </td>
                    </tr>
                </table>";
           }
           else{
            $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
            $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;

            $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;
            $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];
            $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
            $sub_na = wordwrap(strtoupper($value['subject_name']), 65, "\n", true);
            $sub_na = htmlentities($sub_na);
            $subject_name_print = nl2br($sub_na);
            $line_height_sub = strlen($subject_name_print) >61 ?'line-height: 1em !important;':'';
               
            $body .= "
                    <table class='subjects_tables' border='0' width='100%' >
                    <tr>
                        <td  class='sem_print'  width='50px' align='left'>" . $semester_array[$value['semester']]. "</td>
                        <td  class='subject_code' width='95px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                        <td width='450px' style='".$line_height_sub."' colspan='5'  align='left'>" . $subject_name_print . "</td>
                        <td width='37px'  align='left'>" . $value['credit_points'] . "</td>
                        <td width='37px'  align='left'> " . strtoupper($value['grade_name']) . " </td>
                        <td width='37px'  align='left'>" . $grade_point_print . "</td>
                        <td width='65px'  align='right'>" . $year_of_passing . "</td>
                    </tr>
                    </table>";
           }   
        $total_earned_credits += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? $value['credit_points'] : 0;
        $passed_grade_points += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? ($value['credit_points']*$value['grade_point']) : 0;
        $total_credits +=$value['credit_points'];
    } // Else Condition Ends Here 
    $previous_subject_code = $value['subject_code'];
    $previous_reg_number=$value['register_number']; 
    $print_student_map_id = $value['student_map_id'];
    $semester_last_print = $value['semester'];

}// End the foreach variable here 
            
    $check_add_last = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE student_map_id="'.$print_student_map_id.'"')->queryAll();

    if(!empty($check_add_last))
    {
        $body .= "
        <table class='subjects_tables' style='padding-top: 5px' border='0' width='100%' >
        <tr>
            <td colspan='4' width='113px' >&nbsp;</td>
            <td width='626px' colspan='7'  align='left'><b>ADDITIONAL CREDIT ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))."S</b></td>
        </tr>
        </table>";
        foreach ($check_add_last as $valuess) 
        {
            $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
            $year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
            $sem_nu = ConfigUtilities::semCaluclation($valuess['exam_year'],$valuess['exam_month'],$batch_mapping_id);

            $add_style_font = strlen($valuess['subject_name']) >63 ?"style='font-size: 10px !important;'":'';
            $sub_remove_one = str_replace('ADDITIONAL CREDIT '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).': ', '', $valuess['subject_name']);
            /*$sub_na = wordwrap(strtoupper($valuess['subject_name']), 60, "\n", true);
            $sub_na = htmlentities($sub_na);
            $subject_name_print = nl2br($sub_na);*/
            $subject_name_print = $sub_remove_one;

            $body .= "
            <table class='subjects_tables' border='0' width='100%' >
            <tr>
                <td  class='sem_print'  width='50px' align='left'>" . $semester_array[$sem_nu]. "</td>
                <td  class='subject_code' width='95px'  align='left'>" . strtoupper($valuess['subject_code']) . "</td>
                <td width='450px' colspan='5'  align='left'>" . $subject_name_print . "</td>
                <td width='37px'  align='left'>" . $valuess['credits']  . "</td>
                <td width='37px'  align='left'> " . strtoupper($valuess['grade_name']) . " </td>
                <td width='37px'  align='left'>" . $grade_point_print. "</td>
                <td width='65px'  align='right'>" . $year_of_passing . "</td>
            </tr>
            </table>";
        }
        $body .= "
            <table border=0  >
                <tr>
                    <td width='130px' align='left'>&nbsp;</td>
                    <td class='additional_credits_print' colspan='6' > # ADDITIONAL CREDIT ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))."S WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                    <td colspan='4' width='100px'  align='right'>&nbsp;</td>
                </tr>
                <tr>
                    <td class='end_of_statement' width='600px' colspan='7'> ~ END OF STATEMENT ~ </td>
                    
                    <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                </tr>
            </table>";
    }
    else{
        
        $body .= "
            <table border='0' width='100%' >
                <tr>
                    <td class='end_of_statement' width='600px' colspan='7'> ~ END OF STATEMENT ~ </td>
                    <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                </tr>
            </table>";
    }


            $semester_number = ConfigUtilities::semCaluclation($exam_year,$app_month,$batch_mapping_id);
            $course_batch_mapping_id =  CoeBatDegReg::findOne($batch_mapping_id);
            $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
            $total_semesters = $degree_years->degree_total_semesters;
            $colspan_merge = (11-$total_semesters);

            $final_cgpa = $cgpa_calc['final_cgpa'];
            $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
            $cumulative_row = "<tr height='15px'>
                            <td  width='25px' height='15px' valign='top' align='right' > &nbsp; </td>
                            <td class='cumulative_credits' colspan='3'  height='15px' valign='top' align='left'  > <b>" . $cgpa_calc['consolidate_cre'] . "</b> </td>
                            <td width='50px' class='cumulative_cgpa'  height='15px' align='right' valign='top' > <b>" . $cgpa_calc['final_cgpa'] . "</b> </td>
                            <td colspan='7' class='print_classification'  width='150px' align='center' ><b>" . $classification . " </td>
                           
                            </tr>";
            $footer .=$cumulative_row . $date_insert."</table>";

            $merge_body = "<tr><td width='100%' valign='top' colspan='11' height='749px' >" . $body . "</td></tr>";
            $html = $header .$merge_body.$footer;
            $print_stu_data .=$html;

            
            if(isset($_SESSION['get_console_list_pdf'])){ unset($_SESSION['get_console_list_pdf']);}
            $_SESSION['get_console_list_pdf'] = $print_stu_data;
            echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-12" >'.$print_stu_data.'</div></div></div></div></div>'; 
        
}
else
{ 
    Yii::$app->ShowFlashMessages->setMsg('Error','No data Found');            
}

?>