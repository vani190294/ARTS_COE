<?php
use yii\helpers\Html;
use app\components\ConfigUtilities;
use app\models\CoeBatDegReg;
use app\models\Degree;
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
    $add_tr_starting = $print_trimester == 1?"<tr><td colspan='11' width='30px'>&nbsp;</td></tr> <tr><td style='font-weight: bold; font-size: 14px; padding-top: 3px; text-align: center;' colspan='11'> TRIMESTER PATTERN </td></td></tr>":'';
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
    $date = isset($date_print)?"<b>".$date_print."</b>":"<b>".date('d-m-Y')."</b>";
    $date_insert = "<tr><td colspan='11' height='30px' width='50px'> &nbsp;</td></tr> <tr><td  align='left' valign='bottom' class='date_style' colspan='11' width='300px' style='font-size: 13px; padding-top: 14px;' >".$date."</td></tr> ";
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
        $colspan_merge = (11 - ($total_semesters+1));

        $photo_extension = ConfigUtilities::match($supported_extensions, $value['register_number'] . $extension);
        $stu_photo = $photo_extension != "" ? $stu_directory . $value['register_number'] . "." . $photo_extension : $stu_directory . "stu_sample.jpg";
        
        $dob= strtoupper(date('d-M-Y',strtotime($value['dob'])));
        $count_col_spans = 0;
        if ($previous_reg_number != $value['register_number']) 
        {
            $new_stu_flag = $new_stu_flag + 1;
            $print_gender = $value['gender'] == 'F' ? 'FEMALE' : 'MALE';
            
           
            if ($new_stu_flag > 1) 
            {                
                $final_total_credits =array_filter(array()); 

                for ($cal=1; $cal <= $total_semesters ; $cal++) 
                { 
                    $earned_credist = array_filter(array());
                    $gpa_calsss = array_filter(array());
                    $register_credits = array_filter(array());
                    foreach ($print_total_credits as $key => $checks) 
                    {
                       if($checks['sem']==$cal)
                       {
                            if($checks['res']=='PASS')
                            {
                                $earned_credist[] = $checks['credits'];
                                $gpa_calsss[] = $checks['credits']*$checks['grades'];
                            }
                            $register_credits[] = $checks['credits'];
                       }
                    }            
                    if(!empty($register_credits))
                    {
                        $division_row = array_sum($earned_credist)==0?1:array_sum($earned_credist);
                        $gpa_cals = round((array_sum($gpa_calsss)/$division_row),2);
                        $final_total_credits []= ['sem'=>$cal,'earned'=>array_sum($earned_credist),'gpa'=>$gpa_cals,'register'=>array_sum($register_credits)];
                    }
                }
                $copunt_of_arrears = count($final_total_credits);
                $colspan_merge = !empty($final_total_credits) && count($final_total_credits) >0?$colspan_merge - ($copunt_of_arrears>1?$copunt_of_arrears-1:$copunt_of_arrears-0):$colspan_merge;



                $credits_register_row .= "<tr><td width='30px' valign='middle' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $credits_earned_row .= "<tr><td width='30px' valign='middle'  align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $sgpa_row .= "<tr height='25px'>
                <td height='25px' valign='middle'  width='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $td_printe = 1;
                $count_col_spans = $colspan_merge;
                $print_empty_data = 0;
                $print_empty_data = !empty($final_total_credits) && count($final_total_credits) >1?0:1;
                $tt_count = count($final_total_credits);
                $count_of_final = $tt_count-1;
                unset($final_total_credits["$count_of_final"]);
                $increment = 0;
                if(!empty($final_total_credits))
                {
                    
                    $print_status = count($final_total_credits);

                    foreach ($final_total_credits as $key => $print_row) 
                    {
                        if($increment==0 && $print_row['sem']==2)
                        {
                            $count_col_spans = $count_col_spans+2;
                            $td_printe = 5;
                            $credits_register_row .= "<td height='25px' width='30px' colspan=2  valign='bottom' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td height='25px' width='30px'  colspan=2  valign='bottom' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td height='25px' width='30px'  colspan=2  valign='bottom' align='left' >&nbsp;</td>";
                        }
                        else if($increment==0 && $print_row['sem']=3)
                        {
                            $count_col_spans = $count_col_spans+3;
                            $td_printe = 5;
                            $credits_register_row .= "<td height='25px' width='30px'  colspan=3  valign='bottom' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td width='30px'  height='25px' colspan=3  valign='bottom' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td width='30px'  height='25px' colspan=3  valign='bottom' align='left' >&nbsp;</td>";
                        }
                        else if($increment==0 && $print_row['sem']=4 && count($final_total_credits) >1)
                        {

                            $count_col_spans = $count_col_spans+4;
                            $td_printe = 5;
                            $credits_register_row .= "<td width='30px'  height='25px' colspan=4  valign='bottom' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td width='30px'  height='25px' colspan=4 width='30px'  valign='bottom' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td height='25px' colspan=4  valign='bottom' align='left' width='30px'  >&nbsp;</td>";
                        }
                        else if($print_empty_data==1)
                        {
                               
                            $credits_register_row .= "<td width='30px'  height='25px' colspan=4 valign='bottom' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td  width='30px'  height='25px' colspan=4 valign='bottom' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td width='30px'  height='25px' colspan=4  valign='bottom' align='left' >&nbsp;</td>";
                            
                        }
                        
                        if($print_status!=$increment)
                        {
                            $count_col_spans = $count_col_spans+1;
                            $print_ear = $print_row['earned']==0?"-":$print_row['earned'];
                            $print_gpaa = $print_row['gpa']==0?'-':$print_row['gpa'];
                            
                            $align_text = $increment==0 ?'right':'center';
                            $add_padding = $align_text=='center' ?'padding-left: 15px;':'padding-left: 20px;';

                            $credits_register_row .= "
                            <td class='print_credit_points_add' style='".$add_padding."' height='25px' valign='bottom' align='".$align_text."' > <b>".$print_row['register']."</b> </td>";
                            $credits_earned_row .= "<td class='print_credit_points_add' height='25px' valign='bottom' style='".$add_padding."'  align='".$align_text."' > <b>".$print_ear."</b></td>";
                            $sgpa_row .= "<td  style='margin-right: -25px; padding-left: 20px;' style='".$add_padding."' class='print_credit_points_add' height='25px' align='".$align_text."' valign='bottom' ><b> ".$print_gpaa."</b> </td>";

                        }                   
                        $increment = $increment +1;
                    }
                }

                for ($loop_sem = 1; $loop_sem <= $total_semesters; $loop_sem++) 
                {
                    if ($loop_sem == $semester_number) 
                    {
                        $count_col_spans = $count_col_spans+1;
                            $registered_p = $cgpa_calc['registered']==0?"-":$cgpa_calc['registered'];
                            $sem_credits_earned_p = $cgpa_calc['sem_credits_earned']==0?'-':$cgpa_calc['sem_credits_earned'];
                            $sgpa_row_p = $cgpa_calc['gpa']==0?"-":$cgpa_calc['gpa'];

                            $align_text = $increment==0 ?'center':'left';
                            $add_padding = $align_text=='center' ?'padding-left: 15px;':'padding-left: 20px;';
                            $align_text = $loop_sem==5 || $loop_sem==6 ? 'right' :$align_text;
                            $merge_col = $semester_number==6 || $semester_number==5 ? 'colspan=2' : '';
                            
                            $credits_register_row .= "<td class='print_credit_points' valign='bottom' width='30px' style='".$add_padding."' height='25px' align='".$align_text."' ".$merge_col." > <b>" . $registered_p . "</b> </td>";
                            $credits_earned_row .= "<td class='print_credit_points' valign='bottom' width='30px' height='25px' style='".$add_padding."' align='".$align_text."'  ".$merge_col." > <b>" . $sem_credits_earned_p . "</b> </td>";
                            $sgpa_row .= "<td width='30px' class='print_credit_points' valign='bottom' height='25px' style='".$add_padding."' align='".$align_text."' ".$merge_col." > <b>" . $sgpa_row_p . "</b> </td>";
                            
                    } 
                    else 
                    {
                        if($increment==0 && ($semester_number!=6 || $semester_number!=5) )
                        {
                            $count_col_spans = $count_col_spans+1;
                            $credits_register_row .= "<td width='30px' height='25px' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td width='30px' height='25px' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td width='30px' height='25px' align='left' >&nbsp;</td>";
                        }
                            
                        
                    }
                }
                
                if($count_col_spans<11 )
                {
                    if($semester_number==6 || $semester_number==5)
                    {
                        $credits_register_row .= "<td width='30px' height='25px' align='left' >&nbsp;</td>";
                        $credits_earned_row .= "<td width='30px' height='25px' align='left' >&nbsp;</td>";
                        $sgpa_row .= "<td width='30px' height='25px' align='left' >&nbsp;</td>";
                    }
                    else
                    {
                        $credits_register_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)." align='left' >&nbsp;</td>";
                        $credits_earned_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)."  align='left' >&nbsp;</td>";
                        $sgpa_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)."  align='left' >&nbsp;</td>";
                    }

                    
                }
                $cumulative_row .= "<tr style='margin-top: -10px; padding-bottom: 10px;'   height='25px'>
                <td style='padding-bottom: 10px;'   colspan='3' width='20px' > &nbsp; </td>
                <td colspan='2' style='padding-bottom: 10px;'    width='20px'  class='print_credit_points cumulative_row' height='25px' style='margin-top: -10px;' valign='top' align='left' > <b>" . $cgpa_calc['cumulative_earned_credits'] . "</b> </td>
                <td style='padding-bottom: 10px;'   colspan='3' width='20px' > &nbsp; </td>
                <td style='padding-bottom: 10px;'   class='print_credit_points' width='60px'  height='25px' align='center' valign='top' style='margin-top: -10px;' > <b>" . $cgpa_calc['cgpa'] . "</b> </td>
                <td style='padding-bottom: 10px;' colspan='2' width='20px'> &nbsp; </td>
                </tr>";
               
                $credits_register_row .= "</tr>";
                $credits_earned_row .= "</tr>";
                $sgpa_row .= "</tr>";
                

                $footer .= $credits_register_row . $credits_earned_row . $sgpa_row . $cumulative_row . "<tr><td valign='middle' colspan=11 height=32px >&nbsp;</td></tr>".$date_insert."</table>";
                $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$value['year'].'" AND exam_month="'.$value['month'].'" and student_map_id="'.$value['student_map_id'].'"')->queryAll();
                if(!empty($check_add))
                {
                    foreach ($check_add as $valuess) 
                    {
                        $body .= "
                        <table border=0 width='100%'  >
                            <tr>
                                <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                                <td width='80px'  align='left'>" . $valuess['subject_code'] . "</td>
                                <td width='450px' colspan='4'  align='left'>" . wordwrap($valuess['subject_name'],6) . " #</td>
                                <td width='55px'  align='left'>" . $valuess['credits'] . "</td>
                                <td colspan='4' width='150px'  align='center'> COMPLETED </td>
                            </tr>
                        </table>";

                        $body .= " <br />
                        <table border=0  >
                            <tr>
                                <td style='font-weight: bold' width='420px' colspan='7'  align='center'> ~ END OF MARK STATEMENT ~ </td>
                                
                                <td colspan='4' width='230px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                        </table>";
                        $body .= "  <br />
                        <table border=0  >
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
                        <table border=0   >
                            <tr>
                                <td style='font-weight: bold' width='420px' colspan='7'  align='center'> ~ END OF MARK STATEMENT ~ </td>
                                
                                <td colspan='4' width='230px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                }

                $merge_body = "<tr><td width='100%' valign='top' colspan='11' height='545px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='65px' >&nbsp;</td></tr>";
                $html = $header . $merge_body . $footer."<pagebreak />";
                $print_stu_data .= $html;
                $header = "";
                $body = "";
                $footer = ""; $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
                $print_total_credits = array_filter(array(''=>''));
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
                        <td style='border: none;' colspan='11' width='100%'  >
                            <table width='100%' align='left' style='border: none !important; padding-top: 7px;'  border='0'>
                                <tr style='padding: 10px;'>
                                    <td class='line_height' colspan='4' width='30px;' style='width: 40px;' >&nbsp;</td>
                                    <td class='line_height' colspan='5' align='left' style='padding-left: 72px;'> <b>" . strtoupper($value['name']) . "</b> </td>
                                    <td class='line_height' align='right'  colspan='2' ><b>" . $value['year'] . " / " . strtoupper($app_month) . " </b> </td>
                                </tr>
                            
                                <tr style='padding: 10px;'>
                                    <td class='line_height' colspan='4' style='width: 30px;'>&nbsp;</td>
                                    <td class='line_height' align='left'  colspan='2' style='padding-left: 72px;'> <b>" . strtoupper($value['register_number']) . "</b></td>
                                    <td class='line_height' style='padding-left: 50px;' align='left' colspan='3' ><b>" . $dob . "</b></td>
                                    <td class='line_height' align='right' colspan='2' ><b>" . strtoupper($print_gender) . "</b></td>
                                </tr>
                            
                                <tr>
                                    <td class='line_height' colspan='4' width='30px;' style='width: 30px;'>&nbsp;</td>
                                    <td class='line_height' align='left' colspan='5' style='padding-left: 72px;'> <b>" . strtoupper($value['degree_name']) . "</b></td>
                                    <td class='line_height' colspan='2' align='right' ><b>" . $value['regulation_year'] . "</b></td>
                                </tr>
                                <tr>
                                    <td class='line_height' colspan='11' height='35px;' style='width: 30px;'>&nbsp; </td>
                                </tr>
                            </table>
                        </td>
                    </tr>";
            $total_credits = '';
            $total_earned_credits = '';
            $passed_grade_points = '';
            if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) {
                $body .= "
                    <table border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left' >" . $semester_array[$value['semester']]. "</td>
                            <td width='80px'  align='left' >" . $value['subject_code'] . "</td>
                            <td width='450px' colspan='5'  align='left'>" . wordwrap($value['subject_name'],6) . "</td>
                            <td colspan='4' width='205px'  align='center'> COMPLETED </td>
                        </tr>
                    </table>";
            } else {
                //$result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
                $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
                
                $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;
    
                $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;

                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];

                $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
                $body .= "
                    <table border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                            <td width='80px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                            <td width='450px' colspan='5'  align='left'>" . strtoupper(wordwrap($value['subject_name'],6)) . "</td>
                            <td width='55px'  align='left'>" . $value['credit_points'] . "</td>
                            <td width='55px'  align='left'> " . strtoupper($value['grade_name']) . " </td>
                            <td width='45px'  align='center'>" . $grade_point_print . "</td>
                            <td  style='padding-left: 10px;' width='50px'  align='center'>" . $result_stu . "</td></tr></table>";
                /* <td width='50px'  align='left'> ".$value['sub_total_marks']." </td>
                  <td width='50px'  align='left'> ".$value['total']." </td> */
            }
            $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id']);
            
        } // If not the same registration number

        else {
            $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id']);
            if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) {
                $body .= "
                    <table border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                            <td width='80px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                            <td width='450px' colspan='5' align='left'>" . strtoupper(wordwrap($value['subject_name'],6)) . "</td>
                            <td colspan='4' width='205px' align='center'> COMPLETED </td>
                        </tr>
                    </table>";
            } else {
                $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
                $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;

                $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;
                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];
                $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
                $body .= "
                    <table border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                            <td width='80px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                            <td width='450px' colspan='5'  align='left'>" . strtoupper(wordwrap($value['subject_name'],6)) . "</td>
                            <td width='55px'  align='left'>" . $value['credit_points'] . "</td>
                            <td width='55px'  align='left'> " . strtoupper($value['grade_name']) . " </td>
                            <td width='45px' class='margin_right'  align='center'>" . $grade_point_print . "</td>
                            <td  style='padding-left: 10px;' width='50px'  align='center'>" . $result_stu . "</td></tr></table>";
                
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
            <table border='0' width='100%' >
                <tr>
                    <td width='40px' align='left'>" . $semester_array[$semester_last_print]. "</td>
                    <td width='80px'  align='left'>" . $valuess['subject_code'] . "</td>
                    
                    <td width='450px' colspan='4'  align='left'>" . wordwrap($valuess['subject_name'],6) . " #</td>
                    <td width='55px'  align='left'>" . $valuess['credits'] . "</td>
                    <td colspan='4' width='150px'  align='center'> COMPLETED </td>
                </tr>
            </table>";
            $body .="<br />";
            $body .= "
            <table border='0' width='100%' >
                <tr>
                   
                    <td style='font-weight: bold' width='420px' colspan='7'  align='center'> ~ END OF MARK STATEMENT ~ </td>
                    
                    <td colspan='4' width='230px'  align='right'>&nbsp;</td>
                </tr>
            </table>";
            $body .="<br />";
            $body .= "
            <table border='0' width='100%' >
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
            <table border='0' width='100%' >
                <tr>
                  
                    <td style='font-weight: bold' width='420px' colspan='7'  align='center'> ~ END OF MARK STATEMENT ~ </td>
                    <td colspan='4' width='230px'  align='right'>&nbsp;</td>
                </tr>
            </table>";
    }

    $merge_body = "<tr><td valign='top' colspan='11' height='545x' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='65px' >&nbsp;</td></tr>";
   
    $final_total_credits =array_filter(array()); 

    for ($cal=1; $cal <= $total_semesters ; $cal++) 
                { 
                    $earned_credist = array_filter(array());
                    $gpa_calsss = array_filter(array());
                    $register_credits = array_filter(array());
                    foreach ($print_total_credits as $key => $checks) 
                    {
                       if($checks['sem']==$cal)
                       {
                            if($checks['res']=='PASS')
                            {
                                $earned_credist[] = $checks['credits'];
                                $gpa_calsss[] = $checks['credits']*$checks['grades'];
                            }
                            $register_credits[] = $checks['credits'];
                       }
                    }            
                    if(!empty($register_credits))
                    {
                        $division_row = array_sum($earned_credist)==0?1:array_sum($earned_credist);
                        $gpa_cals = round((array_sum($gpa_calsss)/$division_row),2);
                        $final_total_credits []= ['sem'=>$cal,'earned'=>array_sum($earned_credist),'gpa'=>$gpa_cals,'register'=>array_sum($register_credits)];
                    }
                }
                $copunt_of_arrears = count($final_total_credits);
                $colspan_merge = !empty($final_total_credits) && count($final_total_credits) >0?$colspan_merge - ($copunt_of_arrears>1?$copunt_of_arrears-1:$copunt_of_arrears-0):$colspan_merge;



                $credits_register_row .= "<tr><td width='30px' valign='middle' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $credits_earned_row .= "<tr><td width='30px' valign='middle'  align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $sgpa_row .= "<tr height='25px'>
                <td height='25px' valign='middle'  width='30px' align='left' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $td_printe = 1;
                $count_col_spans = $colspan_merge;
                $print_empty_data = 0;
                $print_empty_data = !empty($final_total_credits) && count($final_total_credits) >1?0:1;
                $tt_count = count($final_total_credits);
                $count_of_final = $tt_count-1;
                unset($final_total_credits["$count_of_final"]);
                $increment = 0;
                if(!empty($final_total_credits))
                {
                    
                    $print_status = count($final_total_credits);

                    foreach ($final_total_credits as $key => $print_row) 
                    {
                        if($increment==0 && $print_row['sem']==2)
                        {
                            $count_col_spans = $count_col_spans+2;
                            $td_printe = 5;
                            $credits_register_row .= "<td height='25px' width='30px' colspan=2  valign='bottom' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td height='25px' width='30px'  colspan=2  valign='bottom' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td height='25px' width='30px'  colspan=2  valign='bottom' align='left' >&nbsp;</td>";
                        }
                        else if($increment==0 && $print_row['sem']=3)
                        {
                            $count_col_spans = $count_col_spans+3;
                            $td_printe = 5;
                            $credits_register_row .= "<td height='25px' width='30px'  colspan=3  valign='bottom' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td width='30px'  height='25px' colspan=3  valign='bottom' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td width='30px'  height='25px' colspan=3  valign='bottom' align='left' >&nbsp;</td>";
                        }
                        else if($increment==0 && $print_row['sem']=4 && count($final_total_credits) >1)
                        {

                            $count_col_spans = $count_col_spans+4;
                            $td_printe = 5;
                            $credits_register_row .= "<td width='30px'  height='25px' colspan=4  valign='bottom' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td width='30px'  height='25px' colspan=4 width='30px'  valign='bottom' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td height='25px' colspan=4  valign='bottom' align='left' width='30px'  >&nbsp;</td>";
                        }
                        else if($print_empty_data==1)
                        {
                               
                            $credits_register_row .= "<td width='30px'  height='25px' colspan=4 valign='bottom' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td  width='30px'  height='25px' colspan=4 valign='bottom' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td width='30px'  height='25px' colspan=4  valign='bottom' align='left' >&nbsp;</td>";
                            
                        }
                        
                        if($print_status!=$increment)
                        {
                            $count_col_spans = $count_col_spans+1;
                            $print_ear = $print_row['earned']==0?"-":$print_row['earned'];
                            $print_gpaa = $print_row['gpa']==0?'-':$print_row['gpa'];
                            
                            $align_text = $increment==0 ?'right':'center';
                            $add_padding = $align_text=='center' ?'padding-left: 15px;':'padding-left: 20px;';

                            $credits_register_row .= "
                            <td class='print_credit_points_add' style='".$add_padding."' height='25px' valign='bottom' align='".$align_text."' > <b>".$print_row['register']."</b> </td>";
                            $credits_earned_row .= "<td class='print_credit_points_add' height='25px' valign='bottom' style='".$add_padding."'  align='".$align_text."' > <b>".$print_ear."</b></td>";
                            $sgpa_row .= "<td  style='margin-right: -25px; padding-left: 20px;' style='".$add_padding."' class='print_credit_points_add' height='25px' align='".$align_text."' valign='bottom' ><b> ".$print_gpaa."</b> </td>";

                        }                   
                        $increment = $increment +1;
                    }
                }

                for ($loop_sem = 1; $loop_sem <= $total_semesters; $loop_sem++) 
                {
                    if ($loop_sem == $semester_number) 
                    {
                        $count_col_spans = $count_col_spans+1;
                            $registered_p = $cgpa_calc['registered']==0?"-":$cgpa_calc['registered'];
                            $sem_credits_earned_p = $cgpa_calc['sem_credits_earned']==0?'-':$cgpa_calc['sem_credits_earned'];
                            $sgpa_row_p = $cgpa_calc['gpa']==0?"-":$cgpa_calc['gpa'];

                            $align_text = $increment==0 ?'center':'left';
                            $add_padding = $align_text=='center' ?'padding-left: 15px;':'padding-left: 20px;';

                            $credits_register_row .= "<td class='print_credit_points' valign='bottom' width='30px' style='".$add_padding."' height='25px' align='".$align_text."' > <b>" . $registered_p . "</b> </td>";
                            $credits_earned_row .= "<td class='print_credit_points' valign='bottom' width='30px' height='25px' style='".$add_padding."' align='".$align_text."' > <b>" . $sem_credits_earned_p . "</b> </td>";
                            $sgpa_row .= "<td width='30px' class='print_credit_points' valign='bottom' height='25px' style='".$add_padding."' align='".$align_text."' > <b>" . $sgpa_row_p . "</b> </td>";
                            
                    } 
                    else 
                    {
                        if($increment==0)
                        {
                            $count_col_spans = $count_col_spans+1;
                            $credits_register_row .= "<td width='30px' height='25px' align='left' >&nbsp;</td>";
                            $credits_earned_row .= "<td width='30px' height='25px' align='left' >&nbsp;</td>";
                            $sgpa_row .= "<td width='30px' height='25px' align='left' >&nbsp;</td>";
                        } 
                        
                    }
                }
                
                if($count_col_spans<11)
                {
                    $credits_register_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)." align='left' >&nbsp;</td>";
                    $credits_earned_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)."  align='left' >&nbsp;</td>";
                    $sgpa_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)."  align='left' >&nbsp;</td>";
                }
                $cumulative_row .= "<tr style='margin-top: -10px; padding-bottom: 10px;'   height='25px'>
                <td style='padding-bottom: 10px;'   colspan='3' width='20px' > &nbsp; </td>
                <td colspan='2' style='padding-bottom: 10px;'    width='20px'  class='print_credit_points cumulative_row' height='25px' style='margin-top: -10px;' valign='top' align='left' > <b>" . $cgpa_calc['cumulative_earned_credits'] . "</b> </td>
                <td style='padding-bottom: 10px;'   colspan='3' width='20px' > &nbsp; </td>
                <td style='padding-bottom: 10px;'   class='print_credit_points' width='60px'  height='25px' align='center' valign='top' style='margin-top: -10px;' > <b>" . $cgpa_calc['cgpa'] . "</b> </td>
                <td style='padding-bottom: 10px;' colspan='2' width='20px'> &nbsp; </td>
                </tr>";
               
                $credits_register_row .= "</tr>";
                $credits_earned_row .= "</tr>";
                $sgpa_row .= "</tr>";
                

                $footer .= $credits_register_row . $credits_earned_row . $sgpa_row . $cumulative_row . "<tr><td valign='middle' colspan=11 height=32px >&nbsp;</td></tr>".$date_insert."</table>";
    $html = $header . $merge_body . $footer;
    //$html = $header . $merge_body . $footer." <tr><td colspan='11' width='30px' >".$date."</td></tr> ";
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
