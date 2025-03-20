<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\CoeBatDegReg;
use app\models\ConsolidateMarks;
use app\models\Subjects;
use app\models\SubInfo;
use app\models\Batch;
use app\models\Degree;
use app\models\Programme;
$this->registerCssFile("@web/css/consolidate-markstatement-ug.css");
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$removed_path = str_replace('index.php', '', parse_url($url, PHP_URL_PATH));
$image_path = parse_url($url, PHP_URL_SCHEME)."://".parse_url($url, PHP_URL_HOST).":".parse_url($url, PHP_URL_PORT).$removed_path.'css/consolidate-markstatement-arts-pg.css'; 
$subject_array_ncc = ['18NCC01','18NCC01','18NCC02','18NCC02','18NCC03','18NCC03','18NCC04','18NCC04','18CSS28','18BTU17','18CDU35','18CUG62','18ENU34','18MAU33','18MBU17','18MSU34','18NCC18','18BSU20','18HMU29','18NCC21','18SWP33','18PSU25','18NCC07','18MSU20'];
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
        $publish_date = $show_date_publication = date('d/m/Y',strtotime($date_print));
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        
        $html = $body_1 = $body_2 = "";
        $cumulative_part = '';
        $previous_subject_code= "";
        $header = "";
        $add_body_1 =  '';$morethan_4_sems =4;
        $body ="";
        $footer = "";
        $print_register_number = $prev_stu_map_id ="";
        $new_stu_flag=0;
        $print_stu_data="";
        $print_stu_data1="";
        $exam_year='';
        $app_month='';
        $total_sub_count = $stu_subjects= 0;
        $first_reg_no = 0;
        $cgpa_calc = [];
        $previous_reg_number = $old_sem = "";
        $print_student_map_id = "";
        $app_month_name='';
        $batch_mapping_id='';
        echo Html::a('<i class="fa fa-file-pdf-o"></i> Print Pdf', ['/mark-entry-master/consolidate-mark-sheet-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
            ]);
        echo "<br /><br />";
        $open_div ='<div class="row"><div class="col-xs-12"><div class="col-xs-12 col-sm-1 col-lg-1">&nbsp;</div><div class="col-xs-12 col-sm-10 col-lg-10"><div class="col-xs-12 col-sm-1 col-lg-1"></div>';
        $close_div = "<br /><br /></div></div>";
        if($org_email=='coe@skcet.in')
        {
            $semester_array = ['1'=>'01','2'=>'02','3'=>'03','4'=>'04','5'=>'05','6'=>'06','7'=>'07','8'=>'08','9'=>'09','10'=>'10','11'=>'11','12'=>'12'];
        }
        else{
            $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII','13'=>'XIII','14'=>'XIV'];
        }        

        foreach ($get_console_list as $value) 
        {
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
                        $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$prev_stu_map_id.'" and result like "%pass%"  order by semester')->queryAll();
                        $getadditionalPrint = 0;
                        if(!empty($check_add))
                        {
                            $body_2 .="<table style='line-height: 3em; ' border='0' width='100%' >
                              <tr>
                                        <td valign='top' width='50px' align='left'>&nbsp;</td>
                                        <td valign='top' width='50px' align='left'>&nbsp;</td>
                                        <td valign='top' width='300px' colspan='4'  align='left'>&nbsp;</td>
                                        <td valign='top' width='50px'  align='left'>&nbsp;</td>
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
                              $stu_subjects++;
                            $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                              <tr>
                                        <td valign='top' width='50px' align='left'>&nbsp;</td>
                                        <td valign='top' width='50px' align='left'>&nbsp;</td>
                                        <td valign='top' width='95px'  align='left'>&nbsp;</td>
                                        <td valign='top' width='300px' colspan='4'  align='left'><b>#ADDITIONAL CREDIT COURSES#</b></td>
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
                              $stu_subjects++;
                            foreach ($check_add as $valuess) 
                            {
                                $subject_code_print = strtoupper($valuess['subject_code']);
                                $year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);
                                //$sub_na = wordwrap(strtoupper(mb_convert_encoding($valuess['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
                               //$sub_na = htmlentities($sub_na);
                               // $subject_name_print = "<b>".strtoupper(mb_convert_encoding($valuess['subject_name'],"HTML-ENTITIES","UTF-8"))."</b>";
                                $str = (strtoupper($valuess['subject_name']));
                                $subject_name_print= "<b>". wordwrap($str,35,"<br>\n");
                                $credit_points = isset($valuess['credits']) && !empty($valuess['credits'])?$valuess['credits']:"--";
                                $ese_max_disp = ($valuess['ese_maximum']==0 || $valuess['ese_maximum']==''  || $valuess['ese_maximum']==NULL  || $valuess['ese_maximum']==null) ? '--':$valuess['ese_maximum'];
                                $cia_max_disp = ($valuess['cia_maximum']==0 || $valuess['cia_maximum']==''  || $valuess['cia_maximum']==NULL  || $valuess['cia_maximum']==null) ? '--':$valuess['cia_maximum'];
                                $total_max_disp = $cia_max_disp=='--' ?$ese_max_disp: ($ese_max_disp=='--'?$cia_max_disp:($cia_max_disp+$ese_max_disp));
                                $disp_ese = ($valuess['ESE']==0 || $valuess['ESE']=="") ? '--':$valuess['total'];
                                $disp_cia = ($valuess['CIA']==0 || $valuess['CIA']=="") ? '--':$valuess['total'];
                                $stu_total = $valuess['total'];
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
                                  $send_data = $valuess['part_no']==''?'0':$valuess['part_no'];
                                  $body_2 .=" 
              <table style='line-height: 1.2em; ' border='0' width='100%' >
                  <tr>
                      <td valign='top' width='40px' class='part_no' align='left'><b>" . $semester_array[$valuess['part_no']]. "</b></td>
                      <td valign='top' width='35px' class='semester3' align='left'><b>" . $semester_array[$valuess['semester']]. "</b></td>
                      <td valign='top' width='80px' class='subject_code3' align='left'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                     <td valign='top' width='210px' colspan='3' class='subject_name_print3' align='left'><b>" . strtoupper($valuess['subject_name']) . "</b></td>
                        <td valign='top' width='42px' class='credit_points2' align='left'><b>" . $credit_points . "</b></td>
                      <td valign='top' width='42px' class='ese_max_disp3'  align='left'><b>" . $ese_max_disp . "</b></td>
                      <td valign='top' width='42px'  align='center' class='cia_max_disp2'><b>" . $cia_max_disp . "</b></td>
                      <td valign='top' width='53px'  align='center' class='total_max_disp3'><b>" . $total_max_disp . "</b></td>
                      <td valign='top' width='53px'  align='left' class='disp_ese2' ><b>" . $disp_ese . "</b></td>
                      <td valign='top' width='50px'  align='left' class='disp_cia2'><b>" . $disp_cia . "</b></td>
                      <td valign='top' width='50px'  align='left' class='total_disp_res2'><b>" . $total_disp_res . "</b></td>
                      <td valign='top' width='60px'  align='left' class='grade_point2'><b>" . $grade_point_print . "</b></td>
                      <td valign='top' width='50px' style='padding-left:-15' align='left'><b> " . $valuess['grade_name'] . " </b></td>
                      <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>

                  </tr>
              </table>";
                            $stu_subjects++;
                            $getadditionalPrint++;
                            }  
                           
                        }
                       $body_2 .="<table style='line-height: 3em; ' border='0' width='100%' >
                            <tr>
                                      <td valign='top' width='50px' align='left'>&nbsp;</td>
                                      <td valign='top' width='50px' align='left'>&nbsp;</td>
                                      <td valign='top' width='95px'  align='left'>&nbsp;</td>
                                      <td valign='top' width='300px' colspan='4'  align='center'>~ END OF STATEMENT ~</td>
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
                            $stu_subjects++;
                        $gpa = 0;
                        $total_credits_cgpa=0;
                        for ($i=0; $i <count($cgpa_calc) ; $i++) 
                        { 
                            $gpa += $cgpa_calc[$i]['gpa'];
                            $total_credits_cgpa += $cgpa_calc[$i]['credits'];
                        }
                        $final_cgpa = round($gpa/$total_credits_cgpa,1);
                        $change_css_class = 'change_cumulative_'.$stu_subjects;
                                 
                        $cumulative_part .="<table style='line-height: 1.2em; ' border='0' width='100%' ><tr>
                                <td valign='top'  class='".$change_css_class."' colspan=16  width='100px;' > &nbsp; </td></tr></table>";
                        $max_parts = SubInfo::find()->where(['sub_batch_id'=>$batch_maping_id])->orderBy('part_no desc')->limit(1)->one();
                        for ($i=1; $i <= $max_parts['part_no'] ; $i++) 
                        { 
                           $part_info = ConfigUtilities::getPartDetails($previous_reg_number,$i);

                           $checkData = ConsolidateMarks::find()->where(['student_map_id'=>$prev_stu_map_id,'part_no'=>$i])->all();
                          if(empty($checkData))
                           {
                              $ins_cons = new ConsolidateMarks();
                              $ins_cons->batch_maping_id = $batch_maping_id;
                              $ins_cons->student_map_id = $prev_stu_map_id;
                              $ins_cons->part_no = $i;
                              $ins_cons->part_credits = $part_info['part_credits'];
                              $ins_cons->marks_gain = $part_info['part_marks'];
                              $ins_cons->marks_total = $part_info['part_total_marks'];
                              $ins_cons->percentage = $part_info['part_percentage'];
                              $ins_cons->cgpa = $part_info['part_cgpa'];
                              $ins_cons->grade = $part_info['part_grade_point'];
                              $ins_cons->classification = $part_info['part_class'];
                              $ins_cons->part_add_credits = $part_info['part_additional_cred'];
                              $ins_cons->created_at = date("Y-m-d H:i:s");
                              $ins_cons->created_by = Yii::$app->user->getId();
                              $ins_cons->updated_at = date("Y-m-d H:i:s");
                              $ins_cons->updated_by = Yii::$app->user->getId();
                              $ins_cons->save(false);
                              unset($ins_cons);
                           }
                          $display_marks = $part_info['part_marks']=='--'?'--':$part_info['part_marks']."/".$part_info['part_total_marks'];
                          $classification= "<b>". wordwrap($part_info['part_class'],20,"<br>\n");
      $cumulative_part .="<table style='line-height: 1.2em; ' class='makeitBiggerSize' border='0' width='100%' > 
      <tr>
        <td valign='top'  class='line_height print_cgpa_size' style='padding-left:-120px;' width='100px;' > ".$semester_array[$i]." </td>
        <td valign='top'  class='line_height print_cgpa_size'  width='100px;' style='padding-left:-120px;' > ".$part_info['part_credits']." </td>
        <td valign='top'  class='line_height print_cgpa_size' width='160px;' style='padding-left:-90px;' > ".$display_marks." </td>
        <td valign='top'  class='line_height print_cgpa_size'  width='190px;' style='padding-left:-80px;' > ".$part_info['part_percentage']." </td>
       
        <td valign='top'   class='line_height print_cgpa_size print_class' colspan=6 width='110px' style='padding-left:-120px;' > ".$part_info['part_cgpa']." </td>
        <td valign='top'  class='line_height print_cgpa_size'  width='80px;' style='padding-left:-120px;' > ".$part_info['part_grade_point']."  </td>
        <td valign='top'   class='line_height print_cgpa_size print_class' colspan=6 width='200px' style='padding-left:-120px;' > ".$classification." </td>
        <td valign='top'   class='line_height print_cgpa_size' colspan=4 width='60px;' style='padding-left:-60px;' >".$part_info['part_additional_cred']." </td>                                                             
    </tr>
      </table>";
                        }
                        
                        $merge_two_body_tags = $body_1.'</td>'.$body_2."</td></tr>";
                        $body = $merge_two_body_tags;
                        $merge_body ="<tbody><tr>".$body."</tr></tbody><tr><td valign='top' colspan=32 height='80px' >&nbsp;</td></tr>"; // For
                        $footer .="</table>";
                        $html = $header .$merge_body.$footer;             
                          
                          $print_stu_data1.= "<div class='myfixed'>".$html."</div><div  class='myfixed1'>".$cumulative_part."</div>";   
                          $print_stu_data1 .= "<pagebreak sheet-size='A3-L' >";       
                          $print_stu_data .= $html.$cumulative_part."<pagebreak sheet-size='A3-L' >";
                         
                          $header = $body = $body_1 = $html = $cumulative_part = $footer = $add_body_1 = $body_2 = "";             
                          $new_stu_flag = 1; $stu_subjects=0;$total_sub_count = $getadditionalPrint= 0;
                          unset($cgpa_calc); $cgpa_calc = [];
                          
                      }
                $same_semester=$value['semester'];
            $print_gender = ($value['gender']=='F' || $value['gender']=='FEMALE') ?'FEMALE':'MALE';
            $exam_year=$value['year'];
            $app_month = strtoupper($value['month']);
            $last_appearance = ConfigUtilities::getLastYearOfPassing($value['register_number']);
            $dob= date('d/m/Y',strtotime($value['dob']));
            $batch_maping_id = $value['batch_mapping_id'];
            $photo_extension = ConfigUtilities::match($supported_extensions,$value['register_number'].$extension); 
            $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg";
            $getDegInfo = CoeBatDegReg::findOne($value['course_batch_mapping_id']);
            $batch_details = Batch::findOne($getDegInfo->coe_batch_id);
            $getYearFromLastApp = explode('-', $last_appearance);
            $duration = $getYearFromLastApp[1]-$batch_details->batch_name;
       
            $header ='<table width="100%" autosize=1  >
          '; 
          $header.='<tr>
                        <td valign="top" colspan=4> &nbsp; </td>
                        <td valign="top" colspan=7 class="stu_photo" style="padding-top: 42px !important;" align="center" >  
                            <img  width="120px" height="130px" src='.$stu_photo.' alt='.$stu_photo.' Photo >
                        </td>
                        <td valign="top"  colspan=21 > &nbsp; </td>
                    </tr>';
        $header.='<tr><td valign="top"  colspan="32" height="40px" >&nbsp;</td></tr>';
        $header.='<tr>';
              
        $header.='<td valign="top"   height="150px" colspan="16">'; //first td
         $header.='<table class="makeitBigger" width="100%">
                    <tr>
                        <td valign="top" colspan=3 align="left" width="100" style="padding-top: 10px;">'.strtoupper($value["degree_code"]).'  </td>
                        <td valign="top" colspan=7 align="left" width="320" style="padding-top: 10px;" >'.strtoupper($value["programme_name"]).'</td>
                        <td valign="top" colspan=3 align="left" width="100" style="padding-top: 10px;padding-right: 40;" >'.strtoupper($show_date_publication).'</td>
                        <td valign="top" colspan=3  align="left" width="200" style="padding-top: 20px;padding-left:-70px;" >2018-2021</td>
                    </tr>
                    <tr><td valign="top"  colspan="16" height="40px" >&nbsp;</td></tr>
                    <tr>
                        <td valign="top" class="student_name"  colspan="10">'.strtoupper($value["name"]).'</td>
                        <td valign="top"  colspan=3  class="dob" align="left" width="100"  >'.$dob.'</td>

                          <td valign="top" colspan=3  align="left" width="200" style="padding-top: 5px;padding-left:-70px;" >'.strtoupper($value["register_number"]).'</td>
                    </tr>
                        
                       
                    </tr>
                   </table>
                   </td>';
      
        $header.='<td valign="top"  height="150px" colspan="16" >&nbsp;</td>'; //second td
        $header.='</tr><tr><td valign="top"  colspan="32" height="70px" >&nbsp;</td></tr>'; //main tr

        $total_credits ='';
         $total_earned_credits ='';
         $passed_grade_points ='';
         
         $body_1 .= "<td valign='top' class='line_height subjects_tables left_body' height='700px' valign='top'   colspan='16' >";

         $body_2 .= "<td valign='top' class='line_height subjects_tables right_body' height='500px' valign='top' colspan='16' >"; //1st td
         $subject_code_print = strtoupper($value['subject_code']);
           $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
          //$sub_na = wordwrap(strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
        // $sub_na = htmlentities($sub_na);
          //$subject_name_print = "<b>".strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8"))."</b>";
           $str = (strtoupper($value['subject_name']));
          $subject_name_print= "<b>". wordwrap($str,35,"<br>\n");
          $credit_points = isset($value['credit_points']) && !empty($value['credit_points'])?$value['credit_points']:"--";
          $ese_max_disp = ($value['ESE_max']==0 || $value['ESE_max']==''  || $value['ESE_max']==NULL  || $value['ESE_max']==null) ? '--':$value['ESE_max'];
          $cia_max_disp = ($value['CIA_max']==0 || $value['CIA_max']==''  || $value['CIA_max']==NULL  || $value['CIA_max']==null) ? '--':$value['CIA_max'];
          $total_max_disp = $cia_max_disp=='--' ?$ese_max_disp: ($ese_max_disp=='--'?$cia_max_disp:($cia_max_disp+$ese_max_disp));
          $disp_ese = ($value['ESE']==0 || $value['ESE']=="") ? '--':$value['ESE'];
          $disp_cia = ($value['CIA']==0 || $value['CIA']=="") ? '--':$value['CIA'];

          $stu_total = $disp_cia=='--' ?$disp_ese: ($disp_ese=='--'?$disp_cia:($disp_cia+$disp_ese));

          $total_disp_res = ($value['total']==0 || $value['total']=="") ? '--':$stu_total;
          $grade_point_print = strlen($value['grade_point'])==2 ?$value['grade_point'].".0":(strlen($value['grade_point'])==1 ?$value['grade_point'].".0":$value['grade_point']);

           if($value['ESE_max']==0  && $value['CIA_max']==0 && ($value['result']=='Completed' || $value['result']=='completed' || $value['result']=='COMPLETED')  && $value['part_no']!=5   )
            {      
             
              $res_print = strtoupper($value['result']);
             
                $body_1 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%'  >
                    <tr>
                        <td valign='top' width='50px' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code' align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3' class='subject_name_print'  align='left'>" . trim($subject_name_print) . "</td>
                        
                        <td valign='top' width='350px' colspan=9  align='center'><b>".$res_print."</b></td>
                        <td valign='top' width='95px' class='print_month_year'  align='left'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
            $total_sub_count++;$stu_subjects++;
           } // ZERO ZERO SUBJECTS
           else if($value['ESE_max']==0  && $value['CIA_max']==0 && ($value['result']=='pass' || $value['result']=='PASS' || $value['result']=='Pass') && $value['part_no']!=5  )
            {   
             
              $res_print = strtoupper($value['result']);
             
                $body_1 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%'  >
                    <tr>
                        <td valign='top' width='50px' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code'  align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3' class='subject_name_print' align='left'>" . trim($subject_name_print) . "</td>
                        
                        <td valign='top' width='350px' colspan=9  align='center'><b>".$res_print."</b></td>
                        <td valign='top' width='95px' class='print_month_year'  align='left'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
            $total_sub_count++;$stu_subjects++;
           } // ZERO ZERO SUBJECTS
           else if($value['part_no']=='5' && !empty($value['grade_name']) )
            {
              $classification = $value['grade_name'];
                $body_1 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%'  >
                    <tr>
                        <td valign='top' width='50px' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code' align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3'  align='left' class='subject_name_print'>" . trim($subject_name_print) . "</td>
                        
                        <td valign='top' width='350px' colspan=9  align='center'><b>".$classification."</b></td>
                        <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
            $total_sub_count++;$stu_subjects++;
           } // ZERO ZERO SUBJECTS
           else
           {
                $body_1 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td valign='top' width='50px' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code' align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3'  align='left' class='subject_name_print'>" . trim($subject_name_print) . "</td>
                        <td valign='top' width='42px' class='credit_points' align='left'><b>" . $credit_points . "</b></td>
                        <td valign='top' width='42px' class='ese_max_disp' align='left'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='42px' class='cia_max_disp' align='left'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='53px' class='total_max_disp' align='left'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='53px' class='disp_ese' align='left'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='53px' class='disp_cia' align='left'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='50px' class='total_disp_res' align='left'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px' class='grade_point' align='left'><b>" .  $grade_point_print . "</b></td>
                        <td valign='top' width='50px' class='grade_name' align='left'><b> " . $value['grade_name'] . " </b></td>
                        <td valign='top' width='95px' class='print_month_year1'  align='left'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
                $total_sub_count++;$stu_subjects++;
           } // 1ST SUBJECT PRINT FOR EACH REGISTRATION NUMBER        
            $cgpa_calc []= ['gpa'=> $credit_points*$value['grade_point'],'credits'=>$credit_points];
            $total_earned_credits += ($value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass") ? $credit_points : 0;

            $passed_grade_points += ($value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass") ? ($credit_points*$value['grade_point']) : 0;

            $total_credits +=$value['grade_point'];


            $previous_subject_code = $value['subject_code'];
              $previous_reg_number=$value['register_number'];
            $prev_stu_map_id = $value['student_map_id'];
        } // IF NOT THE SAME REGISTRATION NUMBER 
        else
        {
            $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']); 

            if($value['semester']>=5)
            {
              if($same_semester==4)
              {  
                $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                  <tr>
                            <td valign='top' width='50px' align='left'>&nbsp;</td>
                            <td valign='top' width='50px' align='left'>&nbsp;</td>
                            <td valign='top' width='95px'  align='left'>&nbsp;</td>
                            <td valign='top' width='300px' colspan='4'  align='center' ><b>~ CONTINUE ~</b></td>
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
                  $same_semester=$value['semester'];
                  $total_sub_count++;$stu_subjects++;
              }
              else if($same_semester!=$value['semester'])
              {
                
                $body_2 .=" 
                          <table style='line-height: 1.2em; ' border='0' width='100%' >
                              <tr>
                                  <td valign='top'  class='line_height subjects_tables' colspan=16 >&nbsp; </td>
                              </tr>
                          </table>";
                  $same_semester=$value['semester'];
                  $total_sub_count++;$stu_subjects++;
              }
                  $subject_code_print = strtoupper($value['subject_code']);
                   $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
                  //$sub_na = wordwrap(strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
                // $sub_na = htmlentities($sub_na);
                  //$subject_name_print = "<b>".strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8"))."</b>";
                   $str = (strtoupper($value['subject_name']));
                  $subject_name_print= "<b>". wordwrap($str,35,"<br>\n");
                  $credit_points = isset($value['credit_points']) && !empty($value['credit_points'])?$value['credit_points']:"--";
                  $ese_max_disp = ($value['ESE_max']==0 || $value['ESE_max']==''  || $value['ESE_max']==NULL  || $value['ESE_max']==null) ? '--':$value['ESE_max'];
                  $cia_max_disp = ($value['CIA_max']==0 || $value['CIA_max']==''  || $value['CIA_max']==NULL  || $value['CIA_max']==null) ? '--':$value['CIA_max'];
                  $total_max_disp = $cia_max_disp=='--' ?$ese_max_disp: ($ese_max_disp=='--'?$cia_max_disp:($cia_max_disp+$ese_max_disp));
                  $disp_ese = ($value['ESE']==0 || $value['ESE']=="") ? '--':$value['ESE'];
                  $disp_cia = ($value['CIA']==0 || $value['CIA']=="") ? '--':$value['CIA'];

                  $stu_total = $disp_cia=='--' ?$disp_ese: ($disp_ese=='--'?$disp_cia:($disp_cia+$disp_ese));

                  $total_disp_res = ($value['total']==0 || $value['total']=="") ? '--':$stu_total;
                   $grade_point_print = strlen($value['grade_point'])==2 ?$value['grade_point'].".0":(strlen($value['grade_point'])==1 ?$value['grade_point'].".0":$value['grade_point']);
                   //print_r($grade_point_print);exit;

                 if($value['ESE_max']==0  && $value['CIA_max']==0 && ($value['result']=='Completed' || $value['result']=='completed' || $value['result']=='COMPLETED')  && $value['part_no']!=5   )
            {     
             
              $res_print = strtoupper($value['result']);
              // print_r($res_print);exit;
             
                $body_2 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%'  >
                    <tr>
                        <td valign='top' width='50px'  class='part_no' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' class='semester2' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code2'  align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3' class='subject_name_print2' align='left'>" . trim($subject_name_print) . "</td>
                        
                        <td valign='top' width='350px' colspan=8  align='center'><b>".$res_print."</b></td>
                        <td valign='top' width='95px' class='print_month_year2'  align='left'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
            $total_sub_count++;$stu_subjects++;


           } // ZERO ZERO SUBJECTS
           else if($value['ESE_max']==0  && $value['CIA_max']==0 && ($value['result']=='pass' || $value['result']=='PASS' || $value['result']=='Pass') && $value['part_no']!=5  )
            {
             
              $res_print = strtoupper($value['result']);
             
                $body_2 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%'  >
                    <tr>
                        <td valign='top' width='50px' class='part_no' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' class='semester2' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code2'  align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3' class='subject_name_print2' align='left'>" . trim($subject_name_print) . "</td>
                        
                        <td valign='top' width='350px' colspan=8  align='center'><b>".$res_print."</b></td>
                        <td valign='top' width='95px' class='print_month_year2'  align='left'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
            $total_sub_count++;$stu_subjects++;
           } // ZERO ZERO SUBJECTS
           else if($value['part_no']=='5' && !empty($value['grade_name']) )
            {
              $classification = $value['grade_name'];
                $body_2 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%'  >
                    <tr>
                        <td valign='top' width='50px' class='part_no' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' class='semester2' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code2'  align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3' class='subject_name_print2' align='left'>" . trim($subject_name_print) . "</td>
                        
                        <td valign='top' width='350px' colspan=8  align='center'><b>".$classification."</b></td>
                        <td valign='top' width='95px' class='print_month_year2'  align='left'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
            $total_sub_count++;$stu_subjects++;
           } // ZERO ZERO SUBJECTS
           else
           {
                $body_2 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td valign='top' width='50px' class='part_no' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' class='semester2' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px'  class='subject_code2' align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                       <td valign='top' width='210px' colspan='3' class='subject_name_print2' align='left'>" . trim($subject_name_print) . "</td>
                        <td valign='top' width='42px' class='credit_points2' align='left'><b>" . $credit_points . "</b></td>
                        <td valign='top' width='42px' class='ese_max_disp2' align='left'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='42px'  align='center' class='cia_max_disp2'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='53px'  align='left' class='total_max_disp2'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='53px'  align='left' class='disp_ese2'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='53px'  align='left' class='disp_cia2'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='50px'  align='left' class='total_disp_res2'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px'  align='left' class='grade_point2'><b>" . $grade_point_print . "</b></td>
                        <td valign='top' width='50px'  style='padding-left:-15' align='left'><b> " . $value['grade_name'] . " </b></td>
                         <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
                $total_sub_count++;$stu_subjects++;
           } 
           //print_r($total_sub_count) ;exit;

                  $cgpa_calc []= ['gpa'=> $credit_points*$value['grade_point'],'credits'=>$credit_points];
                  $total_earned_credits += ($value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass") ? $credit_points : 0;
                  $passed_grade_points += ($value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass") ? ($credit_points*$value['grade_point']) : 0;
                  $total_credits +=$value['grade_point'];
               
                
            } // If subjects count more than 40 subjects
            else{
                if($same_semester!=$value['semester'])
                {   
                    $body_1 .=" 
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' colspan=16>&nbsp; </td>
                                </tr>
                            </table>    
                        ";
                    $same_semester=$value['semester'];
                    $total_sub_count++;$stu_subjects++;

                }
                   $subject_code_print = strtoupper($value['subject_code']);
                   $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
                  //$sub_na = wordwrap(strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
                 //$sub_na = htmlentities($sub_na);
                  //$subject_name_print = "<b>".strtoupper(mb_convert_encoding($value['subject_name'],"HTML-ENTITIES","UTF-8"))."</b>";
                   $str = (strtoupper($value['subject_name']));
                   $subject_name_print= "<b>". wordwrap($str,35,"<br>\n");
                  $credit_points = isset($value['credit_points']) && !empty($value['credit_points'])?$value['credit_points']:"--";
                  $ese_max_disp = ($value['ESE_max']==0 || $value['ESE_max']==''  || $value['ESE_max']==NULL  || $value['ESE_max']==null) ? '--':$value['ESE_max'];
                  $cia_max_disp = ($value['CIA_max']==0 || $value['CIA_max']==''  || $value['CIA_max']==NULL  || $value['CIA_max']==null) ? '--':$value['CIA_max'];
                  $total_max_disp = $cia_max_disp=='--' ?$ese_max_disp: ($ese_max_disp=='--'?$cia_max_disp:($cia_max_disp+$ese_max_disp));
                  $disp_ese = ($value['ESE']==0 || $value['ESE']=="") ? '--':$value['ESE'];
                  $disp_cia = ($value['CIA']==0 || $value['CIA']=="") ? '--':$value['CIA'];

                  $stu_total = $disp_cia=='--' ?$disp_ese: ($disp_ese=='--'?$disp_cia:($disp_cia+$disp_ese));

                  $total_disp_res = ($value['total']==0 || $value['total']=="") ? '--':$stu_total;
                  $grade_point_print = strlen($value['grade_point'])==2 ?$value['grade_point'].".0":(strlen($value['grade_point'])==1 ?$value['grade_point'].".0":$value['grade_point']);
            if($value['ESE_max']==0  && $value['CIA_max']==0 && ($value['result']=='Completed' || $value['result']=='completed' || $value['result']=='COMPLETED')  && $value['part_no']!=5   )
            {
             
              $res_print = strtoupper($value['result']);
             // print_r($res_print);exit;
             
                $body_1 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%'  >
                    <tr>
                        <td valign='top' width='50px' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code'  align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3'  align='left' class='subject_name_print'>" . trim($subject_name_print) . "</td>
                        
                        <td valign='top' width='350px' colspan=9  align='center'><b>".$res_print."</b></td>
                        <td valign='top' width='95px' class='print_month_year1'  align='left'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
            $total_sub_count++;$stu_subjects++;
           } // ZERO ZERO SUBJECTS
           else if($value['ESE_max']==0  && $value['CIA_max']==0 && ($value['result']=='pass' || $value['result']=='PASS' || $value['result']=='Pass') && $value['part_no']!=5  )
            {       
             
              $res_print = strtoupper($value['result']);
             
                $body_1 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%'  >
                    <tr>
                        <td valign='top' width='50px' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code' align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3'  align='left' class='subject_name_print'>" . trim($subject_name_print) . "</td>
                        
                        <td valign='top' width='350px' colspan=9  align='center'><b>".$res_print."</b></td>
                        <td valign='top' width='95px' class='print_month_year1'  align='left'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
            $total_sub_count++;$stu_subjects++;
           } // ZERO ZERO SUBJECTS
           else if($value['part_no']=='5' && !empty($value['grade_name']) )
            {
              $classification = $value['grade_name'];
                $body_1 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%'  >
                    <tr>
                        <td valign='top' width='50px' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code' align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3'  align='left' class='subject_name_print'>" . trim($subject_name_print) . "</td>
                        
                        <td valign='top' width='350px' colspan=9  align='center'><b>".$classification."</b></td>
                        <td valign='top' width='95px' class='print_month_year1'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
            $total_sub_count++;$stu_subjects++;
           } // ZERO ZERO SUBJECTS
           else
           {
                $body_1 .=" 
                <table style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td valign='top' width='50px' align='left'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td valign='top' width='35px' align='left'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='80px' class='subject_code' align='left'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='210px' colspan='3' subject_name_print2 align='left' class='subject_name_print'>" . trim($subject_name_print) . "</td>
                        <td valign='top' width='42px' class='credit_points' align='left'><b>" . $credit_points . "</b></td>
                        <td valign='top' width='42px' class='ese_max_disp' align='left'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='42px' class='cia_max_disp' align='left'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='53px' class='total_max_disp' align='left'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='53px' class='disp_ese' align='left'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='53px'  class='disp_cia' align='left'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='50px' class='total_disp_res' align='left'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px' class='grade_point' align='left'><b>" .  $grade_point_print  . "</b></td>
                        <td valign='top' width='50px' class='grade_name' align='left'><b> " . $value['grade_name'] . " </b></td>
                        <td valign='top' width='95px' class='print_month_year1'  align='left'><b>" . strtoupper($year_of_passing) . "</b></td>
                    </tr>
                </table>";
                $total_sub_count++;$stu_subjects++;
           } // 1ST SUBJECT PRINT FOR EACH REGISTRATION NUMBER     
                      $cgpa_calc []= ['gpa'=> $credit_points*$value['grade_point'],'credits'=>$credit_points];
                      $total_earned_credits += ($value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass") ? $credit_points : 0;

                      $passed_grade_points += ($value['result']=="Pass" || $value['result']=="PASS" || $value['result']=="pass") ? ($credit_points*$value['grade_point']) : 0;

                      $total_credits +=$value['grade_point'];
               }  // Else subject count less than 40 count
            $previous_subject_code = $value['subject_code'];
            $previous_reg_number=$value['register_number'];
            $prev_stu_map_id = $value['student_map_id'];
        } // Else Condition to loop the entire subjects 

        }
            $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE   student_map_id="'.$prev_stu_map_id.'" and result like "%pass%" order by semester')->queryAll();    
    $getadditionalPrint  = 0;          
      if(!empty($check_add))
      {
        $body_2 .="<table style='line-height: 3em; ' border='0' width='100%' >
                  <tr>
                            <td valign='top' width='50px' align='left'>&nbsp;</td>
                            <td valign='top' width='50px' align='left'>&nbsp;</td>
                            <td valign='top' width='50px'  align='left'>&nbsp;</td>
                            <td valign='top' width='300px' colspan='4'  align='left'>&nbsp;</td>
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
        $stu_subjects++;
        $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                <tr>
                          <td valign='top' width='50px' align='left'>&nbsp;</td>
                          <td valign='top' width='50px' align='left'>&nbsp;</td>
                          <td valign='top' width='95px'  align='left'>&nbsp;</td>
                          <td valign='top' width='300px' colspan='4'  align='left'><b>#ADDITIONAL CREDIT COURSES#</b></td>                          
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
          $stu_subjects++;
          foreach ($check_add as $valuess) 
          {
              $subject_code_print = strtoupper($valuess['subject_code']);
              $year_of_passing = ConfigUtilities::getYearOfPassing($valuess['year_of_passing']);

              //$sub_na = wordwrap(strtoupper(mb_convert_encoding($valuess['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
             //$sub_na = htmlentities($sub_na);
             // $subject_name_print = "<b>".strtoupper(mb_convert_encoding($valuess['subject_name'],"HTML-ENTITIES","UTF-8"))."</b>";
              $str = (strtoupper($valuess['subject_name']));
             $subject_name_print= "<b>". wordwrap($str,35,"<br>\n");

              $credit_points = isset($valuess['credits']) && !empty($valuess['credits'])?$valuess['credits']:"--";
              $ese_max_disp = ($valuess['ese_maximum']==0 || $valuess['ese_maximum']==''  || $valuess['ese_maximum']==NULL  || $valuess['ese_maximum']==null) ? '--':$valuess['ese_maximum'];
              $cia_max_disp = ($valuess['cia_maximum']==0 || $valuess['cia_maximum']==''  || $valuess['cia_maximum']==NULL  || $valuess['cia_maximum']==null) ? '--':$valuess['cia_maximum'];
              $total_max_disp = $cia_max_disp=='--' ?$ese_max_disp: ($ese_max_disp=='--'?$cia_max_disp:($cia_max_disp+$ese_max_disp));

              $disp_ese = ($valuess['ESE']==0 || $valuess['ESE']=="") ? '--':$valuess['total'];
              $disp_cia = ($valuess['CIA']==0 || $valuess['CIA']=="") ? '--':$valuess['total'];

              $stu_total = $disp_cia=='--' ?$disp_ese: ($disp_ese=='--'?$disp_cia:($disp_cia+$disp_ese));

              $total_disp_res = ($valuess['total']==0 || $valuess['total']=="") ? '--':$stu_total;

              $result_stu = strtoupper($valuess['result']);
              $subjectname = Subjects::findOne(['subject_code'=>$valuess['subject_code']]);
             // $sub_na = wordwrap(strtoupper(mb_convert_encoding($subjectname['subject_name'],"HTML-ENTITIES","UTF-8")), 60, "\n", true);
              //$sub_na = htmlentities($sub_na);
              //$subject_name_print = "<b>".strtoupper(mb_convert_encoding($subjectname['subject_name'],"HTML-ENTITIES","UTF-8"))."</b>";
              //$subject_name_print = "<b>".strtoupper($valuess['subject_name'])."</b>";
              $str = (strtoupper($subjectname['subject_name']));
              $subject_name_print= "<b>". wordwrap($str,35,"<r>\n");
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
                 $grade_point_print = strlen($valuess['grade_point'])==2 ?$valuess['grade_point'].".0":(strlen($valuess['grade_point'])==1 ?$valuess['grade_point'].".0":$valuess['grade_point']);
              $subject_name_print = str_replace('ADDITIONAL CREDIT COURSE: ', '<b>ADDITIONAL CREDIT COURSE: </b>', $subject_name_print);
              $body_2 .=" 
              <table style='line-height: 1.2em; ' border='0' width='100%' >
                  <tr>
                      <td valign='top' width='40px' class='part_no' align='left'><b>" . $semester_array[$valuess['part_no']]. "</b></td>
                      <td valign='top' width='35px' class='semester3' align='left'><b>" . $semester_array[$valuess['semester']]. "</b></td>
                      <td valign='top' width='80px' class='subject_code3' align='left'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                     <td valign='top' width='210px' colspan='3' class='subject_name_print3' align='left'><b>" . strtoupper($valuess['subject_name']) . "</b></td>
                        <td valign='top' width='42px' class='credit_points2' align='left'><b>" . $credit_points . "</b></td>
                      <td valign='top' width='42px' class='ese_max_disp3'  align='left'><b>" . $ese_max_disp . "</b></td>
                      <td valign='top' width='42px'  align='center' class='cia_max_disp2'><b>" . $cia_max_disp . "</b></td>
                      <td valign='top' width='53px'  align='center' class='total_max_disp3'><b>" . $total_max_disp . "</b></td>
                      <td valign='top' width='53px'  align='left' class='disp_ese2' ><b>" . $disp_ese . "</b></td>
                      <td valign='top' width='50px'  align='left' class='disp_cia2'><b>" . $disp_cia . "</b></td>
                      <td valign='top' width='50px'  align='left' class='total_disp_res2'><b>" . $total_disp_res . "</b></td>
                      <td valign='top' width='60px'  align='left' class='grade_point2'><b>" . $grade_point_print . "</b></td>
                      <td valign='top' width='50px' style='padding-left:-15' align='left'><b> " . $valuess['grade_name'] . " </b></td>
                      <td valign='top' width='95px' class='print_month_year'  align='center'><b>" . strtoupper($year_of_passing) . "</b></td>

                  </tr>
              </table>";
                $getadditionalPrint++;$stu_subjects++;
          }
         
      }
      $body_2 .="<table style='line-height: 3em; ' border='0' width='100%' >
      <tr>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='50px' align='left'>&nbsp;</td>
                <td valign='top' width='95px'  align='left'>&nbsp;</td>
                <td valign='top' width='300px' colspan='4'  align='center'>~ END OF STATEMENT ~</td>
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
  $stu_subjects++;
   $change_css_class = 'change_cumulative_'.$stu_subjects;
//print_r($stu_subjects);exit;

    $gpa = 0;
    $total_credits_cgpa=0;
    for ($i=0; $i <count($cgpa_calc) ; $i++) 
    { 
        $gpa += $cgpa_calc[$i]['gpa'];
        $total_credits_cgpa += $cgpa_calc[$i]['credits'];
    }
    $final_cgpa = round($gpa/$total_credits_cgpa,1);

    $max_parts = SubInfo::find()->where(['sub_batch_id'=>$batch_maping_id])->orderBy('part_no desc')->limit(1)->one();
    for ($i=1; $i <= $max_parts['part_no'] ; $i++) 
    {
       $part_info = ConfigUtilities::getPartDetails($previous_reg_number,$i);

       $checkData = ConsolidateMarks::find()->where(['student_map_id'=>$prev_stu_map_id,'part_no'=>$i])->all();
      if(empty($checkData))
       {
          $ins_cons = new ConsolidateMarks();
          $ins_cons->batch_maping_id = $batch_maping_id;
          $ins_cons->student_map_id = $prev_stu_map_id;
          $ins_cons->part_no = $i;
          $ins_cons->part_credits = $part_info['part_credits'];
          $ins_cons->marks_gain = $part_info['part_marks'];
          $ins_cons->marks_total = $part_info['part_total_marks'];
          $ins_cons->percentage = $part_info['part_percentage'];
          $ins_cons->cgpa = $part_info['part_cgpa'];
          $ins_cons->grade = $part_info['part_grade_point'];
          $ins_cons->classification = $part_info['part_class'];
          $ins_cons->part_add_credits = $part_info['part_additional_cred'];
          $ins_cons->created_at = date("Y-m-d H:i:s");
          $ins_cons->created_by = Yii::$app->user->getId();
          $ins_cons->updated_at = date("Y-m-d H:i:s");
          $ins_cons->updated_by = Yii::$app->user->getId();
          $ins_cons->save(false);
          unset($ins_cons);
       }

       $display_marks = $part_info['part_marks']=='--'?'--':$part_info['part_marks']."/".$part_info['part_total_marks'];
       $classification= "<b>". wordwrap($part_info['part_class'],20,"<br>\n");
      $cumulative_part .="<table style='line-height: 1.2em; ' class='makeitBiggerSize' border='0' width='100%' > 
      <tr>
        <td valign='top'  class='line_height print_cgpa_size' style='padding-left:-120px;' width='100px;' > ".$semester_array[$i]." </td>
        <td valign='top'  class='line_height print_cgpa_size'  width='100px;' style='padding-left:-120px;' > ".$part_info['part_credits']." </td>
        <td valign='top'  class='line_height print_cgpa_size' width='160px;' style='padding-left:-90px;' > ".$display_marks." </td>
        <td valign='top'  class='line_height print_cgpa_size'  width='190px;' style='padding-left:-80px;' > ".$part_info['part_percentage']." </td>
       
        <td valign='top'   class='line_height print_cgpa_size print_class' colspan=6 width='110px' style='padding-left:-120px;' > ".$part_info['part_cgpa']." </td>
        <td valign='top'  class='line_height print_cgpa_size'  width='80px;' style='padding-left:-120px;' > ".$part_info['part_grade_point']."  </td>
        <td valign='top'   class='line_height print_cgpa_size print_class' colspan=6 width='200px' style='padding-left:-120px;' > ". $classification." </td>
        <td valign='top'   class='line_height print_cgpa_size' colspan=4 width='60px;' style='padding-left:-60px;' >".$part_info['part_additional_cred']." </td>                                                             
    </tr>
      </table>";
     // print_r($part_info);exit;
    }
    
    $merge_two_body_tags = $body_1.'</td>'.$body_2."</td></tr>";
    $body = $merge_two_body_tags;
    $merge_body ="<tbody><tr>".$body."</tbody><tr><td valign='top'  colspan=32 height='80px' >&nbsp;</td></tr>"; // For
    $footer .="</table>";
    $html = $header .$merge_body.$footer;
     $print_stu_data .=$html.$cumulative_part;  

        if(isset($_SESSION['consolidatemarksheet_print']))
        { 
            unset($_SESSION['consolidatemarksheet_print']);
        }
         $print_stu_data1.= "<div class='myfixed'>".$html."</div><div  class='myfixed1'>".$cumulative_part."</div>";
         $_SESSION['consolidatemarksheet_print']=$print_stu_data1;
        echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-12" >'.$print_stu_data.'</div></div></div></div></div>';
        
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
    }

?>