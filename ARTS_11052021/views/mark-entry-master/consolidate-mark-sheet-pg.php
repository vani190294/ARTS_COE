<?php 
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\Subjects;
use app\models\ConsolidateMarks;
use app\models\CoeBatDegReg;
use app\models\Batch;
use app\models\Degree;
use app\models\Programme;
$updated_by = Yii::$app->user->getId();
$this->title = Yii::t('app', 'Consolidate Mark Statement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Marks'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->ShowFlashMessages->showFlashes(); 

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$removed_path = str_replace('index.php', '', parse_url($url, PHP_URL_PATH));
$image_path = parse_url($url, PHP_URL_SCHEME)."://".parse_url($url, PHP_URL_HOST).":".parse_url($url, PHP_URL_PORT).$removed_path.'css/consolidate-markstatement-arts-pg.css'; 
$subject_array_ncc = ['18NCC01','18NCC01','18NCC02','18NCC02','18NCC03','18NCC03','18NCC04','18NCC04','18CSS28','18BTU17','18CDU35','18CUG62','18ENU34','18MAU33','18MBU17','18MSU34','18NCC18','18BSU20','18HMU29','18NCC21','18SWP33'];
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

             $html = $body_1 = $body_2 = "";
            $cumulative_part = '';
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
            $total_sub_count = 0;
            $first_reg_no = 0;
            $cgpa_calc = [];
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
             $term='';
            $date_print = isset($date_print) ? date('d-m-Y',strtotime($date_print)) : date('d-m-Y');
            $date = "<b>".$date_print."</b>";
        $date_insert = "<tr><td  align='left' valign='bottom' class='date_style' colspan='11' width='300px' style='font-size: 15px;' >".$date."</td></tr> ";

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
            $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII']; 

            foreach ($get_console_list as  $value) 
            {
                    
                if($previous_reg_number!=$value['register_number'])
                {                    
                    $new_stu_flag=$new_stu_flag + 1;

                    if($new_stu_flag > 1) 
                    {
                        $getadditionalPrint = $total_sub_count = 0;
                        $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE  student_map_id="'.$print_student_map_id.'" and result like "%pass%" ')->queryAll();
                        if(!empty($check_add))
                        {
                            $body_1 .="<table style='line-height: 1.5em; ' border='0' width='100%' >
                                    <tr>
                                              <td valign='top' width='50px' align='left'>&nbsp;</td>
                                              <td valign='top' width='50px' align='left'>&nbsp;</td>
                                              <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                              <td valign='top' width='280px' colspan='4'  align='left'>&nbsp;</td>
                                              <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                              <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                              <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                              <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                              <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                              <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                              <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                              <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                              <td width='95px'  align='left'>&nbsp;</td>
                                          </tr>
                                    </table>";
                          $body_1 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
                                  <tr>
                                            <td valign='top' width='50px' align='left'>&nbsp;</td>
                                            <td valign='top' width='50px' align='left'>&nbsp;</td>
                                            <td valign='top' width='95px'  align='left'>&nbsp;</td>
                                            <td valign='top' width='280px' colspan='4'  align='left'><b>#ADDITIONAL CREDIT COURSES#</b></td>                          
                                            <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                            <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                            <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                            <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                            <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                            <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                            <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                            <td valign='top' width='50px'  align='left'>&nbsp;</td>
                                            <td width='95px'  align='left'>&nbsp;</td>
                                        </tr>
                                  </table>";

                            foreach ($check_add as $valuess) 
                            {
                                  $subject_code_print = strtoupper($valuess['subject_code']);
                                  $year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);

                                 // $sub_na = wordwrap(strtoupper(mb_convert_encoding($valuess['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
                                 // $sub_na = htmlentities($sub_na);
                                 // $subject_name_print = "<b>".strtoupper($sub_na)."</b>";
                                   $str = (strtoupper($valuess['subject_name']));
                                   $subject_name_print= wordwrap($str,48,"<br>\n");
                                  $credit_points = isset($valuess['credits']) && !empty($valuess['credits'])?$valuess['credits']:"--";
                                  $ese_max_disp = ($valuess['ese_maximum']==0 || $valuess['ese_maximum']==''  || $valuess['ese_maximum']==NULL  || $valuess['ese_maximum']==null) ? '--':$valuess['ese_maximum'];
                                  $cia_max_disp = ($valuess['cia_maximum']==0 || $valuess['cia_maximum']==''  || $valuess['cia_maximum']==NULL  || $valuess['cia_maximum']==null) ? '--':$valuess['cia_maximum'];
                                  $total_max_disp = $cia_max_disp=='--' ?$ese_max_disp: ($ese_max_disp=='--'?$cia_max_disp:($cia_max_disp+$ese_max_disp));

                                  $disp_ese = ($valuess['ESE']==0 || $valuess['ESE']=="") ? '--':$valuess['total'];
                                  $disp_cia = ($valuess['CIA']==0 || $valuess['CIA']=="") ? '--':$valuess['total'];

                                  $stu_total = $disp_cia=='--' ?$disp_ese: ($disp_ese=='--'?$disp_cia:($disp_cia+$disp_ese));

                                  $total_disp_res = ($valuess['total']==0 || $valuess['total']=="") ? '--':$stu_total;
                                  $result_stu = strtoupper($valuess['result']);

                                   $grade_point_print = strlen($valuess['grade_point'])==2 ?$valuess['grade_point'].".0":(strlen($valuess['grade_point'])==1 ?$valuess['grade_point'].".0":$valuess['grade_point']);

                                  if($valuess['ese_maximum']!=NULL && !empty($valuess['ese_maximum']))
                                   {
                                      $ese_max_disp = $valuess['ese_maximum']=100;      
                                   }
                                 else
                                   {
                                     $ese_max_disp = '--';                       
                                   }                   
                                  $cia_max_disp = $valuess['cia_maximum']==100 ? 100:'--';
                $total_max_disp=100;
              $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);
              $body_1 .=" 
                <table style='line-height: 1.0em; ' class='subjects_tables' border='0' width='100%' >
                   <tr>
                        
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$valuess['semester']]. "</b></td>
                        <td valign='top' width='90px' class='subject_code' align='center'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                        <td valign='top'  class='subject_name' width='280px' colspan='4'  align='left'><b>" . $subject_name_print . "</b></td>
                        <td valign='top' width='45px' class='credit_points' align='center'><b>" . $credit_points . "</b></td>
                        <td valign='top' width='45px' class='ese_max_disp' align='center'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='50px' class='cia_max_disp' align='center'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='50px' class='total_max_disp' align='center'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='50px' class='disp_ese' align='center'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='53px' class='disp_cia' align='center'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='53px' class='total_disp_res' align='center'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px'  align='center' class='grade_point' ><b>" . $grade_point_print  . "</b></td>
                        <td valign='top' width='50px'  align='center' class='grade_name'><b> " . $valuess['grade_name'] . " </b></td>
                        <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
                $total_sub_count++;
        } 
    }
    $body_1 .="<table style='line-height: 1em; ' class='subjects_tables' border='0' width='100%' >
      <tr>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='95px'  align='left'>&nbsp;</td>
                <td valign='top' width='380px' colspan='4'  align='center'><b>~ END OF STATEMENT ~</b></td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td width='95px'  align='left'>&nbsp;</td>
            </tr>
      </table>";
     /* $body_1 .="<table style='line-height: 1em; ' class='subjects_tables' border='0' width='100%' >
      <tr>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='95px'  align='left'>&nbsp;</td>
                <td valign='top' width='380px' colspan='4'  align='center'><b> **IV Semester Examinations held in September-2020 & Supplementary & Arrear Examinations held in December-2020** </b></td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td width='95px'  align='left'>&nbsp;</td>
            </tr>
      </table>";*/

       $body_1 .="<table style='line-height: 1em; ' class='subjects_tables' border='0' width='100%' >
      <tr>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='95px'  align='left'>&nbsp;</td>
                <td valign='top' width='380px' colspan='4'  align='center'><b> **IV Semester Examinations held in September-2020 & Supplementary** </b></td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td width='95px'  align='left'>&nbsp;</td>
            </tr>
      </table>";



                        $change_css_class = $total_sub_count==1 ? 'change_cumulative_1' : ($total_sub_count==2 ? 'change_cumulative' : ($total_sub_count==3 ? 'change_cumulative_3' :  'change_cumulative_normal' ));

                    
                        $cumulative_part .="<table style='line-height: 1.0em; ' border='0' width='100%' ><tr>
                        <td valign='top'  class='".$change_css_class."' colspan=16 width='100px;' > &nbsp; </td></tr></table>";
                        $part_info = ConfigUtilities::getPartDetails($previous_reg_number,3);
                        $cahnge_width = strlen($part_info['part_class'])>=13?'180px':'325px';
                      $cumulative_part .="<table style='line-height: 1.0em;' class='makeitBigger' border='0' width='100%' > 
                      <tr>
                        <td valign='top'  class='line_height_print_cgpa_size' colspan=2  align='center' width='90px;' > ".$part_info['part_credits']." </td>
                        <td valign='top'  align='center' class='line_height_print_cgpa_size print_class' colspan=6 width='".$cahnge_width."'  > ".$part_info['part_class']." </td>
                        <td valign='top'  class='line_height_print_cgpa_size' colspan=2  width='80px;' > ".$part_info['part_percentage']." </td>
                        <td valign='top'  class='line_height_print_cgpa_size' colspan=2  width='70px;' > ".$part_info['part_cgpa']." </td>
                        <td valign='top'  class='line_height_print_cgpa_size' colspan=2  width='80px;' > ".$part_info['part_grade_point']."  </td>
                        <td valign='top'   class='line_height_print_cgpa_size' colspan=2 width='80px;'  >".$part_info['part_additional_cred']." </td>                                                             
                      </tr>
                      </table>";
                      $checkData = ConsolidateMarks::find()->where(['student_map_id'=>$print_student_map_id,'part_no'=>3])->all();
                      if(empty($checkData))
                       {
                        $updated_at = date("Y-m-d H:i:s");
                          $ins_cons = new ConsolidateMarks();
                          $ins_cons->batch_maping_id = $batch_maping_id;
                          $ins_cons->student_map_id = $print_student_map_id;
                          $ins_cons->part_no = 3;
                          $ins_cons->part_credits = $part_info['part_credits'];
                          $ins_cons->marks_gain = $part_info['part_marks'];
                          $ins_cons->marks_total = $part_info['part_total_marks'];
                          $ins_cons->percentage = $part_info['part_percentage'];
                          $ins_cons->cgpa = $part_info['part_cgpa'];
                          $ins_cons->grade = $part_info['part_grade_point'];
                          $ins_cons->classification = $part_info['part_class'];
                          $ins_cons->part_add_credits = $part_info['part_additional_cred'];
                          $ins_cons->created_at = $updated_at;
                          $ins_cons->created_by = $updated_by;
                          $ins_cons->updated_at = $updated_at;
                          $ins_cons->updated_by = $updated_by;
                          $ins_cons->save(false);
                          unset($ins_cons);
                       }
                        $merge_two_body_tags = "<tr>".$body_1.'</td></tr><tr><td valign="top" class="line_height subjects_tables" height="80px" colspan="16" >'.$cumulative_part."</td></tr>";
                        $body = $merge_two_body_tags;
                       // print_r($cumulative_part);exit;
                        $merge_body ="<tbody><tr>".$body."</tr></tbody>"; // For
                        $footer .="</table>";
                        $html = $header . $merge_body .  $footer;
                        //print_r($html);exit;


                        $print_stu_data .= $html."<pagebreak sheet-size='A4-P' >";
                        
                        $header = $body = $body_1 = $html = $cumulative_part = $footer = $add_body_1 = $body_2 = "";
                        $new_stu_flag = 1; $total_sub_count= 0;
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
                    $add_month = $value['month'];
                    $same_semester=$value['semester'];
                    $print_gender = ($value['gender']=='F' || $value['gender']=='FEMALE') ?'FEMALE':'MALE';
                    $exam_year=$value['year'];
                    $app_month = strtoupper($value['month']);
                    $last_appearance = ConfigUtilities::getLastYearOfPassing($value['register_number']);
                    $dob= date('d/m/Y',strtotime($value['dob']));
                    $dateofPUB_print= date('d/m/Y',strtotime($date_print));
                    $batch_maping_id = $value['batch_mapping_id'];
                    $photo_extension = ConfigUtilities::match($supported_extensions,$value['register_number'].$extension);
                
                    $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg"; 

                    $header .="<table width='100%'  autosize='1' >";
                    $header.='<tr>
                        <td valign="top" colspan=12 style="padding-top: 20px !important;" width="360px" > &nbsp; </td>
                        <td valign="top" colspan=3 style="padding-top: 20px !important;" align="left" >  
                            <img  class"img_print_dat" style="margin-left: -35px; padding-right: 30px;" width="130px" height="130px" src='.$stu_photo.' alt='.$stu_photo.' Photo >
                        </td>
                        <td valign="top" style="padding-top: 20px !important;" align="left" >  
                            &nbsp;
                        </td>
                    </tr>';
                    $header.='<tr><td valign="top"  colspan="16" height="50px" >&nbsp;</td></tr>';
                    $header.='<tr>';

                    $app_month_name = ConfigUtilities::getMonthName($value['month']);
                    $batch_mapping_id=$value['course_batch_mapping_id'];

                    $last_appearance = $last_appearance = ConfigUtilities::getLastYearOfPassing($value['register_number']) ;
                    $CoeBatDegReg = CoeBatDegReg::findOne($batch_mapping_id);
                    $getBatchDetails = Batch::findOne($CoeBatDegReg['coe_batch_id']);
                    $getDegree = Degree::findOne($CoeBatDegReg['coe_degree_id']);
                    $month_explode = explode("-", $last_appearance);
                    $ending_batch = strtoupper($month_explode[1]);

                    $dob= date('d/m/Y',strtotime($value['dob']));
                    if($value['degree_code']=="MBA" || $value['degree_code']=="mba" || $value['degree_code']=="Mba")
                    {
                        $deg =strtoupper($value['programme_name']);
                    }
                    else
                    {
                        $deg =strtoupper($value['programme_name']);
                    }
                    $header.='<td valign="top"  height="160px" colspan="16">'; //first td
                     $header.='<table class="makeitBigger" width="100%">
                                <tr>
                                    <td valign="top"  align="left" width="15px"  >&nbsp;</td>
                                    <td valign="top" class="student_name" colspan=2 align="left" width="100" >'.strtoupper($value['degree_code']).'  </td>
                                    <td valign="top" class="student_name" colspan=7 align="center" width="350" >'.strtoupper($deg).'</td>
                                    <td valign="top" class="student_name" colspan=3 align="left" width="100" >'.strtoupper($dateofPUB_print).'</td>
                                    <td valign="top" class="student_name"colspan=3 align="left" width="100" >'.$getBatchDetails['batch_name'].'-'.($getBatchDetails['batch_name']+$getDegree['degree_total_years']).'</td>
                                </tr>
                                <tr><td valign="top"  colspan="16" height="40px" >&nbsp;</td></tr>
                                <tr>
                                    <td valign="top" class="student_name"  colspan="10">'.strtoupper($value["name"]).'</td>
                                    <td valign="top" class="student_name" colspan=3 align="left" width="100" >'.$dob.'</td>
                                    <td valign="top" class="student_name" colspan=3 align="left" width="100" >'.strtoupper($value["register_number"]).'</td>
                                </tr>
                               </table>
                               </td>';
                    $header.='</tr><tr><td valign="top"  colspan="16" height="50px" >&nbsp;</td></tr>'; //main tr
                    
         $total_credits ='';
         $total_earned_credits ='';
         $passed_grade_points ='';
         $height_adj = isset($_POST['bottom_margin']) && $_POST['bottom_margin']>=0?500+$_POST['bottom_margin']:($_POST['bottom_margin']<=0?500+$_POST['bottom_margin']:500);
         $body_1 .= "<td valign='top' class='class_height' valign='top'  colspan='16' >";

         $subject_code_print = strtoupper($value['subject_code']);
         $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
        // $sub_na = wordwrap(strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
         //$sub_na = htmlentities($sub_na);
         // $subject_name_print = "<b>".strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8"))."</b>";
          $str = (strtoupper($value['subject_name']));
                $subject_name_print= wordwrap($str,48,"<br>\n");
          $credit_points = isset($value['credit_points']) && !empty($value['credit_points'])?$value['credit_points']:"--";
          $ese_max_disp = ($value['ESE_max']==0 || $value['ESE_max']==''  || $value['ESE_max']==NULL  || $value['ESE_max']==null) ? '--':$value['ESE_max'];
          $cia_max_disp = ($value['CIA_max']==0 || $value['CIA_max']==''  || $value['CIA_max']==NULL  || $value['CIA_max']==null) ? '--':$value['CIA_max'];
          $total_max_disp = $cia_max_disp=='--' ?$ese_max_disp: ($ese_max_disp=='--'?$cia_max_disp:($cia_max_disp+$ese_max_disp));
          $disp_ese = ($value['ESE']==0 || $value['ESE']=="") ? '--':$value['ESE'];
          $disp_cia = ($value['CIA']==0 || $value['CIA']=="") ? '--':$value['CIA'];
          $stu_total = $disp_cia=='--' ?$disp_ese: ($disp_ese=='--'?$disp_cia:($disp_cia+$disp_ese));
          $total_disp_res = ($value['total']==0 || $value['total']=="") ? '--':$stu_total;
            $grade_point_print = strlen($value['grade_point'])==2 ?$value['grade_point'].".0":(strlen($value['grade_point'])==1 ?$value['grade_point'].".0":$value['grade_point']);


          if($value['CIA_max'] == 0 && $value['ESE_max'] == 0)
            {
                $RESULT_UPPER = strtoupper($value['result']);
                $body_1 .=" 
                <table style='line-height: 1.0em; ' border='0' width='100%'  >
                    <tr>
                      <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                      <td valign='top' width='90px' class='subject_code' align='center'><b>" . strtoupper($value['subject_code']) . "</b></td>
                      <td valign='top' class='subject_name' width='280px' colspan='4'  align='left'><b>" . $subject_name_print . "</b></td>
                      
                      <td valign='top' width='350px' colspan=9  align='center'><b>".$RESULT_UPPER."</b></td>
                      <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>
                  </tr>
                </table>    
            ";
            $credit_point = $credit_point = 0;
            $total_sub_count++;
           } // ZERO ZERO SUBJECTS
           else
           {
                $body_1 .=" 
                <table style='line-height: 1.0em; ' border='0' width='100%' >
                    <tr>                       
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='90px' class='subject_code' align='center'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' class='subject_name' width='280px' colspan='4'  align='left'><b>" . $subject_name_print . "</b></td>
                         <td valign='top' width='45px' class='credit_points' align='center'><b>" . $credit_points . "</b></td>
                        <td valign='top' width='45px' class='ese_max_disp' align='center'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='50px' class='cia_max_disp'  align='center'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='50px' class='total_max_disp' align='center'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='50px' class='disp_ese' align='center'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='53px' class='disp_cia' align='center'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='53px' class='total_disp_res'  align='center'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px'  align='center' class='grade_point'><b>" . $grade_point_print  . "</b></td>
                        <td valign='top' width='50px'  align='center' class='grade_name'><b> " . $value['grade_name'] . " </b></td>
                        <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
                $total_sub_count++;
           }
          $cgpa_calculation = ConfigUtilities::getCgpaCaluclation($value['year'],$value['month'],$value['batch_mapping_id'],$value['student_map_id'],$value['term']);
            $previous_subject_code = $value['subject_code'];
            $previous_reg_number=$value['register_number'];
        // Closing the Main Header Table
                
    }
    else{
         $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);  
         $subject_code_print = strtoupper($value['subject_code']);
         $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
         //$sub_na = wordwrap(strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
         //$sub_na = htmlentities($sub_na);
        //$subject_name_print = "<b>".strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8"))."</b>";
          $str = (strtoupper($value['subject_name']));
          $subject_name_print= wordwrap($str,48,"<br>\n");
          $credit_points = isset($value['credit_points']) && !empty($value['credit_points'])?$value['credit_points']:"--";
          $ese_max_disp = ($value['ESE_max']==0 || $value['ESE_max']==''  || $value['ESE_max']==NULL  || $value['ESE_max']==null) ? '--':$value['ESE_max'];
          $cia_max_disp = ($value['CIA_max']==0 || $value['CIA_max']==''  || $value['CIA_max']==NULL  || $value['CIA_max']==null) ? '--':$value['CIA_max'];
          $total_max_disp = $cia_max_disp=='--' ?$ese_max_disp: ($ese_max_disp=='--'?$cia_max_disp:($cia_max_disp+$ese_max_disp));
          $disp_ese = ($value['ESE']==0 || $value['ESE']=="") ? '--':$value['ESE'];
          $disp_cia = ($value['CIA']==0 || $value['CIA']=="") ? '--':$value['CIA'];
          $stu_total = $disp_cia=='--' ?$disp_ese: ($disp_ese=='--'?$disp_cia:($disp_cia+$disp_ese));
          $total_disp_res = ($value['total']==0 || $value['total']=="") ? '--':$stu_total;
           $grade_point_print = strlen($value['grade_point'])==2 ?$value['grade_point'].".0":(strlen($value['grade_point'])==1 ?$value['grade_point'].".0":$value['grade_point']);

         if($same_semester!=$value['semester'])
            {   
                $body_1 .=" 
                        <table style='line-height: 1.0em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' colspan=16>&nbsp; </td>
                            </tr>
                        </table>    
                    ";
                $same_semester=$value['semester'];
            }
         if($value['CIA_max'] == 0 && $value['ESE_max'] == 0)
            {
                $RESULT_UPPER = strtoupper($value['result']);
                $body_1 .=" 
                <table style='line-height: 1.0em; ' border='0' width='100%'  >
                    <tr>
                      <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                      <td valign='top' width='90px' class='subject_code' align='center'><b>" . strtoupper($value['subject_code']) . "</b></td>
                      <td valign='top' class='subject_name' width='280px' colspan='4'  align='left'><b>" . $subject_name_print . "</b></td>
                      
                      <td valign='top' width='350px' colspan=9  align='center'><b>".$RESULT_UPPER."</b></td>
                      <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>
                  </tr>
                </table>    
            ";
            $credit_point = $credit_point = 0;
            $total_sub_count++;
           } // ZERO ZERO SUBJECTS
           else
           {
                $body_1 .=" 
                <table style='line-height: 1.0em; ' border='0' width='100%' >
                    <tr>                       
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='90px' class='subject_code' align='center'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' class='subject_name' width='280px' colspan='4'  align='left'><b>" . $subject_name_print . "</b></td>
                         <td valign='top' width='45px' class='credit_points' align='center'><b>" . $credit_points . "</b></td>
                        <td valign='top' width='45px'  class='ese_max_disp' align='center'><b>" . $ese_max_disp . "</b></td>
                      <td valign='top' width='50px' class='cia_max_disp'  align='center'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='50px' class='total_max_disp' align='center'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='50px' class='disp_ese' align='center'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='53px' class='disp_cia' align='center'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='53px' class='total_disp_res' align='center'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px'  align='center' class='grade_point'><b>" . $grade_point_print . "</b></td>
                        <td valign='top' width='50px'  align='center' class='grade_name'><b> " . $value['grade_name'] . " </b></td>
                        <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
                $total_sub_count++;
           }
           $cgpa_calculation = ConfigUtilities::getCgpaCaluclation($value['year'],$value['month'],$value['batch_mapping_id'],$value['student_map_id'],$value['term']);
    } // Else Condition Ends Here 
    $previous_subject_code = $value['subject_code'];
    $previous_reg_number=$value['register_number']; 
    $print_student_map_id = $value['student_map_id'];
    $semester_last_print = $value['semester'];

}// End the foreach variable here 
            
    $getadditionalPrint = $total_sub_count = 0;
    $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE  student_map_id="'.$print_student_map_id.'" and result like "%pass%" ')->queryAll();
    if(!empty($check_add))
    {
        $body_1 .="<table style='line-height: 1.5em; ' border='0' width='100%' >
                <tr>
                          <td valign='top' width='50px' align='left'>&nbsp;</td>
                          <td valign='top' width='50px' align='left'>&nbsp;</td>
                          <td valign='top' width='50px'  align='left'>&nbsp;</td>
                          <td valign='top' width='280px' colspan='4'  align='left'>&nbsp;</td>
                          <td valign='top' width='50px'  align='left'>&nbsp;</td>
                          <td valign='top' width='50px'  align='left'>&nbsp;</td>
                          <td valign='top' width='50px'  align='left'>&nbsp;</td>
                          <td valign='top' width='50px'  align='left'>&nbsp;</td>
                          <td valign='top' width='50px'  align='left'>&nbsp;</td>
                          <td valign='top' width='50px'  align='left'>&nbsp;</td>
                          <td valign='top' width='50px'  align='left'>&nbsp;</td>
                          <td valign='top' width='50px'  align='left'>&nbsp;</td>
                          <td width='95px'  align='left'>&nbsp;</td>
                      </tr>
                </table>";
      $body_1 .="<table style='line-height: 1.0em; ' border='0' width='100%' >
              <tr>
                        <td valign='top' width='50px' align='left'>&nbsp;</td>
                        <td valign='top' width='50px' align='left'>&nbsp;</td>
                        <td valign='top' width='95px'  align='left'>&nbsp;</td>
                        <td valign='top' width='280px' colspan='4'  align='left'><b>#ADDITIONAL CREDIT COURSES#</b></td>                          
                        <td valign='top' width='50px'  align='left'>&nbsp;</td>
                        <td valign='top' width='50px'  align='left'>&nbsp;</td>
                        <td valign='top' width='50px'  align='left'>&nbsp;</td>
                        <td valign='top' width='50px'  align='left'>&nbsp;</td>
                        <td valign='top' width='50px'  align='left'>&nbsp;</td>
                        <td valign='top' width='50px'  align='left'>&nbsp;</td>
                        <td valign='top' width='50px'  align='left'>&nbsp;</td>
                        <td valign='top' width='50px'  align='left'>&nbsp;</td>
                        <td width='95px'  align='left'>&nbsp;</td>
                    </tr>
              </table>";

        foreach ($check_add as $valuess) 
        {
              $subject_code_print = strtoupper($valuess['subject_code']);
              $year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
             // $sub_na = wordwrap(strtoupper(mb_convert_encoding($valuess['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
             // $sub_na = htmlentities($sub_na);
             // $subject_name_print = "<b>".strtoupper($sub_na)."</b>";
               $str = (strtoupper($valuess['subject_name']));
                $subject_name_print= wordwrap($str,48,"<br>\n");
              $credit_points = isset($valuess['credits']) && !empty($valuess['credits'])?$valuess['credits']:"--";
              $ese_max_disp = ($valuess['ese_maximum']==0 || $valuess['ese_maximum']==''  || $valuess['ese_maximum']==NULL  || $valuess['ese_maximum']==null) ? '--':$valuess['ese_maximum'];
              $cia_max_disp = ($valuess['cia_maximum']==0 || $valuess['cia_maximum']==''  || $valuess['cia_maximum']==NULL  || $valuess['cia_maximum']==null) ? '--':$valuess['cia_maximum'];
              $total_max_disp = $cia_max_disp=='--' ?$ese_max_disp: ($ese_max_disp=='--'?$cia_max_disp:($cia_max_disp+$ese_max_disp));

              $disp_ese = ($valuess['ESE']==0 || $valuess['ESE']=="") ? '--':$valuess['total'];
              $disp_cia = ($valuess['CIA']==0 || $valuess['CIA']=="") ? '--':$valuess['total'];

              $stu_total = $disp_cia=='--' ?$disp_ese: ($disp_ese=='--'?$disp_cia:($disp_cia+$disp_ese));

              $total_disp_res = ($valuess['total']==0 || $valuess['total']=="") ? '--':$stu_total;

              $result_stu = strtoupper($valuess['result']);

               $grade_point_print = strlen($valuess['grade_point'])==2 ?$valuess['grade_point'].".0":(strlen($valuess['grade_point'])==1 ?$valuess['grade_point'].".0":$valuess['grade_point']);
              
              if($valuess['ese_maximum']!=NULL && !empty($valuess['ese_maximum']))
               {
                  $ese_max_disp = $valuess['ese_maximum']=100;      
               }
             else
               {
                 $ese_max_disp = '--';                       
               }                   
                $cia_max_disp = $valuess['cia_maximum']==100 ? 100:'--';
                $total_max_disp=100;
              $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);
              $body_1 .=" 
                <table style='line-height: 1.0em; ' class='subjects_tables' border='0' width='100%' >
                    <tr>
                        
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$valuess['semester']]. "</b></td>
                        <td valign='top' width='90px' class='subject_code' align='center'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                        <td valign='top'  class='subject_name' width='280px' colspan='4'  align='left'><b>" . $subject_name_print . "</b></td>
                        <td valign='top' width='45px' class='credit_points' align='center'><b>" . $credit_points . "</b></td>
                        <td valign='top' width='45px' class='ese_max_disp' align='center'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='50px' class='cia_max_disp' align='center'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='50px' class='total_max_disp' align='center'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='50px' class='disp_ese' align='center'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='53px' class='disp_cia' align='center'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='53px' class='total_disp_res' align='center'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px'  align='center' class='grade_point' ><b>" . $grade_point_print  . "</b></td>
                        <td valign='top' width='50px'  align='center' class='grade_name'><b> " . $valuess['grade_name'] . " </b></td>
                        <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
                $total_sub_count++;
        } 
    }
    $body_1 .="<table style='line-height: 1em; ' class='subjects_tables' border='0' width='100%' >
      <tr>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='95px'  align='left'>&nbsp;</td>
                <td valign='top' width='380px' colspan='4'  align='center'><b>~ END OF STATEMENT ~</b></td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td width='95px'  align='left'>&nbsp;</td>
            </tr>
      </table>";
      /* $body_1 .="<table style='line-height: 1em; ' class='subjects_tables' border='0' width='100%' >
      <tr>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='95px'  align='left'>&nbsp;</td>
                <td valign='top' width='380px' colspan='4'  align='center'><b>**IV Semester Examinations held in September-2020 & Supplementary & Arrear Examinations held in December-2020**</b></td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td width='95px'  align='left'>&nbsp;</td>
            </tr>
      </table>";
      */

       $body_1 .="<table style='line-height: 1em; ' class='subjects_tables' border='0' width='100%' >
      <tr>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='95px'  align='left'>&nbsp;</td>
                <td valign='top' width='380px' colspan='4'  align='center'><b>**IV Semester Examinations held in September-2020**</b></td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td valign='top' width='50px'  align='left'>&nbsp;</td>
                <td width='95px'  align='left'>&nbsp;</td>
            </tr>
      </table>";

    $change_css_class = $total_sub_count==1 ? 'change_cumulative_1' :  ($total_sub_count==2 ? 'change_cumulative' : ($total_sub_count==3 ? 'change_cumulative_3' :  'change_cumulative_normal' ));
   //print_r($total_sub_count);exit;

    $cumulative_part .="<table style='line-height: 1.0em; ' border='0' width='100%' ><tr>
    <td valign='top'  class='".$change_css_class."' colspan=16 width='100px;' > &nbsp; </td></tr></table>";
    $part_info = ConfigUtilities::getPartDetails($previous_reg_number,3);
    $cahnge_width = strlen($part_info['part_class'])>=13?'180px':'325px';
    $cumulative_part .="<table style='line-height: 1.0em;' class='makeitBigger' border='0' width='100%' > 
  <tr>
    <td valign='top'  class='line_height_print_cgpa_size' colspan=2  align='center' width='90px;' > ".$part_info['part_credits']." </td>
    <td valign='top'  align='center' class='line_height_print_cgpa_size print_class' colspan=6 width='".$cahnge_width."'  > ".$part_info['part_class']." </td>
    <td valign='top'  class='line_height_print_cgpa_size' colspan=2  width='80px;' > ".$part_info['part_percentage']." </td>
    <td valign='top'  class='line_height_print_cgpa_size' colspan=2  width='70px;' > ".$part_info['part_cgpa']." </td>
    <td valign='top'  class='line_height_print_cgpa_size' colspan=2  width='80px;' > ".$part_info['part_grade_point']."  </td>
    <td valign='top'   class='line_height_print_cgpa_size' colspan=2 width='80px;'  >".$part_info['part_additional_cred']." </td>                                                             
