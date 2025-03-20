<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\CoeBatDegReg;
use app\models\StudentMapping;
use app\models\MandatorySubcatSubjects;
use app\models\Degree;
$this->registerCssFile("@web/css/newmarkstatement_ug_2023.css");
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?> 
<?php
if (isset($mark_statement) && !empty($mark_statement)) 
   
{
    //print_r($mark_statement);exit;
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    /*
     *   Already Defined Variables from the above included file
     *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
     *   use these variables for application
     *   use $file_content_available="Yes" for Content Status of the Organisation
     */
    $top_margin = $top_margin!='' && $top_margin!=0 ? $top_margin:'0';
    
    if($bottom_margin!='' && $bottom_margin!=0)
    {
        $bottom_margin = 573+$bottom_margin;        
    }
    else
    {
        $bottom_margin = 573;
    }
    $config_elect = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER); 

    $add_tr_starting = $print_trimester == 1 ? "<tr><td colspan='11' width='30px'>&nbsp;</td></tr> <tr><td class='make_bold_font' id='print_trimester' colspan='11'> TRIMESTER PATTERN </td></td></tr>" : "<tr><td class='make_bold_font' id='no_print_trimester' colspan='11'> &nbsp; </td></td></tr>";
    $supported_extensions = ConfigUtilities::ValidFileExtension();
    $stu_directory = Yii::getAlias("@web") . "/resources/stu_photos/";
    $absolute_dire = Yii::getAlias("@webroot") . "/resources/stu_photos/";
    $total_subs_count = $print_additional_text = 0;
    $html = "";
    $previous_subject_code = $splitted_body = "";
    $previous_reg_number = "";
    $is_additional_printed = 0;
    $header = "";
    $body = "";
    $footer = "";
    $header_1 = "";
    $print_register_number = "";
    $print_student_map_id = "";
    $new_stu_flag = 0;
    $print_stu_data = "";
    $exam_year = ''; $prev_add_pint_code = '';
    $app_month = '';
    $batch_mapping_id = '';
    $first_reg_num = 0;
     $is_arrear_printed = 0;
    $date_print = isset($date_print) ? date('d-m-Y',strtotime($date_print)) : date('d-m-Y');
    $date = "<b>".$date_print."</b>";

   
    $register=$degree_name=$date_style=$middlecontent='';

    if(($mark_statement[0]['programme_code']=='U109' || $mark_statement[0]['programme_code']=='U111'))
    {
        $deg_na = wordwrap(strtoupper(trim($mark_statement[0]['programme_name'])), 50, "\n", true);
        $deg_na = htmlentities($deg_na);
        $deg = "<b>".strtoupper(nl2br($deg_na))."</b>";
        $middlecontent='middlecontent1';

        $date_style='date_style1';
        $degree_name='degree_name1';
        $register='register1';
    }
    else
    {

        $deg=$mark_statement[0]['programme_name'];
        $middlecontent='middlecontent';
        $date_style='date_style';
        $degree_name='degree_name';
        $register='register';
    }

    if($mark_statement[0]['degree_code']=="MBA" || $mark_statement[0]['degree_code']=="mba" || $mark_statement[0]['degree_code']=="Mba" || $mark_statement[0]['degree_code']=="MBABISEM" || $mark_statement[0]['degree_code']=="MBABISEM.")
    {
        $deg =strtoupper($mark_statement[0]['programme_name']);
    }
    else if($mark_statement[0]['degree_code']=='Ph.D')
    {
        $deg =$mark_statement[0]['degree_code']. strtoupper($deg);
    }
    else
    {
        $deg =strtoupper($mark_statement[0]['degree_code']) . ". ". strtoupper($deg);
    }
       
    //print_r($middlecontent);exit;
    $date_insert = "<tr><td colspan='11' height='20px' width='50px'> &nbsp;</td></tr> <tr><td  valign='bottom' class='".$date_style."' colspan='11' width='300px' >".$date."</td></tr> ";
    $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
    echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/mark-statement-print-pdf'], [
        'class' => 'pull-right btn btn-primary',
        'target' => '_blank',
        'data-toggle' => 'tooltip',
        'title' => 'Will open the generated PDF file in a new window'
    ]);
    $exam_month_fin = $_POST['MarkEntry']['month'];
    echo "<br /><br />";
    $open_div = '<div class="row"><div class="col-xs-12"><div class="col-xs-12 col-sm-1 col-lg-1">&nbsp;</div><div class="col-xs-12 col-sm-10 col-lg-10"><div class="col-xs-12 col-sm-1 col-lg-1"></div>';
    $close_div = "<br /><br /></div></div>";

    $deg_type = Yii::$app->db->createCommand("select degree_type from coe_degree as A join coe_bat_deg_reg as B on A.coe_degree_id=B.coe_degree_id where coe_bat_deg_reg_id='" . $_POST['bat_map_val'] . "'")->queryScalar();
    $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII','13'=>'XIII','14'=>'XIV'];

    foreach ($mark_statement as $value) 
    {
        //print_r($mark_statement);exit;
        //$rest = substr($value['subject_code'], 2,-2);
        if(!empty(glob($absolute_dire . $value['register_number'] . ".*")))
        {
            $files = glob($absolute_dire . $value['register_number'] . ".*");
        }
        else if(!empty(glob($absolute_dire . $value['register_number'] . ".*")))
        {
            $files = glob($absolute_dire . strtolower($value['register_number']) . ".*");
        }
        else 
        {
            $files = array_filter(['']);    
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
        $year=$_POST['MarkEntry']['year'];
        $month=$_POST['MarkEntry']['month'];

       
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

        if(!empty(ConfigUtilities::match($supported_extensions, $value['register_number'] . $extension)))
        {
            $photo_extension = ConfigUtilities::match($supported_extensions, $value['register_number'] . $extension);
        }
        else if(!empty(ConfigUtilities::match($supported_extensions, strtolower($value['register_number']) . $extension)))
        {
            $photo_extension = ConfigUtilities::match($supported_extensions, strtolower($value['register_number']) . $extension);
        }
        else
        {
            $photo_extension = '';
        }

        $stu_photo = $photo_extension != "" ? (empty($stu_directory . $value['register_number'] . "." . $photo_extension) ? ($stu_directory . strtolower($value['register_number']) . "." . $photo_extension): ($stu_directory . $value['register_number'] . "." . $photo_extension) ) : $stu_directory . "stu_sample.jpg";
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
                $credits_register_row .= "<tr> <td class='".$register."'  width='40px' valign='middle' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $credits_earned_row .= "<tr><td class='".$register."'  width='40px' valign='middle' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $sgpa_row .= "<tr height='23px'> <td  class='".$register."' height='23px'  width='40px' colspan='" . $colspan_merge . "' > &nbsp; </td>";

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
                                <td width='30px' class='print_credit_points arrear_sem_credits arrear' height='23px' valign='bottom'  > <b>".$total_cre['register']."</b> </td>";
                                $credits_earned_row .= "<td class='print_credit_points arrear_sem_credits arrear' height='23px' valign='bottom' width='30px' > <b>".$print_ear."</b></td>";
                                $sgpa_row .= "<td class='print_credit_points arrear_sem_credits arrear' width='30px' height='23px'  valign='bottom' ><b> ".$print_gpaa."</b> </td>";
                                $sem_printed_val = $printed_sem = $total_cre['sem'];
                                $sem_printed_val++;
                                $print_td++;
                                break;
                            }
                            else
                            {
                                $credits_register_row .= "<td width='30px'  class='".$register."' height='23px' valign='bottom' align='left' >&nbsp;</td>";
                                $credits_earned_row .= "<td width='30px' height='23px'  class='".$register."' valign='bottom' align='left' >&nbsp;</td>";
                                $sgpa_row .= "<td width='30px' height='23px' class='".$register."'  valign='bottom' align='left' >&nbsp;</td>";
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
                        $align_text = $semester_number==8 ||  $semester_number==7 || $semester_number==6 ? 'sem_8':'';
                        $merge_col =  'colspan='.$td_printe; 
                        $increment = 0;
                            $credits_register_row .= "<td class='print_credit_points' valign='bottom' width='60px'   ".$merge_col." > <b>" . $registered_p . "</b> </td>";
                            $credits_earned_row .= "<td class='print_credit_points' valign='bottom' width='60px' height='23px'  ".$merge_col." > <b>" . $sem_credits_earned_p . "</b> </td>";
                            $sgpa_row .= "<td width='60px' class='print_credit_points' valign='bottom' height='23px'  ".$merge_col."  > <b>" . $sgpa_row_p . "</b> </td>";
                    }
                    else 
                    { 
                        $credits_register_row .= "<td  class='".$register."' width='60px' height='23px' >&nbsp;</td>";
                        $credits_earned_row .= "<td  class='".$register."'  width='60px' height='23px' >&nbsp;</td>";
                        $sgpa_row .= "<td width='60px' class='".$register."'  height='23px'  >&nbsp;</td>";  
                    }
                    $count_col_spans = $count_col_spans+1;
                }
                if($count_col_spans<11)
                {
                    $credits_register_row .= "<td  width='30px' height='23px' class='".$register."'  colspan=".(11-$count_col_spans)."  >&nbsp;</td>";
                    $credits_earned_row .= "<td  class='".$register."' width='30px' height='23px' colspan=".(11-$count_col_spans)."   >&nbsp;</td>";
                    $sgpa_row .= "<td width='30px' class='".$register."'  height='23px' colspan=".(11-$count_col_spans)."   >&nbsp;</td>";
                }
                $credits_register_row .= "</tr>";
                $credits_earned_row .= "</tr>";
                $sgpa_row .= "</tr>";
                if($total_subs_count>=$count_of_subs)
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
                $cumulative_row .= "<tr class='cumulative_row' height='23px'>
                <td colspan='3' width='20px' > &nbsp; </td>
                <td class='print_cumulative_credit_points' height='23px' valign='top'> <b>" . $cgpa_calc['cumulative_earned_credits'] . "</b> </td>
                <td colspan='4' width='20px' > &nbsp; </td>
                <td class='print_cumulative_credit_points_cgpa' width='60px'  height='23px' valign='top'> <b>" . $cgpa_calc['cgpa_result_sem'] . "</b> </td>
                <td colspan='2' width='20px'> &nbsp; </td>
                </tr>";
                
                $footer .= $credits_register_row . $credits_earned_row . $sgpa_row . $cumulative_row . "<tr><td valign='middle' colspan=11 height=32px >&nbsp;</td></tr>".$date_insert."</table>";

                $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$exam_year.'" AND exam_month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and result like "%pass%"')->queryAll();






                if($year==2020 && $month==29)
                {
              
              $check_arrear = Yii::$app->db->createCommand('SELECT * FROM coe_mark_entry_master  WHERE year=2020 AND month=29 and student_map_id="'.$print_student_map_id.'" and mark_type="28"')->queryAll();
             // print_r($check_arrear);exit;

                }
                else{

                    
                }

                 

                 $check_arrear = Yii::$app->db->createCommand('SELECT * FROM coe_mark_entry_master  WHERE year="'.$exam_year.'" AND month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and mark_type="28"')->queryAll();

                $check_arrear_subject = Yii::$app->db->createCommand('SELECT subject_code FROM  coe_subjects as A join coe_subjects_mapping as B  on B.subject_id=A.coe_subjects_id join coe_mark_entry_master as C on C.subject_map_id=B.coe_subjects_mapping_id  WHERE year="'.$exam_year.'" AND month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and mark_type="28"')->queryAll();



                if(!empty($check_arrear) && $year=2020 && $month=29)
                {
                    $is_arrear_printed = 1;
                }
                else
                {

 
                    
                }
               
                if(!empty($check_add))
                {
                    foreach ($check_add as $valuess) 
                    {
                        $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                        $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                        
                  
                        $sub_na = wordwrap(strtoupper(trim($valuess['subject_name'])), 60, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";

                        $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);

                        
                        

                        $body .= "
                        <table border='0' width='100%' >
                            <tr>
                                <td valign='top' width='40px' align='left'><b>" . $semester_array[$semester_number]. "</b></td>
                                <td valign='top' width='67px' align='left' class='subjectcode'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                                <td valign='top' width='450px' class='subjectname' colspan='5'  align='left'><b>" . trim($subject_name_print) . "</td>
                                <td valign='top'  class='credits' width='52px'  align='left'><b>" . $valuess['credits'] . "</b></td>
                                <td valign='top' width='45px' class='gradename'  align='left'><b> " . strtoupper($valuess['grade_name']) . "</b> </td>
                                <td valign='top' width='55px'  align='center'><b>" . $grade_point_print . "</b></td>
                                <td  valign='top' style='padding-left: -15px;'  width='50px'  align='center'><b>" . $result_stu . "</b></td>
                            </tr>
                        </table>";
                    }

                        $body .= "
                        <table border=0  >
                            <tr>
                                <td colspan='4'  width='75px' align='left'>&nbsp;</td>
                                 <td  valign='top' class='additional_credits put_margin' width='450px'  colspan='3'  align='center'> # ADDITIONAL CREDITS EARNED WILL NOT TO BE CONSIDERED FOR GPA/CGPA CALCULATION</td>
                                <td valign='top' colspan='4' width='170px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                        $body .= " 
                        <table border=0  >
                            <tr>
                                <td valign='top' class='make_bold_font' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                
                                <td valign='top' colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                        </table>";
                     /*if(!empty($check_arrear) && $value['batch_name']!=2017  && $value['batch_name']!=2018 && $value['batch_name']!=2019 and $value['degree_type']="UG" && $year=2020 && $month=29 )
                         {

                        $body .= " 
                        <table border=0  >
                            <tr>
                               <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' > Due to COVID - 19 Pandemic, April/May 2020 Arrear Examinations were conducted during Nov/Dec 2020 as per Anna University guidelines.</td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";

                         }
                         else if(!empty($check_arrear))
                         {
                             $body .= " 
                         <table border=0  >
                            <tr>
                                <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' >  Due to COVID - 19 Pandemic, April/May 2020 Regular/Arrear Examinations were processed/conducted during Nov/Dec 2020 as per Anna University guidelines. </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";


                         }
                         else{


                         }*/
                    
                       

                   
                }
                else if($is_additional_printed==1 && $print_additional_text==0)
                {
                     $body .= "<table border=0  >
                        <tr>
                            <td colspan='4'  width='75px' align='left'>&nbsp;</td>
                            <td  valign='top' class='additional_credits put_margin' width='450px'  colspan='3'  align='center'> # ADDITIONAL CREDITS EARNED WILL NOT TO BE CONSIDERED FOR GPA/CGPA CALCULATION</td>
                            <td valign='top' colspan='4' width='170px'  align='right'>&nbsp;</td>
                        </tr>
                    </table>";
                    $body .= " 
                    <table border=0  >
                        <tr>
                            <td valign='top' class='make_bold_font' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                            
                            <td valign='top' colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                        </tr>
                    </table>";
             /*if(!empty($check_arrear) && $value['batch_name']!=2017  && $value['batch_name']!=2018 && $value['batch_name']!=2019 and $value['degree_type']="UG" &&  $year=2020 && $month=29   )
                         {

                        $body .= " 
                        <table border=0  >
                            <tr>
                               <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' > Due to COVID - 19 Pandemic, April/May 2020 Arrear Examinations were conducted during Nov/Dec 2020 as per Anna University guidelines.</td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";

                         }
                         else if(!empty($check_arrear))
                         {
                             $body .= " 
                         <table border=0  >
                            <tr>
                                <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' >  Due to COVID - 19 Pandemic, April/May 2020 Regular/Arrear Examinations were processed/conducted during Nov/Dec 2020 as per Anna University guidelines. </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";


                         }
                         else{


                         }*/
                    
                }
                else
                {
                    
                    $body .= "
                        <table border=0   >
                            <tr>
                                <td valign='top' class='make_bold_font'  width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                
                                <td valign='top' colspan='4' width='170px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                     /*if(!empty($check_arrear) && $value['batch_name']!=2017  && $value['batch_name']!=2018 && $value['batch_name']!=2019 and $value['degree_type']="UG" && $year=2020 && $month=29   )
                         {

                        $body .= " 
                        <table border=0  >
                            <tr>
                               <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' > Due to COVID - 19 Pandemic, April/May 2020 Arrear Examinations were conducted during Nov/Dec 2020 as per Anna University guidelines.</td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";

                         }
                         else if(!empty($check_arrear))
                         {
                             $body .= " 
                         <table border=0  >
                            <tr>
                                <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' >  Due to COVID - 19 Pandemic, April/May 2020 Regular/Arrear Examinations were processed/conducted during Nov/Dec 2020 as per Anna University guidelines. </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";


                         }
                         else{

                            
                         }*/
                    
                         

                }
                $checkWaiverGradne = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$print_student_map_id.'"';
                    $getWaiverStatusGrande = Yii::$app->db->createCommand($checkWaiverGradne)->queryAll();
                $checkIsHaveingAdditional = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks as A JOIN coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id where result like "%Pass%" and student_map_id="'.$print_student_map_id.'" and year<="'.$exam_year.'" and is_additional="YES" ')->queryAll();
                $check_add_attemot = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks as A JOIN coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id where result like "%Pass%" and student_map_id="'.$print_student_map_id.'"   and year<="'.$exam_year.'"  and result like "%Pass%" and is_additional="YES" ')->queryAll();
                $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$print_student_map_id.'" and year="'.$exam_year.'" and month="'.$add_month.'"';
                $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();
               
                $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='64px' >&nbsp;</td></tr>";
                if(!empty($getWaiverStatus) && count($check_add_attemot)>=$config_elect)
                {
                    $esrned_credits = $cgpa_calc['cumulative_additional_credits']-$config_elect;
                    if(!empty($cgpa_calc['cumulative_additional_credits']) && $cgpa_calc['cumulative_additional_credits']!=0)
                    {
                        $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='30px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$esrned_credits."</b></td></tr>";
                    }
                }
                else if($is_additional_printed==1 && $print_additional_text==0 )
                {
                    if(!empty($cgpa_calc['cumulative_additional_credits']) && $cgpa_calc['cumulative_additional_credits']!=0)
                    {
                            $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='30px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$cgpa_calc['cumulative_additional_credits']."</b></td></tr>";
                    }
                }
                else if(!empty($checkIsHaveingAdditional) && count($check_add_attemot)<$config_elect  && empty($getWaiverStatusGrande))
                {
                    if(!empty($cgpa_calc['cumulative_additional_credits']) && $cgpa_calc['cumulative_additional_credits']!=0)
                    {
                        $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='30px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$cgpa_calc['cumulative_additional_credits']."</b></td></tr>";
                    }
                }
                else if(!empty($check_add_attemot) && empty($getWaiverStatusGrande))
                {
                    if(!empty($cgpa_calc['cumulative_additional_credits']) && $cgpa_calc['cumulative_additional_credits']!=0)
                    {
                        $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='30px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$cgpa_calc['cumulative_additional_credits']."</b></td></tr>";
                    }
                }
                $html = $header . $merge_body . $footer."<pagebreak />";
                $print_stu_data .= $html;
                $header = "";
                $body = "";
                $footer = ""; $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
                $print_total_credits = array_filter(array(''=>''));
                $new_stu_flag = 1;
                $is_additional_printed=0; $total_subs_count=0;
            }
            
            $header .= "<table width='100%' autosize='1' style='padding-top: ".$top_margin."px;'  >";
            $header .= '<tr>
                           <td height="113px" class="imageclass" style="border: none; text-align: right;" colspan="11" >
                                <img class="img-responsive" width="80" height="80" src="' . $stu_photo . '" alt="' . $stu_photo . '" Photo >
                                
                            </td>

                        </tr>'.$add_tr_starting;
            $umis=''; 
            $namepadding='padding-top:-2px;';
            if($with_umis=='on')
            {
                $header .='<tr>
                                            
                        <td  class="num" style="border: none;text-align: right; padding-right: 18px; " colspan="11"><b>UMIS NO:'.($value["UMISnumber"]).'</b></td>
                        </tr>';
                $namepadding='padding-top:-2px;';
                        
                
            }

              
            $header .= "
                    <tr>
                        <td colspan='11' width='100%'  >
                            <table class='font_bigger' width='100%' align='left' style='border: none !important; ".$namepadding."'  border='0'>
                                <tr  style='padding: 10px;'>
                                    <td class='line_height' colspan='4' width='75px;' >&nbsp;</td>
                                    <td class='line_height' colspan='6' width='230px;'  > <b>" . strtoupper($value['name']) . "</b> </td>
                                    <td class='line_height' width='100px;'  ><b>" . strtoupper($month_disp)  . "  " . $value['year'] . " </b> </td>
                                </tr>
                            
                                <tr style='padding: 10px;'>
                                    <td class='line_height pull_down' colspan='4' width='75px;' >&nbsp;</td>
                                    <td class='line_height pull_down' colspan='5' width='230px;'  > <b>" . strtoupper($value['register_number']) . "</b></td>
                                    <td class='line_height pull_down   ' ><b>" . $dob . "</b></td>
                                    <td class='line_height pull_down'  width='100px;' ><b>" . strtoupper($print_gender) . "</b></td>
                                </tr>
                            
                                <tr  style='padding: 10px;'>
                                    <td class='".$degree_name."' colspan='4' width='75px;' >&nbsp;</td>
                                    <td class='".$degree_name."' colspan='6' width='230px;'  style='font-weight:bold;'> <b>" . $deg . "</b></td>
                                    <td class='".$degree_name."'  width='100px;' ><b>" . $value['regulation_year'] . "</b></td>
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

                $sub_na = wordwrap(strtoupper(trim($value['subject_name'])),60, "\n", true);
                  $sub_na = htmlentities($sub_na);
                 $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";

                $result_stu = $value['result'] == "Pass" || $value['result'] == "PASS" || $value['result'] == "pass"? "COMPLETED" : "NOT COMPLETED";
                 
                $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td width='40px' class='sem' align='left' >" . $semester_array[$value['semester']]. "</td>
                            <td width='67px'  align='left' class='subjectcode'>" . $value['subject_code'] . "</td>
                            <td width='450px' class='subjectname' colspan='5'  align='left'>" . $subject_name_print . "</td>
                            <td colspan='4' style='padding-left: -10px;'  width='205px'  align='center'> ".$result_stu." </td>
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


             /*  if($value['semester']!=$semester_number)
                //if(!empty($check_arrear_subject)&&$value['se'])
                {
                    $result_stu=$result_stu.'<sup>*</sup>';


                }
                else{

                     $result_stu=$result_stu;
                }*/

                 $sub_na = wordwrap(strtoupper(trim($value['subject_name'])), 60, "\n", true);
                  $sub_na = htmlentities($sub_na);
                 $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";

                $grade_name = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? 'AB' : $value['grade_name'];

             //   $grade_name = ($value['grade_name'] == "WH" || $value['grade_name'] == "wh") ? 'RA' : $grade_name;
                $grade_name = ($value['grade_name'] == "WD" || $value['grade_name'] == "wd") ? 'W' : $grade_name;

               // $grade_name = $value['withheld'] == "w" || $value['withheld'] == "W" ? "RA" : $grade_name;
                if($value['batch_name']<2021 && $value['withheld'] == "w" || $value['withheld'] == "W" && $value['grade_name'] == "WH" || $value['grade_name'] == "wh")
                {

                     $grade_name="RA";

                }
                else if($value['batch_name']>=2021 && $value['withheld'] == "w" || $value['withheld'] == "W" && $value['grade_name'] == "WH" || $value['grade_name'] == "wh")
                {
                          $grade_name="U";
                }
                else
                {
                     $grade_name;

                }

                $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
                $grade_point_print = $grade_name=='RA'?"-":$grade_point_print;
                $grade_point_print = $value['withheld'] == "w" || $value['withheld'] == "W" ? "-":$grade_point_print;

                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];
                if(isset($value['is_additional']))
                {
                    $check_add_pass = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks as A JOIN coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id where result like "%Pass%" and student_map_id="'.$value['student_map_id'].'" and subject_map_id="'.$value['subject_map_id'].'" and result like "%Pass%" and is_additional="YES" ')->queryAll();
                    $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$value['student_map_id'].'" and year="'.$exam_year.'" and month="'.$exam_month_fin.'"';
                    $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();
                    $config_elect = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER); 
                        $check_add_attemot = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks as A JOIN coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id where result like "%Pass%" and student_map_id="'.$value['student_map_id'].'" and result like "%Pass%"   and year<="'.$exam_year.'" and is_additional="YES" ')->queryAll();
                    

                    if(count($check_add_attemot) >=$config_elect && !empty($getWaiverStatus))
                    {
                        $print_additional_text = 1;
                    }
                    else if(empty($getWaiverStatus) && count($check_add_pass)>0 && !empty($check_add_pass))
                    {
                        $print_additional_text = 0;
                    }

                    if($is_additional_printed==0 && $value['is_additional']=='YES' && count($check_add_pass)>0 && !empty($check_add_pass) && count($check_add_attemot) < $config_elect)
                    {
                        $body .= "
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>    
                                <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                <td valign='top' width='450px' class='put_margin' colspan='5'  align='left'><b> ADDITIONAL CREDITS EARNED #</b></td>
                                <td valign='top' width='52px'  align='left'>&nbsp;</td>
                                <td valign='top' width='45px'  align='left'> &nbsp;</td>
                                <td valign='top' width='55px'  align='center'>&nbsp;</td>
                                <td valign='top'  style='padding-left: 10px;' width='50px'  align='center'><b>&nbsp;</b></td>
                            </tr>
                        </table>";
                        $is_additional_printed=1;
                        
                    }
                    else if($is_additional_printed==0 && $value['is_additional']=='YES' && count($check_add_pass)>0 && !empty($check_add_pass) && empty($getWaiverStatus))
                    {
                        $body .= "
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>    
                                <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                <td valign='top' width='450px' class='put_margin' colspan='5'  align='left'><b> ADDITIONAL CREDITS EARNED #</b></td>
                                <td valign='top' width='52px'  align='left'>&nbsp;</td>
                                <td valign='top' width='45px'  align='left'> &nbsp;</td>
                                <td valign='top' width='55px'  align='center'>&nbsp;</td>
                                <td valign='top'  style='padding-left: 10px;' width='50px'  align='center'><b>&nbsp;</b></td>
                            </tr>
                        </table>";
                        $is_additional_printed=1;
                        
                    }

                    if($value['is_additional']=='YES' )
                    {
                        if(count($check_add_pass)>0 && !empty($check_add_pass))
                        {
                            if($prev_add_pint_code==$value['subject_code'])
                            {
                                $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                               
                                   $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 45, "\n", true);
                                $sub_na = htmlentities($sub_na);
                                $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";

                                 $body .= "
                                <table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>    
                                        <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                        <td valign='top' width='450px' colspan='5'  align='left' class='subjectcode'><b> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . " </b></td>
                                        <td valign='top'  class='credits' width='52px'  align='left'><b>" . $value['credit_points'] . "</b></td>
                                        <td valign='top' class='gradename'
                                        width='45px'  align='left'> <b>" . strtoupper($grade_name) . " </b></td>
                                        <td valign='top'   class='gradepoint' width='55px'  align='center'><b>" . $grade_point_print . "</b></td>
                                        <td valign='top'  style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td></tr></table>";
                            }
                            else
                            {
                                $body .= "
                                <table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top' width='40px' align='left' class='sem'><b>" . $semester_array[$value['semester']]. "</b></td>
                                        <td valign='top' width='67px' align='left' class='subjectcode' ><b>" . strtoupper($value['subject_code']) . "</b></td>
                                        <td valign='top' width='450px' colspan='5'  align='left' class='subjectname'>" . $subject_name_print . "</b></td>
                                        <td valign='top' width='52px' colspan=4  align='left'>&nbsp;</td>
                                       </tr></table>";  

                                $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 45, "\n", true);
                            $sub_na = htmlentities($sub_na);
                            $subject_name_print = "<b>".nl2br($sub_na)."</b>";

                                 $body .= "
                                <table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>    
                                        <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                        <td valign='top' width='450px' colspan='5'  align='left' class='subjectcode'><b> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . " </b></td>
                                        <td valign='top' class='credits' width='52px'  align='left'><b>" . $value['credit_points'] . "</b></td>
                                        <td valign='top' class='gradename' width='45px'  align='left'> <b>" . strtoupper($grade_name) . " </b></td>
                                        <td valign='top' width='55px'  align='center' class='gradepoint' ><b>" . $grade_point_print . "</b></td>
                                        <td valign='top'  style='padding-left: -15px;' width='50px'  align='center' style='padding-left: -5px;' ><b>" . $result_stu . "</b></td></tr></table>";
                            }
                            $prev_add_pint_code=$value['subject_code'];
                            $total_subs_count++;
                        }
                    }
                    else
                    {
                        if($prev_add_pint_code==$value['subject_code'])
                        {
                            $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                            $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 45, "\n", true);
                            $sub_na = htmlentities($sub_na);
                            $subject_name_print = "<b>".nl2br($sub_na)."</b>";

              /* if($value['semester']!=$semester_number)
                //if(!empty($check_arrear_subject)&&$value['se'])
                {
                    $result_stu=$result_stu.'<sup>*</sup>';


                }
                else{

                     $result_stu=$result_stu;
                }*/
                             
                             $body .= "
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>    
                                    <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                    <td valign='top' width='450px' colspan='5'  class='subjectcode' align='left' ><b> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</b></td>
                                    <td valign='top' width='52px'  align='left' class='credits'><b>" . $value['credit_points'] . "</b></td>
                                    <td valign='top' width='45px'  align='left' class='gradename' > <b>" . strtoupper($grade_name) . " </b></td>
                                    <td valign='top' width='55px' class='gradepoint'   align='center'><b>" . $grade_point_print . "</b></td>
                                    <td valign='top'  style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td></tr></table>";
                        }
                        else
                        {
                            $body .= "
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='40px' align='left' class='sem'><b>" . $semester_array[$value['semester']]. "</b></td>
                                    <td valign='top' width='67px' align='left' class='subjectcode'><b>" . strtoupper($value['subject_code']) . "</b></td>
                                    <td valign='top' width='450px' colspan='5'  align='left' class='subjectname'>" . $subject_name_print . "</b></td>
                                    <td valign='top' width='52px' colspan=4  align='left'>&nbsp;</td>
                                   </tr></table>";  

                           $sub_na = wordwrap(strtoupper(trim($get_man_det['sub_cat_name'])), 45, "\n", true);
                  $sub_na = htmlentities($sub_na);
                  $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";

              /* if($value['semester']!=$semester_number)
                //if(!empty($check_arrear_subject)&&$value['se'])
                {
                    $result_stu=$result_stu.'<sup>*</sup>';


                }
                else{

                     $result_stu=$result_stu;
                }*/
                             $body .= "
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>    
                                    <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                    <td valign='top' width='450px' colspan='5'  align='left' class='subjectcode'><b> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</b></td>
                                    <td valign='top' class='credits' width='52px'  align='left'><b>" . $value['credit_points'] . "</b></td>
                                    <td valign='top' width='45px'  align='left' class='gradename'> <b>" . strtoupper($grade_name) . " </b></td>
                                    <td valign='top' width='55px'  align='center' class='gradepoint' ><b>" . $grade_point_print . "</b></td>
                                    <td valign='top'  style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td></tr></table>";
                        }
                        $prev_add_pint_code=$value['subject_code'];
                        $total_subs_count++;
                    }
                      
                }
                else
                {
                   
                    $with_add = $value['subject_code']=='15PMA103'?'30px':'67px';
                    $change_class = $value['subject_code']=='15PMA103'?'change_margin_ma10':'put_margin';
                    $subject_name_print = ltrim($subject_name_print);

              /* if($value['semester']!=$semester_number)
                //if(!empty($check_arrear_subject)&&$value['se'])
                {
                    $result_stu=$result_stu.'<sup>*</sup>';


                }
                else{

                     $result_stu=$result_stu;
                }*/
                    
                     
                    $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top' width='40px' align='left' class='sem'><b>" . $semester_array[$value['semester']]. "</b></td>
                            <td valign='top' width='".$with_add."'  align='left' class='subjectcode'><b>" . strtoupper($value['subject_code']) . "</b></td>
                            <td valign='top' width='450px' class='".$change_class."'   colspan='5'  align='left' class='subjectname'>" . $subject_name_print . "</td>
                            <td valign='top'  class='credits' width='52px'  align='left'><b>" . $value['credit_points'] . "</b></td>
                            <td valign='top' class='gradename'  width='45px'  align='left'><b> " . strtoupper($grade_name) . " </b></td>
                            <td valign='top' width='55px'  class='gradepoint'  align='center'><b>" . $grade_point_print . "</b></td>
                            <td valign='top'  style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td></tr></table>";  
                    $total_subs_count++;
                }
            } // If subject not contains the ESE_max==0  Condition
            $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id'],$semester_number);
            $prev_add_pint_code = '';
        } // If not the same registration number
        else 
        {
            if($total_subs_count>=$count_of_subs)
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

           $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id'],$semester_number);
            if ($value['CIA_max'] == 0 && $value['ESE_max'] == 0) 
            {
               
                $sub_na = wordwrap(strtoupper(trim($value['subject_name'])), 45, "\n", true);
                  $sub_na = htmlentities($sub_na);
                 $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";

                //$subject_name_print = "<b>".strtoupper($value['subject_name'])."</b>";
                $result_stu = $value['result'] == "Pass" || $value['result'] == "PASS" || $value['result'] == "pass"? "COMPLETED" : "NOT COMPLETED";
                $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top' width='40px' align='left' class='sem' ><b>" . $semester_array[$value['semester']]. "</b></td>
                            <td valign='top' width='67px'  align='left' class='subjectcode'><b>" . $value['subject_code'] . "</b></td>
                            <td valign='top' width='450px' colspan='5'  align='left' class='subjectname'>" . $subject_name_print . "</td>
                            <td valign='top' style='padding-left: -10px;'  colspan='4' width='205px'  align='center'><b> ".$result_stu."</b> </td>
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

               /*if($value['semester']!=$semester_number)
                //if(!empty($check_arrear_subject)&&$value['se'])
                {
                    $result_stu=$result_stu.'<sup>*</sup>';


                }
                else{

                     $result_stu=$result_stu;
                }*/
               


                 $sub_na = wordwrap(strtoupper(trim($value['subject_name'])), 45, "\n", true);
                  $sub_na = htmlentities($sub_na);
                 $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                //$subject_name_print = "<b>".strtoupper($value['subject_name'])."</b>";

                $grade_name = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? 'AB' : $value['grade_name'];

              //  $grade_name = $value['grade_name'] == "WH" || $value['grade_name'] == "wh" ? 'RA' : $value['grade_name'];
                $grade_name = $value['grade_name'] == "WD" || $value['grade_name'] == "wd" ? 'W' : $value['grade_name'];

                //$grade_name = $value['withheld'] == "w" || $value['withheld'] == "W" ? "RA" : $grade_name;

                if($value['batch_name']<2021 && $value['withheld'] == "w" || $value['withheld'] == "W" && $value['grade_name'] == "WH" || $value['grade_name'] == "wh")
                {

                     $grade_name="RA";

                }
                else if($value['batch_name']>=2021 && $value['withheld'] == "w" || $value['withheld'] == "W" && $value['grade_name'] == "WH" || $value['grade_name'] == "wh")
                {
                          $grade_name="U";
                }
                else
                {
                     $grade_name;

                }

                $grade_point_print = $value['grade_point']==0?"-":$value['grade_point'];
                $grade_point_print = $grade_name=='RA'?"-":$grade_point_print;

                $grade_point_print = $value['withheld'] == "w" || $value['withheld'] == "W" ?"-":$grade_point_print;

                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu];

                if(isset($value['is_additional']))
                {
                    $check_add_pass = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks as A JOIN coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id where result like "%Pass%" and student_map_id="'.$value['student_map_id'].'" and subject_map_id="'.$value['subject_map_id'].'" and result like "%Pass%" and is_additional="YES" ')->queryAll();


                    $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$value['student_map_id'].'" and year="'.$exam_year.'" and month="'.$exam_month_fin.'"';
                    $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();

                    $config_elect = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER); 
                    $check_add_attemot = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks as A JOIN coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id where result like "%Pass%" and student_map_id="'.$value['student_map_id'].'"  and year<="'.$exam_year.'"  and result like "%Pass%" and is_additional="YES" ')->queryAll();
                    //print_r($check_add_attemot);exit;
                    if(count($check_add_attemot) >=$config_elect  && !empty($getWaiverStatus))
                    {
                        $print_additional_text = 1;
                    }
                    else if(empty($getWaiverStatus) && count($check_add_pass)>0 && !empty($check_add_pass))
                    {
                        $print_additional_text = 0;
                    }

                    if($is_additional_printed==0 && $value['is_additional']=='YES' && count($check_add_pass)>0 && !empty($check_add_pass) && count($check_add_attemot) < $config_elect)
                    {
                        $body .= "<table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>    
                                <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                <td valign='top' width='450px' class='put_margin' colspan='5'  align='left'><b> ADDITIONAL CREDITS EARNED #</b></td>
                                <td valign='top' width='52px'  align='left'>&nbsp;</td>
                                <td valign='top' width='45px'  align='left'> &nbsp;</td>
                                <td valign='top' width='55px'  align='center'>&nbsp;</td>
                                <td valign='top'  style='padding-left: 10px;' width='50px'  align='center'><b>&nbsp;</b></td>
                            </tr>
                        </table>";
                        $is_additional_printed=1;                        
                    }
                    else if($is_additional_printed==0 && $value['is_additional']=='YES' && count($check_add_pass)>0 && !empty($check_add_pass) && empty($getWaiverStatus))
                    {
                        $body .= "
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>    
                                <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                <td valign='top' width='450px' class='put_margin' colspan='5'  align='left'><b> ADDITIONAL CREDITS EARNED #</b></td>
                                <td valign='top' width='52px'  align='left'>&nbsp;</td>
                                <td valign='top' width='45px'  align='left'> &nbsp;</td>
                                <td valign='top' width='55px'  align='center'>&nbsp;</td>
                                <td valign='top'  style='padding-left: 10px;' width='50px'  align='center'><b>&nbsp;</b></td>
                            </tr>
                        </table>";
                        $is_additional_printed=1;
                        
                    }

                    if($value['is_additional']=='YES')
                    {
                        if(count($check_add_pass)>0 && !empty($check_add_pass))
                        {
                            if($prev_add_pint_code==$value['subject_code'])
                            {
                                $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                              
                                  $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 55, "\n", true);
                                  $sub_na = htmlentities($sub_na);
                                 $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";

              
                                 $body .= "
                                <table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>    
                                        <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                        <td valign='top' width='450px' class='put_margin' colspan='5'  align='left'><b> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</b></td>
                                        <td valign='top'  class='credits' width='52px'  align='left'><b>" . $value['credit_points'] . "</b></td>
                                        <td valign='top'  class='gradename' width='45px'  align='left'> <b>" . strtoupper($grade_name) . " </b></td>
                                        <td valign='top' width='55px'  align='center' class='gradepoint' ><b>" . $grade_point_print . "</b></td>
                                        <td valign='top'  style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td></tr></table>";
                            }
                            else
                            {
                                $body .= "
                                <table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top' width='40px' align='left' class='sem'><b>" . $semester_array[$value['semester']]. "</b></td>
                                        <td valign='top' width='67px' align='left' class='subjectcode'><b>" . strtoupper($value['subject_code']) . "</b></td>
                                        <td valign='top' width='450px' colspan='5'  align='left' class='subjectname'>" . $subject_name_print . "</b></td>
                                        <td valign='top' width='52px' colspan=4  align='left'>&nbsp;</td>
                                       </tr></table>";  

                                $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                                $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 45, "\n", true);
                                $sub_na = htmlentities($sub_na);
                                $subject_name_print = "<b>".nl2br($sub_na)."</b>";

              /* if($value['semester']!=$semester_number)
                //if(!empty($check_arrear_subject)&&$value['se'])
                {
                    $result_stu=$result_stu.'<sup>*</sup>';


                }
                else{

                     $result_stu=$result_stu;
                }*/
                                 $body .= "
                                <table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>    
                                        <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                        <td valign='top' width='450px' colspan='5'  align='left' class='subjectname'><b> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</b></td>
                                        <td valign='top'  class='credits' width='52px'  align='left'><b>" . $value['credit_points'] . "</b></td>
                                        <td valign='top' width='45px'  align='left' class='gradename'> <b>" . strtoupper($grade_name) . " </b></td>
                                        <td valign='top' width='55px'  align='center' class='gradepoint'><b>" . $grade_point_print . "</b></td>
                                        <td valign='top'  style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td></tr></table>";
                            }
                            $prev_add_pint_code=$value['subject_code'];    
                            $total_subs_count++;
                        }
                        
                    }
                    else
                    {
                        if($prev_add_pint_code==$value['subject_code'])
                        {
                            $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                            $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 45, "\n", true);
                            $sub_na = htmlentities($sub_na);
                            $subject_name_print = "<b>".nl2br($sub_na)."</b>";
                             $body .= "
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>    
                                    <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                    <td valign='top' width='450px' colspan='5'  align='left' class='subjectname'><b> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</b></td>
                                    <td valign='top' width='52px' class='credits'  align='left'><b>" . $value['credit_points'] . "</b></td>
                                    <td valign='top' width='45px'  class='gradename'  align='left'> <b>" . strtoupper($grade_name) . " </b></td>
                                    <td valign='top' class='gradepoint' width='55px'  align='center'><b>" . $grade_point_print . "</b></td>
                                    <td valign='top'  style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td></tr></table>";
                        }
                        else
                        {
                            $body .= "
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='40px' align='left' class='sem'><b>" . $semester_array[$value['semester']]. "</b></td>
                                    <td valign='top' width='67px' align='left' class='subjectcode'><b>" . strtoupper($value['subject_code']) . "</b></td>
                                    <td valign='top' width='450px' colspan='5'  align='left' class='subjectname'>" . $subject_name_print . "</b></td>
                                    <td valign='top' width='52px' colspan=4  align='left'>&nbsp;</td>
                                   </tr></table>";  

                            $get_man_det = MandatorySubcatSubjects::findOne($value['subject_map_id']);
                            $sub_na = wordwrap(strtoupper($get_man_det['sub_cat_name']), 45, "\n", true);

                            $sub_na = htmlentities($sub_na);
                            $subject_name_print = "<b>".nl2br($sub_na)."</b>";

               /*if($value['semester']!=$semester_number)
                //if(!empty($check_arrear_subject)&&$value['se'])
                {
                    $result_stu=$result_stu.'<sup>*</sup>';


                }
                else{

                     $result_stu=$result_stu;
                }*/
                             $body .= "
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>    
                                    <td valign='top' colspan=2  width='108px'  align='right'>&nbsp;</td>                        
                                    <td valign='top' width='450px' colspan='5'  align='left' class='subjectname'> <b> " .strtoupper($get_man_det['sub_cat_code'])." - ". $subject_name_print . "</b></td>
                                    <td valign='top'class='credits' width='52px'  align='left'><b>" . $value['credit_points'] . "</b></td>
                                    <td valign='top' width='45px'  align='left' class='gradename' > <b>" . strtoupper($grade_name) . " </b></td>
                                    <td valign='top' width='55px'  align='center' class='gradepoint'><b>" . $grade_point_print . "</b></td>
                                    <td valign='top'  style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td></tr></table>";
                        }
                        $prev_add_pint_code=$value['subject_code'];
                        $total_subs_count++;
                    }

                    
                }
                else
                {

                /*if($value['semester']!=$semester_number)
                //if(!empty($check_arrear_subject)&&$value['se'])
                {
                    $result_stu=$result_stu.'<sup>*</sup>';


                }
                else{

                     $result_stu=$result_stu;
                }*/

                    $subject_name_print = ltrim($subject_name_print);
                    $body .= "
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top' width='40px' align='left' class='sem'><b>" . $semester_array[$value['semester']]. "</b></td>
                            <td valign='top' width='67px' align='left' class='subjectcode'><b>" . strtoupper($value['subject_code']) . "</b></td>
                            <td valign='top' width='450px' colspan='5'  align='left' class='subjectname'>" . $subject_name_print . "</td>
                            <td valign='top'  class='credits' width='52px'  align='left'><b>" . $value['credit_points'] . "</b></td>
                            <td valign='top' width='45px'  align='left' class='gradename'> <b>" . strtoupper($grade_name) . " </b></td>
                            <td valign='top' width='55px'  align='center' class='gradepoint'><b>" . $grade_point_print . "</b></td>
                            <td valign='top' style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td></tr></table>";  
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
                $credits_register_row .= "<tr> <td  class='".$register."' width='40px' valign='middle' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $credits_earned_row .= "<tr><td class='".$register."'  width='40px' valign='middle' colspan='" . $colspan_merge . "' > &nbsp; </td>";
                $sgpa_row .= "<tr height='23px'> <td  class='".$register."'  height='23px'  width='40px' colspan='" . $colspan_merge . "' > &nbsp; </td>";

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
                                <td width='30px' class='print_credit_points arrear_sem_credits arrear' height='23px' valign='bottom'  > <b>".$total_cre['register']."</b> </td>";
                                $credits_earned_row .= "<td class='print_credit_points arrear_sem_credits arrear' height='23px' valign='bottom' width='30px' > <b>".$print_ear."</b></td>";
                                $sgpa_row .= "<td class='print_credit_points arrear_sem_credits arrear' width='30px' height='23px'  valign='bottom' ><b> ".$print_gpaa."</b> </td>";
                                $sem_printed_val = $printed_sem = $total_cre['sem'];
                                $sem_printed_val++;
                                $print_td++;
                                break;
                            }
                            else
                            {
                                $credits_register_row .= "<td width='30px'  class='".$register."'  height='23px' valign='bottom' align='left' >&nbsp;</td>";
                                $credits_earned_row .= "<td width='30px' class='".$register."' height='23px' valign='bottom' align='left' >&nbsp;</td>";
                                $sgpa_row .= "<td  class='".$register."'  width='30px' height='23px' valign='bottom' align='left' >&nbsp;</td>";
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
                        $align_text = $semester_number==8 || $semester_number==7 || $semester_number==6 ? 'sem_8':'';
                        $merge_col =  'colspan='.$td_printe; 
                        $increment = 0;
                            $credits_register_row .= "<td class='print_credit_points' valign='bottom'   width='60px' ".$merge_col." > <b>" . $registered_p . "</b> </td>";
                            $credits_earned_row .= "<td class='print_credit_points' valign='bottom' width='60px' height='23px'  ".$merge_col." > <b>" . $sem_credits_earned_p . "</b> </td>";
                            $sgpa_row .= "<td width='60px' class='print_credit_points' valign='bottom'  height='23px'  ".$merge_col."  > <b>" . $sgpa_row_p . "</b> </td>";
                    }
                    else 
                    {  
                        $credits_register_row .= "<td  class='".$register."' width='60px' height='23px' >&nbsp;</td>";
                        $credits_earned_row .= "<td  class='".$register."' width='60px' height='23px' >&nbsp;</td>";
                        $sgpa_row .= "<td class='".$register."'  width='60px' height='23px'  >&nbsp;</td>"; 
                        
                    }
                    $count_col_spans = $count_col_spans+1;
                }
                if($count_col_spans<11)
                {
                    $credits_register_row .= "<td  class='".$register."'  width='30px' height='23px' colspan=".(11-$count_col_spans)."  >&nbsp;</td>";
                    $credits_earned_row .= "<td width='30px' height='23px'  class='".$register."' colspan=".(11-$count_col_spans)."   >&nbsp;</td>";
                    $sgpa_row .= "<td  class='".$register."'  width='30px' height='23px' colspan=".(11-$count_col_spans)."   >&nbsp;</td>";
                }
                $credits_register_row .= "</tr>";
                $credits_earned_row .= "</tr>";
                $sgpa_row .= "</tr>";
                if($total_subs_count>=$count_of_subs)
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
                $cumulative_row .= "<tr class='cumulative_row' height='23px'>
                <td colspan='3' width='20px' > &nbsp; </td>
                <td class='print_cumulative_credit_points' height='23px' valign='top'> <b>" . $cgpa_calc['cumulative_earned_credits'] . "</b> </td>
                <td colspan='4' width='20px' > &nbsp; </td>
                <td class='print_cumulative_credit_points_cgpa' width='60px'  height='23px' valign='top' > <b>" . $cgpa_calc['cgpa_result_sem'] . "</b> </td>
                <td colspan='2' width='20px'> &nbsp; </td>
                </tr>";
                
                $footer .= $credits_register_row . $credits_earned_row . $sgpa_row . $cumulative_row . "<tr><td valign='middle' colspan=11 height=32px >&nbsp;</td></tr>".$date_insert."</table>";

             $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$exam_year.'" AND exam_month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and result like "%pass%"')->queryAll();



               if($year==2020 && $month==29)
                {
              
              $check_arrear = Yii::$app->db->createCommand('SELECT * FROM coe_mark_entry_master  WHERE year=2020 AND month=29 and student_map_id="'.$print_student_map_id.'" and mark_type="28"')->queryAll();
             // print_r($check_arrear);exit;

                }
                else{

                    
                }

                 

                 $check_arrear = Yii::$app->db->createCommand('SELECT * FROM coe_mark_entry_master  WHERE year="'.$exam_year.'" AND month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and mark_type="28"')->queryAll();

               $check_arrear_subject = Yii::$app->db->createCommand('SELECT subject_code FROM  coe_subjects as A join coe_subjects_mapping as B  on B.subject_id=A.coe_subjects_id join coe_mark_entry_master as C on C.subject_map_id=B.coe_subjects_mapping_id  WHERE year="'.$exam_year.'" AND month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and mark_type="28"')->queryAll();

               if(!empty($check_arrear) && $year=2020 && $month=29)
                {
                    $is_arrear_printed = 1;
                }
                else
                {

 
                    
                }

               

                 

               
                if(!empty($check_add))
                {
                    foreach ($check_add as $valuess) 
                    {
                        $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                        $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                        
                        $sub_na = wordwrap(strtoupper($valuess['subject_name']), 60, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";

               /*if($valuess['semester']!=$semester_number)
                //if(!empty($check_arrear_subject)&&$value['se'])
                {
                    $result_stu=$result_stu.'<sup>*</sup>';


                }
                else{

                     $result_stu=$result_stu;
                }*/
                      

                        $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);

                        $body .= "
                        <table border='0' width='100%' >
                            <tr>
                                <td width='40px' align='left'><b>" . $semester_array[$semester_number]. "</b></td>
                                <td width='67px' align='left' class='subjectcode'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                                <td width='450px' colspan='5'  align='left' class='subjectname'><b>" . $subject_name_print . "</td>
                                <td width='52px'  class='credits' align='left'><b>" . $valuess['credits'] . "</b></td>
                                <td width='45px'  class='gradename' align='left'><b> " . strtoupper($valuess['grade_name']) . "</b> </td>
                                <td width='55px'  class='gradepoint' align='center'><b>" . $grade_point_print . "</b></td>
                                <td  style='padding-left: -15px;' width='50px'  align='center'><b>" . $result_stu . "</b></td>
                            </tr>
                        </table>";
                        $total_subs_count++;
                    }

                        $body .= "
                        <table border=0  >
                            <tr>
                                <td colspan='4'  width='75px' align='left'>&nbsp;</td>
                                <td class='additional_credits put_margin' width='450px' colspan='3'  align='center'> # ADDITIONAL CREDITS EARNED WILL NOT TO BE CONSIDERED FOR GPA/CGPA CALCULATION</td>
                                <td colspan='4' width='170px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                        $body .= " 
                        <table border=0  >
                            <tr>
                                <td class='make_bold_font' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                        </table>";
                     /*if(!empty($check_arrear) && $value['batch_name']!=2017  && $value['batch_name']!=2018 && $value['batch_name']!=2019 and $value['degree_type']="UG" &&  $year=2020 && $month=29   )
                         {

                        $body .= " 
                        <table border=0  >
                            <tr>
                               <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' > Due to COVID - 19 Pandemic, April/May 2020 Arrear Examinations were conducted during Nov/Dec 2020 as per Anna University guidelines.</td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";

                         }
                         else if(!empty($check_arrear))
                         {
                             $body .= " 
                         <table border=0  >
                            <tr>
                                <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' >  Due to COVID - 19 Pandemic, April/May 2020 Regular/Arrear Examinations were processed/conducted during Nov/Dec 2020 as per Anna University guidelines. </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";


                         }
                         else{

                            
                         }*/
                    

                   
                }
                else if($is_additional_printed==1 && $print_additional_text==0)
                {
                     $body .= "<table border=0  >
                        <tr>
                            <td colspan='4'  width='75px' align='left'>&nbsp;</td>
                            <td  valign='top' class='additional_credits put_margin' width='450px' colspan='3'  align='center'> # ADDITIONAL CREDITS EARNED WILL NOT TO BE CONSIDERED FOR GPA/CGPA CALCULATION</td>
                            <td valign='top' colspan='4' width='170px'  align='right'>&nbsp;</td>
                        </tr>
                    </table>";
                    $body .= " 
                    <table border=0  >
                        <tr>
                            <td valign='top' class='make_bold_font' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                            
                            <td valign='top' colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                        </tr>
                    </table>";
                /*if(!empty($check_arrear) && $value['batch_name']!=2017  && $value['batch_name']!=2018 && $value['batch_name']!=2019 and $value['degree_type']="UG" &&  $year=2020 && $month=29   )
                         {

                        $body .= " 
                        <table border=0  >
                            <tr>
                               <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' > Due to COVID - 19 Pandemic, April/May 2020 Arrear Examinations were conducted during Nov/Dec 2020 as per Anna University guidelines.</td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";

                         }
                         else if(!empty($check_arrear))
                         {
                             $body .= " 
                         <table border=0  >
                            <tr>
                                <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' >  Due to COVID - 19 Pandemic, April/May 2020 Regular/Arrear Examinations were processed/conducted during Nov/Dec 2020 as per Anna University guidelines. </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";


                         }
                         else{

                            
                         }*/
                    

                }
                else
                {
                    
                    $body .= "
                        <table border=0   >
                            <tr>
                                <td class='make_bold_font'  width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;</td>
                            </tr>
                        </table>";
                   /*if(!empty($check_arrear) && $value['batch_name']!=2017  && $value['batch_name']!=2018 && $value['batch_name']!=2019 and $value['degree_type']="UG" &&  $year=2020 && $month=29   )
                         {

                        $body .= " 
                        <table border=0  >
                            <tr>
                               <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' > Due to COVID - 19 Pandemic, April/May 2020 Arrear Examinations were conducted during Nov/Dec 2020 as per Anna University guidelines.</td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";

                         }
                         else if(!empty($check_arrear))
                         {
                             $body .= " 
                         <table border=0  >
                            <tr>
                                <td  width='10px' class='mark' colspan='7'  align='center' style='padding-left: 90px !important;' >  Due to COVID - 19 Pandemic, April/May 2020 Regular/Arrear Examinations were processed/conducted during Nov/Dec 2020 as per Anna University guidelines. </td>
                                
                                <td colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                            </tr>
                            </table>";


                         }
                         else{

                            
                         }*/
                    

                }
    $checkIsHaveingAdditional = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks as A JOIN coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id where result like "%Pass%" and student_map_id="'.$print_student_map_id.'" and year<="'.$exam_year.'" and is_additional="YES" ')->queryAll();
    $check_add_attemot = Yii::$app->db->createCommand('SELECT * FROM coe_mandatory_stu_marks as A JOIN coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id where result like "%Pass%" and student_map_id="'.$print_student_map_id.'" and  year<="'.$exam_year.'"  and  result like "%Pass%" and is_additional="YES" ')->queryAll();
    $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$print_student_map_id.'" and year="'.$exam_year.'" and month="'.$add_month.'"';
    $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();

    $checkWaiverGradne = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$print_student_map_id.'"';
    $getWaiverStatusGrande = Yii::$app->db->createCommand($checkWaiverGradne)->queryAll();
    $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='11' width='30px' height='64px' >&nbsp;</td></tr>";
    if(!empty($getWaiverStatus) && count($check_add_attemot)>=$config_elect)
    {
        $esrned_credits = $cgpa_calc['cumulative_additional_credits']-$config_elect;
        if(!empty($cgpa_calc['cumulative_additional_credits']) && $cgpa_calc['cumulative_additional_credits']!=0)
        {
            $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='30px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$esrned_credits."</b></td></tr>";
        }
    }
    else if($is_additional_printed==1 && $print_additional_text==0 )
    {
        if(!empty($cgpa_calc['cumulative_additional_credits']) && $cgpa_calc['cumulative_additional_credits']!=0)
        {
                $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='30px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$cgpa_calc['cumulative_additional_credits']."</b></td></tr>";
        }
    }
    else if(!empty($checkIsHaveingAdditional) && count($check_add_attemot)<$config_elect  && empty($getWaiverStatusGrande))
    {
        if(!empty($cgpa_calc['cumulative_additional_credits']) && $cgpa_calc['cumulative_additional_credits']!=0)
        {
            $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='30px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$cgpa_calc['cumulative_additional_credits']."</b></td></tr>";
        }
    }
    else if(!empty($check_add_attemot) && empty($getWaiverStatusGrande))
    {
        if(!empty($cgpa_calc['cumulative_additional_credits']) && $cgpa_calc['cumulative_additional_credits']!=0)
        {
            $merge_body = "<tr><td class='".$middlecontent."' width='100%' valign='top' colspan='11' height='".$bottom_margin."px' >" . $body . "</td></tr><tr><td colspan='2' width='50px' height='30px' >&nbsp;</td><td colspan='9' width='300px' height='64px' ><b style='text-align: left' >CUMULATIVE ADDITIONAL CREDITS EARNED : ".$cgpa_calc['cumulative_additional_credits']."</b></td></tr>";
        }
    }
   
    $html = $header . $merge_body . $footer;
    $print_stu_data .= $html;
    //print_r($merge_body );exit;
 //print_r($cgpa_calc['cumulative_additional_credits']);exit;

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
