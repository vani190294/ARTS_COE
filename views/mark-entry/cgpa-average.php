<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\MarkEntry;
use app\models\HallAllocate;
use yii\db\Query;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="CGPA Average";

?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

    <?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">


            <div class="col-lg-2 col-sm-2">                
                <?= $form->field($model, 'year')->textInput(['id'=>'course_year','name'=>'year','value'=>date("Y")]) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),   
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id'=>'exam_month',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                    ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <br />
            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">

                <input type="Submit" class="btn btn-success" onclick="spinner()" value="Submit">
                <?= Html::a("Reset", Url::toRoute(['mark-entry/cgpa-average']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>



<?php 
if(!empty($content_data))
{

?>
    
    <div class="col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-cgpa-average','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/cgpa-average-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
    </div>
     <div class="col-xs-12 col-sm-12 col-lg-12">
        
            <div class="col-xs-12" style="padding-top: 30px;" >
                <div class="col-lg-12"  style="overflow-x:auto;"> 
                    <?php 
                    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                     $html='';
                    $monthname = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='" . $month1 . "'")->queryScalar();

                    $explode=explode("/", $monthname);


                    if($explode[1]=='Nov')
                    {
                        $explode='Nov/Dec';
                    }
                    else
                    {
                        $explode=$monthname;
                    }

                    $det_rejoin_type ='253';
                    $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                    $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                    $det_long_absent = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Long Absent%'")->queryScalar();

                    $html .= '<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" ><tr>
                                    <td align="center" style="border-right:0px; border-bottom:0px" >  
                                            <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                    </td>
                                    <td colspan="5" align="center" style="color: #2173bc; border-left:0px; border-right:0px; border-bottom:0px; font-size: 12px !important;"> 
                                                <center><b><font size="6px">' . $org_name . '</font></b></center>
                                                <center><b>(An Autonomous Insitution)</b></center>
                                                <center><b>Approved By AICTE and Affiliated to Anna University, Chennai</b></center>
                                                <center><b>Accredited by NAAC with "A" Grade</b></center>
                                                <center><b>' . $org_address . '</b></center>
                                                 
                                     </td>

                                    <td align="center" style="border-left:0px; border-bottom:0px"> 
                                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                                           
                            </tr>';
                    $html .= '<tr><td colspan="7" align="center" style=" color: #2173bc; border-top:0px; border-bottom:0px; padding-bottom:10px; font-size: 12px !important;"><b>7278 - CGPA AVERAGE - '.strtoupper($explode).' '.$Year1.' EXAMINATIONS</b></td></tr></table>';
                      
                    $html .= '<table  style="overflow-x:auto; " width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >';  
                    $i=0;  
                   // print_r($content_data); exit;
                    $course_result_table ='';
                    foreach ($content_data as $key => $value1) 
                    {
                        $course_result_tableug = '';
                        $course_result_tablepg = '';
                        $content_1= Yii::$app->db->createCommand("select d.coe_batch_id, d.coe_bat_deg_reg_id, degree_code, degree_type, programme_name, semester, degree_total_semesters from coe_mark_entry_master a 
                            JOIN coe_subjects_mapping c on c.coe_subjects_mapping_id=a.subject_map_id 
                            JOIN coe_bat_deg_reg d on d.coe_bat_deg_reg_id=c.batch_mapping_id
                            JOIN coe_programme e on e.coe_programme_id=d.coe_programme_id 
                            JOIN coe_degree as f ON f.coe_degree_id=d.coe_degree_id 
                            where f.degree_code!='Ph.D' AND d.coe_batch_id='" . $value1["batch"] . "' and a.year=".$Year1." and a.month=".$month1." AND c.course_type_id NOT IN (231,232) group by d.coe_bat_deg_reg_id  Order By programme_code ASC")->queryAll();
                        if($i>0)
                        {
                            $course_result_table .= '<tr><td colspan=5 align="center" style="border-left:0px !important; border-right:0px !important;"><br></td></tr>';
                        }
                        if(!empty($content_1))
                        {

                            $total_pass_stu=0; $total_enroll_stu=0; $tot_stu ='';

                            $batch_name = Yii::$app->db->createCommand("select batch_name from coe_batch where coe_batch_id = '".$value1["batch"]."'")->queryScalar(); 

                            $yrs=$value1["year"];

                            $failed_stus=Yii::$app->db->createCommand("SELECT student_map_id,withdraw from coe_mark_entry_master a 
                                    JOIN coe_subjects_mapping c on c.coe_subjects_mapping_id=a.subject_map_id 
                                    JOIN coe_bat_deg_reg d on d.coe_bat_deg_reg_id=c.batch_mapping_id
                                    where d.coe_batch_id='" .  $value1["batch"] . "' AND year=".$Year1." and month=".$month1." and mark_type=27 and year_of_passing='' AND c.course_type_id NOT IN (231,232)")->queryAll();

                            $stud_count = count($failed_stus);

                            $notIn = array_filter(['']);
                            foreach ($failed_stus as $key => $fails) 
                            {
                                if($fails['withdraw']!='wd' && $fails['withdraw']!='WD')
                                {
                                    $notIn[$fails['student_map_id']]=$fails['student_map_id'];
                                }
                            }
                           $notIn = array_filter($notIn);

                           //print_r($notIn); exit;
                           
                           $total_year_cgpaavg=0; 
                           $overall_apperead=0;

                           $total_year_gpa_avg=0;

                            $ug_appered=$pg_appered=$ug_pass=$pg_pass=$ug_avg=$pg_avg=0;
                            $ug_all_pass=$pg_all_pass=$ug_gpa_avg=$pg_gpa_avg=0;
                            $u=$p=0;

                            $course_result_table .= '<tr><td colspan=7 align="center" style="background-color: #2173bc; color: #fff;"><b>BATCH - ' . $batch_name . '</b></td></tr>';
                             $course_result_table .= '<tr>                                                                                                                                
                                <th> S. NO </th> 
                                <th>Programme</th> 
                                <th>Appeared Student</th>
                                <th>All Clear in Current Sem</th> 
                                <th>GPA Average </th>
                                <th>All Clear in All Sem</th> 
                                <th>CGPA Average </th>
                                ';
                            $course_result_table .= '</tr>';
                           
                            $j=0; $pagebreak=1; $pagebreakout=0; $colspan=0; 
                            $sn=1;
                            $degree_code='';
                            foreach ($content_1  as $program) 
                            {                               
                                //echo "<br>".$value1['batch']."-".$program['coe_bat_deg_reg_id'];
                                
                                //if($total_cgpa['cgpa']>0)
                                //{
                                    $sem = ConfigUtilities::semCaluclation($Year1, $month1, $program['coe_bat_deg_reg_id']);

                                    $failed_stusall = Yii::$app->db->createCommand('SELECT DISTINCT A.student_map_id, A.withdraw FROM coe_mark_entry_master A JOIN coe_subjects_mapping C ON C.coe_subjects_mapping_id=A.subject_map_id JOIN coe_bat_deg_reg d on d.coe_bat_deg_reg_id=C.batch_mapping_id where (result="Fail" OR result="Absent") AND d.coe_bat_deg_reg_id="' . $program["coe_bat_deg_reg_id"] . '" AND A.subject_map_id NOT IN (SELECT B.subject_map_id FROM coe_mark_entry_master B JOIN coe_student_mapping D ON D.coe_student_mapping_id=B.student_map_id JOIN coe_subjects_mapping CC ON CC.coe_subjects_mapping_id=B.subject_map_id WHERE result="Pass" AND year_of_passing!="" AND A.student_map_id=B.student_map_id AND CC.course_type_id NOT IN (231,232)) AND C.course_type_id NOT IN (231,232)')->queryAll();

                                    $stud_countall = count($failed_stusall);

                                    $stud_count_overall = array_filter(['']);
                                    foreach ($failed_stusall as $key => $fails) 
                                    {
                                        if($fails['withdraw']!='wd' && $fails['withdraw']!='WD')
                                        {
                                        
                                            $stud_count_overall[$fails['student_map_id']]=$fails['student_map_id'];

                                        }
                                        
                                    }
                                    $stud_count_overall = array_filter($stud_count_overall);                                    
                                   
                                    $query_enroll = new Query();
                                    $query_enroll->select('count(DISTINCT student_map_id)')
                                        ->from('coe_student_mapping a')
                                        ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id and c.batch_mapping_id=a.course_batch_mapping_id')
                                        ->where(['a.course_batch_mapping_id' => $program['coe_bat_deg_reg_id'],'b.year' => $Year1, 'b.month' => $month1,'c.semester'=>$sem]);
                                    //$query_enroll->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                                    $student_enrol = $query_enroll->createCommand()->queryScalar();


                                    if($student_enrol>0)
                                    {
                                        if($program['degree_type']=='UG')
                                        {
                                            if($degree_code!=$program['degree_type'])
                                            {
                                                $sn=1;
                                            }
                                            $query_sub = new Query();
                                            $query_sub->select('DISTINCT (subject_map_id)')
                                                ->from('coe_student_mapping a')
                                                ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                                                ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id and c.batch_mapping_id=a.course_batch_mapping_id')
                                                ->where(['a.course_batch_mapping_id' => $program['coe_bat_deg_reg_id'],'b.year' => $Year1, 'b.month' => $month1,'c.semester'=>$sem]);
                                            //$query_sub->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                                            //echo $query_sub->createCommand()->getrawsql();exit;
                                            $subject_app = $query_sub->createCommand()->queryAll();

                                            $notIn1 = array_filter(['']);
                                             foreach ($subject_app as $key => $subs) {
                                                
                                                    $notIn1[$subs['subject_map_id']]=$subs['subject_map_id'];
                                                
                                            }

                                            $notIn1 = array_filter($notIn1);

                                            $tot_stu = implode(",",$notIn1);

                                            $course_result_tableug .= '<tr><td align="left">' . $sn . '</td><td align="left">'. $program['degree_code'] ." - " . strtoupper($program['programme_name']) . '</td>';

                                            $query_appeared = new Query();
                                            $query_appeared->select('count(DISTINCT student_map_id)')
                                                ->from('coe_student_mapping a')
                                                ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                                                ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                                                ->where(['a.course_batch_mapping_id' => $program['coe_bat_deg_reg_id'], 'b.year' => $Year1, 'b.month' => $month1,'c.semester'=>$sem])->andWhere(['<>', 'status_category_type_id', '93'])->andWhere(['<>', 'status_category_type_id', '4']);
                                            $student_appeared = $query_appeared->createCommand()->queryScalar();
                                            
                                            $course_result_tableug .= '<td>' . $student_appeared . '</td>';

                                            $query_map_id = new Query();
                                            $query_map_id->select('count(DISTINCT student_map_id)')
                                                ->from('coe_student_mapping a')
                                                ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                                                ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id and c.batch_mapping_id=a.course_batch_mapping_id')
                                                ->where(['a.course_batch_mapping_id' => $program['coe_bat_deg_reg_id'],'b.year' => $Year1, 'b.month' => $month1,'b.result'=>'Pass','b.mark_type'=>27,'c.semester'=>$sem])->andWhere(['NOT IN', 'student_map_id', $notIn]);
                                           //echo "<br>".$query_map_id->createCommand()->getRawSql(); exit;
                                            $students_map_id_pass = $query_map_id->createCommand()->queryScalar();

                                            $student_pass = isset($students_map_id_pass)?$students_map_id_pass:'0';
                                            $course_result_tableug .= '<td>' . $student_pass . '</td>';

                                            $total_cgpa = Yii::$app->db->createCommand('SELECT sum(gpa) as gpasum, sum(cgpa) as cgpasum, count(gpa) as gpacount, count(cgpa) as cgpacount FROM coe_cgpa_student where year="'.$Year1.'" and month="'.$month1.'" and batch_mapping_id="'.$program['coe_bat_deg_reg_id'].'" ')->queryOne();

                                            if($student_pass>0)
                                            {
                                                $total_year_cgpaavg=$total_year_cgpaavg+$total_cgpa['cgpasum'];
                                                $overall_apperead=$overall_apperead+$total_cgpa['cgpacount'];
                                                $ug_avg=$ug_avg+$total_cgpa['cgpasum'];
                                                $total_year_gpa_avg=$total_year_gpa_avg+$total_cgpa['gpasum'];

                                            }
                                            
                                            $course_result_tableug .= '<td>'.round(($total_cgpa['gpasum']/$total_cgpa['gpacount']),2).'</td>';

                                            $ug_appered=$ug_appered+$student_appeared;
                                            
                                            if($student_pass>0)
                                            {
                                               
                                                $u=$u+1;
                                            }
                                            $ug_pass=$ug_pass+$student_pass;
                                            

                                            $query_map_id1 = new Query();
                                            $query_map_id1->select('count(DISTINCT student_map_id)')
                                                ->from('coe_student_mapping a')
                                                ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                                                ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id and c.batch_mapping_id=a.course_batch_mapping_id')
                                                ->where(['a.course_batch_mapping_id' => $program['coe_bat_deg_reg_id']])->andWhere(['NOT IN', 'student_map_id', $stud_count_overall])
                                                ->andWhere(['AND',['<>', 'a.status_category_type_id', $det_cat_type],
                                                        ['<>', 'a.status_category_type_id', $det_rejoin_type],
                                                       ['<>', 'a.status_category_type_id', $det_disc_type],
                                                       ['<>', 'a.status_category_type_id', $det_long_absent]]);
                                           //echo "<br>".$query_map_id->createCommand()->getRawSql(); exit;
                                            $students_map_id_pass1 = $query_map_id1->createCommand()->queryScalar();

                                            $student_pass_overall = isset($students_map_id_pass1)?$students_map_id_pass1:'0';
                                            $course_result_tableug .= '<td>' . $student_pass_overall . '</td>';

                                            $ug_all_pass=$ug_all_pass+$student_pass_overall;
                                            $ug_gpa_avg=$ug_gpa_avg+$total_cgpa['gpasum'];

                                            $course_result_tableug .= '<td>'.round(($total_cgpa['cgpasum']/$student_pass_overall),2).'</td>';
                                            $course_result_tableug .= '</tr>';
                                        }
                                        else if($program['degree_type']=='PG')
                                        {
                                            $query_sub = new Query();
                                            $query_sub->select('DISTINCT (subject_map_id)')
                                                ->from('coe_student_mapping a')
                                                ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                                                ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id and c.batch_mapping_id=a.course_batch_mapping_id')
                                                ->where(['a.course_batch_mapping_id' => $program['coe_bat_deg_reg_id'],'b.year' => $Year1, 'b.month' => $month1,'c.semester'=>$sem]);
                                            //$query_sub->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                                            //echo $query_sub->createCommand()->getrawsql();exit;
                                            $subject_app = $query_sub->createCommand()->queryAll();

                                            $notIn1 = array_filter(['']);
                                             foreach ($subject_app as $key => $subs) {
                                                
                                                    $notIn1[$subs['subject_map_id']]=$subs['subject_map_id'];
                                                
                                            }

                                            $notIn1 = array_filter($notIn1);

                                            $tot_stu = implode(",",$notIn1);

                                            $course_result_tablepg .= '<tr><td align="left">' . $sn . '</td><td align="left">'. $program['degree_code'] ." - " . strtoupper($program['programme_name']) . '</td>';

                                            $query_appeared = new Query();
                                            $query_appeared->select('count(DISTINCT student_map_id)')
                                                ->from('coe_student_mapping a')
                                                ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                                                ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                                                ->where(['a.course_batch_mapping_id' => $program['coe_bat_deg_reg_id'], 'b.year' => $Year1, 'b.month' => $month1,'c.semester'=>$sem])->andWhere(['<>', 'status_category_type_id', '93'])->andWhere(['<>', 'status_category_type_id', '4']);
                                            $student_appeared = $query_appeared->createCommand()->queryScalar();
                                            
                                            $course_result_tablepg .= '<td>' . $student_appeared . '</td>';

                                             $query_map_id = new Query();
                                            $query_map_id->select('count(DISTINCT student_map_id)')
                                                ->from('coe_student_mapping a')
                                                ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                                                ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id and c.batch_mapping_id=a.course_batch_mapping_id')
                                                ->where(['a.course_batch_mapping_id' => $program['coe_bat_deg_reg_id'],'b.year' => $Year1, 'b.month' => $month1,'b.result'=>'Pass','b.mark_type'=>27,'c.semester'=>$sem])->andWhere(['NOT IN', 'student_map_id', $notIn]);
                                           //echo "<br>".$query_map_id->createCommand()->getRawSql(); exit;
                                            $students_map_id_pass = $query_map_id->createCommand()->queryScalar();

                                            $student_pass = isset($students_map_id_pass)?$students_map_id_pass:'0';
                                            $course_result_tablepg .= '<td>' . $student_pass . '</td>';

                                            $total_cgpa = Yii::$app->db->createCommand('SELECT sum(gpa) as gpasum, sum(cgpa) as cgpasum, count(gpa) as gpacount, count(cgpa) as cgpacount FROM coe_cgpa_student where year="'.$Year1.'" and month="'.$month1.'" and batch_mapping_id="'.$program['coe_bat_deg_reg_id'].'" ')->queryOne();

                                            if($student_pass>0)
                                            {
                                                $total_year_cgpaavg=$total_year_cgpaavg+$total_cgpa['cgpasum'];
                                                $overall_apperead=$overall_apperead+$total_cgpa['cgpacount'];
                                                $pg_avg=$pg_avg+$total_cgpa['cgpasum'];
                                                $total_year_gpa_avg=$total_year_gpa_avg+$total_cgpa['gpasum'];
                                            }

                                            $course_result_tablepg .= '<td>'.round(($total_cgpa['gpasum']/$total_cgpa['gpacount']),2).'</td>';

                                            if($student_pass>0)
                                            {
                                               
                                                $p=$p+1;
                                            }
                                            $pg_pass=$pg_pass+$student_pass;
                                            //$pg_avg=$pg_avg+round(($total_cgpa['cgpa']/$total_cgpa['count']),2);
                                            $pg_appered=$pg_appered+$student_appeared;

                                            $query_map_id1 = new Query();
                                            $query_map_id1->select('count(DISTINCT student_map_id)')
                                                ->from('coe_student_mapping a')
                                                ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                                                ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id and c.batch_mapping_id=a.course_batch_mapping_id')
                                                ->where(['a.course_batch_mapping_id' => $program['coe_bat_deg_reg_id']])->andWhere(['NOT IN', 'student_map_id', $stud_count_overall])
                                                ->andWhere(['AND',['<>', 'a.status_category_type_id', $det_cat_type],
                                                    ['<>', 'a.status_category_type_id', $det_rejoin_type],
                                                       ['<>', 'a.status_category_type_id', $det_disc_type],
                                                       ['<>', 'a.status_category_type_id', $det_long_absent]]);
                                           //echo "<br>".$query_map_id->createCommand()->getRawSql(); exit;
                                            $students_map_id_pass1 = $query_map_id1->createCommand()->queryScalar();

                                            $student_pass_overall = isset($students_map_id_pass1)?$students_map_id_pass1:'0';
                                            $course_result_tablepg .= '<td>' . $student_pass_overall . '</td>';

                                            $pg_all_pass=$pg_all_pass+$student_pass_overall;
                                            $pg_gpa_avg=$pg_gpa_avg+$total_cgpa['gpasum'];

                                            $course_result_tablepg .= '<td>'.round(($total_cgpa['cgpasum']/$student_pass_overall),2).'</td>';
                                            $course_result_tablepg .= '</tr>';
                                        }
                                    
                                        $sn++;
                                    }
                                //}

                                    $degree_code=$program['degree_type'];
                            }

                            $course_result_table.=$course_result_tableug;
                            if($ug_pass>0)
                            {
                                $course_result_table .= '<tr>';
                                $course_result_table .= '<td colspan="2" style="text-align:right;"><b> UG :</b></td> 
                                <td><span style="float:left;">'.$ug_appered.'</span></td>
                                <td><span style="float:left;">'.$ug_pass.'</span></td> 
                                <td><span style="float:left;">'.round(($ug_gpa_avg/$ug_pass),2).'</span></td>
                                <td><span style="float:left;">'.$ug_all_pass.'</span></td>
                                <td>'.round(($ug_avg/$ug_all_pass),2).'</td>';
                                $course_result_table .= '</tr>';
                            }
                                
                            $course_result_table.=$course_result_tablepg;

                             

                            if($pg_pass>0)
                            {
                                $course_result_table .= '<tr>';
                                $course_result_table .= '<td colspan="2" style="text-align:right;"><b> PG :</b></td> 
                                <td><span style="float:left;">'.$pg_appered.'</span></td>
                                <td><span style="float:left;">'.$pg_pass.'</span></td> 
                                <td><span style="float:left;">'.round(($pg_gpa_avg/$pg_pass),2).'</span></td>
                                <td><span style="float:left;">'.$pg_all_pass.'</span></td> 
                                <td>'.round(($pg_avg/$pg_all_pass),2).'</td>';
                                $course_result_table .= '</tr>';
                            }

                            $course_result_table .= '<tr>';
                             $course_result_table .= '<td colspan="2" style="text-align:right;"><b> Overall:</b></td> 
                             <td><span style="float:left;">'.($ug_appered+$pg_appered).'</span></td>
                             <td><span style="float:left;">'.($ug_pass+$pg_pass).'</span></td> 
                             <td><span style="float:left;">'.round(($total_year_gpa_avg/($ug_pass+$pg_pass)),2).'</span></td>
                             <td><span style="float:left;">'.($ug_all_pass+$pg_all_pass).'</span></td>
                             
                             <td>'.round(($total_year_cgpaavg/($ug_all_pass+$pg_all_pass)),2).'</td>';
                           $course_result_table .= '</tr>';
                           
                        }
                        

                        $semester=$semester+2;
                        
                      $i++; 
                    }

                      $course_result_table .= '</table>';

                      $html.=$course_result_table;
                    
                        if(isset($_SESSION['cgpa_avg_printtemp']))
                        {
                            unset($_SESSION['cgpa_avg_printtemp']);
                        }
                        $_SESSION['cgpa_avg_printtemp'] = $html;
                    
                    echo $html;

                    ?>


                </div>
            </div>


    </div>



<?php 
}

?>
    

    <?php ActiveForm::end(); ?>

    

    </div>
    </div>
    </div>