</tr>
  </table>";
  $checkData = ConsolidateMarks::find()->where(['student_map_id'=>$print_student_map_id,'part_no'=>3])->all();
  if(empty($checkData))
   {
    $updated_at = date("Y-m-d H:i:s");
      $ins_cons = new ConsolidateMarks();
      $ins_cons->batch_maping_id = $batch_maping_id;
      $ins_cons->student_map_id = $print_student_map_id;
      $ins_cons->part_no = 3;
      $ins_cons->part_credits = $part_info['part_credits'];
      $ins_cons->marks_gain = $part_info['part_marks'];
      $ins_cons->marks_total = $part_info['part_total_marks'];
      $ins_cons->percentage = $part_info['part_percentage'];
      $ins_cons->cgpa = $part_info['part_cgpa'];
      $ins_cons->grade = $part_info['part_grade_point'];
      $ins_cons->classification = $part_info['part_class'];
      $ins_cons->part_add_credits = $part_info['part_additional_cred'];
      $ins_cons->created_at = $updated_at;
      $ins_cons->created_by = $updated_by;
      $ins_cons->updated_at = $updated_at;
      $ins_cons->updated_by = $updated_by;
      $ins_cons->save(false);
      unset($ins_cons);
   }
    $merge_two_body_tags = "<tr>".$body_1.'</td></tr><tr><td valign="top" class="line_height subjects_tables" height="80px" colspan="16" >'.$cumulative_part."</td></tr>";
    $body = $merge_two_body_tags;
    $merge_body ="<tbody><tr>".$body."</tr></tbody>"; // For
    $footer .="</table>";
    $html = $header . $merge_body .  $footer;

   
    $print_stu_data .= $html;
            
            if(isset($_SESSION['get_console_list_pdf'])){ unset($_SESSION['get_console_list_pdf']);}
            $_SESSION['get_console_list_pdf'] = $print_stu_data;
            echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-12" >'.$print_stu_data.'</div></div></div></div></div>'; 
        
}
else
{ 
    Yii::$app->ShowFlashMessages->setMsg('Error','No data Found');            
}

?>