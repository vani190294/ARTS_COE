<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\CoeBatDegReg;
use app\models\StudentMapping;
use app\models\MandatorySubcatSubjects;
use app\models\Degree;
$this->registerCssFile("@web/css/skcet_oldmarkstatement.css");
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?> 
<?php
if (isset($mark_statement) && !empty($mark_statement)) 
{
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    /*
     *   Already Defined Variables from the above included file
     *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
     *   use these variables for application
     *   use $file_content_available="Yes" for Content Status of the Organisation
     */

    $add_tr_starting = $print_trimester == 1 ? "<tr><td colspan='11' width='30px'>&nbsp;</td></tr> <tr><td class='make_bold_font' id='print_trimester' colspan='11'> TRIMESTER PATTERN </td></td></tr>" : "<tr><td class='make_bold_font' id='no_print_trimester' colspan='11'> &nbsp; </td></td></tr>";
    $supported_extensions = ConfigUtilities::ValidFileExtension();
    $stu_directory = Yii::getAlias("@web") . "/resources/stu_photos/";
    $absolute_dire = Yii::getAlias("@webroot") . "/resources/stu_photos/";
    $total_subs_count = $is_additional_printed = 0;
    $html = "";
    $previous_subject_code = $splitted_body = "";
    $previous_reg_number = "";
    $header = "";
    $body = "";
    $footer = "";
    $print_register_number = "";
    $print_student_map_id = "";
    $new_stu_flag = 0;
    $print_stu_data = "";
    $exam_year = ''; $prev_add_pint_code = '';
    $app_month = '';
    $batch_mapping_id = '';
    $first_reg_num = 0;
    $date_print = isset($date_print) ? date('d-m-Y',strtotime($date_print)) : date('d-m-Y');
    $date = "<b>".$date_print."</b>";
    
    $date_insert = "<tr><td colspan='11' height='20px' width='50px'> &nbsp;</td></tr> <tr><td  valign='bottom' class='date_style font_bigger' colspan='11' width='300px' >".$date."</td></tr> ";
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
    $semester_array = ['1'=>'01','2'=>'02','3'=>'03','4'=>'04','5'=>'05','6'=>'06','7'=>'07','8'=>'08','9'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14'];

    foreach ($mark_statement as $value) 
    {
        $files = glob($absolute_dire . $value['register_number'] . ".*"); // Will find 2.JPG, 2.php, 2.gif
        // Process through each file in the list
        // and output its extension
        //$deg = $print_trimester == 1 ? strtoupper($value['programme_name']) : strtoupper($value['degree_code']) . ". ". strtoupper($value['programme_name']);
        
        if($value['degree_code']=="MBA" || $value['degree_code']=="mba" || $value['degree_code']=="Mba")
        {
            $deg =strtoupper($value['programme_name']);
        }
        else
        {
            $deg =strtoupper($value['degree_code']) . ". ". strtoupper($value['programme_name']);
        }

        if (count($files) > 0) 
        {
            foreach ($files as $file) 
            {
                $info = pathinfo($file);
                $extension = "." . $info["extension"];
            }
        } 
        else 
        {
            $extension = "";
        }
        
        $exam_year = $value['year'];
        $app_month = $value['month'];
        $month_disp = $value['month']=='Oct/Nov' ||  $value['month']=='OCT/NOV' ||  $value['month']=='oct/nov' ?'NOV':'APR';
        if($value['month']=='Oct/Nov' ||  $value['month']=='OCT/NOV' ||  $value['month']=='oct/nov')
        {
            $month_disp ="NOV";
        }
        else if($value['month']=='April/May' ||  $value['month']=='APRIL/MAY' ||  $value['month']=='april/may')
        {
            $month_disp ="APR";
        }
        else
        {
            $month_disp =strtoupper($value['month']);
        }
        $add_month = $value['add_month'];
        $batch_mapping_id = $value['course_batch_mapping_id'];
        $semester_number = ConfigUtilities::semCaluclation($exam_year, $app_month, $batch_mapping_id);
        $course_batch_mapping_id = CoeBatDegReg::findOne($batch_mapping_id);
        $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
        $total_semesters = $degree_years->degree_total_semesters;
        $changeCssClass = 'print_credit_points';
        $colspan_merge = 3;

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
                        $round_4 = (array_sum($gpa_calsss)/$division_row);
                        $gpa_cals = round($round_4,2);
                        $gpa_cals = strlen($gpa_cals)==1?$gpa_cals.'.00':$gpa_cals;
                        $gpa_cals = strlen($gpa_cals)==3?$gpa_cals.'0':$gpa_cals;
                        $final_total_credits []= ['sem'=>$cal,'earned'=>array_sum($earned_credist),'gpa'=>$gpa_cals,'register'=>array_sum($register_credits)];
                    }
                }
                $credits_register_row .= "<tr> <td width='40px' valign='middle' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $credits_earned_row .= "<tr><td width='40px' valign='middle' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $sgpa_row .= "<tr height='25px'> <td height='25px'  width='40px' colspan='" . $colspan_merge . "' > &nbsp; </td>";

                $td_printe = 0;
                $count_col_spans = 3;
                $print_empty_data = 0;
                $print_empty_data = !empty($final_total_credits) && count($final_total_credits) >1?0:1;
                $tt_count = count($final_total_credits);
                $count_of_final = $tt_count-1;
                unset($final_total_credits["$count_of_final"]);
                $increment = 0;
               
                $print_td=0;
                 $printed_sem = $sem_printed_val =1; 
                if(!empty($final_total_credits))
                {
                    $i=0;
                    
                    foreach ($final_total_credits as $key => $total_cre) 
                    {
                        for ($loop_sems=$sem_printed_val; $loop_sems <$semester_number ; $loop_sems++) 
                        { 
                           
                            if($total_cre['sem']==$loop_sems)
                            {
                                $print_ear = $total_cre['earned']==0?"-":$total_cre['earned'];
                                $print_gpaa = $total_cre['gpa']==0?'-':$total_cre['gpa'];
                                $credits_register_row .= "
                                <td width='30px' class='print_credit_points arrear_sem_credits font_bigger' height='25px' valign='bottom'  > <b>".$total_cre['register']."</b> </td>";
                                $credits_earned_row .= "<td class='print_credit_points arrear_sem_credits font_bigger' height='25px' valign='bottom' width='30px' > <b>".$print_ear."</td>";
                                $sgpa_row .= "<td class='print_credit_points arrear_sem_credits font_bigger' width='30px' height='25px'  valign='bottom' ><b> ".$print_gpaa."</b> </td>";
                                $sem_printed_val = $printed_sem = $total_cre['sem'];
                                $sem_printed_val++;
                                $print_td++;
                                break;
                            }
                            else
                            {
                                $credits_register_row .= "<td width='30px' height='25px' valign='bottom' align='left' >&nbsp;</td>";
                                $credits_earned_row .= "<td width='30px' height='25px' valign='bottom' align='left' >&nbsp;</td>";
                                $sgpa_row .= "<td width='30px' height='25px' valign='bottom' align='left' >&nbsp;</td>";
                                $increment = $increment+1;
                                $print_td++;
                            }
                            
                        }
                       
                    }
                }

                 $count_col_spans = $count_col_spans+$print_td; 
                for ($loop_sem = $sem_printed_val; $loop_sem <= $total_semesters; $loop_sem++) 
                {
                    if ($loop_sem == $semester_number) 
                    {
                        $registered_p = $cgpa_calc['registered']==0?"-":$cgpa_calc['registered'];
                        $sem_credits_earned_p = $cgpa_calc['sem_credits_earned']==0?'-':$cgpa_calc['sem_credits_earned'];
                        $sgpa_row_p = $cgpa_calc['gpa']==0?"-":$cgpa_calc['gpa'];
                        $align_text = $semester_number==8 || $semester_number==6 ? 'sem_8':'';
                        $merge_col =  'colspan='.$td_printe; 
                        $increment = 0;
                            $credits_register_row .= "<td class='print_credit_points $align_text font_bigger' valign='bottom' width='60px' ".$merge_col." > <b>" . $registered_p . "</b> </td>";
                            $credits_earned_row .= "<td class='print_credit_points $align_text font_bigger' valign='bottom' width='60px' height='25px'  ".$merge_col." > <b>" . $sem_credits_earned_p . "</b> </td>";
                            $sgpa_row .= "<td width='60px' class='print_credit_points $align_text font_bigger' valign='bottom' height='25px'  ".$merge_col."  > <b>" . $sgpa_row_p . "</b> </td>";
                    }
                    else 
                    { 
                        /*if($increment!=0)
                        {                            
                             
                        }
                        else
                        {*/
                            $credits_register_row .= "<td width='60px' height='25px' >&nbsp;</td>";
                            $credits_earned_row .= "<td width='60px' height='25px' >&nbsp;</td>";
                            $sgpa_row .= "<td width='60px' height='25px'  >&nbsp;</td>";  
                        //}
                        
                    }
                    $count_col_spans = $count_col_spans+1;
                }
                if($count_col_spans<11)
                {
                    $credits_register_row .= "<td  width='30px' height='25px' colspan=".(11-$count_col_spans)."  >&nbsp;</td>";
                    $credits_earned_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)."   >&nbsp;</td>";
                    $sgpa_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)."   >&nbsp;</td>";
                }
                $credits_register_row .= "</tr>";
                $credits_earned_row .= "</tr>";
                $sgpa_row .= "</tr>";
                if($total_subs_count>=23)
                {
                     $body .= " 
                    <table border=0  >
                        <tr>
                            <td class='make_bold_font font_bigger' width='600px' colspan='7'  align='center'> ~ CONTINUED IN NEXT PAGE ~ </td>                        
                            <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                        </tr>
                    </table>";

                    $splitted_body = "<tr><td width='100%' valign='top' colspan='11' height='547px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='64px' >&nbsp;</td></tr>";

                    $html = $header . $splitted_body . "</table><pagebreak />";

                    $print_stu_data  .= $html;
                    $total_subs_count = 1; $body = $splitted_body = '';
                }
                 $cgpa_calculation = ConfigUtilities::getCgpaCaluclationtransfer1($value['course_batch_mapping_id'],$value['student_map_id'],$value['semester']);
                $cumulative_row .= "<tr class='cumulative_row' height='25px'>
                <td colspan='3' width='20px' > &nbsp; </td>
                <td class='print_cumulative_credit_points font_bigger' height='25px' valign='top' > <b>" . $cgpa_calculation['transfer_cumulative'] . "</b> </td>
                <td colspan='4' width='20px' > &nbsp; </td>
                <td class='print_cumulative_credit_points_cgpa font_bigger' width='60px'  height='25px'  valign='top' > <b>" . $cgpa_calculation['final_cgpa'] . "</b> </td>
                <td colspan='2' width='20px'> &nbsp; </td>
                </tr>";
                
                $footer .= $credits_register_row . $credits_earned_row . $sgpa_row . $cumulative_row . "<tr><td valign='middle' colspan=11 height=32px >&nbsp;</td></tr>".$date_insert."</table>";

                $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$exam_year.'" AND exam_month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and result like "%pass%"')->queryAll();
                $check_add_print = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE student_map_id="'.$print_student_map_id.'" and result like "%pass%"')->queryAll();
                if(!empty($check_add_print))
                {
                    $is_additional_printed = 1;
                }
                if(!empty($check_add))
                {
                    $body .= "
                    <table border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left'>&nbsp;</td>
                            <td width='67px' align='left'>&nbsp;</td>
                            <td width='450px' class='put_margin' colspan='9'  align='left'><b>ADDITIONAL CREDIT COURSE(S)*</b></td>
                        </tr>
                    </table>";
                    $is_additional_printed = 1;
                    foreach ($check_add as $valuess) 
                    {
                        $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                        $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                        
                        $sub_na = wordwrap(strtoupper($valuess['subject_name']), 50, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = strtoupper(nl2br($sub_na));
                        //$subject_name_print = "<b>".strtoupper($valuess['subject_name'])."</b>";

                        $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);

                        $body .= "
                        <table border='0' width='100%' >
                            <tr>
                                <td width='40px' align='left'>" . $semester_array[$semester_number]. "</td>
                                <td width='67px' align='left'>" . strtoupper($valuess['subject_code']) . "</td>
                                <td width='650px' class='put_margin' colspan='5'  align='left'>" . $subject_name_print . "</td>
                                <td width='52px'  align='left'>" . $valuess['credits'] . "</td>
                                <td width='150px' colspan=3  align='left'> COMPLETED </td>
                            </tr>
                        </table>";
                    }

                        $body .= "
                        <table border=0  >
                            <tr>
                                <td colspan='4'  width='75px' align='left'>&nbsp;</td>
                                <td class='additional_credits' width='450px' class='additional_credits' colspan='3'  align='center'> *ADDITIONAL CREDITS WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                                <td colspan='4' width='170px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                        $body .= " 
                        <table border=0  >
                            <tr>
                                <td class='make_bold_font font_bigger' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                        </table>";
                   
                }
                else
                {
                    
                    $body .= "
                        <table border=0   >
                            <tr>
                                <td  class='make_bold_font font_bigger' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                }

                if($is_additional_printed==1 && !empty($cgpa_calc['normal_additional_credits']) && $cgpa_calc['normal_additional_credits']!=0 )
                {                    
                    $merge_body = "<tr><td width='100%' valign='top' colspan='11' height='547px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='64px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$cgpa_calc['normal_additional_credits']."</b></td></tr>";
                }
                else
                {
                    $merge_body = "<tr><td width='100%' valign='top' colspan='11' height='547px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='64px' >&nbsp;</td></tr>";
                }


                
               
                $html = $header . $merge_body . $footer."<pagebreak />";
                $print_stu_data .= $html;
                $header = "";
                $body = "";
                $footer = ""; $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
                $print_total_credits = array_filter(array(''=>''));
                $new_stu_flag = 1; $total_subs_count=0; $is_additional_printed = 0;
            }
            
            $header .= "<table width='100%' autosize='1'  >";
            $header .= '<tr>
                           <td height="113px" style="border: none; padding-top: 15px; text-align: right; padding-right: 10px; " colspan="11" >
                                <img class="img-responsive" width="80" height="80" src="' . $stu_photo . '" alt="' . $stu_photo . '" Photo >
                                
                            </td>
                        </tr>'.$add_tr_starting;
            $header .= "
                    <tr>
                        <td colspan='11' width='100%'  >
                            <table class='font_bigger' width='100%' align='left' style='border: none !important; padding-top: 10px;'  border='0'>
                                <tr  style='padding: 10px;'>
                                    <td class='line_height' colspan='4' width='100px;' >&nbsp;</td>
                                    <td class='line_height' colspan='6' width='250px;'  > <b>" . strtoupper($value['name']) . "</b> </td>
                                    <td class='line_height' width='100px;'  ><b>" . strtoupper($month_disp)  . "  " . $value['year'] . " </b> </td>
                                </tr>
                            
                                <tr style='padding: 10px;'>
                                    <td class='line_height pull_down' colspan='4' width='100px;' >&nbsp;</td>
                                    <td class='line_height pull_down' colspan='5' width='250px;'  > <b>" . strtoupper($value['register_number']) . "</td>
                                    <td class='line_height pull_down' ><b>" . $dob . "</td>
                                    <td class='line_height pull_down'  width='100px;' ><b>" . strtoupper($print_gender) . "</td>
                                </tr>
                            
                                <tr  style='padding: 10px;'>
                                    <td class='line_height pull_down' colspan='4' width='100px;' >&nbsp;</td>
                                    <td class='line_height pull_down' colspan='6' width='250px;'  > <b>" . $deg . "</td>
                                    <td class='line_height pull_down'  width='100px;' ><b>" . $value['regulation_year'] . "</td>
                                </tr>
                                <tr>
                                    <td class='line_height' colspan='11' class='stu_sub_gap' >&nbsp; </td>
                                </tr>
                            </table>
                        </td>
                    </tr>";
            $total_credits = '';
            $total_earned_credits = '';
            $additional_credit_group = '';
            $passed_grade_points = '';
            if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) 
            {
                $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = strtoupper(nl2br($sub_na));
                //$subject_name_print = "<b>".strtoupper($value['subject_name'])."</b>";

                $result_stu = $value['result'] == "Pass" || $value['result'] == "PASS" || $value['result'] == "pass"? "COMPLETED" : "NOT COMPLETED";

                $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left' >" . $semester_array[$value['semester']]. "</td>
                            <td width='67px'  align='left'>" . $value['subject_code'] . "</td>
                            <td width='450px' class='put_margin' colspan='5'  align='left'>" . $subject_name_print . "</td>
                            <td colspan='4' width='205px'  align='center'> ".$result_stu." </td>
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
                $result_stu = ($value['withheld'] == "w" || $value['withheld'] == "W") ? "RA" : $result_stu;

                $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = strtoupper(nl2br($sub_na));
                //$subject_name_print = "<b>".strtoupper($value['subject_name'])."</b>";

                $grade_name = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? 'AB' : $value['grade_name'];

                $grade_name = ($value['grade_name'] == "WH" || $value['grade_name'] == "wh") ? 'RA' : $grade_name;
                $grade_name = ($value['grade_name'] == "WD" || $value['grade_name'] == "wd") ? 'W' : $grade_name;

                $grade_name = $value['withheld'] == "w" || $value['withheld'] == "W" ? "RA" : $grade_name;

                $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
                $grade_point_print = $grade_name=='RA'?"-":$grade_point_print;
                $grade_point_print = $value['withheld'] == "w" || $value['withheld'] == "W" ? "-":$grade_point_print;

                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];
                if(isset($value['is_additional']))
                {
                    if($prev_add_pint_code==$value['subject_code'])
                    {
                        $disp_credit = $value['credit_points']==0?'-':$value['credit_points'];
                        $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                        $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 50, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = strtoupper(nl2br($sub_na));
                         $body .= "
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>    
                                <td colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                <td width='450px' class='put_margin' colspan='5'  align='left'> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</td>
                                <td width='52px'  align='left'>" . $disp_credit . "</td>
                                <td width='45px'  align='left'> " . strtoupper($grade_name) . " </td>
                                <td width='55px'  align='center'>" . $grade_point_print . "</td>
                                <td  style='padding-left: 10px;' width='50px'  align='center'>" . $result_stu . "</td></tr></table>";
                    }
                    else
                    {
                        $disp_credit = $value['credit_points']==0?'-':$value['credit_points'];
                        $body .= "
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                                <td width='67px' align='left'>" . strtoupper($value['subject_code']) . "</td>
                                <td width='450px' class='put_margin' colspan='5'  align='left'>" . $subject_name_print . "</td>
                                <td width='52px' colspan=4  align='left'>" . $disp_credit . "</td>
                               </tr></table>";  

                        $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                        $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 50, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = strtoupper(nl2br($sub_na));
                        $disp_credit = $value['credit_points']==0?'-':$value['credit_points'];
                         $body .= "
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>    
                                <td colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                <td width='450px' class='put_margin' colspan='5'  align='left'> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</td>
                                <td width='52px'  align='left'>" . $disp_credit . "</td>
                                <td width='45px'  align='left'> " . strtoupper($grade_name) . " </td>
                                <td width='55px'  align='center'>" . $grade_point_print . "</td>
                                <td  style='padding-left: 10px;' width='50px'  align='center'>" . $result_stu . "</td></tr></table>";
                    }
                    $prev_add_pint_code=$value['subject_code'];
                    $total_subs_count++;   
                }
                else
                {
                    $disp_credit = $value['credit_points']==0?'-':$value['credit_points'];
                    $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                            <td width='67px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                            <td width='450px' class='put_margin'   colspan='5'  align='left'>" . $subject_name_print . "</td>
                            <td width='52px'  align='left'>" . $disp_credit . "</td>
                            <td width='45px'  align='left'> " . strtoupper($grade_name) . " </td>
                            <td width='55px'  align='center'>" . $grade_point_print . "</td>
                            <td  style='padding-left: 10px;' width='50px'  align='center'>" . $result_stu . "</td></tr></table>"; 
                    $total_subs_count++; 
                }
            } // If subject not contains the ESE_max==0  Condition
            $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id'],$semester_number);
        } // If not the same registration number
        else 
        {

            if($total_subs_count>=23)
            {
                 $body .= " 
                <table border=0  >
                    <tr>
                        <td class='make_bold_font font_bigger' width='600px' colspan='7'  align='center'> ~ CONTINUED IN NEXT PAGE ~ </td>                        
                        <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                    </tr>
                </table>";

                $splitted_body = "<tr><td width='100%' valign='top' colspan='11' height='547px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='64px' >&nbsp;</td></tr>";

                $html = $header . $splitted_body . "</table><pagebreak />";

                $print_stu_data  .= $html;
                $total_subs_count = 1; $body = $splitted_body = '';
            }

          $cgpa_calculation = ConfigUtilities::getCgpaCaluclationtransfer1($value['course_batch_mapping_id'],$value['student_map_id'],$value['semester']);
            if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) 
            {
                $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = strtoupper(nl2br($sub_na));
                //$subject_name_print = "<b>".strtoupper($value['subject_name'])."</b>";
                $result_stu = $value['result'] == "Pass" || $value['result'] == "PASS" || $value['result'] == "pass"? "COMPLETED" : "NOT COMPLETED";
                $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left' >" . $semester_array[$value['semester']]. "</td>
                            <td width='67px'  align='left'>" . $value['subject_code'] . "</td>
                            <td width='450px' class='put_margin'   colspan='5'  align='left'>" . $subject_name_print . "</td>
                            <td colspan='4' width='205px'  align='center'> ".$result_stu."</b> </td>
                        </tr>
                    </table>";
                $total_subs_count++;
            } 
            else
            {
                $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "RA" : $value['result'];
                
                $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;
    
                $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;

                $result_stu = $value['grade_name'] == "WD" || $value['grade_name'] == "wd" ? "RA" : $result_stu;

                $result_stu = $value['withheld'] == "w" || $value['withheld'] == "W"  ? "RA" : $result_stu;


                $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = strtoupper(nl2br($sub_na));
                //$subject_name_print = "<b>".strtoupper($value['subject_name'])."</b>";

                $grade_name = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? 'AB' : $value['grade_name'];

                $grade_name = $value['grade_name'] == "WH" || $value['grade_name'] == "wh" ? 'RA' : $value['grade_name'];
                $grade_name = $value['grade_name'] == "WD" || $value['grade_name'] == "wd" ? 'W' : $value['grade_name'];

                $grade_name = $value['withheld'] == "w" || $value['withheld'] == "W" ? "RA" : $grade_name;

                $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
                $grade_point_print = $grade_name=='RA'?"-":$grade_point_print;

                $grade_point_print = $value['withheld'] == "w" || $value['withheld'] == "W" ?"-":$grade_point_print;

                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];

                if(isset($value['is_additional']))
                {
                    if($prev_add_pint_code==$value['subject_code'])
                    {
                        $disp_credit = $value['credit_points']==0?'-':$value['credit_points'];
                        $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                        $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 50, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = strtoupper(nl2br($sub_na));
                         $body .= "
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>    
                                <td colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                <td width='450px' class='put_margin' colspan='5'  align='left'> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</td>
                                <td width='52px'  align='left'>" . $disp_credit. "</td>
                                <td width='45px'  align='left'> " . strtoupper($grade_name) . " </td>
                                <td width='55px'  align='center'>" . $grade_point_print . "</td>
                                <td  style='padding-left: 10px;' width='50px'  align='center'>" . $result_stu . "</td></tr></table>";
                    }
                    else
                    {
                        $body .= "
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                                <td width='67px' align='left'>" . strtoupper($value['subject_code']) . "</td>
                                <td width='450px' class='put_margin' colspan='5'  align='left'>" . $subject_name_print . "</td>
                                <td width='52px' colspan=4  align='left'>&nbsp;</td>
                               </tr></table>";  

                        $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                        $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 50, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = strtoupper(nl2br($sub_na));
                        $disp_credit = $value['credit_points']==0?'-':$value['credit_points'];
                         $body .= "
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>    
                                <td colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                <td width='450px' class='put_margin' colspan='5'  align='left'> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</td>
                                <td width='52px'  align='left'>" . $disp_credit. "</td>
                                <td width='45px'  align='left'> " . strtoupper($grade_name) . " </td>
                                <td width='55px'  align='center'>" . $grade_point_print . "</td>
                                <td  style='padding-left: 10px;' width='50px'  align='center'>" . $result_stu . "</td></tr></table>";
                    }
                    $prev_add_pint_code=$value['subject_code'];
                    $total_subs_count++;
                }
                else
                {
                    $disp_credit = $value['credit_points']==0?'-':$value['credit_points'];
                    $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left'>" . $semester_array[$value['semester']]. "</td>
                            <td width='67px'  align='left'>" . strtoupper($value['subject_code']) . "</td>
                            <td width='450px' class='put_margin'   colspan='5'  align='left'>" . $subject_name_print . "</td>
                            <td width='52px'  align='left'>" . $disp_credit . "</td>
                            <td width='45px'  align='left'> " . strtoupper($grade_name) . " </td>
                            <td width='55px'  align='center'>" . $grade_point_print . "</td>
                            <td  style='padding-left: 10px;' width='50px'  align='center'>" . $result_stu . "</td></tr></table>";  
                    $total_subs_count++;
                }
            }
        }
        $previous_subject_code = $value['subject_code'];
        $previous_reg_number = $value['register_number'];
        $print_student_map_id = $value['student_map_id'];
        $semester_last_print = $value['semester'];
    }// End the foreach variable here
   
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
                        $round_4 = (array_sum($gpa_calsss)/$division_row);
                        $gpa_cals = round($round_4,2);
                        $gpa_cals = strlen($gpa_cals)==1?$gpa_cals.'.00':$gpa_cals;
                        $gpa_cals = strlen($gpa_cals)==3?$gpa_cals.'0':$gpa_cals;
                        $final_total_credits []= ['sem'=>$cal,'earned'=>array_sum($earned_credist),'gpa'=>$gpa_cals,'register'=>array_sum($register_credits)];
                    }
                }
                $credits_register_row .= "<tr> <td width='40px' valign='middle' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $credits_earned_row .= "<tr><td width='40px' valign='middle' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $sgpa_row .= "<tr height='25px'> <td height='25px'  width='40px' colspan='" . $colspan_merge . "' > &nbsp; </td>";

                $td_printe = 0;
                $count_col_spans = 3;
                $print_empty_data = 0;
                $print_empty_data = !empty($final_total_credits) && count($final_total_credits) >1?0:1;
                $tt_count = count($final_total_credits);
                $count_of_final = $tt_count-1;
                unset($final_total_credits["$count_of_final"]);
                $increment = 0;
               
                $print_td=0;
                $printed_sem = $sem_printed_val =1;  
                if(!empty($final_total_credits))
                {
                    $i=0;
                    
                    foreach ($final_total_credits as $key => $total_cre) 
                    {
                        for ($loop_sems=$sem_printed_val; $loop_sems <$semester_number ; $loop_sems++) 
                        { 
                           
                            if($total_cre['sem']==$loop_sems)
                            {
                                $print_ear = $total_cre['earned']==0?"-":$total_cre['earned'];
                                $print_gpaa = $total_cre['gpa']==0?'-':$total_cre['gpa'];
                                $credits_register_row .= "
                                <td width='30px' class='print_credit_points arrear_sem_credits font_bigger' height='25px' valign='bottom'  > <b>".$total_cre['register']."</b> </td>";
                                $credits_earned_row .= "<td class='print_credit_points arrear_sem_credits font_bigger' height='25px' valign='bottom' width='30px' > <b>".$print_ear."</td>";
                                $sgpa_row .= "<td class='print_credit_points arrear_sem_credits font_bigger' width='30px' height='25px'  valign='bottom' ><b> ".$print_gpaa."</b> </td>";
                                $sem_printed_val = $printed_sem = $total_cre['sem'];
                                $sem_printed_val++;
                                $print_td++;
                                break;
                            }
                            else
                            {
                                $credits_register_row .= "<td width='30px' height='25px' valign='bottom' align='left' >&nbsp;</td>";
                                $credits_earned_row .= "<td width='30px' height='25px' valign='bottom' align='left' >&nbsp;</td>";
                                $sgpa_row .= "<td width='30px' height='25px' valign='bottom' align='left' >&nbsp;</td>";
                                $increment = $increment+1;
                                $print_td++;
                            }
                            
                        }
                       
                    }
                }

                 $count_col_spans = $count_col_spans+$print_td; 
                for ($loop_sem = $sem_printed_val; $loop_sem <= $total_semesters; $loop_sem++) 
                {
                    if ($loop_sem == $semester_number) 
                    {
                        $registered_p = $cgpa_calc['registered']==0?"-":$cgpa_calc['registered'];
                        $sem_credits_earned_p = $cgpa_calc['sem_credits_earned']==0?'-':$cgpa_calc['sem_credits_earned'];
                        $sgpa_row_p = $cgpa_calc['gpa']==0?"-":$cgpa_calc['gpa'];
                        $align_text = $semester_number==8 || $semester_number==6 ? 'sem_8':'';
                        $merge_col =  'colspan='.$td_printe; 
                        $increment = 0;
                            $credits_register_row .= "<td class='print_credit_points $align_text font_bigger ' valign='bottom' width='60px' ".$merge_col." > <b>" . $registered_p . "</b> </td>";
                            $credits_earned_row .= "<td class='print_credit_points $align_text font_bigger' valign='bottom' width='60px' height='25px'  ".$merge_col." > <b>" . $sem_credits_earned_p . "</b> </td>";
                            $sgpa_row .= "<td width='60px' class='print_credit_points $align_text font_bigger' valign='bottom' height='25px'  ".$merge_col."  > <b>" . $sgpa_row_p . "</b> </td>";
                    }
                    else 
                    { 
                        /*if($increment!=0)
                        {                            
                             
                        }
                        else
                        {*/
                            $credits_register_row .= "<td width='60px' height='25px' >&nbsp;</td>";
                            $credits_earned_row .= "<td width='60px' height='25px' >&nbsp;</td>";
                            $sgpa_row .= "<td width='60px' height='25px'  >&nbsp;</td>";  
                        //}
                        
                    }
                    $count_col_spans = $count_col_spans+1;
                }
                $cgpa_calculation = ConfigUtilities::getCgpaCaluclationtransfer1($value['course_batch_mapping_id'],$value['student_map_id'],$value['semester']);
                if($count_col_spans<11)
                {
                    $credits_register_row .= "<td  width='30px' height='25px' colspan=".(11-$count_col_spans)."  >&nbsp;</td>";
                    $credits_earned_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)."   >&nbsp;</td>";
                    $sgpa_row .= "<td width='30px' height='25px' colspan=".(11-$count_col_spans)."   >&nbsp;</td>";
                }
                $credits_register_row .= "</tr>";
                $credits_earned_row .= "</tr>";
                $sgpa_row .= "</tr>";

                $cumulative_row .= "<tr class='cumulative_row' height='25px'>
                <td colspan='3' width='20px' > &nbsp; </td>
                <td class='print_cumulative_credit_points font_bigger' height='25px' valign='top' > <b>" . $cgpa_calculation['transfer_cumulative'] . "</b> </td>
                <td colspan='4' width='20px' > &nbsp; </td>
                <td class='print_cumulative_credit_points_cgpa font_bigger' width='60px'  height='25px'  valign='top' > <b>" . $cgpa_calculation['final_cgpa'] . "</b> </td>
                <td colspan='2' width='20px'> &nbsp; </td>
                </tr>";
                if($total_subs_count>=23)
                {
                     $body .= " 
                    <table border=0  >
                        <tr>
                            <td class='make_bold_font font_bigger' width='600px' colspan='7'  align='center'> ~ CONTINUED IN NEXT PAGE ~ </td>                        
                            <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                        </tr>
                    </table>";

                    $splitted_body = "<tr><td width='100%' valign='top' colspan='11' height='547px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='64px' >&nbsp;</td></tr>";

                    $html = $header . $splitted_body . "</table><pagebreak />";

                    $print_stu_data  .= $html;
                    $total_subs_count = 1; $body = $splitted_body = '';
                }
                $footer .= $credits_register_row . $credits_earned_row . $sgpa_row . $cumulative_row . "<tr><td valign='middle' colspan=11 height=32px >&nbsp;</td></tr>".$date_insert."</table>";

                $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$exam_year.'" AND exam_month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and result like "%pass%"')->queryAll();
                $check_add_print = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE student_map_id="'.$print_student_map_id.'" and result like "%pass%"')->queryAll();
                if(!empty($check_add_print))
                {
                    $is_additional_printed = 1;
                }
                if(!empty($check_add))
                {
                    $body .= "
                    <table border='0' width='100%' >
                        <tr>
                            <td width='40px' align='left'>&nbsp;</td>
                            <td width='67px' align='left'>&nbsp;</td>
                            <td width='450px' class='put_margin' colspan='9'  align='left'><b>ADDITIONAL CREDIT COURSE(S)*</b></td>
                        </tr>
                    </table>";
                    $is_additional_printed = 1;
                    foreach ($check_add as $valuess) 
                    {
                        $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                        $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                        
                        $sub_na = wordwrap(strtoupper($valuess['subject_name']), 50, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = strtoupper(nl2br($sub_na));
                        //$subject_name_print = "<b>".strtoupper($valuess['subject_name'])."</b>";

                        $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);

                        $body .= "
                        <table border='0' width='100%' >
                            <tr>
                                <td width='40px' align='left'>" . $semester_array[$semester_number]. "</td>
                                <td width='67px' align='left'>" . strtoupper($valuess['subject_code']) . "</td>
                                <td width='650px' class='put_margin' colspan='5'  align='left'>" . $subject_name_print . "</td>
                                <td width='52px'  align='left'>" . $valuess['credits'] . "</td>
                                <td width='150px' colspan=3  align='left'> COMPLETED </td>
                            </tr>
                        </table>";
                        $total_subs_count++;
                    }

                        $body .= "
                        <table border=0  >
                            <tr>
                                <td colspan='4'  width='75px' align='left'>&nbsp;</td>
                                <td class='additional_credits' width='450px' class='additional_credits' colspan='3'  align='center'> *ADDITIONAL CREDITS WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                                <td colspan='4' width='170px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                        $body .= " 
                        <table border=0  >
                            <tr>
                                <td class='make_bold_font font_bigger' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                        </table>";
                   
                }
                else
                {
                    
                    $body .= "
                        <table border=0   >
                            <tr>
                                <td  class='make_bold_font font_bigger' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                }
    if($is_additional_printed==1 && !empty($cgpa_calc['normal_additional_credits']) && $cgpa_calc['normal_additional_credits']!=0  )
    {
        
        $merge_body = "<tr><td width='100%' valign='top' colspan='11' height='547px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='64px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$cgpa_calc['normal_additional_credits']."</b></td></tr>";
    }
    else
    {
        $merge_body = "<tr><td width='100%' valign='top' colspan='11' height='547px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='64px' >&nbsp;</td></tr>";
    }
    $html = $header . $merge_body . $footer;
    $print_stu_data .= $html;

    if (isset($_SESSION['mark_statement_pdf']))
    {
        unset($_SESSION['mark_statement_pdf']);
    }
    $_SESSION['mark_statement_pdf'] = $print_stu_data;
    echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-12" >' . $print_stu_data . '</div></div></div></div></div>';
} 
else 
{
    Yii::$app->ShowFlashMessages->setMsg('Error', 'No data Found');
}
?>
