<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\CoeBatDegReg;
use app\models\StudentMapping;
use app\models\MandatorySubcatSubjects;
use yii\db\Query;

use app\models\Degree;

$this->registerCssFile("@web/css/newmarkstatement.css");

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
    $top_margin = $top_margin!='' && $top_margin!=0 ? $top_margin:'0';
    $space = $bottom_margin;
    $bottom_margin = ($bottom_margin!='' && $bottom_margin!=0)?$bottom_margin:0;
    $add_tr_starting =  "<tr><td class='make_bold_font' id='no_print_trimester' colspan='16'> &nbsp; </td></td></tr>";
    $supported_extensions = ConfigUtilities::ValidFileExtension();
    $stu_directory = Yii::getAlias("@web") . "/resources/stu_photos/";
    $absolute_dire = Yii::getAlias("@webroot") . "/resources/stu_photos/";
    
    $html = "";
    $previous_subject_code = "";    $previous_reg_number = "";
    $header = "";
    $body = "";
    $footer = "";
    $print_register_number = "";
    $print_student_map_id = "";
    $new_stu_flag = 0;
    $print_stu_data = "";
    $exam_year = '';
    $app_month = '';
    $batch_mapping_id = '';
    $first_reg_num = 0;
    //$term='';
    $date_print = isset($date_print) ? date('d-m-Y',strtotime($date_print)) : date('d-m-Y');
    $date = "<b>".$date_print."</b>";
    
    
    $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
    echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/mark-statement-arts-print-pdf'], [
        'class' => 'pull-right btn btn-primary',
        'target' => '_blank',
        'data-toggle' => 'tooltip',
        'title' => 'Will open the generated PDF file in a new window'
    ]);

    echo "<br /><br />";
    $open_div = '<div class="row"><div class="col-xs-12"><div class="col-xs-12 col-sm-1 col-lg-1">&nbsp;</div><div class="col-xs-12 col-sm-10 col-lg-10"><div class="col-xs-12 col-sm-1 col-lg-1"></div>';
    $close_div = "<br /><br /></div></div>";

    $deg_type = Yii::$app->db->createCommand("select degree_type from coe_degree as A join coe_bat_deg_reg as B on A.coe_degree_id=B.coe_degree_id where coe_bat_deg_reg_id='" . $_POST['bat_map_val'] . "'")->queryScalar();
    $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII','13'=>'XIII','14'=>'XIV'];

    foreach ($mark_statement as $value) 
    {
        $files = glob($absolute_dire . $value['register_number'] . ".*"); // Will find 2.JPG, 2.php, 2.gif
        
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
        //print_r($mark_statement);exit;
       
        $app_month = $value['month'];
        $month_disp = $value['month']=='Oct/Nov' ||  $value['month']=='OCT/NOV' ||  $value['month']=='oct/nov' ?'NOV':'APR';
        if($value['month']=='Oct/Nov' ||  $value['month']=='OCT/NOV' ||  $value['month']=='oct/nov')
        {
            $month_disp ="OCT/NOV";
        }
        else if($value['year']!=2022  && $value['year']!=2023 && $value['month']=='April/May' ||  $value['month']=='APRIL/MAY' ||  $value['month']=='april/may')
        {
            $month_disp ="APR/MAY";
        }
      else if($value['year']=2022 &&  $value['year']!=2023 &&  $value['month']=='April/May' ||  $value['month']=='APRIL/MAY' ||  $value['month']=='april/may')
        {
            $month_disp ="JUNE";
        }
         else if($value['year']=2022 && $value['month']=='JUNE' ||  $value['month']=='june' ||  $value['month']=='June')
        {
            $month_disp ="AUG";
        }
        else if($value['year']=2023 && $value['month']=='April/May' ||  $value['month']=='APRIL/MAY' ||  $value['month']=='april/may')
        {
            $month_disp ="MAY";
        }

        /* else if($value['year']=2022 && $value['month']=='April/May' ||  $value['month']=='APRIL/MAY' ||  $value['month']=='april/may')
        {
            $month_disp ="AUG";
        }*/
        

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
        $term=$value['term'];
        $student_map_id=$value['student_map_id'];
        

        $photo_extension = ConfigUtilities::match($supported_extensions, $value['register_number'] . $extension);
        $stu_photo = $photo_extension != "" ? $stu_directory . $value['register_number'] . "." . $photo_extension : $stu_directory . "stu_sample.jpg";
        
        $dob= strtoupper(date('d-m-Y',strtotime($value['dob'])));
        $count_col_spans = 0;
       
       // $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$print_student_map_id,$value['term'],$value['semester']);
    

       

       
        if ($previous_reg_number != $value['register_number']) 
        {
            $new_stu_flag = $new_stu_flag + 1;
            $print_gender = $value['gender'] == 'F' ? 'FEMALE' : 'MALE';
            $is_additional_printed = 0;
             //$cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id'],$term,$semester_number);
        

         //print_r($semester_number);exit;
            if ($new_stu_flag > 1) 
            {    
                  
               $credits_register_row .= "<tr> 
                             <td width='40px' class='part_no_print' height='25px' colspan=2 > I </td>";
                $credits_register_row .= "<td width='40px' class='part_no_print' height='21px' colspan=2 > ".$cgpa_calc['part_1_earned']." </td>";
                $credits_register_row .= "<td width='40px' class='part_no_print' height='21px' colspan=2 > ".$cgpa_calc['part_1_gpa']."</td>";
                $credits_register_row .= "<td width='40px' class='part_no_print' height='21px' colspan=2 > ".$cgpa_calc['part_1_cgpa']."</td>";
                $credits_register_row .= "<td width='40px' class='part_no_print' height='21px' colspan=2 > II </td>";
                $credits_register_row .= "<td width='40px' class='part_no_print' height='21px' colspan=2 > ".$cgpa_calc['part_2_earned']." </td>";
                $credits_register_row .= "<td width='40px' class='part_no_print' height='21px' colspan=2 > ".$cgpa_calc['part_2_gpa']."</td>";
                $credits_register_row .= "<td width='40px' class='part_no_print' height='21px' colspan=2 > ".$cgpa_calc['part_2_cgpa']."</td>";
                $credits_register_row .= "</tr>";

                $credits_earned_row .= "<tr style='padding-top: 10px !important; ' >
                                        <td width='40px' class='part_no_print_1' height='21px' colspan=2 >III</td>";
                $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_3_earned']."</td>";
                $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_3_gpa']."</td>";
                $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_3_cgpa']."</td>";
                $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 > IV</td>";
                 $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_4_earned']."</td>";
                $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_4_gpa']."</td>";
                $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_4_cgpa']."</td>";
                $credits_earned_row .= "</tr>";               
                $footer .= $credits_register_row . $credits_earned_row ."</table>";
                $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$exam_year.'" AND exam_month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and result like "%pass%"')->queryAll();

               //print_r($check_add);exit;
                $check_add_1= new Query();
                $check_add_1->select('c.register_number,subject_code,subject_name,ESE_max as ese_max,ESE_min as ese_min, CIA_max as cia_max,CIA_min as cia_min,part_no, credit_points as  credits,a.CIA,a.ESE,d.semester,a.result,a.total,a.grade_name,a.subject_map_id,a.student_map_id,a.year,a.month,degree_code,programme_name,a.grade_point')
               ->from('coe_value_mark_entry a')
               ->join('JOIN','coe_student_mapping b','
                a.student_map_id=b.coe_student_mapping_id')
               ->join('JOIN','coe_student c', 'b.student_rel_id=c.coe_student_id')
               ->join('JOIN','sub d','a.subject_map_id=d.coe_sub_mapping_id')
               ->join('JOIN', 'coe_value_subjects e','d.val_subject_id=e.coe_val_sub_id')
               ->join('JOIN','coe_bat_deg_reg g' ,'g.coe_bat_deg_reg_id=b.course_batch_mapping_id')
               ->join('JOIN', 'coe_degree h' ,'h.coe_degree_id=g.coe_degree_id')
               ->join('JOIN', 'coe_batch k','k.coe_batch_id=g.coe_batch_id')
               ->join('JOIN','coe_programme i','i.coe_programme_id=g.coe_programme_id')
              ->Where(['a.year' => $exam_year, 'a.month' => $add_month,'b.course_batch_mapping_id'=>$_POST['bat_map_val'],'d.course_type_id'=>122,'a.student_map_id'=>$print_student_map_id])
               ->andWhere(['LIKE','a.result','Pass'])
              ->andWhere(['<>','status_category_type_id', $det_disc_type]);
            $check_add_1->groupBy('a.student_map_id,a.subject_map_id,d.semester')->orderBy('c.register_number');  
            
                 $check_add_2 =$check_add_1->createCommand()->queryAll();
 


                 $check_add_print = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE student_map_id="'.$print_student_map_id.'" and  result like "%pass%"')->queryAll();
         //print_r($check_add_print);exit;
                     $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$exam_year.'" AND exam_month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and result like "%pass%"')->queryAll();


                 $check_add_print_1 = new Query();
                 $check_add_print_1->select('c.register_number,subject_code,subject_name,ESE_max as ese_max,ESE_min as ese_min, CIA_max as cia_max,CIA_min as cia_min,part_no, credit_points as  credits,a.CIA,a.ESE,d.semester,a.result,a.total,a.grade_name,a.subject_map_id,a.student_map_id,a.year,a.month,degree_code,programme_name,a.grade_point')
                ->from('coe_value_mark_entry a')
                ->join('JOIN','coe_student_mapping b','
                 a.student_map_id=b.coe_student_mapping_id')
                ->join('JOIN','coe_student c', 'b.student_rel_id=c.coe_student_id')
                ->join('JOIN','sub d','a.subject_map_id=d.coe_sub_mapping_id')
                ->join('JOIN', 'coe_value_subjects e','d.val_subject_id=e.coe_val_sub_id')
                ->join('JOIN','coe_bat_deg_reg g' ,'g.coe_bat_deg_reg_id=b.course_batch_mapping_id')
                ->join('JOIN', 'coe_degree h' ,'h.coe_degree_id=g.coe_degree_id')
                ->join('JOIN', 'coe_batch k','k.coe_batch_id=g.coe_batch_id')
                ->join('JOIN','coe_programme i','i.coe_programme_id=g.coe_programme_id')
                 ->Where(['a.year' => $exam_year, 'a.month' => $add_month,'b.course_batch_mapping_id'=>$_POST['bat_map_val'],'d.course_type_id'=>122,'a.student_map_id'=>$print_student_map_id])
                 ->andWhere(['LIKE','a.result','Pass'])
                 ->andWhere(['<>','status_category_type_id', $det_disc_type]);
             $check_add_print_1->groupBy('a.student_map_id,a.subject_map_id,d.semester')->orderBy('c.register_number');  
               
                $check_add_print_2=$check_add_print_1->createCommand()->queryAll();


                if(!empty($check_add_print || $check_add_print_2 ))
                {
                    $is_additional_printed = 1;
                }
                if(!empty($check_add || $check_add_2))
                {
                    $body .= "
                    <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td width='60px' align='left'><b>&nbsp;</b></td>
                        <td  valign='top' width='47px' align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='100px' class='put_margin_pg'  align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='440px' class='put_margin_pg' colspan='4' align='center'>## ADDITIONAL CREDIT COURSES ##</td>
                        <td valign='top' width='55px'  align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='55px'  align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='55px'  align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='60px'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='50px'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='60px'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='60px'  align='left'><b> &nbsp; </b></td>
                        <td valign='top'  style='padding-right: 5px;' width='95px'  align='left'><b>&nbsp;</b></td>
                    </tr>
                        </table>";
                    $is_additional_printed = 1;
                    foreach ($check_add as $valuess) 
                    {                
                        $result_stu = 'PASS';        
                        $sub_na = wordwrap(strtoupper($valuess['subject_name']), 30, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                        $subject_name_print = str_replace('&AMP;', '&', $subject_name_print);

                        

                        //$grade_name = $valuess['grade_name'];

                if($value['batch_name']>='2021' && $valuess['result']=="Absent" || $valuess['result']=="ABSENT" || $valuess['result']=="absent")  
                {
                    
                     $grade_name='AAA';
                      
                }
                else
                {
                    $grade_name = $valuess['grade_name'];
                }

                        $grade_point_print = strlen($valuess['grade_point'])==2 ?$valuess['grade_point'].".0":(strlen($valuess['grade_point'])==1 ?$valuess['grade_point'].".0":$valuess['grade_point']);

                        $ese_max_disp = $valuess['ese_maximum']==0?'--':100;
                        $cia_max_disp = $valuess['cia_maximum']==0?'--':100;
                    
                     //$cia_max_disp = $valuess['cia_maximum']==0 &&($valuess['cia_maximum']==100  && $valuess['ese_maximum']!=0)?'--':100;
                   $total_max_disp = ($ese_max_disp!='--' && $cia_max_disp!='--') ?$ese_max_disp+$cia_max_disp:($ese_max_disp=='--'?$cia_max_disp:$ese_max_disp);
                     //$total_max_disp = ($ese_max_disp!='--' && $cia_max_disp!='--') ?$ese_max_disp+$cia_max_disp:($ese_max_disp=='--'?$cia_max_disp:$ese_max_disp=='--'?$cia_max_disp:$ese_max_disp);
                    $disp_ese = $valuess['ese_maximum']==0?'--':$valuess['ESE'];
                    $disp_cia = $valuess['cia_maximum']==0?'--':$valuess['CIA'];

                    $total_disp_res = $valuess['total'];
            if($value['CIA_max'] == 0 && $value['ESE_max'] == 0)

            {
                $body .= "
                        <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$valuess['part_no']]. "</b></td>
                                    <td  valign='top' width='60px' align='center' style='padding-left:-15x;'><b>" . $semester_array[$valuess['semester']]. "</b></td>
                                    <td valign='top' width='115px'   colspan='3'  align='left' class='sub' style='padding-left:-5px;'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                                      <td valign='top' style='padding-left:-135px;' align='left' width='430px'  >" . $subject_name_print . "</td>
                                     
                                    <td valign='top'  width='95px' colspan='8'  align='center'><b>" . $result_stu . "</b></td></tr>
                        </table>";  
           
           } // ZERO ZERO SUBJECTS

           else{
                        $body .= "
                        <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$valuess['part_no']]. "</b></td>
                                    <td  valign='top' width='65px' align='center' style='padding-left:-30px;'><b>" . $semester_array[$valuess['semester']]. "</b></td>
                                    <td valign='top' width='115px'  colspan='3' class='subject_code' align='left' style='padding-left:-10px;'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                                    <td valign='top' width='435px' colspan='8' align='left'>" . $subject_name_print . "</td>
                                    <td valign='top' width='50px'  align='left' style='padding-left:-2px;padding-left:5px;'><b>" . $valuess['credits'] . "</b></td>
                                    <td valign='top' width='60px'  align='left' style='padding-left:-8px;'><b>" . $ese_max_disp . "</b></td>
                                    <td valign='top' width='55px'  align='left' style='padding-left:-15px;'><b>" . $cia_max_disp . "</b></td>
                                    <td valign='top' width='60px'  align='center' style='padding-left:-50px;'><b>" . $total_max_disp . "</b></td>
                                    <td valign='top' width='55px'  align='left' style='padding-left:-10px;'><b>" . $disp_ese . "</b></td>
                                    <td valign='top' width='60px'  align='left' style='padding-left:-10px;'><b>" . $disp_cia . "</b></td>
                                    <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $total_disp_res. "</b></td>
                                    <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $grade_point_print . "</b></td>
                                    <td valign='top' width='60px'  align='left' class='tot_gp_pg1'><b> " . strtoupper($grade_name) . " </b></td>
                                    <td valign='top'  style='padding-right: 15px;' width='95px'  align='left' class='tot_gp_pg2'><b>" . $result_stu . "</b></td></tr>
                        </table>";  
                    }
                   }

                    foreach ($check_add_print_2 as $valuessadd) 
                    {                
                        $result_stu = 'PASS';        
                        $sub_na = wordwrap(strtoupper($valuessadd['subject_name']), 30, "\n", true);
                        $sub_na = htmlentities($sub_na);
                        $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                        $subject_name_print = str_replace('&AMP;', '&', $subject_name_print);

                        

                        //$grade_name = $valuess['grade_name'];

                if($value['batch_name']>='2021' && $valuessadd['result']=="Absent" || $valuessadd['result']=="ABSENT" || $valuessadd['result']=="absent")  
                {
                    
                     $grade_name='AAA';
                      
                }
                else
                {
                    $grade_name = $valuessadd['grade_name'];
                }

                        $grade_point_print = strlen($valuessadd['grade_point'])==2 ?$valuessadd['grade_point'].".0":(strlen($valuessadd['grade_point'])==1 ?$valuessadd['grade_point'].".0":$valuessadd['grade_point']);

                        $ese_max_disp = $valuessadd['ese_max']==0?'--':100;
                        $cia_max_disp = $valuessadd['cia_max']==0?'--':100;
                    
                     //$cia_max_disp = $valuess['cia_maximum']==0 &&($valuess['cia_maximum']==100  && $valuess['ese_maximum']!=0)?'--':100;
                   $total_max_disp = ($ese_max_disp!='--' && $cia_max_disp!='--') ?$ese_max_disp+$cia_max_disp:($ese_max_disp=='--'?$cia_max_disp:$ese_max_disp);
                     //$total_max_disp = ($ese_max_disp!='--' && $cia_max_disp!='--') ?$ese_max_disp+$cia_max_disp:($ese_max_disp=='--'?$cia_max_disp:$ese_max_disp=='--'?$cia_max_disp:$ese_max_disp);
                   // $disp_ese = $valuessadd['ese_max']==0?'--':$valuessadd['ESE'];
                  if($valuessadd['ese_max']==50)
                   {

                    $disp_ese=$valuessadd['ESE']*2;

                   }
                  else
                   {
                    $disp_ese = $valuessadd['ese_max']==0?'--':$valuessadd['ESE'];

                    }
                   
                    $disp_cia = $valuessadd['cia_max']==0?'--':$valuessadd['CIA'];

                    $total_disp_res = $valuessadd['total'];
            if($value['CIA_max'] == 0 && $value['ESE_max'] == 0)

            {
                $body .= "
                        <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$valuessadd['part_no']]. "</b></td>
                                    <td  valign='top' width='60px' align='center' style='padding-left:-15x;'><b>" . $semester_array[$valuessadd['semester']]. "</b></td>
                                    <td valign='top' width='115px'   colspan='3'  align='left' class='sub' style='padding-left:-5px;'><b>" . strtoupper($valuessadd['subject_code']) . "</b></td>
                                      <td valign='top' style='padding-left:-135px;' align='left' width='430px'  >" . $subject_name_print . "</td>
                                     
                                    <td valign='top'  width='95px' colspan='8'  align='center'><b>" . $result_stu . "</b></td></tr>
                        </table>";  
           
           } // ZERO ZERO SUBJECTS

           else{
                        $body .= "
                        <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$valuessadd['part_no']]. "</b></td>
                                    <td  valign='top' width='65px' align='center' style='padding-left:-30px;'><b>" . $semester_array[$valuessadd['semester']]. "</b></td>
                                    <td valign='top' width='115px'  colspan='3' class='subject_code' align='left' style='padding-left:-10px;'><b>" . strtoupper($valuessadd['subject_code']) . "</b></td>
                                    <td valign='top' width='435px' colspan='8' align='left'>" . $subject_name_print . "</td>
                                    <td valign='top' width='50px'  align='left' style='padding-left:-2px;padding-left:5px;'><b>" . $valuessadd['credits'] . "</b></td>
                                    <td valign='top' width='60px'  align='left' style='padding-left:-8px;'><b>" . $ese_max_disp . "</b></td>
                                    <td valign='top' width='55px'  align='left' style='padding-left:-15px;'><b>" . $cia_max_disp . "</b></td>
                                    <td valign='top' width='60px'  align='center' style='padding-left:-50px;'><b>" . $total_max_disp . "</b></td>
                                    <td valign='top' width='55px'  align='left' style='padding-left:-10px;'><b>" . $disp_ese . "</b></td>
                                    <td valign='top' width='60px'  align='left' style='padding-left:-10px;'><b>" . $disp_cia . "</b></td>
                                    <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $total_disp_res. "</b></td>
                                    <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $grade_point_print . "</b></td>
                                    <td valign='top' width='60px'  align='left' class='tot_gp_pg1'><b> " . strtoupper($grade_name) . " </b></td>
                                    <td valign='top'  style='padding-right: 15px;' width='95px'  align='left' class='tot_gp_pg2'><b>" . $result_stu . "</b></td></tr>
                        </table>";  
                    }
                   }



                }







                $body .= "
                    <table width='100%' align='left' style='border: none !important; font-size: 20px;'  border='0'>
                        <tr>
                            <td class='make_bold_font'  width='350px' colspan='10'  align='center'> ~ END OF STATEMENT ~ </td>
                            
                            <td colspan='6' width='300px'  align='right'>&nbsp;</td>
                        </tr>
                    </table>";
                $margin= $is_additional_printed == 1?(1062+$bottom_margin):($bottom_margin+1059);
                $merge_body = "<tr><td colspan='16' width='100%' height='".$margin."px' valign='top'  >" . $body . "</td></tr>
                   <tr><td colspan='16' width='30px' height='58px' >&nbsp;</td></tr>";
                $html = $header . $merge_body . $footer."<pagebreak />";
                $print_stu_data .= $html;
                $header = "";
                $body = "";
                $footer = ""; $credits_register_row = $credits_earned_row = $sgpa_row = $cumulative_row = '';
                $print_total_credits = array_filter(array(''=>''));
                $new_stu_flag = 1;
            }
           
            $header .= "<table width='100%' autosize='1' style='padding-top: ".$top_margin."px;'  >";
            $header .= '<tr>
                           <td height="150px" style="border: none; padding-right: 45px;  padding-top: 25px; text-align: right;" colspan="16" >
                                <img width="150" height="150" src="' . $stu_photo . '" alt="' . $stu_photo . '" Photo >
                                
                            </td>
                        </tr>'.$add_tr_starting;

            $prg_name = strlen($value['programme_name']);
            $prg_name_print = $prg_name>28?'-183px':'-90px';

                        $padding_change = 
            $header .= "
                    <tr>
                        <td colspan='16' width='100%'  >
                            <table width='100%' style='border: none !important; text-align: center !important; padding-top: 1px; font-size: 22px;'  border='0'>

                                <tr style='padding: 10px; ' class='push_down' >
                                    <td  class='line_height' style='padding-left: 40px; padding-bottom: 29px;' colspan='2' align='left' width='100px;' ><b>" . strtoupper($value['degree_code']) . "</b></td>
                                    <td style='padding-bottom: 29px; padding-left: ".$prg_name_print.";' class='line_height' align='left' colspan='8' width='350px;' ><b>" . strtoupper($value['programme_name']) . "</b></td>
                                    <td style='padding-bottom: 29px;' class='line_height' colspan='3' align='left' width='150px;'  >".$date."</td>
                                    <td style='padding-bottom: 29px;' class='line_height_reg' colspan='3' align='center' width='250px;'  ><b>" . strtoupper( $month_disp)  . "  " . $exam_year . " </b> </td>
                                </tr>


                            
                                <tr>
                                    <td class='line_height' align='center' colspan='10' width='350px;' ><b>" . strtoupper($value['name']) . "</b></td>
                                    <td colspan='3' align='left' width='150px;' class='line_height' ><b>" . $dob . "</b></td>
                                    <td  class='line_height_reg' colspan='3' width='150px;' align='center'  > <b>" . strtoupper($value['register_number']) . "</b></td>
                                </tr>
                            
                                <tr>
                                    <td class='line_height' colspan='16' class='stu_sub_gap' >&nbsp; </td>
                                </tr>
                            </table>
                        </td>
                    </tr>";

                    //print_r($value);exit;
            $total_credits = '';
            $total_earned_credits = '';
            $passed_grade_points = '';
            
               $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
               /* if ($value['batch_name']<2021)
               {
                    $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
               }
               else 
            
                {
                   $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AAA" : $value['result'];
                }*/
                
                $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;
    
                $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;

                $result_stu = ($value['grade_name'] == "WD" || $value['grade_name'] == "wd" || $value['withheld'] == "w" || $value['withheld'] == "W" ) ? "RA" : $result_stu;
                $sub_na = wordwrap(strtoupper($value['subject_name']), 30, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                $subject_name_print = str_replace('&AMP;', '&', $subject_name_print);

               // $grade_name = $value['grade_name'];
                
             if($value['batch_name']>='2021' && $value['result']=="Absent" || $value['result']=="ABSENT" || $value['result']=="absent") 
                {
                    
                     $grade_name='AAA';
                     
                }
                elseif($value['grade_name'] == "WH" || $value['grade_name'] == "wh" || $value['withheld'] == "w" || $value['withheld'] == "W")
                {
                    $grade_name='--';
                }
                else
                {
                    $grade_name = $value['grade_name'];
                }
                //$grade_name = ($value['grade_name'] == "WH" || $value['grade_name'] == "wh" || $value['withheld'] == "w" || $value['withheld'] == "W") ? '--' : $Ab;
                //$grade_name = ($value['grade_name'] == "WD" || $value['grade_name'] == "wd") ? 'W' :$Ab;

                $grade_point_send = strlen($value['grade_point'])==2 ?$value['grade_point'].".0":(strlen($value['grade_point'])==1 ?$value['grade_point'].".0":$value['grade_point']);

                $grade_point_print = ($value['grade_point']==0 || $grade_point_send==0 || $grade_name=='--') ?"--":$grade_point_send;
                
                $ese_max_disp = $value['ESE_max']==0?'--':$value['ESE_max'];
                $cia_max_disp = $value['CIA_max']==0?'--':$value['CIA_max'];
                $total_max_disp = ( ($value['CIA_max']+$value['ESE_max'])==0 )?'--':($value['CIA_max']+$value['ESE_max']);
                $disp_ese = $value['ESE']==0?'--':(($value['grade_name'] == "WH" || $value['grade_name'] == "wh"  || $value['withheld'] == "w" || $value['withheld'] == "W")?'--':$value['ESE']);
                $disp_cia = $value['CIA']==0?'--':$value['CIA'];

                $total_disp_res = ($value['CIA']+$value['ESE'])==0?'--': (($value['withheld'] == "w" || $value['withheld'] == "W" || $value['grade_name'] == "WH" || $value['grade_name'] == "wh" )?$value['CIA']:($value['CIA']+$value['ESE'])) ;


                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu,'part_no'=>$value['part_no']];
                if($value['CIA_max'] == 0 && $value['ESE_max'] == 0)
            {
                $body .= "
                        <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$value['part_no']]. "</b></td>
                                    <td  valign='top' width='60px'  align='center' style='padding-left:-30px;'><b>" . $semester_array[$value['semester']]. "</b></td>
                                    <td valign='top' width='115px' class='sub' colspan='3'  align='left' style='padding-left:-5px;'><b>" . strtoupper($value['subject_code']) . "</b></td>
                                      <td valign='top' style='padding-left:-135px;' align='left' width='430px'  >" . $subject_name_print . "</td>
                                     
                                     <td valign='top'  width='95px' colspan='8'  align='center'><b>" . $result_stu . "</b></td></tr>
                        </table>";  
            
           } // ZERO ZERO SUBJECTS
           else{

               $body .= "
            <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td  valign='top' width='65px' align='center' style='padding-left:-30px;'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='115px'  colspan='3' class='subject_code' align='left' style='padding-left:-10px;'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='435px'  colspan='8' align='left'>" . $subject_name_print . "</td>
                        <td valign='top' width='50px'  align='left' style='padding-left:-2px;padding-left:5px;'><b>" . $value['credit_points'] . "</b></td>
                        <td valign='top' width='60px'  align='left' style='padding-left:-8px;'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='55px'  align='left' style='padding-left:-15px;'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='60px'  align='center' style='padding-left:-50px;'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='55px'  align='left' style='padding-left:-10px;'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='60px'  align='left' style='padding-left:-10px;'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $grade_point_print . "</b></td>
                        <td valign='top' width='60px'  align='left' class='tot_gp_pg1'><b> " . strtoupper($grade_name) . " </b></td>
                        <td valign='top'  style='padding-right: 15px;' width='95px'  align='left' class='tot_gp_pg2'><b>" . $result_stu . "</b></td></tr>
            </table>";  
                
             $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id'],$term,$semester_number);
        }
        } // If not the same registration number
        else 
        {
            $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id'],$term,$semester_number);
            
            $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
           /*  if ($value['batch_name']<2021)
               {
                    $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AB" : $value['result'];
               }
               else 
            
                {
                   $result_stu = $value['result'] == "Absent" || $value['result'] == "ABSENT" || $value['result'] == "absent" || $value['result'] == "AB" ? "AAA" : $value['result'];
                }*/
                
                $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;
    
                $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;

                $result_stu = ($value['grade_name'] == "WD" || $value['grade_name'] == "wd" || $value['withheld'] == "w" || $value['withheld'] == "W" ) ? "RA" : $result_stu;
                $sub_na = wordwrap(strtoupper($value['subject_name']), 30, "\n", true);
                $sub_na = htmlentities($sub_na);
                $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                $subject_name_print = str_replace('&AMP;', '&', $subject_name_print);

                //$grade_name = $value['grade_name'];
              if($value['batch_name']>='2021' && $value['result']=="Absent" || $value['result']=="ABSENT" || $value['result']=="absent")  
                {
                    
                     $grade_name='AAA';
                      
                }
                elseif($value['grade_name'] == "WH" || $value['grade_name'] == "wh" || $value['withheld'] == "w" || $value['withheld'] == "W")
                {
                    $grade_name='--';
                }

                else
                {
                    $grade_name = $value['grade_name'];
                }

                //$grade_name = ($value['grade_name'] == "WH" || $value['grade_name'] == "wh" || $value['withheld'] == "w" || $value['withheld'] == "W") ? '--' : $Ab;
                //$grade_name = ($value['grade_name'] == "WD" || $value['grade_name'] == "wd") ? 'W' : $Ab;

              
                $grade_point_send = strlen($value['grade_point'])==2 ?$value['grade_point'].".0":(strlen($value['grade_point'])==1 ?$value['grade_point'].".0":$value['grade_point']);

                $grade_point_print = ($value['grade_point']==0 || $grade_point_send==0 || $grade_name=='--') ?"--":$grade_point_send;
                
                $ese_max_disp = $value['ESE_max']==0?'--':$value['ESE_max'];
                $cia_max_disp = $value['CIA_max']==0?'--':$value['CIA_max'];
                $total_max_disp = ( ($value['CIA_max']+$value['ESE_max'])==0 )?'--':($value['CIA_max']+$value['ESE_max']);
                $disp_ese = $value['ESE']==0?'--':(($value['grade_name'] == "WH" || $value['grade_name'] == "wh"  || $value['withheld'] == "w" || $value['withheld'] == "W")?'--':$value['ESE']);
                $disp_cia = $value['CIA']==0?'--':$value['CIA'];

                $total_disp_res = ($value['CIA']+$value['ESE'])==0?'--': (($value['withheld'] == "w" || $value['withheld'] == "W" || $value['grade_name'] == "WH" || $value['grade_name'] == "wh" )?$value['CIA']:($value['CIA']+$value['ESE'])) ;

                $print_total_credits[] = ['sem'=>$value['semester'],'credits'=>$value['credit_points'],'grades'=>$value['grade_point'],'res'=>$result_stu,'part_no'=>$value['part_no']];

                if($value['CIA_max'] == 0 && $value['ESE_max'] == 0)
            {
                $body .= "
                        <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$value['part_no']]. "</b></td>
                                    <td  valign='top' width='60px'  align='center' style='padding-left:-25px;'><b>" . $semester_array[$value['semester']]. "</b></td>
                                    <td valign='top' width='115px'  colspan='3'   class='sub' align='left' style='padding-left:-5px;'><b>" . strtoupper($value['subject_code']) . "</b></td>
                                     <td valign='top' style='padding-left:-135px;' align='left' width='430px'  >" . $subject_name_print . "</td>
                                     
                                     <td valign='top'  width='95px' colspan='8'  align='center'><b>" . $result_stu . "</b></td></tr>
                        </table>";  
           
           } // ZERO ZERO SUBJECTS
           else{

            
            $body .= "<table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$value['part_no']]. "</b></td>
                        <td  valign='top' width='65px' align='center' style='padding-left:-30px;'><b>" . $semester_array[$value['semester']]. "</b></td>
                        <td valign='top' width='115px' colspan='3' class='subject_code' align='left' style='padding-left:-10px;'><b>" . strtoupper($value['subject_code']) . "</b></td>
                        <td valign='top' width='435px'  colspan='8' align='left'>" . $subject_name_print . "</td>
                        <td valign='top' width='50px'  align='left' style='padding-left:-2px;padding-left:5px;'><b>" . $value['credit_points'] . "</b></td>
                        <td valign='top' width='60px'  align='left' style='padding-left:-8px;'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='55px'  align='left' style='padding-left:-15px;'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='60px'  align='center' style='padding-left:-50px;'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='55px'  align='left' style='padding-left:-10px;'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='60px'  align='left' style='padding-left:-10px;'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $grade_point_print . "</b></td>
                        <td valign='top' width='60px'  align='left' class='tot_gp_pg1'><b> " . strtoupper($grade_name) . " </b></td>
                        <td valign='top'  style='padding-right: 15px;' width='95px'  align='left' class='tot_gp_pg2'><b>" . $result_stu . "</b></td></tr>
            </table>";  
        }
    }
        $previous_subject_code = $value['subject_code'];
        $previous_reg_number = $value['register_number'];
        $print_student_map_id = $value['student_map_id'];
        $semester_last_print = $value['semester'];
    }// End the foreach variable here

    $is_additional_printed = 0;
    $cgpa_calc = ConfigUtilities::getCgpaCaluclation($exam_year,$app_month,$batch_mapping_id,$value['student_map_id'],$term,$semester_number);

    //print_r($cgpa_calc);exit;
    $credits_register_row .= "<tr> 
                             <td width='40px' class='part_no_print' height='25px' colspan=2 > I </td>";
    $credits_register_row .= "<td width='40px' class='part_no_print' height='25px' colspan=2 > ".$cgpa_calc['part_1_earned']." </td>";
    $credits_register_row .= "<td width='40px' class='part_no_print' height='25px' colspan=2 > ".$cgpa_calc['part_1_gpa']."</td>";
    $credits_register_row .= "<td width='40px' class='part_no_print' height='25px' colspan=2 > ".$cgpa_calc['part_1_cgpa']."</td>";
    $credits_register_row .= "<td width='40px' class='part_no_print' height='25px' colspan=2 > II </td>";
    $credits_register_row .= "<td width='40px' class='part_no_print' height='25px' colspan=2 > ".$cgpa_calc['part_2_earned']." </td>";
    $credits_register_row .= "<td width='40px' class='part_no_print' height='25px' colspan=2 > ".$cgpa_calc['part_2_gpa']."</td>";
    $credits_register_row .= "<td width='40px' class='part_no_print' height='25px' colspan=2 > ".$cgpa_calc['part_2_cgpa']."</td>";
    $credits_register_row .= "</tr>";

    $credits_earned_row .= "<tr style='padding-top: 10x !important; '>
                            <td width='40px' class='part_no_print_1' height='21px' colspan=2 >III</td>";
    $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_3_earned']."</td>";
    $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_3_gpa']."</td>";
    $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_3_cgpa']."</td>";
    $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 > IV</td>";
     $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_4_earned']."</td>";
    $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_4_gpa']."</td>";
    $credits_earned_row .= "<td width='40px' class='part_no_print_1' height='21px' colspan=2 >".$cgpa_calc['part_4_cgpa']."</td>";
    $credits_earned_row .= "</tr>";               
    $footer .= $credits_register_row . $credits_earned_row ."</table>";
    $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
    $check_add = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE exam_year="'.$exam_year.'" AND exam_month="'.$add_month.'" and student_map_id="'.$print_student_map_id.'" and result like "%pass%"')->queryAll();
     $check_add_1 = new  Query();
     $check_add_1->select('c.register_number,subject_code,subject_name,ESE_max as ese_max,ESE_min as ese_min, CIA_max as cia_max,CIA_min as cia_min,part_no, credit_points as  credits,a.CIA,a.ESE,d.semester,a.result,a.total,a.grade_name,a.subject_map_id,a.student_map_id,a.year,a.month,degree_code,programme_name,a.grade_point')
    ->from('coe_value_mark_entry a')
    ->join('JOIN','coe_student_mapping b','
        a.student_map_id=b.coe_student_mapping_id')
    ->join('JOIN','coe_student c', 'b.student_rel_id=c.coe_student_id')
    ->join('JOIN','sub d','a.subject_map_id=d.coe_sub_mapping_id')
    ->join('JOIN', 'coe_value_subjects e','d.val_subject_id=e.coe_val_sub_id')
    ->join('JOIN','coe_bat_deg_reg g' ,'g.coe_bat_deg_reg_id=b.course_batch_mapping_id')
    ->join('JOIN', 'coe_degree h' ,'h.coe_degree_id=g.coe_degree_id')
    ->join('JOIN', 'coe_batch k','k.coe_batch_id=g.coe_batch_id')
    ->join('JOIN','coe_programme i','i.coe_programme_id=g.coe_programme_id')
    ->Where(['a.year' => $exam_year, 'a.month' => $add_month,'b.course_batch_mapping_id'=>$_POST['bat_map_val'],'d.course_type_id'=>122,'a.student_map_id'=>$print_student_map_id])
    ->andWhere(['LIKE','a.result','Pass'])
    ->andWhere(['<>','status_category_type_id', $det_disc_type]);
     $check_add_1->groupBy('a.student_map_id,a.subject_map_id,d.semester')->orderBy('c.register_number'); 
   
     $check_add_2 =$check_add_1->createCommand()->queryAll();

//print_r($check_add_1);exit;
     $check_add_print = Yii::$app->db->createCommand('SELECT * FROM coe_additional_credits WHERE student_map_id="'.$print_student_map_id.'" and  result like "%pass%"')->queryAll();

//print_r($check_add_print);exit;
     

     $check_add_print_1 = new Query();
     $check_add_print_1->select('c.register_number,subject_code,subject_name,ESE_max as ese_max,ESE_min as ese_min, CIA_max as cia_max,CIA_min as cia_min,part_no, credit_points as  credits,a.CIA,a.ESE,d.semester,a.result,a.total,a.grade_name,a.subject_map_id,a.student_map_id,a.year,a.month,degree_code,programme_name,a.grade_point')
    ->from('coe_value_mark_entry a')
    ->join('JOIN','coe_student_mapping b','
        a.student_map_id=b.coe_student_mapping_id')
    ->join('JOIN','coe_student c', 'b.student_rel_id=c.coe_student_id')
    ->join('JOIN','sub d','a.subject_map_id=d.coe_sub_mapping_id')
    ->join('JOIN', 'coe_value_subjects e','d.val_subject_id=e.coe_val_sub_id')
    ->join('JOIN','coe_bat_deg_reg g' ,'g.coe_bat_deg_reg_id=b.course_batch_mapping_id')
    ->join('JOIN', 'coe_degree h' ,'h.coe_degree_id=g.coe_degree_id')
    ->join('JOIN', 'coe_batch k','k.coe_batch_id=g.coe_batch_id')
    ->join('JOIN','coe_programme i','i.coe_programme_id=g.coe_programme_id')
    ->Where(['a.year' => $exam_year, 'a.month' => $add_month,'b.course_batch_mapping_id'=>$_POST['bat_map_val'],'d.course_type_id'=>122,'a.student_map_id'=>$print_student_map_id])
    ->andWhere(['LIKE','a.result','Pass'])
    ->andWhere(['<>','status_category_type_id', $det_disc_type]);
     $check_add_print_1->groupBy('a.student_map_id,a.subject_map_id,d.semester')->orderBy('c.register_number'); 
   
     
    $check_add_print_2=$check_add_print_1->createCommand()->queryAll();


    //print_r($cgpa_calc['part_3_cgpa']);exit;
    //print_r($check_add_print_2);exit;
                
    
    


    if(!empty($check_add_print || $check_add_print_2))
    {
        $is_additional_printed = 1;
    }
    if(!empty($check_add || $check_add_2))
    {
        $body .= "
        <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td valign='top' width='60px' align='left'><b>&nbsp;</b></td>
                        <td  valign='top' width='47px' align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='100px' class='put_margin_pg'  align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='440px' class='put_margin_pg' colspan='4' align='center'>## ADDITIONAL CREDIT COURSES ##</td>
                        <td valign='top' width='55px'  align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='55px'  align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='55px'  align='center'><b>&nbsp;</b></td>
                        <td valign='top' width='60px'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='50px'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='60px'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>&nbsp;</b></td>
                        <td valign='top' width='60px'  align='left'><b> &nbsp; </b></td>
                        <td valign='top'  style='padding-right: 5px;' width='95px'  align='left'><b>&nbsp;</b></td></tr>
            </table>";
       
        $is_additional_printed = 1;
        foreach ($check_add as $valuess) 
        {                
        $result_stu = 'PASS';        
        $sub_na = wordwrap(strtoupper($valuess['subject_name']), 30, "\n", true);
        $sub_na = htmlentities($sub_na);
        $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
        $subject_name_print = str_replace('&AMP;', '&', $subject_name_print);

        //$grade_name = $valuess['grade_name'];
               if($value['batch_name']>='2021' && $valuess['result']=="Absent" || $valuess['result']=="ABSENT" || $valuess['result']=="absent")  
                {
                    
                      $grade_name='AAA';
                }
                else
                {
                    $grade_name = $valuess['grade_name'];
                }
        $grade_point_print = strlen($valuess['grade_point'])==2 ?$valuess['grade_point'].".0":(strlen($valuess['grade_point'])==1 ?$valuess['grade_point'].".0":$valuess['grade_point']);

        $ese_max_disp = $valuess['ese_maximum']==0?'--':100;
        $cia_max_disp = $valuess['cia_maximum']==0?'--':100;
       // $cia_max_disp = $valuess['cia_maximum']==0 &&($valuess['cia_maximum']==100  && $valuess['ese_maximum']!=0)?'--':100;
         $total_max_disp = ($ese_max_disp!='--' && $cia_max_disp!='--') ?$ese_max_disp+$cia_max_disp:($ese_max_disp=='--'?$cia_max_disp:$ese_max_disp);
        $disp_ese = $valuess['ese_maximum']==0?'--':$valuess['ESE'];
        $disp_cia = $valuess['cia_maximum']==0?'--':$valuess['CIA'];

        $total_disp_res = $valuess['total'];
         if($valuess['cia_maximum'] == 0 && $valuess['ese_maximum'] == 0)
            {
                $body .= "
                        <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$valuess['part_no']]. "</b></td>
                                    <td  valign='top' width='60px' align='center' style='padding-left:-25px;'><b>" . $semester_array[$valuess['semester']]. "</b></td>
                                    <td valign='top' width='115px'  colspan='3'   class='sub' align='left' style='padding-left:-5px;'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                                    <td valign='top' style='padding-left:-135px;' align='left' width='430px'  >" . $subject_name_print . "</td>
                                     
                                    <td valign='top'  width='95px' colspan='8'  align='center'><b>" . $result_stu . "</b></td></tr>
                        </table>";  
            $total_sub_count++;
           } 
           else{
           // ZERO ZERO SUBJECTS
            $body .= "
            <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$valuess['part_no']]. "</b></td>
                        <td  valign='top' width='65px' align='center' style='padding-left:-30px;'><b>" . $semester_array[$valuess['semester']]. "</b></td>
                        <td valign='top' width='115px' colspan='3' class='subject_code'   align='left' style='padding-left:-10px;'><b>" . strtoupper($valuess['subject_code']) . "</b></td>
                        <td valign='top' width='435px'  colspan='8' align='left'>" . $subject_name_print . "</td>
                        <td valign='top' width='50px'  align='left' style='padding-left:-2px;padding-left:5px;'><b>" . $valuess['credits'] . "</b></td>
                        <td valign='top' width='60px'  align='left' style='padding-left:-8px;'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='55px'  align='left' style='padding-left:-15px;'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='60px'  align='center' style='padding-left:-50px;'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='55px'  align='left' style='padding-left:-10px;'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='60px'  align='left' style='padding-left:-10px;'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $grade_point_print . "</b></td>
                        <td valign='top' width='60px'  align='left' class='tot_gp_pg1'><b> " . strtoupper($grade_name) . " </b></td>
                        <td valign='top'  style='padding-right: 15px;' width='95px'  align='left' class='tot_gp_pg2'><b>" . $result_stu . "</b></td></tr>
            </table>";  
        }
    }
    
     foreach ($check_add_print_2 as $valuessadd) 
      {             
       
        $result_stu = 'PASS';        
        $sub_na = wordwrap(strtoupper($valuessadd['subject_name']), 30, "\n", true);
        $sub_na = htmlentities($sub_na);
        $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
        $subject_name_print = str_replace('&AMP;', '&', $subject_name_print);

        //$grade_name = $valuess['grade_name'];
               if($value['batch_name']>='2021' && $valuessadd['result']=="Absent" || $valuessadd['result']=="ABSENT" || $valuessadd['result']=="absent")  
                {
                    
                      $grade_name='AAA';
                }
                else
                {
                    $grade_name = $valuessadd['grade_name'];
                }
        $grade_point_print = strlen($valuessadd['grade_point'])==2 ?$valuessadd['grade_point'].".0":(strlen($valuessadd['grade_point'])==1 ?$valuessadd['grade_point'].".0":$valuessadd['grade_point']);

        $ese_max_disp = $valuessadd['ese_max']==0?'--':100;
        $cia_max_disp = $valuessadd['cia_max']==0?'--':100;
       // $cia_max_disp = $valuess['cia_maximum']==0 &&($valuess['cia_maximum']==100  && $valuess['ese_maximum']!=0)?'--':100;
         $total_max_disp = ($ese_max_disp!='--' && $cia_max_disp!='--') ?$ese_max_disp+$cia_max_disp:($ese_max_disp=='--'?$cia_max_disp:$ese_max_disp);
        //$disp_ese = $valuessadd['ese_max']==0?'--':$valuessadd['ESE'];
         if($valuessadd['ese_max']==50)
          {

             $disp_ese=$valuessadd['ESE']*2;

          }
          else
          {
            $disp_ese = $valuessadd['ese_max']==0?'--':$valuessadd['ESE'];

          }
        $disp_cia = $valuessadd['cia_max']==0?'--':$valuessadd['CIA'];

        $total_disp_res = $valuessadd['total'];
      if($valuessadd['cia_max'] == 0 && $valuessadd['ese_max'] == 0)
            {
                $body .= "
                        <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$valuessadd['part_no']]. "</b></td>
                                    <td  valign='top' width='60px' align='center' style='padding-left:-25px;'><b>" . $semester_array[$valuessadd['semester']]. "</b></td>
                                    <td valign='top' width='115px'  colspan='3'   class='sub' align='left' style='padding-left:-5px;'><b>" . strtoupper($valuessadd['subject_code']) . "</b></td>
                                    <td valign='top' style='padding-left:-135px;' align='left' width='430px'  >" . $subject_name_print . "</td>
                                     
                                    <td valign='top'  width='95px' colspan='8'  align='center'><b>" . $result_stu . "</b></td></tr>
                        </table>";  
            $total_sub_count++;
           } 
           else{
           // ZERO ZERO SUBJECTS
            $body .= "
            <table class='body_print_marks' style='line-height: 1.2em; ' border='0' width='100%' >
                    <tr>
                        <td valign='top' width='55px' align='left' style='padding-left:4px;'><b>" . $semester_array[$valuessadd['part_no']]. "</b></td>
                        <td  valign='top' width='65px' align='center' style='padding-left:-30px;'><b>" . $semester_array[$valuessadd['semester']]. "</b></td>
                        <td valign='top' width='115px' colspan='3' class='subject_code'   align='left' style='padding-left:-10px;'><b>" . strtoupper($valuessadd['subject_code']) . "</b></td>
                        <td valign='top' width='435px'  colspan='8' align='left'>" . $subject_name_print . "</td>
                        <td valign='top' width='50px'  align='left' style='padding-left:-2px;padding-left:5px;'><b>" . $valuessadd['credits'] . "</b></td>
                        <td valign='top' width='60px'  align='left' style='padding-left:-8px;'><b>" . $ese_max_disp . "</b></td>
                        <td valign='top' width='55px'  align='left' style='padding-left:-15px;'><b>" . $cia_max_disp . "</b></td>
                        <td valign='top' width='60px'  align='center' style='padding-left:-50px;'><b>" . $total_max_disp . "</b></td>
                        <td valign='top' width='55px'  align='left' style='padding-left:-10px;'><b>" . $disp_ese . "</b></td>
                        <td valign='top' width='60px'  align='left' style='padding-left:-10px;'><b>" . $disp_cia . "</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $total_disp_res . "</b></td>
                        <td valign='top' width='60px' class='tot_gp_pg'  align='left'><b>" . $grade_point_print . "</b></td>
                        <td valign='top' width='60px'  align='left' class='tot_gp_pg1'><b> " . strtoupper($grade_name) . " </b></td>
                        <td valign='top'  style='padding-right: 15px;' width='95px'  align='left' class='tot_gp_pg2'><b>" . $result_stu . "</b></td></tr>
            </table>";  
        }
      }

       
    }
    $body .= "
        <table width='100%' align='left' style='border: none !important; font-size: 20px;'  border='0'>
            <tr>
                <td class='make_bold_font'  width='350px' colspan='10'  align='center'> ~ END OF STATEMENT ~ </td>
                
                <td colspan='6' width='300px'  align='right'>&nbsp;</td>
            </tr>
        </table>";
    $margin= $is_additional_printed == 1?(1062+$bottom_margin):($bottom_margin+1059);
    $merge_body = "<tr><td colspan='16' width='100%' height='".$margin."px' valign='top'  >" . $body . "</td></tr>
                   <tr><td colspan='16' width='30px' height='58px' >&nbsp;</td></tr>";
   // $html = $header . $merge_body . $footer;
    $html = $header. $merge_body . $footer;
    $print_stu_data .= $html;
    //print_r($html);exit;

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
