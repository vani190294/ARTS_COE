<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\CoeBatDegReg;
use app\models\Batch;
use app\models\Degree;
use app\models\Programme;
use app\models\StudentMapping;
$this->registerCssFile("@web/css/consolidate-markstatement-ug-skcet.css");

?>
<style type="text/css">
    table , .makeitBigger,.subjects_tables, .print_cgpa_size{
        font-size: 12px !important;
    }
</style>
<?php Yii::$app->ShowFlashMessages->showFlashes(); 


    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $add_tr_starting =  "<tr><td class='make_bold_font' id='no_print_trimester' colspan='26'> &nbsp; </td></tr>";
    if($file_content_available=="Yes")
    {
        $later_entry = Categorytype::find()->where(['category_type'=>'Lateral Entry'])->orWhere(['description'=>'Lateral Entry'])->one();
        $lateral_entry_id = $later_entry->coe_category_type_id;
        $supported_extensions = ConfigUtilities::ValidFileExtension(); 
        $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
        $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";
        $publish_date = date('d/m/Y',strtotime($date_print));
        $html = $body_1 = $body_2 = "";
        $previous_subject_code= "";
        $previous_reg_number = "";
        $header = "";
        $add_body_1 =  '';$morethan_4_sems =4; $additional_creditsPrinte =0;
        $body ="";
        $footer = "";
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $print_register_number = $prev_stu_map_id ="";
        $new_stu_flag=0;
        $print_stu_data="";
        $exam_year='';
        $app_month='';
        $batch_mapping_id='';
        $total_sub_count = 0;
        $first_reg_no = 0;
        $cgpa_calc = [];
        echo Html::a('<i class="fa fa-file-pdf-o"></i> Print Pdf', ['/mark-entry-master/consolidate-mark-sheet-pdf-skcet'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
            ]);
        echo "<br /><br />";
        $open_div ='<div class="row"><div class="col-xs-12"><div class="col-xs-12 col-sm-1 col-lg-1">&nbsp;</div><div class="col-xs-12 col-sm-10 col-lg-10"><div class="col-xs-12 col-sm-1 col-lg-1"></div>';
        $close_div = "<br /><br /></div></div>";
        
        $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII','13'=>'XIII','14'=>'XIV'];
                

        foreach ($get_console_list as $value) 
        {
            if($first_reg_no==0)
            {
                $cgpa_calculation = ConfigUtilities::getCgpaCaluclation($value['year'],$value['month'],$value['course_batch_mapping_id'],$value['student_map_id']);
                $first_reg_no=5;
                $final_cgpa = $cgpa_calculation['final_cgpa'];
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
                        $gpa = $total_sub_count= 0;
                        $total_credits_cgpa=0;
                        
                        $final_cgpa = $cgpa_calculation['final_cgpa'];
                        $total_credits_cgpa = $cgpa_calculation['cumulative_earned_credits'];
                        $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
                        $cumulative_row ="
                       
                        <tr>
                            <td valign='top'   class='line_height print_cgpa_size' colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger' colspan=5 width='400px'  > ".$total_credits_cgpa." </td>
                            
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger PRINT_finalfinal_cgpa' colspan=8 width='100px;'  >".$final_cgpa."</td>
                            <td valign='top' colspan=10 class='line_height print_cgpa_size'  >&nbsp;</td>                                       
                        </tr>
                        
                        <tr>
                            <td valign='top'   class='line_height print_cgpa_size' colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top' height='65px'  class='line_height print_cgpa_size print_class makeitBigger' colspan=5 width='400px'  > ".$classification." </td>
                            
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger PRINT_finalfinal_cgpa' colspan=8 width='100px;'  >&nbsp;</td>
                            <td valign='top' colspan=10 class='line_height print_cgpa_size'  >&nbsp;</td> 

                        </tr>                       
                        <tr>     
                            <td valign='top'   class='line_height print_cgpa_size' colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger' colspan=5 width='400px'  >".$publish_date." </td>
                            
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger PRINT_finalfinal_cgpa' colspan=8 width='100px;'  >&nbsp;</td>
                            <td valign='top' colspan=10 class='line_height print_cgpa_size'  >&nbsp;</td>

                        </tr>";

                        $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$prev_stu_map_id.'" and result like "%pass%" and exam_year="'.$exam_year.'" and exam_month="'.$exam_month.'" ')->queryAll();
                        
                        if(!empty($check_add))
                        {
                             $body_2 .= "<table border='0' width='100%' >
                                <tr>
                                    <td width='60px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='78px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='450px' colspan='6'  align='left'><b>ADDITIONAL CREDIT COURSE(S)*</b></td>
                                    <td width='67px' colspan=3 align='left'>&nbsp;</td>
                                </tr>
                            </table>";
                            foreach ($check_add as $valuess) 
                            {
                                $additional_creditsPrinte = 1;
                                $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                                $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                                
                               $sub_na = strtoupper($valuess['subject_name']);
                                $subject_name_print = $sub_na;
                                $man_year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
                                $semester_number = ConfigUtilities::semCaluclation($exam_year,$exam_month,$batch_mapping_id);
                                $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);

                                $body_2 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$semester_number]."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='100px' >".strtoupper($valuess['subject_code'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >". trim($subject_name_print)."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".$valuess['credits']."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_point'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)."</td>
                                </tr>
                                </table>";
                                $total_sub_count++;
                              
                            }                           
                        }
                        if($additional_creditsPrinte==1)
                        {
                            $add_body_1 .= "
                            <table border=0  >
                                <tr>
                                    <td valign='top'  colspan='4'  width='180px' align='left'>&nbsp;</td>
                                    <td valign='top'  class='make_bold_font'  width='600px' colspan='5'  align='left' > *ADDITIONAL CREDITS WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                                    <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;</td>
                                </tr>
                            </table>";
                        }
                        $add_body_1 .= "
                            <table border=0   >
                                <tr>
                                    <td valign='top'  colspan='4'  width='75px' align='left'>&nbsp;</td>
                                    <td valign='top'  class='make_bold_font'  width='600px' colspan='5'  align='center'> ~ END OF STATEMENT ~ </td>                            
                                    <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;</td>
                                </tr>
                            </table>";
                            $total_sub_count++;
                        

                        $footer .=$cumulative_row."</table>";
                        $merge_two_body_tags = $body_1.'</td>'.$body_2.$add_body_1."</td>";
                        $body = $merge_two_body_tags;
                        $merge_body ="<tbody><tr>".$body."</tr></tbody><tr><td valign='top' colspan=26 height='63px' >&nbsp;</td></tr>";
                        $html = $header .$merge_body.$footer; 
                        $print_stu_data .= $html."<pagebreak sheet-size='A3-L' >";
                       
                        $header =$body_1 = $add_body_1 = $body_2 = "";
                        $body ="";
                        $footer = "";
                        $additional_creditsPrinte = 0;
                        $new_stu_flag = 1;
                        unset($cgpa_calc); $cgpa_calc = [];
                    } 
                $same_semester=$value['semester'];
                $print_gender = $value['gender']=='F'?'FEMALE':'MALE';
                $exam_year=$value['year'];
                $app_month = ConfigUtilities::getMonthName($value['month']);
                $batch_mapping_id=$value['course_batch_mapping_id'];
                $last_appearance = ConfigUtilities::getLastYearOfPassing($value['register_number']);
                $dob= date('d/m/Y',strtotime($value['dob']));

                $photo_extension = ConfigUtilities::match($supported_extensions,$value['register_number'].$extension); 
                $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg";
                $getDegInfo = CoeBatDegReg::findOne($value['course_batch_mapping_id']);
                $batch_details = Batch::findOne($getDegInfo->coe_batch_id);
                $getYearFromLastApp = explode('-', $last_appearance);
                $duration = $getYearFromLastApp[1]-$batch_details->batch_name;
                $header ='<table width="100%"   >
              '; 
              $header.='<tr>
                            <td valign="top"  colspan=18> &nbsp; </td>
                            <td valign="top"  style="padding-top: 30px !important;" colspan=8 align="center" >  
                                <img  class"img_print_dat"   width=150 height=150 src='.$stu_photo.' alt='.$stu_photo.' Photo >
                            </td>
                        </tr>'.$add_tr_starting;
            $header.='<tr>';
            $stu_dob = date('d/m/Y',strtotime($value["dob"]));
            $header.='<td valign="top"  height="150" colspan="13">'; //first td
             $header.='<table class="makeitBigger" width="100%">
                        <tr>
                            <td valign="top" width="350" colspan="6">&nbsp;</td>
                            <td valign="top"  align="left" class="bring_name make_bold_font left_alignment" height="40" colspan="7">'.strtoupper($value["name"]).'</td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="6" width="100" >&nbsp;</td>
                            <td valign="top"  class="dob_print make_bold_font " colspan="6">'.$stu_dob.'</td>
                            <td valign="top"  >&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top" width="350"  colspan="6"></td>
                            <td valign="top"  align="left" class="make_bold_font left_alignment" colspan="7">'.strtoupper($value["degree_code"].". ".$value["programme_name"]).'</td>
                        </tr>
                       </table>
                       </td>';
            
            $header.='<td valign="top" class="bring_to_the_right"  height="150" colspan="13">
                        <table class="makeitBigger"  width="100%">
                        <tr>
                            <td valign="top"  colspan="6" width="100" >&nbsp; </td>
                            <td valign="top" width="300" class="this_should_be_up push_regulation make_bold_font" colspan="6">'.strtoupper($value["register_number"]).'</td>
                            <td valign="top"  class="push_regulation make_bold_font  " style="text-align: left;" >'.$value["regulation_year"].'</td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="6" width="100" >&nbsp;</td>
                            <td valign="top" width="300" style="padding-top: 8px;" class="this_should_be_up push_regulation  make_bold_font move_gender" colspan="6">'.$print_gender.'</td>
                            <td valign="top"  class="push_regulation make_bold_font " style="text-align: left;" >'.$last_appearance.'</td>
                        </tr>
                        
                    </table>
                    </td>';

            $header.='</tr><tr><td valign="top"  colspan="26" height="60px" >&nbsp;</td></tr>'; //main tr

            $total_credits ='';
             $total_earned_credits ='';
             $passed_grade_points ='';
             $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
             $body_1 .= "<td valign='top' class='line_height subjects_tables left_table_td' valign='top'  colspan='13' >";
             $body_2 .= "<td valign='top'  class='line_height subjects_tables right_table_td' valign='top' colspan='13' >"; //1st td
               if($value['ESE_max']==0 && $value['CIA_max']==0)
               {
                    $body_1 .=" 
                    <table style='line-height: 1.0em; ' border='0' width='100%'  >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='60px' >".$semester_array[$value['semester']]."</td>
                            <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['subject_code']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >".strtoupper($value['subject_name'])."</td>
                            <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5> <b>COMPLETED</b> </td>
                        </tr>
                    </table>    
                ";
                $total_sub_count++;
               }
               else
               {
                    $body_1 .=" 
                        <table style='line-height: 1.0em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='60px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >".strtoupper($value['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$value['credit_points']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_point']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".$year_of_passing."</td>
                            </tr>
                        </table>";
                        $total_sub_count++;
               }
                
                $cgpa_calc []= ['gpa'=> $value['credit_points']*$value['grade_point'],'credits'=>$value['credit_points']];
                $total_earned_credits += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? $value['credit_points'] : 0;

                $passed_grade_points += $value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass" ? ($value['credit_points']*$value['grade_point']) : 0;

                $total_credits +=$value['credit_points'];
            } // Not the same register Number closed here 
            else
            {
                
            $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']); 
            if($value['status_category_type_id']==$lateral_entry_id && $value['semester']>6)
            {
                if($same_semester!=$value['semester'])
                {   
                    if($same_semester==6)
                    {
                        $exam_year = $value['year'];
                        $exam_month = $value['month'];          
                        $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$value['student_map_id'].'" and result like "%pass%" and exam_year="'.$exam_year.'" and exam_month="'.$exam_month.'" ')->queryAll();

                        if(!empty($check_add))
                        {

                            $body_2 .= "
                            <table border='0' width='100%' >
                                <tr>
                                    <td width='60px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='78px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='450px' colspan='6'  align='left'><b>ADDITIONAL CREDIT COURSE(S)*</b></td>
                                    <td width='67px' colspan=3 align='left'>&nbsp;</td>
                                </tr>
                            </table>";
                            foreach ($check_add as $valuess) 
                            {
                                $additional_creditsPrinte=1;
                                $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                                $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                                
                                $sub_na = strtoupper($valuess['subject_name']);
                                $subject_name_print = $sub_na;
                                $semester_number = ConfigUtilities::semCaluclation($exam_year,$exam_month,$batch_mapping_id);
                                $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);
                                $man_year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
                                 $body_2 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
                                            <tr>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$semester_number]."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='100px' >".strtoupper($valuess['subject_code'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >". trim($subject_name_print)."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$valuess['credits']."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_name'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_point'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)."</td>
                                            </tr>
                                            </table>";
                                $total_sub_count++;
                            }
                           
                        }
                    }
                    else
                    {
                        $exam_year = $value['year'];
                        $exam_month = $value['month'];          
                        $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$value['student_map_id'].'" and result like "%pass%" and exam_year="'.$exam_year.'" and exam_month="'.$exam_month.'" ')->queryAll();

                        if(!empty($check_add))
                        {
                            $body_2 .= "
                            <table border='0' width='100%' >
                                <tr>
                                    <td width='60px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='78px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='450px' colspan='6'  align='left'><b>ADDITIONAL CREDIT COURSE(S)*</b></td>
                                    <td width='67px' colspan=3 align='left'>&nbsp;</td>
                                </tr>
                            </table>";
                            foreach ($check_add as $valuess) 
                            {
                                $additional_creditsPrinte=1;
                                $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                                $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                                
                               $sub_na = strtoupper($valuess['subject_name']);
                                $subject_name_print = $sub_na;
                                $semester_number = ConfigUtilities::semCaluclation($exam_year,$exam_month,$batch_mapping_id);
                                $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);
                                $man_year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
                                 $body_2 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
                                            <tr>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$semester_number]."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='100px' >".strtoupper($valuess['subject_code'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >". trim($subject_name_print)."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$valuess['credits']."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_name'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_point'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)."</td>
                                            </tr>
                                            </table>";
                                $total_sub_count++;
                            }
                           
                        }
                         $body_2 .=" 
                            <table style='line-height: 0.6em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' colspan=13>&nbsp; </td>
                                </tr>
                          </table>";
                    }
                    $same_semester=$value['semester'];
                    $total_sub_count++;
                }
                if($value['ESE_max']==0 && $value['CIA_max']==0)
                   {
                        $body_2 .=" 
                        <table style='line-height: 1.0em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >".strtoupper($value['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5 > <b>COMPLETED</b> </td>
                            </tr>
                        </table>";
                    $total_sub_count++;
                   }
               else{
                        $body_2 .=" 
                        <table style='line-height: 1.0em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >".strtoupper($value['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$value['credit_points']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_point']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".$year_of_passing."</td>
                            </tr>
                        </table>    
                    ";
                    $total_sub_count++;
               }
                
            }
            else if($value['semester']>4 && $value['status_category_type_id']!=$lateral_entry_id)
            {
                if($same_semester!=$value['semester'])
                {   
                    if($same_semester==4)
                    {
                        $exam_year = $value['year'];
                        $exam_month = $value['month'];          
                        $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$value['student_map_id'].'" and result like "%pass%" and exam_year="'.$exam_year.'" and exam_month="'.$exam_month.'" ')->queryAll();

                        if(!empty($check_add))
                        {
                            $body_2 .= "
                            <table border='0' width='100%' >
                                <tr>
                                    <td width='60px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='78px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='450px' colspan='6'  align='left'><b>ADDITIONAL CREDIT COURSE(S)*</b></td>
                                    <td width='67px' colspan=3 align='left'>&nbsp;</td>
                                </tr>
                            </table>";
                            foreach ($check_add as $valuess) 
                            {
                                $additional_creditsPrinte=1;
                                $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                                $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                                
                                $sub_na = strtoupper($valuess['subject_name']);
                                $subject_name_print = $sub_na;
                                $semester_number = ConfigUtilities::semCaluclation($exam_year,$exam_month,$batch_mapping_id);
                                $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);
                                $man_year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
                                 $body_2 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
                                            <tr>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$semester_number]."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='100px' >".strtoupper($valuess['subject_code'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >". trim($subject_name_print)."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$valuess['credits']."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_name'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_point'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)."</td>
                                            </tr>
                                            </table>";
                                $total_sub_count++;
                            }
                           
                        }
                    }
                    else
                    {
                         $exam_year = $value['year'];
                        $exam_month = $value['month'];          
                        $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$value['student_map_id'].'" and result like "%pass%" and exam_year="'.$exam_year.'" and exam_month="'.$exam_month.'" ')->queryAll();

                        if(!empty($check_add))
                        {
                            $body_2 .= "
                            <table border='0' width='100%' >
                                <tr>
                                    <td width='60px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='78px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='450px' colspan='6'  align='left'><b>ADDITIONAL CREDIT COURSE(S)*</b></td>
                                    <td width='67px' colspan=3 align='left'>&nbsp;</td>
                                </tr>
                            </table>";
                            foreach ($check_add as $valuess) 
                            {
                                $additional_creditsPrinte=1;
                                $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                                $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                                
                               $sub_na = strtoupper($valuess['subject_name']);
                                $subject_name_print = $sub_na;
                                $semester_number = ConfigUtilities::semCaluclation($exam_year,$exam_month,$batch_mapping_id);
                                $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);
                                $man_year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
                                $body_2 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
                                            <tr>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$semester_number]."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='100px' >".strtoupper($valuess['subject_code'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >". trim($subject_name_print)."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$valuess['credits']."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_name'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_point'])."</td>
                                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)."</td>
                                            </tr>
                                            </table>";
                                $total_sub_count++;
                            }
                           
                        }
                         $body_2 .=" 
                            <table style='line-height: 0.6em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' colspan=13>&nbsp; </td>
                                </tr>
                          </table>";
                    }
                    $same_semester=$value['semester'];
                    $total_sub_count++;
                }
                if($value['ESE_max']==0 && $value['CIA_max']==0)
                   {
                        $body_2 .=" 
                        <table style='line-height: 1.0em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >".strtoupper($value['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5 > <b>COMPLETED</b> </td>
                            </tr>
                        </table>";
                    $total_sub_count++;
                   }
               else{
                        $body_2 .=" 
                        <table style='line-height: 1.0em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >".strtoupper($value['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$value['credit_points']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_point']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".$year_of_passing."</td>
                            </tr>
                        </table>    
                    ";
                    $total_sub_count++;
               }
                
            }  // If Subjects morethan 40
            else{
                if($same_semester!=$value['semester'])
                {   
                    $exam_year = $value['year'];
                    $exam_month = $value['month'];          
                    $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$value['student_map_id'].'" and result like "%pass%" and exam_year="'.$exam_year.'" and exam_month="'.$exam_month.'" ')->queryAll();

                    if(!empty($check_add))
                    {
                        $body_1 .= "
                            <table border='0' width='100%' >
                                <tr>
                                    <td width='60px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='78px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='450px' colspan='6'  align='left'><b>ADDITIONAL CREDIT COURSE(S)*</b></td>
                                    <td width='67px' colspan=3 align='left'>&nbsp;</td>
                                </tr>
                            </table>";
                        foreach ($check_add as $valuess) 
                        {
                            $additional_creditsPrinte=1;
                            $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                            $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                            
                            $sub_na = strtoupper($valuess['subject_name']);
                                $subject_name_print = $sub_na;

                            $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);
                            $semester_number = ConfigUtilities::semCaluclation($exam_year,$exam_month,$batch_mapping_id);
                            $man_year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
                             $body_1 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
                                        <tr>
                                            <td valign='top'  class='line_height subjects_tables' width='60px' >".$semester_array[$semester_number]."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='100px' >".strtoupper($valuess['subject_code'])."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >". trim($subject_name_print)."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' >".$valuess['credits']."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_name'])."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_point'])."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)."</td>
                                        </tr>
                                        </table>";
                            $total_sub_count++;
                        }
                       
                    }

                    $body_1 .=" <table style='line-height: 0.6em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' colspan=13>&nbsp; </td>
                                </tr>
                            </table> ";
                    $same_semester=$value['semester'];
                    $total_sub_count++;

                }
                   if($value['ESE_max']==0 && $value['CIA_max']==0)
                   {
                        $body_1 .=" 
                        <table style='line-height: 1.0em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='60px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >".strtoupper($value['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5 > <b>COMPLETED</b> </td>
                            </tr>
                        </table>    
                    ";
                    $total_sub_count++;
                   }
                   else{
                        $body_1 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='60px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='100px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >".strtoupper($value['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$value['credit_points']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_point']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".$year_of_passing."</td>
                            </tr>
                        </table>";
                        $total_sub_count++;
                   }   
                   
               }   
                $cgpa_calculation = ConfigUtilities::getCgpaCaluclation($value['year'],$value['month'],$value['course_batch_mapping_id'],$value['student_map_id']);
            }
            $previous_subject_code = $value['subject_code'];
            $previous_reg_number=$value['register_number'];
            $prev_stu_map_id =$value['student_map_id']; 
            $last_num_year = $value['year'];
            $last_num_month = $value['month'];
            $last_num_bat = $value['course_batch_mapping_id'];
            $last_num_stu = $value['student_map_id'];

        }
            $cgpa_calculation = ConfigUtilities::getCgpaCaluclation($last_num_year,$last_num_month,$last_num_bat,$last_num_stu);

            $semester_number = ConfigUtilities::semCaluclation($exam_year,$app_month,$batch_mapping_id);
            $course_batch_mapping_id =  CoeBatDegReg::findOne($batch_mapping_id);
            $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
            $total_semesters = $degree_years->degree_total_semesters;

            $colspan_merge = (26-$total_semesters);  
            $exam_year = $value['year'];
            $exam_month = $value['month'];          
            $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$prev_stu_map_id.'" and result like "%pass%" and exam_year="'.$exam_year.'" and exam_month="'.$exam_month.'" ')->queryAll();

            if(!empty($check_add))
            {
                $body_2 .= "<table border='0' width='100%' >
                                <tr>
                                    <td width='60px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='78px' colspan=2 align='left'>&nbsp;</td>
                                    <td width='450px' colspan='6'  align='left'><b>ADDITIONAL CREDIT COURSE(S)*</b></td>
                                    <td width='67px' colspan=3 align='left'>&nbsp;</td>
                                </tr>
                            </table>";
                foreach ($check_add as $valuess) 
                {
                    $additional_creditsPrinte=1;
                    $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
                    $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];
                    
                   $sub_na = strtoupper($valuess['subject_name']);
                    $subject_name_print = $sub_na;

                    $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);
                    $semester_number = ConfigUtilities::semCaluclation($exam_year,$exam_month,$batch_mapping_id);
                    $man_year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
                     $body_2 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".$semester_array[$semester_number]."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='100px' >".strtoupper($valuess['subject_code'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='665px' colspan=6 >". trim($subject_name_print)."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".$valuess['credits']."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($valuess['grade_point'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)."</td>
                                </tr>
                                </table>";
                    $total_sub_count++;
                }
               
            }
            if($additional_creditsPrinte==1)
            {
                $add_body_1 .= "
                <table border=0  >
                    <tr>
                        <td valign='top'  colspan='4'  width='180px' align='left'>&nbsp;</td>
                        <td valign='top'  class='make_bold_font'  width='600px' colspan='5'  align='left' > *ADDITIONAL CREDITS WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                        <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;</td>
                    </tr>
                </table>";
            }
            $add_body_1 .= "
                <table border=0   >
                    <tr>
                        <td valign='top'  colspan='4'  width='75px' align='left'>&nbsp;</td>
                        <td valign='top'  class='make_bold_font'  width='600px' colspan='5'  align='center'> ~ END OF STATEMENT ~ </td>                            
                        <td valign='top'  colspan='4' width='170px'  align='right'>&nbsp;</td>
                    </tr>
                </table>";
            $total_sub_count++;
            
            $merge_two_body_tags = $body_1.'</td>'.$body_2.$add_body_1."</td>";
            $body = $merge_two_body_tags;
            $merge_body ="<tbody><tr>".$body."</tr></tbody><tr><td valign='top'  colspan=26 height='63px' >&nbsp;</td></tr>";

            $gpa = 0;
            $total_credits_cgpa=0;
            
            $final_cgpa = $cgpa_calculation['final_cgpa'];
            $total_credits_cgpa = $cgpa_calculation['cumulative_earned_credits'];
            $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
            $cumulative_row ="                        
                        <tr>
                            <td valign='top'  colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top'  class='line_height print_cgpa_size makeitBigger' colspan=5 width='400px'  > ".$total_credits_cgpa." </td>
                            
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger PRINT_finalfinal_cgpa' colspan=8 width='100px;'  >".$final_cgpa."</td>
                            <td valign='top' colspan=10  >&nbsp;</td>                                       
                        </tr>
                        
                        <tr>
                            <td valign='top' colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top' height='65px'  class='line_height print_cgpa_size print_class makeitBigger' colspan=5 width='400px'  > ".$classification." </td>
                            
                            <td valign='top' colspan=8 width='100px;'  >&nbsp;</td>
                            <td valign='top' colspan=10  >&nbsp;</td> 

                        </tr>                       
                        <tr>     
                            <td valign='top'  colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top'  class='line_height print_cgpa_size makeitBigger' colspan=5 width='400px'  >".$publish_date." </td>
                            
                            <td valign='top' colspan=8 width='100px;'  >&nbsp;</td>
                            <td valign='top' colspan=10 >&nbsp;</td>

                        </tr>";
            
            $footer .=$cumulative_row."</table>";
            $html = $header .$merge_body.$footer;
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