<?php
use kartik\mpdf\Pdf;
use yii\helpers\Html;
use yii\db\Query;
use app\components\ConfigUtilities;
use yii\helpers\Url;

$stu_directory = Yii::getAlias("@web") . "/resources/stu_photos/";
$absolute_dire = Yii::getAlias("@webroot") . "/resources/stu_photos/";
$change_css_file =  'css/sem-mark-statement.css';

$monthname= Yii::$app->db->createCommand("SELECT category_type FROM coe_category_type WHERE coe_category_type_id=".$month)->queryScalar();


$monthname = $monthname=='Oct/Nov' ||  $monthname=='OCT/NOV' ||  $monthname=='oct/nov' ?'NOV':'APR';

$semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X','11'=>'XI','12'=>'XII','13'=>'XIII','14'=>'XIV'];


$supported_extensions = ConfigUtilities::ValidFileExtension();
//print_r($supported_extensions); exit;

$semester_number = ConfigUtilities::semCaluclation($year, $month, $batch_map_id);

$content='';

$nn=count($mark_statement)-1;
$ii=0;
foreach ($mark_statement as $value) 
{
    $files = glob($absolute_dire . $value['register_number'] . ".*");
    
    //print_r($files); exit;
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
    //echo $extension; exit;
    if(!empty(ConfigUtilities::match($supported_extensions, $value['register_number'] . $extension)))
    {
        $photo_extension = "." .ConfigUtilities::match($supported_extensions, $value['register_number'] . $extension);
    }
    else if(!empty(ConfigUtilities::match($supported_extensions, strtolower($value['register_number']) . $extension)))
    {
        $photo_extension = "." .ConfigUtilities::match($supported_extensions, strtolower($value['register_number']) . $extension);
    }
    else
    {
        $photo_extension = '';
    }
    //echo $photo_extension; exit;
    $stu_photo = $photo_extension != "" ? (empty($stu_directory . $value['register_number'] . $photo_extension) ? ($stu_directory . strtolower($value['register_number']) . $photo_extension): ($stu_directory . $value['register_number'] . $photo_extension) ) : $stu_directory . "stu_sample.jpg";

    $dob= strtoupper(date('d-M-Y',strtotime($value['dob'])));

    $print_gender = $value['gender'] == 'F' ? 'FEMALE' : 'MALE';

    $degreename='';

    $degreeclass=$stuname=$registernumber=$subjectname='';

    if($value['batch_name']>='2021')
    {
        $registernumber='registernumber';
        $stuname='stuname';
        $degreeclass='degreename';
        $subjectname='subjectname';
    }
    else
    {
        $registernumber='registernumber1';
        $stuname='stuname1';
        $degreeclass='degreename1';
        $subjectname='subjectname1';
    }
    
    if($value['batch_name']>='2022' && ($value['programme_code']=='U109'))
    {
        $deg_na = wordwrap(strtoupper(trim($value['programme_name'])), 50, "\n", true);
        $deg_na = htmlentities($deg_na);
        $degreename = "<b>".$value['degree_code'].' '.strtoupper(nl2br($deg_na))."</b>";
        $degreeclass='degreename2';
    }
    else if($value['batch_name']>='2022' && ($value['programme_code']=='U110' || $value['programme_code']=='U111'))
    {
        
        $degreename = "<b>".$value['degree_code'].' '.$value['programme_name']."</b>";
        $degreeclass='degreename3';
    }
    else
    {

        $degreename=strtoupper($value['degree_code'].'. '.$value['programme_name']);
    }

   

    $body_content=$header_content=$umis='';
    if($with_umis=='on' && !empty($value['UMISnumber']))
    {
        $header_content="<div class='header_content'>
                <table width=100%>
                <tr>
                    <td class='emptyspace'>&nbsp;</td>
                    <td colspan=3 class='stu_photo'>
                        <img class='img-responsive' src='" . $stu_photo . "' alt=Photo>
                    </td>
                </tr>
                <tr>
                    <td class='emptyspace'>&nbsp;</td>
                    <td colspan=3 class='umis'>
                        UMIS No.: ".$value['UMISnumber']."
                    </td>
                </tr>
                </table>
                </div>";
    }
    else
    {
        $header_content="<div class='header_content'>
                <table width=100%>
                <tr>
                    <td class='emptyspace'>&nbsp;</td>
                    <td colspan=3 class='stu_photo'>
                        <img class='img-responsive' src='" . $stu_photo . "' alt=Photo>
                    </td>
                </tr>
                <tr>
                    <td class='emptyspace'>&nbsp;</td>
                    <td colspan=3 class='umis'>
                       &nbsp;
                    </td>
                </tr>
                </table>
                </div>";
    }
     
    $header_content.="<div class='header_content1'>
                <table>
                <tr>
                    <td class='emptyspace'>&nbsp;</td>
                    <td colspan=2 class=".$stuname.">".strtoupper($value['name'])."</td>
                    <td class=exammonth>".strtoupper($monthname)."  " .$year."</td>
                </tr>
                <tr>
                    <td class='emptyspace'>&nbsp;</td>
                    <td class=".$registernumber.">".strtoupper($value['register_number'])."</td>
                    <td class=dob>".$dob."</td>
                    <td class=gender>".$print_gender."</td>
                </tr>
                <tr>
                    <td class='emptyspace'>&nbsp;</td>
                    <td colspan=2 class=".$degreeclass.">".$degreename."</td> 
                    <td class=reg_year>".$value['regulation_year']."</td>
                </tr>
                </table>
                </div>";

   

    $student_mark = Yii::$app->db->createCommand('SELECT G.subject_type_id, G.paper_no, G.semester, H.subject_code, H.subject_name, H.credit_points, H.ESE_max, H.CIA_max, F.* FROM  coe_mark_entry_master as F join coe_subjects_mapping as G  ON G.coe_subjects_mapping_id=F.subject_map_id JOIN coe_subjects H ON H.coe_subjects_id=G.subject_id WHERE F.student_map_id="'.$value['student_map_id'].'" AND F.year="'.$year.'" AND F.month="'.$month.'" AND H.subject_code NOT IN (SELECT HS.subject_code FROM cur_honours_subject_list HS WHERE HS.register_number="'.$value['register_number'].'" AND HS.semester='.$semester_number.') ORDER BY G.paper_no ASC')->queryAll();
               
    //$student_mark = $query->createCommand()->queryAll();
    
    $body_content="<div class='body_content'><table width=100%>";
    $semcredits=array();
    foreach ($student_mark as $valuess) 
    {
        $sub_na = wordwrap(strtoupper(trim($valuess['subject_name'])), 52, "\n", true);
        $sub_na = htmlentities($sub_na);
        $subject_name_print = strtoupper(nl2br($sub_na));

        if($valuess['subject_type_id']==15)
        {
            $explodees=explode(":", $valuess['subject_name']);

            if(count($explodees)<2)
            {
                $subject_name_print = "PROFESSIONAL ELECTIVE: ".strtoupper(nl2br($sub_na));
            }
            else
            {
                $subject_name_print = strtoupper(nl2br($sub_na));
            }
        }

        $grade_name='';
        if($value['batch_name']>='2021' && $valuess['grade_name']=='RA')
        {
            $grade_name='U';
        }
        else
        {
            $grade_name=strtoupper($valuess['grade_name']);
        }


        if($value['batch_name']>='2023' && $valuess['credit_points']=='0')
        {
            $result_stu = $valuess['result'] == "Pass" || $valuess['result'] == "PASS" || $valuess['result'] == "pass"? "COMPLETED" : "NOT COMPLETED";

            $body_content.=" <tr>
                        <td class='sem'>".$semester_array[$valuess['semester']] ."</td> 
                        <td class='subjectcode'>".strtoupper($valuess['subject_code'])."</td>
                        <td class='".$subjectname."'>".$subject_name_print."</td>
                        <td class='credit_points'>&nbsp;</td>
                        <td colspan='3' class='subject_completed1'>".$result_stu."</td>
                    </tr>";
        }
        else if ($valuess['CIA_max'] == 0 && $valuess['ESE_max'] == 0) 
        {

            $result_stu = $valuess['result'] == "Pass" || $valuess['result'] == "PASS" || $valuess['result'] == "pass"? "COMPLETED" : "NOT COMPLETED";

            $body_content.=" <tr>
                        <td class='sem'>".$semester_array[$valuess['semester']] ."</td> 
                        <td class='subjectcode'>".strtoupper($valuess['subject_code'])."</td>
                        <td class='".$subjectname."'>".$subject_name_print."</td>
                        <td colspan='4' class='subject_completed'>".$result_stu."</td>
                    </tr>";
        }
        else
        {
            $grade_point_print = $valuess['grade_point']==0?"-":$valuess['grade_point'];
            $result_stu = $valuess['result'] == "PASS" || $valuess['result'] == "pass" || $valuess['result'] == "Pass" ? "PASS" : $valuess['result'];

            $result_stu = $valuess['result'] == "Absent" || $valuess['result'] == "ABSENT" || $valuess['result'] == "absent" || $valuess['result'] == "AB" ? "RA" : $valuess['result'];
                
            $result_stu = $result_stu == "PASS" || $result_stu == "pass" || $result_stu == "Pass" ? "PASS" : $result_stu;

            $result_stu = $result_stu == "Fail" || $result_stu == "fail" || $result_stu == "Fail" ? "RA" : $result_stu;

            $result_stu = ($valuess['grade_name'] == "WD" || $valuess['grade_name'] == "wd") ? "RA" : $result_stu;
            $result_stu = ($valuess['withheld'] == "w" || $valuess['withheld'] == "W") ? "RA" : $result_stu;

            $grade_name=strtoupper($grade_name);
            $grade_name = ($valuess['grade_name'] == "WD" || $valuess['grade_name'] == "wd") ? 'W' : $grade_name;


            if($value['batch_name']<2021 && $valuess['withheld'] == "w" || $valuess['withheld'] == "W" && $valuess['grade_name'] == "WH" || $valuess['grade_name'] == "wh")
            {

                 $grade_name="RA";

            }
            else if($value['batch_name']>=2021 && $valuess['withheld'] == "w" || $valuess['withheld'] == "W" && $valuess['grade_name'] == "WH" || $valuess['grade_name'] == "wh")
            {
                      $grade_name="U";
            }
            else
            {
                 $grade_name=strtoupper($grade_name);

            }
            
            $body_content.=" <tr>
                        <td class='sem'>".$semester_array[$valuess['semester']] ."</td> 
                        <td class='subjectcode'>".strtoupper($valuess['subject_code'])."</td>
                        <td class='".$subjectname."'>".$subject_name_print."</td>
                        <td class='credit_points'>".$valuess['credit_points']."</td>
                        <td class='grade_name'>".$grade_name."</td>
                        <td class='grade_point_print'>".$grade_point_print."</td>
                        <td class='result_stu'>".strtoupper($result_stu)."</td>
                    </tr>";
        }

        $semcredits= ['sem'=>$valuess['semester']];
        
    }

   
    $hm_subject = Yii::$app->db->createCommand('SELECT A.*,C.*,B.semester,B.subject_type_id FROM  coe_subjects as A join coe_subjects_mapping as B  on B.subject_id=A.coe_subjects_id join coe_mark_entry_master as C on C.subject_map_id=B.coe_subjects_mapping_id JOIN cur_honours_subject_list D ON D.subject_code=A.subject_code AND B.semester=D.semester AND B.batch_mapping_id=D.batch_map_id WHERE D.register_number="'.$value['register_number'].'" AND C.year="'.$year.'" AND C.month="'.$month.'" and C.student_map_id="'.$value['student_map_id'].'" and C.mark_type=27 AND C.year_of_passing!="" AND C.result like "%Pass%" AND D.semester="'.$semester_number.'" ORDER BY B.paper_no ASC')->queryAll();
    //print_r($hm_subject); exit;
    if(!empty($hm_subject))
    {
        
        $body_content.="<tr>
                        <td colspan='2' class='add_code emptyspace5'>&nbsp;</td>
                        <td colspan='2' class='".$subjectname." emptyspace5'># ADDITIONAL COURSES</td>
                    </tr>";

        foreach ($hm_subject as $hmvaluess) 
        {
           
            if($hmvaluess["subject_type_id"]!=233)
            {
                $explode=explode(":", $hmvaluess["subject_name"]);
                //print_r($explode); exit();
                $subject_name=$explode[1];
            }
            else
            {
                $subject_name=$hmvaluess["subject_name"];
            }

            $explode1=explode(":", $hmvaluess["subject_name"]);

            if(count($explode1)>=2)
            {
                $subject_name=$explode1[1];
            }

            $sub_na = wordwrap(strtoupper(trim($subject_name)), 52, "\n", true);
            $sub_na = htmlentities($sub_na);
            $subject_name_print = strtoupper(nl2br($sub_na));

            $grade_point_print = $hmvaluess['grade_point']==0?"-":$hmvaluess['grade_point'];
            $result_stu = $hmvaluess['result'] == "PASS" || $hmvaluess['result'] == "pass" || $hmvaluess['result'] == "Pass" ? "PASS" : $hmvaluess['result'];

            $body_content.=" <tr>
                        <td class='sem'>".$semester_array[$hmvaluess['semester']] ."</td> 
                        <td class='subjectcode'>".strtoupper($hmvaluess['subject_code'])."</td>
                        <td class='".$subjectname."'>".$subject_name_print."</td>
                        <td class='credit_points'>".$hmvaluess['credit_points']."</td>
                        <td class='grade_name'>".strtoupper($hmvaluess['grade_name'])."</td>
                        <td class='grade_point_print'>".$grade_point_print."</td>
                        <td class='result_stu'>".strtoupper($result_stu)."</td>
                    </tr>";
            
        }
    }

    $mand_subject = Yii::$app->db->createCommand('SELECT A.*,C.*,B.* FROM  coe_mandatory_subjects as A join coe_mandatory_subcat_subjects as B on B.man_subject_id=A.coe_mandatory_subjects_id join coe_mandatory_stu_marks as C on C.subject_map_id=B.coe_mandatory_subcat_subjects_id WHERE C.year="'.$year.'" AND C.month="'.$month.'" and C.student_map_id="'.$value['student_map_id'].'" and C.mark_type=27 AND C.year_of_passing!=""')->queryAll();
    //print_r($mand_subject); exit;
    if(!empty($mand_subject))
    {   
       
        $body_content.="<tr>
                        <td colspan='2' class='add_code emptyspace5'>&nbsp;</td>
                        <td colspan='5' class='".$subjectname." emptyspace5'># ADDITIONAL CREDITS</td>
                    </tr>";
        

        $body_content.="<tr>
                        <td class='sem'>".$semester_array[$mand_subject[0]['semester']] ."</td> 
                        <td class='subjectcode'>".strtoupper($mand_subject[0]['subject_code'])."</td>
                        <td class='".$subjectname."'>".strtoupper($mand_subject[0]['subject_name'])."</td>
                    </tr>";
        
        foreach ($mand_subject as $manvaluess) 
        {
            $sub_na = wordwrap(strtoupper(trim($manvaluess['sub_cat_name'])), 45, "\n", true);
            $sub_na = htmlentities($sub_na);
            $subject_name_print = strtoupper(nl2br($sub_na));

            $grade_point_print = $manvaluess['grade_point']==0?"-":$manvaluess['grade_point'];
            $result_stu = $manvaluess['result'] == "PASS" || $manvaluess['result'] == "pass" || $manvaluess['result'] == "Pass" ? "PASS" : $manvaluess['result'];

            if($value['batch_name']>='2022')
            {
                if($value['batch_name']=='2022' && $value['degree_type']=='PG')
                {
                    $body_content.=" <tr>
                        <td colspan='2' class='add_code'>&nbsp;</td>
                        <td class='".$subjectname."'>".strtoupper($manvaluess['sub_cat_code'])." - ". $subject_name_print ."</td>
                        <td class='credit_points'>".$manvaluess['credit_points']."</td>
                        <td class='grade_name'>".strtoupper($manvaluess['grade_name'])."</td>
                        <td class='grade_point_print'>".$grade_point_print."</td>
                        <td class='result_stu'>".strtoupper($result_stu)."</td>
                    </tr>";
                }
                else
                {
                    $body_content.=" <tr>
                        <td colspan='2' class='add_code'>&nbsp;</td>
                        <td class='".$subjectname."'>".strtoupper($manvaluess['sub_cat_code'])." - ". $subject_name_print ."</td>
                        <td class='credit_points'>".$manvaluess['credit_points']."</td>
                        <td colspan=3  class='subject_completed1'>COMPLETED</td>
                    </tr>";
                }
            }
            else
            {
                $body_content.=" <tr>
                        <td colspan='2' class='add_code'>&nbsp;</td>
                        <td class='".$subjectname."'>".strtoupper($manvaluess['sub_cat_code'])." - ". $subject_name_print ."</td>
                        <td class='credit_points'>".$manvaluess['credit_points']."</td>
                        <td class='grade_name'>".strtoupper($manvaluess['grade_name'])."</td>
                        <td class='grade_point_print'>".$grade_point_print."</td>
                        <td class='result_stu'>".strtoupper($result_stu)."</td>
                    </tr>";
            }
            
        }
    }

    if(!empty($mand_subject) || !empty($hm_subject))
    { 
        $body_content.=" <tr>
                        <td colspan='2' class='add_code'>&nbsp;</td>
                        <td class='add_cred_stmt'># ADDITIONAL COURSES / CREDITS EARNED WILL NOT BE CONSIDERED FOR GPA/CGPA CALCULATION</td>
                        <td colspan='2'></td>
                    </tr>";
    }

    $body_content.=" <tr>
                        <td colspan='2' class='add_code'>&nbsp;</td>
                        <td class='end_stmt'>~ END OF STATEMENT ~</td>
                         <td colspan='4'>&nbsp;</td>
                    </tr>";
    $body_content.="</table></div>";


    $total_semesters= $value['degree_total_semesters'];

    $add_content='';  $additional_hm_credit=0;
    
        $cumulative_add_cre_earn = Yii::$app->db->createCommand('SELECT sum(B.credit_points) FROM coe_mandatory_stu_marks as A JOIN  coe_mandatory_subcat_subjects as B ON B.coe_mandatory_subcat_subjects_id=A.subject_map_id WHERE A.student_map_id="'.$value['student_map_id'].'" and A.mark_type=27 AND A.year_of_passing!="" and is_additional="YES" AND A.semester<="'.$semester_number.'"')->queryScalar();

        $additional_hm_credit = Yii::$app->db->createCommand('SELECT sum(A.credit_points) FROM  coe_subjects as A join coe_subjects_mapping as B  on B.subject_id=A.coe_subjects_id join coe_mark_entry_master as C on C.subject_map_id=B.coe_subjects_mapping_id JOIN cur_honours_subject_list D ON D.subject_code=A.subject_code AND B.semester=D.semester AND B.batch_mapping_id=D.batch_map_id WHERE D.register_number="'.$value['register_number'].'" and C.student_map_id="'.$value['student_map_id'].'" and C.mark_type=27 AND C.year_of_passing!="" AND C.result like "%Pass%" AND D.semester<="'.$semester_number.'" AND B.semester<="'.$semester_number.'"')->queryScalar();

    $additionl_credits=($additional_hm_credit+$cumulative_add_cre_earn);
    if($additionl_credits>0)
    {
        $add_content= "<div class='add_content'><table width=100%>
        <tr><td colspan='2' class='add_code'>&nbsp;</td>
                            <td class='add_cred_earned_stmt'>CUMULATIVE ADDITIONAL CREDITS EARNED : ".($additional_hm_credit+$cumulative_add_cre_earn)."</td>
                            <td colspan='2'></td></tr>";

        $add_content.="</table></div>";
    }
    
    $footer_content='';
    $footer_content="<div class='footer_content'>";

    $credits_register_row= "<table autosize='1'>";
    $credits_earned_row= "<table autosize='1'>";
    $sgpa_row= "<table autosize='1'>";

    if($semester_number==1)
    {
        $credits_register_row.= "<tr><td class='emptyspace1'>&nbsp;</td>";
        $credits_earned_row.= "<tr><td class='emptyspace1'>&nbsp;</td>";
        $sgpa_row.= "<tr><td class='emptyspace1'>&nbsp;</td>";
    }
    else
    {
        $credits_register_row.= "<tr><td class='emptyspace11'>&nbsp;</td>";
        $credits_earned_row.= "<tr><td class='emptyspace11'>&nbsp;</td>";
        $sgpa_row.= "<tr><td class='emptyspace11'>&nbsp;</td>";
    }

    $current_sem=max($semcredits);

    $footer_colspan=$current_sem-1;

    $arrear=0;
    for ($cal=1; $cal <= $current_sem ; $cal++) 
    {       
        
        if($cal==$semester_number)
        {
            $cgpa_calc = ConfigUtilities::getCgpaCaluclation_HM($year,$month,$batch_map_id,$value['student_map_id'],$cal);

            $registered_credit = $cgpa_calc['registered']==0?"-":$cgpa_calc['registered'];
            $sem_earned_credit = $cgpa_calc['sem_credits_earned']==0?'-':$cgpa_calc['sem_credits_earned'];
            $sem_gpa = $cgpa_calc['gpa']==0?"-":$cgpa_calc['gpa'];

            $credits_register_row .= "<td class='registered_credit'>" . $registered_credit . "</td>";

            $credits_earned_row .= "<td class='sem_earned_credit' >" . $sem_earned_credit . "</td>";

            $sgpa_row .= "<td class='sem_gpa' >" . $sem_gpa . "</td>";
        }
        else
        {
            $cgpa_calc = ConfigUtilities::getCgpaCaluclation_Arrear($year,$month,$batch_map_id,$value['student_map_id'],$cal);
            //print_r($cgpa_calc); exit();
            if(!empty($cgpa_calc['registered']))
            {
                $registered_credit = $cgpa_calc['registered']==0?"-":$cgpa_calc['registered'];
                $sem_earned_credit = $cgpa_calc['sem_credits_earned']==0?'-':$cgpa_calc['sem_credits_earned'];
                $sem_gpa = $cgpa_calc['gpa']==0?"-":$cgpa_calc['gpa'];

                $credits_register_row .= "<td class='other_registered_credit'>" . $registered_credit . "</td>";

                $credits_earned_row .= "<td class='other_sem_earned_credit' >" . $sem_earned_credit . "</td>";

                $sgpa_row .= "<td class='other_sem_gpa' >" . $sem_gpa . "</td>";

                $arrear=$arrear+1;
            }
            else
            {
                
                $credits_register_row .= "<td class='non_registered_credit'>---</td>";

                $credits_earned_row .= "<td class='non_sem_earned_credit'>---</td>";

                $sgpa_row .="<td class='non_sem_gpa' >---</td>";
            }

           
        }
        
    }

    $credits_register_row.= "</tr></table>";
    $credits_earned_row.= "</tr></table>";
    $sgpa_row.= "</tr></table>";
    $cgpa_calc = ConfigUtilities::getCgpaCaluclation_HM($year,$month,$batch_map_id,$value['student_map_id'],$semester_number);
   

    $footer_content .= $credits_register_row.$credits_earned_row.$sgpa_row."</div>";
    $footer_content1= "<div class='footer_content1'><table><tr>";
     $footer_content1.= "<td class='emptyspace3'>&nbsp;</td>";
    
    $footer_content1.="<td class='cumulative_earned_credits'>" . ($cgpa_calc['cumulative_earned_credits']+$additional_hm_credit) . "</td><td class='emptyspace4'>&nbsp;</td>
    <td class='cgpa'>" . $cgpa_calc['cgpa_result_sem'] . "</td></tr>";

    $footer_content1.="</table></div>";

    $footer_date="<div class='print_date'><table width=100%>";

    $footer_date .= "<tr><td>" . date("d-m-Y",strtotime($print_date)) . "</td></tr>";

    $footer_date.="</table></div>";


    $content.=$header_content.$body_content.$add_content.$footer_content.$footer_content1.$footer_date;

    if($ii<$nn)
    {
        $content.='<pagebreak>';
    }

    $ii++;
}


$pdf = new Pdf([
        'mode' => Pdf::MODE_CORE,
        'filename' => 'SEM MARK STATEMENT.pdf',
        'format' => Pdf::FORMAT_A4,
        'orientation' => Pdf::ORIENT_PORTRAIT,
        //'destination' => Pdf::DEST_DOWNLOAD,
        'content' => $content, 
        'cssFile' => $change_css_file,
        'options' => ['title' => 'SEM MARK STATEMENT'],
        ]);

$top_margin= $_POST['top_margin'];
$left_margin= $_POST['left_margin'];

if(!empty($top_margin))
{
    $pdf->marginTop = $top_margin;
}
else
{
    $pdf->marginTop = 0;
}

if(!empty($left_margin))
{
    $pdf->marginLeft = $left_margin;
}
else
{
    $pdf->marginLeft = "5.2";
}

$pdf->marginBottom = "0";
$pdf->marginRight = "0";
$pdf->marginHeader = "3";
$pdf->marginFooter = "0";


Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
$headers = Yii::$app->response->headers;
$headers->add('Content-Type', 'application/pdf');

return $pdf->render();

?>