<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\CoeBatDegReg;
use app\models\Degree;
use app\models\Programme;
$this->registerCssFile("@web/css/consolidate-markstatement-ug.css");

?>
<style type="text/css">
    table , .makeitBigger,.subjects_tables, .print_cgpa_size{
        font-size: 12px !important;
    }
</style>
<?php Yii::$app->ShowFlashMessages->showFlashes(); 


    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    
    if($file_content_available=="Yes")
    {
        $supported_extensions = ConfigUtilities::ValidFileExtension(); 
        $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
        $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";

        $html = $body_1 = $body_2 = "";
        $previous_subject_code= "";
        $previous_reg_number = "";
        $header = "";
        $add_body_1 =  '';$morethan_4_sems =4;
        $body ="";
        $footer = "";
        $print_register_number = $prev_stu_map_id ="";
        $new_stu_flag=0;
        $print_stu_data="";
        $exam_year='';
        $app_month='';
        $batch_mapping_id='';
        $total_sub_count = 0;
        $first_reg_no = 0;
        $cgpa_calc = [];
        echo Html::a('<i class="fa fa-file-pdf-o"></i> Print Pdf', ['/mark-entry-master/consolidate-mark-sheet-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
            ]);
        echo "<br /><br />";
        $open_div ='<div class="row"><div class="col-xs-12"><div class="col-xs-12 col-sm-1 col-lg-1">&nbsp;</div><div class="col-xs-12 col-sm-10 col-lg-10"><div class="col-xs-12 col-sm-1 col-lg-1"></div>';
        $close_div = "<br /><br /></div></div>";
        $semester_array = ['1'=>'01','2'=>'02','3'=>'03','4'=>'04','5'=>'05','6'=>'06','7'=>'07','8'=>'08','9'=>'09','10'=>'10','11'=>'11','12'=>'12'];

        foreach ($get_console_list as $value) 
        {
            if($first_reg_no==0)
            {
                $cgpa_calculation = ConfigUtilities::getCgpaCaluclation($value['year'],$value['month'],$value['course_batch_mapping_id'],$value['student_map_id']);
                $first_reg_no=5;
                $final_cgpa = $cgpa_calculation['cgpa'];


            }
            if($previous_reg_number!=$value['register_number'])
            {
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
                    $new_stu_flag=$new_stu_flag + 1;
                    if($new_stu_flag > 1) 
                    {
                        
                        $semester_number = ConfigUtilities::semCaluclation($exam_year,$app_month,$batch_mapping_id);
                        $course_batch_mapping_id =  CoeBatDegReg::findOne($batch_mapping_id);
                        $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
                        $total_semesters = $degree_years->degree_total_semesters;
                        $colspan_merge = (26-$total_semesters);
                        $eqals = $colspan_merge/3;
                        $gpa = 0;
                        $total_credits_cgpa=0;
                        for ($i=0; $i <count($cgpa_calc) ; $i++) 
                        { 
                            $gpa += $cgpa_calc[$i]['gpa'];
                            $total_credits_cgpa += $cgpa_calc[$i]['credits'];
                        }
                        $total_credits_cgpa = $total_credits_cgpa==0?1:$total_credits_cgpa;
                        $final_cgpa = round($gpa/$total_credits_cgpa,2);
                        $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
                        $cumulative_row ="
                        <tr>
                            <td class='line_height print_cgpa_size' valign='top' colspan=26 height=30px >
                                <table width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height print_cgpa_size' colspan=10 width='300px;' > &nbsp; </td>
                                        <td valign='top'   class='line_height print_cgpa_size' colspan=8 width='400px'  > ".$total_credits_cgpa." </td>
                                        
                                        <td valign='top'   class='line_height print_cgpa_size' colspan=4 width='300px;'  >".$final_cgpa."</td>
                                        <td valign='top'   class='line_height print_cgpa_size' width='300px;'   >".$classification."</td>                                       
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class='line_height print_cgpa_size' valign='top' colspan=26 height=100px > &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td class='line_height print_cgpa_size' valign='top' colspan=2 height=30px > &nbsp;
                            </td>
                            <td class='line_height print_cgpa_size' valign='top' colspan=4 height=30px > ".date('d-m-Y')."
                            </td>
                            <td class='line_height print_cgpa_size' valign='top' colspan=20 height=30px > &nbsp;
                            </td>
                        </tr>";

                        $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$prev_stu_map_id.'" and result like "%pass%"')->queryAll();
               
                        if(!empty($check_add))
                        {
                            foreach ($check_add as $valuess) 
                            {
                                $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                                $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                                
                                $sub_na = wordwrap(strtoupper($valuess['subject_name']), 50, "\n", true);
                                $sub_na = htmlentities($sub_na);
                                $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                                //$subject_name_print = "<b>".strtoupper($valuess['subject_name'])."</b>";

                                $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);

                                $body .= "
                                <table border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  width='40px' align='left'><b>" . $semester_array[$semester_number]. "</b></td>
                                        <td valign='top'  width='67px' align='left'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                                        <td valign='top'  width='450px' class='put_margin' colspan='5'  align='left'><b>" . trim($subject_name_print) . "</td>
                                        <td valign='top'  width='52px'  align='left'><b>" . $valuess['credits'] . "</b></td>
                                        <td valign='top'  width='45px'  align='left'><b> " . strtoupper($valuess['grade_name']) . "</b> </td>
                                        <td valign='top'  width='55px'  align='center'><b>" . $grade_point_print . "</b></td>
                                        <td valign='top'   style='padding-left: 10px;' width='50px'  align='center'><b>" . $result_stu . "</b></td>
                                    </tr>
                                </table>";
                            }

                                $body .= "
                                <table border=0  >
                                    <tr>
                                        <td valign='top'  colspan='4'  width='75px' align='left'>&nbsp;</td>
                                        <td valign='top'  class='additional_credits' width='450px' class='put_margin' colspan='3'  align='center'> # ADDITIONAL CREDIT ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))."S WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                                        <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;</td>
                                    </tr>
                                </table>";
                                $body .= " 
                                <table border=0  >
                                    <tr>
                                        <td valign='top'  class='make_bold_font' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                        
                                        <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                                    </tr>
                                </table>";
                           
                        }
                        else
                        {
                            
                            $body .= "
                                <table border=0   >
                                    <tr>
                                        <td valign='top'  class='make_bold_font'  width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                        
                                        <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;</td>
                                    </tr>
                                </table>";
                        }

                        $footer .=$cumulative_row."</table>";
                        $merge_two_body_tags = $body_1.'</td>'.$body_2."</td>";
                        $body = $merge_two_body_tags;
                        $merge_body ="<tbody><tr>".$body."</tr></tbody><tr><td valign='top'  colspan=26 height='80px' >&nbsp;</td></tr>";
                        $html = $header .$merge_body.$footer; 
                        $print_stu_data .= $html."<pagebreak sheet-size='A3-L' >";
                       
                        $header =$body_1 = $body_2 = "";
                        $body ="";
                        $footer = "";
                        $new_stu_flag = 1;
                        unset($cgpa_calc); $cgpa_calc = [];
                    } 
                $same_semester=$value['semester'];
                $print_gender = $value['gender']=='F'?'FEMALE':'MALE';
                $exam_year=$value['year'];
                $app_month = ConfigUtilities::getMonthName($value['month']);
                $batch_mapping_id=$value['course_batch_mapping_id'];
                $last_appearance = ConfigUtilities::getYearOfPassing($value['last_appearance']);
                $dob= date('d/m/Y',strtotime($value['dob']));

                $photo_extension = ConfigUtilities::match($supported_extensions,$value['register_number'].$extension); 
                $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg";
               
                $header ='<table width="100%" autosize=1  >
              '; 
              $header.='<tr>
                            <td valign="top"  colspan=24> &nbsp; </td>
                            <td valign="top"  style="padding-top: 55px !important;" colspan=2 align="center" >  
                                <img  class"img_print_dat"  width=150 height=150 src='.$stu_photo.' alt='.$stu_photo.' Photo >
                            </td>
                        </tr>';
            $header.='<tr>';
                  
            $header.='<td valign="top"  height="150" colspan="12">'; //first td
             $header.='<table class="makeitBigger" width="100%">
                        <tr>
                            <td valign="top"  colspan="4">&nbsp;</td>
                            <td valign="top"  align="center" class="put_some_gap" colspan="8">'.$value["name"].'</td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="4">&nbsp;</td>
                            <td valign="top"  align="center" class="put_some_gap" colspan="8">'.strtoupper($value["degree_code"].". ".$value["programme_name"]).'</td>
                        </tr>
                       </table>
                       </td>';

            $header.='<td valign="top"  height="100" colspan="2" >&nbsp;</td>'; //second td
            
            $header.='<td valign="top"  height="150" colspan="12">
                        <table class="makeitBigger"  width="100%">
                        <tr>
                            <td valign="top"  colspan="4" width="100" >&nbsp; </td>
                            <td valign="top"   class="put_some_gap push_left" colspan="6">'.strtoupper($value["register_number"]).'</td>
                            <td valign="top"  class="put_some_gap push_left" colspan="2">'.$value["regulation_year"].'</td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="4" width="100" >&nbsp;</td>
                            <td valign="top"   class="put_some_gap push_left" colspan="6">'.$value["dob"].'</td>
                            <td valign="top"   class="put_some_gap push_left"  colspan="2">'.$print_gender.'</td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="4" width="100" >&nbsp;</td>
                            <td valign="top"   class="put_some_gap push_left" colspan="6">'.$last_appearance = ConfigUtilities::getYearOfPassing($value['last_appearance']).'</td>
                            <td valign="top"   class="put_some_gap push_left" colspan="2">ENGLISH</td>
                        </tr>
                    </table>
                    </td>';

            $header.='</tr><tr><td valign="top"  colspan="26" height="70px" >&nbsp;</td></tr>'; //main tr

            $total_credits ='';
             $total_earned_credits ='';
             $passed_grade_points ='';
             $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
             $body_1 .= "<td valign='top'  class='line_height subjects_tables' valign='top'  colspan='13' >";
             $body_2 .= "<td valign='top'  class='line_height subjects_tables' valign='top' colspan='13' >"; //1st td
               if($value['ESE_max']==0 && $value['CIA_max']==0)
               {
                    $body_1 .=" 
                    <table style='line-height: 1.2em; ' border='0' width='100%'  >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='100px'>".$semester_array[$value['semester']]."</td>
                            <td valign='top'  class='line_height subjects_tables' width='150px' >".$value['subject_code']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='700px' colspan=5 >".$value['subject_name']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5> <b>COMPLETED</b> </td>
                        </tr>
                    </table>    
                ";
               }
               else
               {
                    $body_1 .=" 
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='100px'>".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='150px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='700px' colspan=5 >".$value['subject_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['credit_points']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['grade_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['grade_point']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".$year_of_passing."</td>
                            </tr>
                        </table>";
               }
                $total_sub_count++;
                $cgpa_calc []= ['gpa'=> $value['credit_points']*$value['grade_point'],'credits'=>$value['credit_points']];
                $total_earned_credits += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? $value['credit_points'] : 0;

                $passed_grade_points += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? ($value['credit_points']*$value['grade_point']) : 0;

                $total_credits +=$value['credit_points'];
            } // Not the same register Number closed here 
            else
            {
                
            $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']); 

            if($value['semester']>4)
            {
                if($morethan_4_sems != $value['semester'])
                {
                    $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' class='line_height subjects_tables' colspan=12>&nbsp; </td>
                                </tr>
                            </table>";
                    $morethan_4_sems = $value['semester'];
                }


            if($value['ESE_max']==0 && $value['CIA_max']==0)
               {
                    $body_2 .=" 
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='100px'>".$semester_array[$value['semester']]."</td>
                            <td valign='top'  class='line_height subjects_tables' width='150px' >".$value['subject_code']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='700px' colspan=5 >".$value['subject_name']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5 > <b>COMPLETED</b> </td>
                        </tr>
                    </table>    
                ";
               }
               else{
                        $body_2 .=" 
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='100px'>".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='150px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='700px' colspan=5 >".$value['subject_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['credit_points']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['grade_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['grade_point']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".$year_of_passing."</td>
                            </tr>
                        </table>    
                    ";
               }

                $total_sub_count++;
            }
            else{
                if($same_semester!=$value['semester'])
                {   
                    $body_1 .=" 
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' colspan=12>&nbsp; </td>
                                    
                                   
                                </tr>
                            </table>    
                        ";
                 
                    $same_semester=$value['semester'];

                }
               if($value['ESE_max']==0 && $value['CIA_max']==0)
               {
                    $body_1 .=" 
                    <table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='100px'>".$semester_array[$value['semester']]."</td>
                            <td valign='top'  class='line_height subjects_tables' width='150px' >".$value['subject_code']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='700px' colspan=5 >".$value['subject_name']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5 > <b>COMPLETED</b> </td>
                        </tr>
                    </table>    
                ";
               }
               else{
                    $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='100px'>".$semester_array[$value['semester']]."</td>
                            <td valign='top'  class='line_height subjects_tables' width='150px' >".$value['subject_code']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='700px' colspan=5 >".$value['subject_name']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['credit_points']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['grade_name']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['grade_point']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".$year_of_passing."</td>
                        </tr>
                    </table>";
               }   
               $total_sub_count++;
               }   
                $cgpa_calculation = ConfigUtilities::getCgpaCaluclation($value['year'],$value['month'],$value['course_batch_mapping_id'],$value['student_map_id']);
                    
                $cgpa_calc []= ['gpa'=> $value['credit_points']*$value['grade_point'],'credits'=>$value['credit_points']];
                $total_earned_credits += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? $value['credit_points'] : 0;
                $passed_grade_points += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? ($value['credit_points']*$value['grade_point']) : 0;
                $total_credits +=$value['credit_points'];
            }
            $previous_subject_code = $value['subject_code'];
            $previous_reg_number=$value['register_number'];
            $prev_stu_map_id =$value['student_map_id']; 
        }
        
            $semester_number = ConfigUtilities::semCaluclation($exam_year,$app_month,$batch_mapping_id);
            $course_batch_mapping_id =  CoeBatDegReg::findOne($batch_mapping_id);
            $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
            $total_semesters = $degree_years->degree_total_semesters;

            $colspan_merge = (26-$total_semesters);
            
            $merge_two_body_tags = $body_1.'</td>'.$body_2."</td>";
            $body = $merge_two_body_tags;
            $merge_body ="<tbody><tr>".$body."</tr></tbody><tr><td valign='top'  colspan=26 height='80px' >&nbsp;</td></tr>";
           
            
            $gpa = 0;
            $total_credits_cgpa=0;
            for ($i=0; $i <count($cgpa_calc) ; $i++) 
            { 
                $gpa += $cgpa_calc[$i]['gpa'];
                $total_credits_cgpa += $cgpa_calc[$i]['credits'];
            }
            $final_cgpa = round($gpa/$total_credits_cgpa,2);
            $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
            $cumulative_row ="
                        <tr>
                            <td valign='top'  class='line_height print_cgpa_size' valign='top' colspan=26 height=30px >
                                <table width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height print_cgpa_size' colspan=10 width='300px;' > &nbsp; </td>
                                        <td valign='top'   class='line_height print_cgpa_size' colspan=8 width='400px'  > ".$total_credits_cgpa." </td>
                                        
                                        <td valign='top'   class='line_height print_cgpa_size' colspan=4 width='300px;'  >".$final_cgpa."</td>
                                        <td valign='top'   class='line_height print_cgpa_size' width='300px;'   >".$classification."</td>                                       
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class='line_height print_cgpa_size' valign='top' colspan=26 height=100px > &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td class='line_height print_cgpa_size' valign='top' colspan=2 height=30px > &nbsp;
                            </td>
                            <td class='line_height print_cgpa_size' valign='top' colspan=4 height=30px > ".date('d-m-Y')."
                            </td>
                            <td class='line_height print_cgpa_size' valign='top' colspan=20 height=30px > &nbsp;
                            </td>
                        </tr>";
            $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$prev_stu_map_id.'" and result like "%pass%"')->queryAll();
               
            if(!empty($check_add))
            {
                foreach ($check_add as $valuess) 
                {
                    $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                    $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                    
                    $sub_na = wordwrap(strtoupper($valuess['subject_name']), 50, "\n", true);
                    $sub_na = htmlentities($sub_na);
                    $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                    //$subject_name_print = "<b>".strtoupper($valuess['subject_name'])."</b>";

                    $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);

                    $add_body_1 .= "
                    <table border='0' width='100%' >
                        <tr>
                            <td valign='top'  width='40px' align='left'><b>" . $semester_array[$semester_number]. "</b></td>
                            <td valign='top'  width='67px' align='left'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                            <td valign='top'  width='450px' class='put_margin' colspan='5'  align='left'><b>" . trim($subject_name_print) . "</td>
                            <td valign='top'  width='52px'  align='left'><b>" . $valuess['credits'] . "</b></td>
                            <td valign='top'  width='45px'  align='left'><b> " . strtoupper($valuess['grade_name']) . "</b> </td>
                            <td valign='top'  width='55px'  align='center'><b>" . $grade_point_print . "</b></td>
                            <td valign='top'   style='padding-left: 10px;' width='50px'  align='center'><b>" . $result_stu . "</b></td>
                        </tr>
                    </table>";
                }

                    $add_body_1 .= "
                    <table border=0  >
                        <tr>
                            <td valign='top'  colspan='4'  width='75px' align='left'>&nbsp;</td>
                            <td valign='top'  class='additional_credits' width='450px' class='put_margin' colspan='3'  align='center'> # ADDITIONAL CREDIT ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))."S WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                            <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;</td>
                        </tr>
                    </table>";
                    $add_body_1 .= " 
                    <table border=0  >
                        <tr>
                            <td valign='top'  class='make_bold_font' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                            
                            <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;&nbsp; </td>
                        </tr>
                    </table>";
               
            }
            else
            {
                
                $add_body_1 .= "
                    <table border=0   >
                        <tr>
                            <td valign='top'  class='make_bold_font'  width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                            
                            <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;</td>
                        </tr>
                    </table>";
            }
            $footer .=$cumulative_row."</table>";
            $html = $header .$merge_body.$add_body_1.$footer;
            $print_stu_data .=$html;
                    
    
        if(isset($_SESSION['consolidatemarksheet_print']))
        { 
            unset($_SESSION['consolidatemarksheet_print']);
        }
        $_SESSION['consolidatemarksheet_print'] = $print_stu_data;

        echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-12" >'.$print_stu_data.'</div></div></div></div></div>';
        
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
    }

?>