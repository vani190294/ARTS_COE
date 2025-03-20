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

if (isset($mark_statement) && !empty($mark_statement)) {
    
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    /*
     *   Already Defined Variables from the above included file
     *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
     *   use these variables for application
     *   use $file_content_available="Yes" for Content Status of the Organisation
     */
    $add_tr_starting = $print_trimester == 1?"<tr><td colspan='11' width='30px'>&nbsp;</td></tr> <tr><td style='font-weight: bold; text-align: center;' colspan='11'> TRIMESTER PATTERN </td></td></tr>":'';
    $supported_extensions = ConfigUtilities::ValidFileExtension();
    $stu_directory = Yii::getAlias("@web") . "/resources/stu_photos/";
    $absolute_dire = Yii::getAlias("@webroot") . "/resources/stu_photos/";

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
    $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
    echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/mark-statement-print-pdf'], [
        'class' => 'pull-right btn btn-primary',
        'target' => '_blank',
        'data-toggle' => 'tooltip',
        'title' => 'Will open the generated PDF file in a new window'
    ]);

    echo "<br /><br />";
    $open_div = '<div class="row"><div class="col-xs-12"><div class="col-xs-12 col-sm-1 col-lg-1">&nbsp;</div><div class="col-xs-12 col-sm-10 col-lg-10"><div class="col-xs-12 col-sm-1 col-lg-1"></div>';
    $close_div = "<br /><br /></div></div>";

    $deg_type = Yii::$app->db->createCommand("select degree_type from coe_degree as A join coe_bat_deg_reg as B on A.coe_degree_id=B.coe_degree_id where coe_bat_deg_reg_id='" . $_POST['bat_map_val'] . "'")->queryScalar();
    $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X'];

    foreach ($mark_statement as $value) {
        $files = glob($absolute_dire . $value['register_number'] . ".*"); // Will find 2.JPG, 2.php, 2.gif
        // Process through each file in the list
        // and output its extension
        if (count($files) > 0) {
            foreach ($files as $file) {
                $info = pathinfo($file);
                $extension = "." . $info["extension"];
            }
        } else {
            $extension = "";
        }
        
        $exam_year = $value['year'];
        $app_month = $app_month = $value['month'];
        $batch_mapping_id = $value['course_batch_mapping_id'];
        $semester_number = ConfigUtilities::semCaluclation($exam_year, $app_month, $batch_mapping_id);
        $course_batch_mapping_id = CoeBatDegReg::findOne($batch_mapping_id);
        $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
        $total_semesters = $degree_years->degree_total_semesters;
        $colspan_merge = (11 - $total_semesters);

        $photo_extension = ConfigUtilities::match($supported_extensions, $value['register_number'] . $extension);
        $stu_photo = $photo_extension != "" ? $stu_directory . $value['register_number'] . "." . $photo_extension : $stu_directory . "stu_sample.jpg";
        
        $dob= date('d-M-Y',strtotime($value['dob']));
        
        if ($previous_reg_number != $value['register_number']) 
        {
            $new_stu_flag = $new_stu_flag + 1;
            $print_gender = $value['gender'] == 'F' ? 'FEMALE' : 'MALE';
            
           
            if ($new_stu_flag > 1) {
                
                $credits_register_row .= "<tr height='40px'><td width='30px' height='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $credits_earned_row .= "<tr height='40px'><td width='30px' height='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $sgpa_row .= "<tr height='40px'><td height='30px' width='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $cumulative_row .= "<tr height='40px'><td height='30px' width='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";

                for ($loop_sem = 1; $loop_sem <= $total_semesters; $loop_sem++) {
                    if ($loop_sem == $semester_number) {
                        
                            $credits_register_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['registered'] . "</b> </td>";
                            $credits_earned_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['sem_credits_earned'] . "</b> </td>";
                           
                            $sgpa_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['gpa'] . "</b> </td>";
                            $cumulative_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['cumulative_earned_credits'] . "</b> </td>";

                            $cumulative_row .= "<td height='30px' align='left' >&nbsp;</td>";
                            
                            $cumulative_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['cgpa'] . "</b> </td>";
                        
                    } else {
                        $credits_register_row .= "<td height='30px' align='left' >&nbsp;</td>";
                        $credits_earned_row .= "<td height='30px' align='left' >&nbsp;</td>";
                        $sgpa_row .= "<td height='30px' align='left' >&nbsp;</td>";
                        $cumulative_row .= $loop_sem<=4?"<td height='30px' align='left' >&nbsp;</td>":'';
                    }
                }

                $credits_register_row .= "</tr>";
                $credits_earned_row .= "</tr>";
                $sgpa_row .= "</tr>";
                $cumulative_row .= "</tr>";

                $footer .= $credits_register_row . $credits_earned_row . $sgpa_row . $cumulative_row . "<tr><td valign='top' colspan=11 height=120px >&nbsp;</td></tr></table>";
                $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$value['year'].'" AND exam_month="'.$value['month'].'" and student_map_id="'.$value['student_map_id'].'"')->queryAll();
                if(!empty($check_add))
                {
                    foreach ($check_add as $valuess) 
                    {
                        $body .= "
                        <table border=0 width='100%'  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                            <tr>
                                <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                                <td width='100px'  align='left'>" . $valuess['subject_code'] . "</td>
                                
                                <td width='420px' colspan='3'  align='left'>" . wordwrap($valuess['subject_name'],6) . " #</td>
                                <td width='50px'  align='left'>" . $valuess['credits'] . "</td>
                                <td colspan='4' width='230px'  align='right'> COMPLETED </td>
                            </tr>
                        </table>";

                        $body .= " <br />
                        <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                            <tr>
                                <td style='font-weight: bold' width='420px' colspan='7'  align='center'> ~ END OF MARK STATEMENT ~ </td>
                                
                                <td colspan='4' width='230px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                        </table>";
                        $body .= "  <br />
                        <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                            <tr>
                               
                                <td style='font-weight: bold' width='420px' colspan='7'  align='left'> # ADDITIONAL CREDIT COURSE NOT TO BE CONSIDER FOR CGPA CALUCLATION </td>
                                
                                <td colspan='4' width='230px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";


                    }
                   
                }
                else{
                    $body .= "<br /><br />";
                    $body .= "
                        <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                            <tr>

                               
                                <td style='font-weight: bold' width='420px' colspan='7'  align='center'> ~ END OF MARK STATEMENT ~ </td>
                                
                                <td colspan='4' width='230px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                }

                $merge_body = "<tr><td valign='top' colspan='11' height='600px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='80px' >&nbsp;</td></tr>";
                $html = $header . $merge_body . $footer."<pagebreak />";
                $print_stu_data .= $html;
                $header = "";
                $body = "";
                $footer = ""; $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
                $new_stu_flag = 1;
            }
            
            $header .= "<table width='100%'  autosize='1'  >";
            $header .= '
                        <tr>
                           <td height="100px" style="border: none; text-align: right;" colspan="11" >
                                
                                <img class="img-responsive" width="100" height="100" src="' . $stu_photo . '" alt="' . $stu_photo . '" Photo >
                                
                            </td>
                        </tr>'.$add_tr_starting;
            

            $header .= "
                    <tr>
                        <td style='border: none;' colspan='11' >
                            <table width='100%' align='left' style='border: none !important;' border='0'>
                                <tr style='padding: 10px;'>
                                    <td colspan='4' width='30px;' style='width: 40px;' >&nbsp;</td>
                                    <td colspan='5' align='left' > <b>" . strtoupper($value['name']) . "</b> </td>
                                    <td align='right'  colspan='2' ><b>" . $value['year'] . " / " . strtoupper($app_month) . " </b> </td>
                                </tr>
                            
                                <tr style='padding: 10px;'>
                                    <td colspan='4' style='width: 30px;'>&nbsp;</td>
                                    <td align='left' colspan='2' > <b>" . strtoupper($value['register_number']) . "</b></td>
                                    <td align='right' colspan='3' ><b>" . $dob . "</b></td>
                                    <td align='right' colspan='2' ><b>" . strtoupper($print_gender) . "</b></td>
                                </tr>
                            
                                <tr>
                                    <td colspan='4' width='30px;' style='width: 30px;'>&nbsp;</td>
                                    <td align='left' colspan='5' > <b>" . strtoupper($value['degree_name']) . "</b></td>
                                    <td colspan='2' align='right' ><b>" . $value['regulation_year'] . "</b></td>
                                </tr>
                                <tr>
                                    <td colspan='11' height='35px;' style='width: 30px;'>&nbsp; </td>
                                </tr>
                            </table>
                        </td>
                    </tr>";
            $total_credits = '';
            $total_earned_credits = '';
            $passed_grade_points = '';
            if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) {
                $body .= "
                    <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                        <tr>
                            <td width='40px' align='left' >" . $semester_array[$value['semester']]. "</td>
                            <td width='100px'  align='left' >" . $value['subject_code'] . "</td>
                            <td width='420px' colspan='4'  align='left'>" . wordwrap($value['subject_name'],6) . "</td>
                            <td colspan='5' width='230px'  align='center'> COMPLETED </td>
                        </tr>
                    </table>";
            } else {
                $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
                $body .= "
                    <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                        <tr>
                            <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                            <td width='100px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                            <td width='420px' colspan='5'  align='left'>" . strtoupper(wordwrap($value['subject_name'],6)) . "</td>
                            <td width='50px'  align='left'>" . $value['credit_points'] . "</td>
                            <td width='60px'  align='left'> " . strtoupper($value['grade_name']) . " </td>
                            <td width='60px'  align='left'>" . $value['grade_point'] . "</td>";

                if ($deg_type == "UG") {
                    $body .= "<td width='50px'  align='center'>" . $result_stu . "</td>";
                } else {
                    if ($value['year_of_passing'] != "") {
                        $body .= "<td width='50px'  align='center'>" . ConfigUtilities::getYearOfPassing($value['year_of_passing']) . "</td>";
                    } else {
                        $body .= "<td width='60px'  align='left'>&nbsp;</td>";
                    }
                }

                $body .= "</tr>
                    </table>";
                /* <td width='50px'  align='left'> ".$value['sub_total_marks']." </td>
                  <td width='50px'  align='left'> ".$value['total']." </td> */
            }
            
        } // If not the same registration number

        else {
            $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id']);
            if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) {
                $body .= "
                    <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                        <tr>
                            <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                            <td width='100px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                            <td width='420px' colspan='4' align='left'>" . strtoupper(wordwrap($value['subject_name'],6)) . "</td>
                            <td colspan='5' width='230px' align='right'> COMPLETED </td>
                        </tr>
                    </table>";
            } else {
                $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
                $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;

                $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;


                $body .= "
                    <table border=0 style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                        <tr>
                            <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                            <td width='100px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                            <td width='420px' colspan='5'  align='left'>" . strtoupper(wordwrap($value['subject_name'],6)) . "</td>
                            <td width='50px'  align='left'>" . $value['credit_points'] . "</td>
                            <td width='60px'  align='left'> " . strtoupper($value['grade_name']) . " </td>
                            <td width='60px'  align='left'>" . $value['grade_point'] . "</td>";

                if ($deg_type == "UG") {
                    $body .= "<td width='50px'  align='center'>" . $result_stu . "</td>";
                } else {
                    if ($value['year_of_passing'] != "") {
                        $body .= "<td width='50px'  align='center'>" . ConfigUtilities::getYearOfPassing($value['year_of_passing']) . "</td>";
                    } else {
                        $body .= "<td width='60px'  align='center'>&nbsp;</td>";
                    }
                }

                $body .= "</tr>
                    </table>";
            }
           
        }
        $previous_subject_code = $value['subject_code'];
        $previous_reg_number = $value['register_number'];

        $semester_last_print = $value['semester'];
    }// End the foreach variable here

    $check_add_last = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$value['year'].'" AND exam_month="'.$value['month'].'" and student_map_id="'.$value['student_map_id'].'"')->queryAll();
    if(!empty($check_add_last))
    {
        foreach ($check_add_last as $valuess)
        {
            $body .= "
            <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                <tr>
                    <td width='30px' align='left'>" . $semester_array[$semester_last_print]. "</td>
                    <td width='100px'  align='left'>" . $valuess['subject_code'] . "</td>
                    
                    <td width='420px' colspan='4'  align='left'>" . wordwrap($valuess['subject_name'],6) . " #</td>
                    <td width='30px'  align='left'>" . $valuess['credits'] . "</td>
                    <td colspan='4' width='230px'  align='right'> COMPLETED </td>
                </tr>
            </table>";
            $body .="<br />";
            $body .= "
            <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                <tr>
                   
                    <td style='font-weight: bold' width='420px' colspan='7'  align='center'> ~ END OF MARK STATEMENT ~ </td>
                    
                    <td colspan='4' width='230px'  align='right'>&nbsp;</td>
                </tr>
            </table>";
            $body .="<br />";
            $body .= "
            <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                <tr>
                    <td width='30px' align='left'>&nbsp;</td>
                    <td width='50px'  align='left'>&nbsp;</td>
                    <td style='font-weight: bold' width='420px' colspan='4'  align='left'> # ADDITIONAL CREDIT COURSE NOT TO BE CONSIDER FOR CGPA CALUCLATION </td>
                    <td width='30px'  align='left'>&nbsp;</td>
                    <td colspan='4' width='230px'  align='right'>&nbsp;</td>
                </tr>
            </table>";


        }
       
    }
    else{
        $body .= "<br /><br />";
        $body .= "
            <table border=0  style='width: 100%; font-family: Open Sans, sans-serif; font-size: 13px !important; ' >
                <tr>
                  
                    <td style='font-weight: bold' width='420px' colspan='7'  align='left'> ~ END OF MARK STATEMENT ~ </td>
                    <td colspan='4' width='230px'  align='right'>&nbsp;</td>
                </tr>
            </table>";
    }

    $merge_body = "<tr><td valign='top' colspan='11' height='600px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='80px' >&nbsp;</td></tr>";
    $credits_register_row .= "<tr height='40px'><td width='30px' height='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
    $credits_earned_row .= "<tr height='40px'><td width='30px' height='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
    $sgpa_row .= "<tr height='40px'><td height='30px' width='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
    $cumulative_row .= "<tr height='40px'><td height='30px' width='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
    
    for ($loop_sem = 1; $loop_sem <= $total_semesters; $loop_sem++) {
        if ($loop_sem == $semester_number) {
    
            $credits_register_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['registered'] . "</b> </td>";
            $credits_earned_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['sem_credits_earned'] . "</b> </td>";
    
            $sgpa_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['gpa'] . "</b> </td>";
            $cumulative_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['cumulative_earned_credits'] . "</b> </td>";
    
            $cumulative_row .= "<td height='30px' align='left' >&nbsp;</td>";
    
            $cumulative_row .= "<td height='30px' align='left' > <b>" . $cgpa_calc['cgpa'] . "</b> </td>";
            
        } else {
            $credits_register_row .= "<td height='30px' align='left' >&nbsp;</td>";
            $credits_earned_row .= "<td height='30px' align='left' >&nbsp;</td>";
            $sgpa_row .= "<td height='30px' align='left' >&nbsp;</td>";
            $cumulative_row .= $loop_sem<=4?"<td height='30px' align='left' >&nbsp;</td>":'';
        }
    }
    
    $credits_register_row .= "</tr>";
    $credits_earned_row .= "</tr>";
    $sgpa_row .= "</tr>";
    $cumulative_row .= "</tr>";
    /*$credits_register_row = "<tr height='40px'><td height='30px' align='left' colspan=" . $colspan_merge . " > &nbsp; </td>";
    $credits_earned_row = "<tr height='40px'><td height='30px' align='left' colspan=" . $colspan_merge . " > &nbsp; </td>";
    $sgpa_row = "<tr height='40px'><td height='30px' align='left' colspan=" . $colspan_merge . " > &nbsp; </td>";
    $cumulative_row = "<tr height='40px'><td height='30px' align='left' colspan=" . $colspan_merge . " > &nbsp; </td>";
    
    for ($last_loop_sem = 1; $last_loop_sem <= $total_semesters; $last_loop_sem++) 
    {
        if ($last_loop_sem == $semester_last_print) {
            // if (stristr($deg_type, "UG")) {
                $credits_register_row .= "<td width='30px' height='30px' align='left' > " . $cgpa_calc['registered'] . " </td>";
                $credits_earned_row .= "<td width='30px' height='30px' align='left' > " . $cgpa_calc['sem_credits_earned'] . " </td>";               
                $sgpa_row .= "<td width='30px' height='30px' align='left' > " . $cgpa_calc['gpa'] . " </td>";
                $cumulative_row .= "<td width='30px' height='30px' align='left' > " . $cgpa_calc['cumulative_earned_credits']  . " </td>";
               $cumulative_row .= "<td height='30px' align='left' >&nbsp;</td>";
                            
                $cumulative_row .= "<td height='30px' align='left' > " . $cgpa_calc['cgpa'] . " </td>";
            // } else {
            //     $credits_earned_row .= "<td width='30px' height='30px' align='left' > " . $total_earned_credits . " </td>";
            //     $cumulative_row .= "<td width='30px' height='30px' align='left' >" .  $cgpa_calc['cumulative_earned_credits'] . " </td>";
            // }
        } else {
            $credits_register_row .= "<td width='30px' height='30px' align='left' >&nbsp;</td>";
            $credits_earned_row .= "<td width='30px' height='30px' align='left' >&nbsp;</td>";
            $sgpa_row .= "<td height='30px' align='left' >&nbsp;</td>";
            $cumulative_row .= "<td height='30px' align='left' >&nbsp;</td>";
        }
    }
    
    $credits_register_row .= "</tr>";
    $credits_earned_row .= "</tr>";
    $sgpa_row .= "</tr>";
    $cumulative_row .= "</tr>"; */

    $footer .= $credits_register_row . $credits_earned_row . $sgpa_row . $cumulative_row . "<tr><td valign='top' colspan=11 height=120px >&nbsp;</td></tr></table>";
    $html = $header . $merge_body . $footer;
    $print_stu_data .= $html;

    if (isset($_SESSION['mark_statement_pdf'])) {
        unset($_SESSION['mark_statement_pdf']);
    }
    $_SESSION['mark_statement_pdf'] = $print_stu_data;
    echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-12" >' . $print_stu_data . '</div></div></div></div></div>';
} else {
    Yii::$app->ShowFlashMessages->setMsg('Error', 'No data Found');
}
?>