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
$this->registerCssFile("@web/css/consolidate-markstatement-ug.css");

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
        $esrned_credits = '';
        $add_body_1 =  '';$morethan_4_sems =4; $additional_creditsPrinte =0; $eletive_printed=0;
        $body ="";
        $footer = "";
        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
        $print_register_number = $prev_stu_map_id ="";
        $new_stu_flag=0;
        $print_stu_data="";
        $exam_year= $prev_exam_year = $prev_exam_mont =''; 
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
        
        $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII','13'=>'XIII','14'=>'XIV'];
         

        foreach ($get_console_list as $value) 
        {
            if($first_reg_no==0)
            {
                $cgpa_calculation = ConfigUtilities::getCgpaCaluclationRejoin($value['course_batch_mapping_id'],$value['student_map_id']);
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
                        $total_credits_cgpa = $cgpa_calculation['rejoin_cumulative'];
                        $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
                        $esrned_credits = $cgpa_calculation['cumulative_additional_credits'];
                        $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,is_additional,sub_cat_code, sub_cat_name,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' AND B.coe_student_mapping_id NOT IN(select student_map_id from coe_mark_entry_master where year_of_passing='' and subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where student_map_id=F.student_map_id and result like '%pass%')) and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$previous_reg_number."' and student_map_id='".$prev_stu_map_id."' and is_additional='NO' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code order by subject_code";
                        $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();
                        
                        if(!empty($get_console_list_man))
                        {
                            $prev_man_sub_codde ='';
                            foreach ($get_console_list_man as $key => $mandato) 
                            {
                                if($prev_man_sub_codde!=$mandato['subject_code'])
                                {
                                    $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                                    $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                                    </tr>
                                    </table>";
                                    $total_sub_count++;
                                    $prev_man_sub_codde=$mandato['subject_code'];
                                }                                    
                                $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                </tr>
                                </table>";
                                $total_sub_count++;
                            }
                        }

                        $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$last_num_stu.'" ';
                        $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();
                        if(!empty($getWaiverStatus) && count($getWaiverStatus)>0 && $eletive_printed==0)
                        {
                            $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,is_additional,sub_cat_code, sub_cat_name,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' AND B.coe_student_mapping_id NOT IN(select student_map_id from coe_mark_entry_master where year_of_passing='' and subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where student_map_id=F.student_map_id and result like '%pass%')) and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$previous_reg_number."' and student_map_id='".$prev_stu_map_id."' and is_additional='YES' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code order by subject_code";
                            $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                            if(!empty($get_console_list_man))
                            {
                                $prev_man_sub_codde ='';
                                foreach ($get_console_list_man as $key => $mandato) 
                                {
                                    if($prev_man_sub_codde!=$mandato['subject_code'])
                                    {
                                        $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                                        $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                        <tr>
                                            <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                                        </tr>
                                        </table>";
                                        $total_sub_count++;
                                        $prev_man_sub_codde=$mandato['subject_code'];
                                    }                                    
                                    $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                    </tr>
                                    </table>";
                                    $total_sub_count++;
                                }
                            }
                        }
                        else if($eletive_printed==1)
                        {
                            $config_elect = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER);
                            $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$prev_stu_map_id.'" ';
                            $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();

                            if(!empty($getWaiverStatus))
                            {   $subMap_ids = '';
                                foreach ($getWaiverStatus as $key => $ss) 
                                {
                                    $abce= $ss['subject_codes'];
                                    $explode_1 = explode(',', $abce);
                                    for ($inc=0; $inc <count($explode_1) ; $inc++) 
                                    { 
                                        $sub_code_splite = explode('-', $explode_1[$inc]);
                                        $mapIds = Yii::$app->db->createCommand('select coe_mandatory_subcat_subjects_id FROM coe_mandatory_subcat_subjects as A JOIN coe_mandatory_subjects as B ON B.coe_mandatory_subjects_id=A.man_subject_id and B.man_batch_id=A.coe_batch_id and B.batch_mapping_id=A.batch_map_id where B.subject_code="'.$sub_code_splite[0].'" and A.sub_cat_code="'.$sub_code_splite[1].'" and B.batch_mapping_id="'.$value['course_batch_mapping_id'].'" and A.batch_map_id="'.$value['course_batch_mapping_id'].'" ')->queryScalar();
                                        $subMap_ids .=$mapIds.",";
                                    }
                                    $subMap_ids = trim($subMap_ids,',');
                                }
                                
                            }
                            
                            $get_stu_query_man_add = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,sub_cat_code,sub_cat_name,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$batch_mapping_id."' and G.batch_map_id='".$batch_mapping_id."' AND status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$previous_reg_number."' and student_map_id='".$last_num_stu."' and is_additional='YES' and result like '%Pass%' and subject_map_id NOT IN(".$subMap_ids.")  group by A.register_number,H.subject_code,sub_cat_code order by subject_code";

                            $check_add_pass = Yii::$app->db->createCommand($get_stu_query_man_add)->queryAll();

                            if(count($check_add_pass)>0 && !empty($check_add_pass) && $additional_creditsPrinte==0)
                            {
                                $mandato = '';
                                $additional_creditsPrinte =  count($check_add_pass)<$config_elect?1:0;
                                $old_sub_code_printe = '';
                                $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                        <tr>
                                            <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                            <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                            <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >ADDITIONAL CREDITS EARNED # </td>
                                            <td valign='top'  class='line_height subjects_tables' width='90px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='90px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='90px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                                        </tr>
                                        </table>";
                                        $total_sub_count++;
                                foreach ($check_add_pass as $key => $mandato) 
                                {
                                    if($old_sub_code_printe!=$mandato['subject_code'])
                                    {
                                        $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                                        $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                        <tr>
                                            <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                                        </tr>
                                        </table>";
                                        $total_sub_count++;                        
                                        $old_sub_code_printe=$mandato['subject_code'];
                                    }                    
                                    $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                    </tr>
                                    </table>";
                                    $total_sub_count++;
                                }
                            }
                        }
                        else
                        {
                            $get_stu_query_man_add = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,sub_cat_code,sub_cat_name,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$batch_mapping_id."' and G.batch_map_id='".$batch_mapping_id."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$previous_reg_number."' and student_map_id='".$last_num_stu."' and is_additional='YES' and result like '%Pass%'  group by A.register_number,H.subject_code,sub_cat_code order by subject_code";
                            $check_add_pass = Yii::$app->db->createCommand($get_stu_query_man_add)->queryAll();
                            $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$last_num_stu.'" ';
                            $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();
                            $config_elect = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER);
                            if(count($check_add_pass)>0 && !empty($check_add_pass) && $additional_creditsPrinte==0)
                            {
                                $old_sub_code_printe = '';
                                $mandato = '';
                                $additional_creditsPrinte =  count($check_add_pass)<$config_elect?1:0;
                                $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >ADDITIONAL CREDITS EARNED # </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                                </tr>
                                </table>";
                                $total_sub_count++;
                                foreach ($check_add_pass as $key => $mandato) 
                                {
                                    $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                                    if($old_sub_code_printe!=$mandato['subject_code'])
                                    {
                                        $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                        <tr>
                                            <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                                        </tr>
                                        </table>";
                                        $total_sub_count++;
                                        $old_sub_code_printe=$mandato['subject_code'];
                                    }
                                    
                                    $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                    </tr>
                                    </table>";
                                    $total_sub_count++;
                                    
                                    
                                }
                            }
                        }
                        
                        $cahnge_text = $additional_creditsPrinte==1 ? "CUMULATIVE ADDITIONAL CREDITS EARNED : ".$esrned_credits." ":'&nbsp;';
                        $cumulative_row ="
                       
                        <tr>
                            <td valign='top'   class='line_height print_cgpa_size' colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger' colspan=7 width='400px'  > ".$total_credits_cgpa." </td>
                            
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger PRINT_finalfinal_cgpa' colspan=6 width='100px;'  >".$final_cgpa."</td>
                            <td valign='top' colspan=10 class='line_height print_cgpa_size'  >".$cahnge_text."</td>                                       
                        </tr>
                        <tr>
                            <td valign='top'  colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top'  class='print_classification print_cgpa_size makeitBigger' valign='top' colspan=6 height=123px >".$classification."</td>
                            <td valign='top'  class=' print_cgpa_size' valign='top' colspan=18 height=30px >&nbsp;</td>
                        </tr>                       
                        <tr>                            
                            <td class='line_height print_cgpa_size makeitBigger date_print' valign='top' colspan=6 height=30px > ".$publish_date."
                            </td>
                            <td class='line_height print_cgpa_size' valign='top' colspan=20 height=30px > &nbsp;
                            </td>
                        </tr>";

                        if($additional_creditsPrinte==1)
                        {
                            $add_body_1 .="<table style='line-height: 1.2em; '  class='additional_credits'  border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables additional_credits' width='680px' colspan=6 ># ADDITIONAL CREDIT ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))."S WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                                </tr>
                                </table>";
                            $total_sub_count++;
                            $add_body_1 .= " 
                            <table border=0  >
                                <tr>
                                    <td valign='top' height='30'  class='make_bold_font' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                                    
                                    <td valign='top'  colspan='5' width='170px'  align='right'>&nbsp;&nbsp; </td>
                                </tr>
                            </table>";
                            $total_sub_count++;
                           
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
                                $total_sub_count++;
                        }

                        $footer .=$cumulative_row."</table>";
                        $merge_two_body_tags = $body_1.'</td>'.$body_2.$add_body_1."</td>";
                        $body = $merge_two_body_tags;
                        $merge_body ="<tbody><tr>".$body."</tr></tbody><tr><td valign='top'  colspan=26 height='80px' >&nbsp;</td></tr>";
                        $html = $header .$merge_body.$footer; 
                        $print_stu_data .= $html."<pagebreak sheet-size='A3-L' >";
                       
                        $header =$body_1 = $add_body_1 = $body_2 = "";
                        $body ="";
                        $footer = ""; $eletive_printed = 0;
                        $new_stu_flag = 1; $additional_creditsPrinte = $total_sub_count = 0;
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
                  
            $header.='<td valign="top"  height="150" colspan="13">'; //first td
             $header.='<table class="makeitBigger" width="100%">
                        <tr>
                            <td valign="top" width="350" colspan="6">&nbsp;</td>
                            <td valign="top"  align="left" class="bring_name make_bold_font left_alignment" colspan="7">'.strtoupper($value["name"]).'</td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="5">&nbsp;</td>
                            <td valign="top"  align="center" class="REDUCE_GAP_some_gap" colspan="8">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top" width="350"  colspan="6"></td>
                            <td valign="top"  align="left" class="make_bold_font left_alignment" colspan="7">'.strtoupper($value["degree_code"].". ".$value["programme_name"]).'</td>
                        </tr>
                       </table>
                       </td>';
            $stu_dob = date('d-m-Y',strtotime($value["dob"]));
            $header.='<td valign="top" class="bring_to_the_right"  height="150" colspan="13">
                        <table class="makeitBigger"  width="100%">
                        <tr>
                            <td valign="top"  colspan="6" width="100" >&nbsp; </td>
                            <td valign="top" width="300" class="this_should_be_up make_bold_font" colspan="6">'.strtoupper($value["register_number"]).'</td>
                            <td valign="top"  class="push_regulation make_bold_font  " style="text-align: left;" >'.$value["regulation_year"].'</td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="6" width="100" >&nbsp;</td>
                            <td valign="top" width="300"  class=" this_should_be_up make_bold_font " colspan="6">'.$print_gender.'</td>
                            <td valign="top"  class="push_regulation make_bold_font " style="text-align: left;" >'.$last_appearance.'</td>
                        </tr>
                        <tr>
                            <td valign="top"  colspan="6" width="100" >&nbsp;</td>
                            <td valign="top"  class=" this_should_be_up make_bold_font " colspan="6">'.$stu_dob.'</td>
                            <td valign="top"  >&nbsp;</td>
                        </tr>
                    </table>
                    </td>';

            $header.='</tr><tr><td valign="top"  colspan="26" height="80px" >&nbsp;</td></tr>'; //main tr

            $total_credits ='';
             $total_earned_credits ='';
             $passed_grade_points ='';
             $year_of_passing = ConfigUtilities::getYearOfPassing($value['year_of_passing']);
             $body_1 .= "<td valign='top' class='line_height subjects_tables left_table_td' valign='top'  colspan='13' >";
             $body_2 .= "<td valign='top'  class='line_height subjects_tables right_table_td' valign='top' colspan='13' >"; //1st td
               if($value['ESE_max']==0 && $value['CIA_max']==0)
               {
                    $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                    $sub_na = htmlentities($sub_na);
                    $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";

                    $body_1 .=" 
                    <table style='line-height: 1.2em; ' border='0' width='100%'  >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$value['semester']]."</td>
                            <td valign='top'  class='line_height subjects_tables' width='120px' >".$value['subject_code']."</td>
                            <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($subject_name_print)."</td>
                            <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5> <b>COMPLETED</b> </td>
                        </tr>
                    </table>    
                ";
                $total_sub_count++;
               }
               else
               {
                    $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                    $sub_na = htmlentities($sub_na);
                    $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                    $body_1 .=" 
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($subject_name_print)."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$value['credit_points']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='90x' >".$value['grade_point']."</td>
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
                if($same_semester==6)
                {
                    $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.subject_map_id,F.year,F.month,B.course_batch_mapping_id,is_additional,sub_cat_code,sub_cat_name,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$value['register_number']."' and is_additional='NO' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code order by  subject_code";
            
                    $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                    if(!empty($get_console_list_man) && $same_semester!=$value['semester'])
                    {
                        $prev_man_sub_codde='';
                        foreach ($get_console_list_man as $key => $mandato) 
                        {
                            $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                            if($prev_man_sub_codde!=$mandato['subject_code'])
                            {
                                 $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='90px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp;</td>
                                    </tr>
                                    </table>";
                                    $total_sub_count++;
                                $prev_man_sub_codde=$mandato['subject_code'];
                            }
                            $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='90px' >".strtoupper($mandato['grade_point'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                </tr>
                                </table>";
                            $total_sub_count++;
                            
                        }
                    }
                    $same_semester = $value['semester'];
                }
                else if($same_semester!=$value['semester'])
                {   
                    $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.subject_map_id,F.year,F.month,B.course_batch_mapping_id,is_additional,sub_cat_code,sub_cat_name,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$value['register_number']."' and is_additional='NO' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code  order by subject_code";
            
                    $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                    if(!empty($get_console_list_man) && $same_semester!=$value['semester'])
                    {
                        $prev_man_sub_codde='';
                        foreach ($get_console_list_man as $key => $mandato) 
                        {
                            if($prev_man_sub_codde!=$mandato['subject_code'])
                            {
                                $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                                $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp;</td>
                                </tr>
                                </table>";
                                $total_sub_count++;
                                $prev_man_sub_codde=$mandato['subject_code'];
                            }

                            $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                </tr>
                                </table>";
                            $total_sub_count++;
                            
                        }
                    }
                    $body_2 .=" 
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='sasd line_height subjects_tables' colspan=13>&nbsp; </td>
                                </tr>
                          </table>";
                    
                    $same_semester=$value['semester'];
                    $total_sub_count++;
                }

                if($value['ESE_max']==0 && $value['CIA_max']==0)
                   {
                    $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                    $sub_na = htmlentities($sub_na);
                    $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                        $body_2 .=" 
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($subject_name_print)."</td>
                                <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5 > <b>COMPLETED</b> </td>
                            </tr>
                        </table>";
                    $total_sub_count++;
                   }
               else{
                    $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                    $sub_na = htmlentities($sub_na);
                    $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                        $body_2 .=" 
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($subject_name_print)."</td>
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
            else if($value['status_category_type_id']!=$lateral_entry_id && ($total_sub_count>=42 || $value['semester']>4) )
            {
                if($same_semester==4)
                {
                    $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.subject_map_id,F.year,F.month,B.course_batch_mapping_id,is_additional,sub_cat_code,sub_cat_name,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$value['register_number']."' and is_additional='NO' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code  order by subject_code";
            
                    $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                    if(!empty($get_console_list_man) && $same_semester!=$value['semester'])
                    {
                        $prev_man_sub_codde='';
                        foreach ($get_console_list_man as $key => $mandato) 
                        {
                            $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                            if($prev_man_sub_codde!=$mandato['subject_code'])
                            {
                                $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='90px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp;</td>
                                </tr>
                                </table>";
                                $total_sub_count++;
                                $prev_man_sub_codde=$mandato['subject_code'];
                            }                           

                            $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='90px' >".strtoupper($mandato['grade_point'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                </tr>
                                </table>";
                            $total_sub_count++;
                            
                        }
                    }
                    $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$value['student_map_id'].'" ';
                    $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();
                    if(!empty($getWaiverStatus))
                    {
                        $eletive_printed = 1;
                        $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.subject_map_id,F.year,F.month,B.course_batch_mapping_id,is_additional,sub_cat_code,sub_cat_name,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$value['register_number']."' and is_additional='YES' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code  order by subject_code";            
                        $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                        if(!empty($get_console_list_man) && $same_semester!=$value['semester'])
                        {
                            $prev_man_sub_codde='';
                            foreach ($get_console_list_man as $key => $mandato) 
                            {
                                $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                                if($prev_man_sub_codde!=$mandato['subject_code'])
                                {
                                    $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp;</td>
                                    </tr>
                                    </table>";
                                    $total_sub_count++;
                                    $prev_man_sub_codde=$mandato['subject_code'];
                                }
                                $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                    </tr>
                                    </table>";
                                $total_sub_count++;
                                
                            }
                        }
                    }
                    $same_semester = $value['semester'];
                }
                else if($same_semester!=$value['semester'])
                {                       
                    $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.subject_map_id,F.year,F.month,B.course_batch_mapping_id,is_additional,sub_cat_code,sub_cat_name,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$value['register_number']."' and is_additional='NO' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code  order by subject_code";
            
                    $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                    if(!empty($get_console_list_man) && $same_semester!=$value['semester'])
                    {
                        $prev_man_sub_codde='';
                        foreach ($get_console_list_man as $key => $mandato) 
                        {
                            $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                            if($prev_man_sub_codde!=$mandato['subject_code'])
                            {
                                $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp;</td>
                                </tr>
                                </table>";
                                $total_sub_count++;
                                $prev_man_sub_codde=$mandato['subject_code'];
                            }
                            $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                </tr>
                                </table>";
                            $total_sub_count++;
                            
                        }
                    }     
                    $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$value['student_map_id'].'" ';
                    $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();
                    if(!empty($getWaiverStatus))
                    {
                        $eletive_printed = 1;
                        $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.subject_map_id,F.year,F.month,B.course_batch_mapping_id,is_additional,sub_cat_code,sub_cat_name,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$value['register_number']."' and is_additional='YES' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code  order by subject_code";            
                        $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                        if(!empty($get_console_list_man) && $same_semester!=$value['semester'])
                        {
                            $prev_man_sub_codde='';
                            foreach ($get_console_list_man as $key => $mandato) 
                            {
                                $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                                if($prev_man_sub_codde!=$mandato['subject_code'])
                                {
                                    $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp;</td>
                                    </tr>
                                    </table>";
                                    $total_sub_count++;
                                    $prev_man_sub_codde=$mandato['subject_code'];
                                }
                                $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                    </tr>
                                    </table>";
                                $total_sub_count++;
                                
                            }
                        }
                    }

                    $body_2 .=" 
                            <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='sasd line_height subjects_tables' colspan=13>&nbsp; </td>
                                </tr>
                          </table>";
                    
                    $same_semester=$value['semester'];
                    $total_sub_count++;
                }
                if($value['ESE_max']==0 && $value['CIA_max']==0)
                   {
                    $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                    $sub_na = htmlentities($sub_na);
                    $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                        $body_2 .=" 
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($subject_name_print)."</td>
                                <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5 > <b>COMPLETED</b> </td>
                            </tr>
                        </table>";
                    $total_sub_count++;
                   }
               else{
                $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                    $sub_na = htmlentities($sub_na);
                    $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                        $body_2 .=" 
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($subject_name_print)."</td>
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
                    $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.subject_map_id,F.grade_name,F.year,F.month,B.course_batch_mapping_id,is_additional,sub_cat_code,sub_cat_name,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$value['register_number']."' and is_additional='NO' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code  order by subject_code";
            
                    $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                    if(!empty($get_console_list_man) && $same_semester!=$value['semester'])
                    {
                        $prev_man_sub_codde='';
                        foreach ($get_console_list_man as $key => $mandato) 
                        {                           
                            $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                            if($prev_man_sub_codde!=$mandato['subject_code'])
                            {
                                $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='90px' >&nbsp;</td>
                                    <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp;</td>
                                </tr>
                                </table>";
                                $total_sub_count++;
                                $prev_man_sub_codde=$mandato['subject_code'];
                            }                            

                            $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                <td valign='top'  class='line_height subjects_tables' width='90px' >".strtoupper($mandato['grade_point'])." </td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                            </tr>
                            </table>";
                            $total_sub_count++;
                            
                        }
                    }
                    $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$value['student_map_id'].'" ';
                    $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();
                    if(!empty($getWaiverStatus))
                    {
                        $eletive_printed = 1;
                        $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.subject_map_id,F.year,F.month,B.course_batch_mapping_id,is_additional,sub_cat_code,sub_cat_name,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$value['register_number']."' and is_additional='YES' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code  order by subject_code";            
                        $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                        if(!empty($get_console_list_man) && $same_semester!=$value['semester'])
                        {
                            $prev_man_sub_codde='';
                            foreach ($get_console_list_man as $key => $mandato) 
                            {
                                $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                                if($prev_man_sub_codde!=$mandato['subject_code'])
                                {
                                    $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp;</td>
                                    </tr>
                                    </table>";
                                    $total_sub_count++;
                                    $prev_man_sub_codde=$mandato['subject_code'];
                                }
                                $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                                    <tr>
                                        <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                        <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                                        <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                                    </tr>
                                    </table>";
                                $total_sub_count++;
                                
                            }
                        }
                    }
                    $body_1 .=" <table style='line-height: 1.2em; ' border='0' width='100%' >
                                <tr>
                                    <td valign='top'  class='line_height subjects_tables' colspan=13>&nbsp; </td>
                                </tr>
                            </table> ";
                    $same_semester=$value['semester'];
                    $total_sub_count++;

                }
                   if($value['ESE_max']==0 && $value['CIA_max']==0)
                   {
                    $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                    $sub_na = htmlentities($sub_na);
                    $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                        $body_1 .=" 
                        <table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($subject_name_print)."</td>
                                <td valign='top'  class='line_height subjects_tables' width='450px' colspan=5 > <b>COMPLETED</b> </td>
                            </tr>
                        </table>    
                    ";
                    $total_sub_count++;
                   }
                   else{
                    $sub_na = wordwrap(strtoupper($value['subject_name']), 50, "\n", true);
                    $sub_na = htmlentities($sub_na);
                    $subject_name_print = "<b>".strtoupper(nl2br($sub_na))."</b>";
                        $body_1 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$value['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$value['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($subject_name_print)."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' >".$value['credit_points']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80x' >".$value['grade_name']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='90x' >".$value['grade_point']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".$year_of_passing."</td>
                            </tr>
                        </table>";
                        $total_sub_count++;
                   }   
                   
               }   
                $cgpa_calculation = ConfigUtilities::getCgpaCaluclationRejoin($value['course_batch_mapping_id'],$value['student_map_id']);
            }
            $previous_subject_code = $value['subject_code'];
            $previous_reg_number=$value['register_number'];
            $prev_stu_map_id =$value['student_map_id']; 
            $last_num_year = $value['year'];
            $last_num_month = $value['month'];
            $last_num_bat = $value['course_batch_mapping_id'];
            $last_num_stu = $value['student_map_id'];

        }
            $cgpa_calculation = ConfigUtilities::getCgpaCaluclationRejoin($last_num_bat,$last_num_stu);

            $semester_number = ConfigUtilities::semCaluclation($exam_year,$app_month,$batch_mapping_id);
            $course_batch_mapping_id =  CoeBatDegReg::findOne($batch_mapping_id);
            $degree_years = Degree::findOne($course_batch_mapping_id->coe_degree_id);
            $total_semesters = $degree_years->degree_total_semesters;
            $esrned_credits = $cgpa_calculation['cumulative_additional_credits'];
            $colspan_merge = (26-$total_semesters);

            $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,sub_cat_code,sub_cat_name,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$previous_reg_number."' and student_map_id='".$last_num_stu."' and is_additional='NO' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code  order by subject_code";
            
                $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                if(!empty($get_console_list_man))
                {
                    $prev_man_sub_codde='';
                    foreach ($get_console_list_man as $key => $mandato) 
                    {
                        $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                        if($prev_man_sub_codde!=$mandato['subject_code'])
                        {
                            $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                            </tr>
                            </table>";
                            $total_sub_count++;
                            $prev_man_sub_codde=$mandato['subject_code'];
                        }
                        
                        $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                        </tr>
                        </table>";
                        $total_sub_count++;
                        $stu_map_id_for_add = $mandato['student_map_id'];
                    }

                }
            $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$last_num_stu.'" ';
            $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();
            if(!empty($getWaiverStatus) && count($getWaiverStatus)>0 && $eletive_printed==0)
            {
                $eletive_printed = 1;
                $get_stu_query_man = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,sub_cat_code,sub_cat_name,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$value['course_batch_mapping_id']."' and G.batch_map_id='".$value['course_batch_mapping_id']."' and status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$previous_reg_number."' and student_map_id='".$last_num_stu."' and is_additional='YES' and F.semester='".$same_semester."' group by A.register_number,H.subject_code,sub_cat_code  order by subject_code";
            
                $get_console_list_man = Yii::$app->db->createCommand($get_stu_query_man)->queryAll();

                if(!empty($get_console_list_man))
                {
                    $prev_man_sub_codde='';
                    foreach ($get_console_list_man as $key => $mandato) 
                    {
                        $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                        if($prev_man_sub_codde!=$mandato['subject_code'])
                        {
                            $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                            </tr>
                            </table>";
                            $total_sub_count++;
                            $prev_man_sub_codde=$mandato['subject_code'];
                        }
                        
                        $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                        </tr>
                        </table>";
                        $total_sub_count++;
                        $stu_map_id_for_add = $mandato['student_map_id'];
                    }

                }
            }
            else if($eletive_printed==1)
            {
                $config_elect = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER);
                $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$last_num_stu.'" ';
                $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();

                if(!empty($getWaiverStatus))
                {   $subMap_ids = '';
                    foreach ($getWaiverStatus as $key => $ss) 
                    {
                        $abce= $ss['subject_codes'];
                        $explode_1 = explode(',', $abce);
                        for ($inc=0; $inc <count($explode_1) ; $inc++) 
                        { 
                            $sub_code_splite = explode('-', $explode_1[$inc]);
                            $mapIds = Yii::$app->db->createCommand('select coe_mandatory_subcat_subjects_id FROM coe_mandatory_subcat_subjects as A JOIN coe_mandatory_subjects as B ON B.coe_mandatory_subjects_id=A.man_subject_id and B.man_batch_id=A.coe_batch_id and B.batch_mapping_id=A.batch_map_id where B.subject_code="'.$sub_code_splite[0].'" and A.sub_cat_code="'.$sub_code_splite[1].'" and B.batch_mapping_id="'.$value['course_batch_mapping_id'].'" and A.batch_map_id="'.$value['course_batch_mapping_id'].'" ')->queryScalar();
                            $subMap_ids .=$mapIds.",";
                        }
                        $subMap_ids = trim($subMap_ids,',');
                    }
                    
                }

                
                $get_stu_query_man_add = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,sub_cat_code,sub_cat_name,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$batch_mapping_id."' and G.batch_map_id='".$batch_mapping_id."' AND status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$previous_reg_number."' and student_map_id='".$last_num_stu."' and is_additional='YES' and result like '%Pass%' and subject_map_id NOT IN(".$subMap_ids.")  group by A.register_number,H.subject_code,sub_cat_code order by subject_code";

                $check_add_pass = Yii::$app->db->createCommand($get_stu_query_man_add)->queryAll();
                
                $config_elect = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER);
                if(count($check_add_pass)>0 && !empty($check_add_pass) && $additional_creditsPrinte==0)
                {
                    $mandato = '';
                    $additional_creditsPrinte =  count($check_add_pass)<=$config_elect?1:0;
                    $old_sub_code_printe = '';
                    $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >ADDITIONAL CREDITS EARNED # </td>
                                <td valign='top'  class='line_height subjects_tables' width='90px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='90px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='90px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                            </tr>
                            </table>";
                            $total_sub_count++;
                    foreach ($check_add_pass as $key => $mandato) 
                    {
                        if($old_sub_code_printe!=$mandato['subject_code'])
                        {
                            $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                            $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                            </tr>
                            </table>";
                            $total_sub_count++;                        
                            $old_sub_code_printe=$mandato['subject_code'];
                        }                    
                        $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                        </tr>
                        </table>";
                        $total_sub_count++;
                        
                        
                    }
                }
            }
            else
            {
                $get_stu_query_man_add = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,F.semester,G.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,sub_cat_code,sub_cat_name,F.result,max(F.year_of_passing) as last_appearance FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mandatory_stu_marks as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_mandatory_subcat_subjects as G ON G.coe_mandatory_subcat_subjects_id=F.subject_map_id JOIN coe_mandatory_subjects as H ON H.coe_mandatory_subjects_id=G.man_subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id and I.coe_batch_id=G.coe_batch_id and I.coe_batch_id=H.man_batch_id and G.batch_map_id=C.coe_bat_deg_reg_id and H.batch_mapping_id=C.coe_bat_deg_reg_id where H.batch_mapping_id='".$batch_mapping_id."' and G.batch_map_id='".$batch_mapping_id."' AND status_category_type_id NOT IN('".$det_disc_type."') AND A.register_number ='".$previous_reg_number."' and student_map_id='".$last_num_stu."' and is_additional='YES' and result like '%Pass%'  group by A.register_number,H.subject_code,sub_cat_code order by subject_code";

                $check_add_pass = Yii::$app->db->createCommand($get_stu_query_man_add)->queryAll();
                $checkWaiver = 'SELECT * FROM coe_elective_waiver where student_map_id="'.$last_num_stu.'" ';

                $getWaiverStatus = Yii::$app->db->createCommand($checkWaiver)->queryAll();
                $config_elect = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ELECTIVE_WAIVER);
                if(count($check_add_pass)>0 && !empty($check_add_pass) && $additional_creditsPrinte==0)
                {
                    $mandato = '';
                    $additional_creditsPrinte =  count($check_add_pass)<=$config_elect?1:0;
                    $old_sub_code_printe = '';
                    $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >ADDITIONAL CREDITS EARNED # </td>
                                <td valign='top'  class='line_height subjects_tables' width='90px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='90px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='90px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                            </tr>
                            </table>";
                            $total_sub_count++;
                    foreach ($check_add_pass as $key => $mandato) 
                    {
                        if($old_sub_code_printe!=$mandato['subject_code'])
                        {
                            $man_year_of_passing = ConfigUtilities::getYearOfPassing($mandato['year_of_passing']); 
                            $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                            <tr>
                                <td valign='top'  class='line_height subjects_tables' width='70px' >".$semester_array[$mandato['semester']]."</td>
                                <td valign='top'  class='line_height subjects_tables' width='120px' >".$mandato['subject_code']."</td>
                                <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".strtoupper($mandato['subject_name'])."</td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                                <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                            </tr>
                            </table>";
                            $total_sub_count++;                        
                            $old_sub_code_printe=$mandato['subject_code'];
                        }                    
                        $body_2 .="<table style='line-height: 1.2em; ' border='0' width='100%' >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables' width='680px' colspan=6 >".$mandato['sub_cat_code']."-".strtoupper($mandato['sub_cat_name'])."</td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' > ".$mandato['credit_points']." </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_name'])." </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' >".strtoupper($mandato['grade_point'])." </td>
                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >".strtoupper($man_year_of_passing)." </td>
                        </tr>
                        </table>";
                        $total_sub_count++;
                        
                        
                    }
                }
            }            
            if($additional_creditsPrinte==1)
            {
                $add_body_1 .="<table style='line-height: 1.2em; ' class='additional_credits' border='0' width='100%' >
                        <tr>
                            <td valign='top'  class='line_height subjects_tables' width='70px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables' width='120px' >&nbsp;</td>
                            <td valign='top'  class='line_height subjects_tables additional_credits' width='680px' colspan=6 ># ADDITIONAL CREDIT ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))."S WILL NOT TO BE CONSIDERED FOR CGPA CALCULATION</td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                            <td valign='top'  class='line_height subjects_tables' width='80px' > &nbsp; </td>
                            <td valign='top'  class='line_height subjects_tables' width='250px' colspan=2 >&nbsp; </td>
                        </tr>
                        </table>";
                $total_sub_count++;
                $add_body_1 .= " 
                <table border=0  >
                    <tr>
                        <td valign='top'  class='make_bold_font' height='30' width='600px' colspan='7'  align='center'> ~ END OF STATEMENT ~ </td>
                        <td valign='top'  colspan='5' width='170px'  align='right'>&nbsp;&nbsp; </td>
                    </tr>
                </table>";
                $total_sub_count++;
               
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
                    $total_sub_count++;
            }
            $merge_two_body_tags = $body_1.'</td>'.$body_2.$add_body_1."</td>";
            $body = $merge_two_body_tags;
            $merge_body ="<tbody><tr>".$body."</tr></tbody><tr><td valign='top'  colspan=26 height='80px' >&nbsp;</td></tr>";
            $gpa = 0;
            $total_credits_cgpa=0;
            
            $final_cgpa = $cgpa_calculation['final_cgpa'];
            $total_credits_cgpa = $cgpa_calculation['rejoin_cumulative'];
            $classification = ConfigUtilities::getClassification($final_cgpa,$value['regulation_year'],$previous_reg_number);
            $cahnge_text = $additional_creditsPrinte==1 ? "CUMULATIVE ADDITIONAL CREDITS EARNED : ".$esrned_credits." ":'&nbsp;';
            $cumulative_row ="                        
                        <tr>
                            <td valign='top'   class='line_height print_cgpa_size' colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger' colspan=7 width='400px'  > ".$total_credits_cgpa." </td>
                            
                            <td valign='top'   class='line_height print_cgpa_size makeitBigger PRINT_finalfinal_cgpa' colspan=6 width='100px;'  >".$final_cgpa."</td>
                            <td valign='top' colspan=10 class='line_height print_cgpa_size'  >".$cahnge_text."</td>                                       
                        </tr>
                        
                        <tr>
                            <td valign='top'  colspan=2 width='200px'  > &nbsp; </td>
                            <td valign='top'  class='print_classification print_cgpa_size makeitBigger' valign='top' colspan=6 height=123px >".$classification."</td>
                            <td valign='top'  class='print_cgpa_size' valign='top' colspan=18 height=30px >&nbsp;</td>
                        </tr>                       
                        <tr>                            
                            <td class='line_height print_cgpa_size makeitBigger date_print' valign='top' colspan=6 height=30px > ".$publish_date."
                            </td>
                            <td class='line_height print_cgpa_size' valign='top' colspan=20 height=30px > &nbsp;
                            </td>
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