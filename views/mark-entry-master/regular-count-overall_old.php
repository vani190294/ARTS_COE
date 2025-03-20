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
use app\models\Categorytype;
use app\models\MarkEntryMaster;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "REGULAR APPEARED COUNT";
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>

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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
            </div>
            
            <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => ConfigUtilities::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
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

        <br />  
            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Download', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry/regular-count-overall']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>


<?php
if(isset($total_appearred))
{
     require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    /* 
    *   Already Defined Variables from the above included file
    *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
    *   use these variables for application
    *   use $file_content_available="Yes" for Content Status of the Organisation
    */

    $examsession ='';
    if($file_content_available=="Yes")
        {
            if(isset($_SESSION['regular-count-overall'])){ unset($_SESSION['regular-count-overall']);}
            $previous_subject_code= "";
            $previous_programme_name= "";
            $previous_reg_number = "";
            $html = "";
            $header = "";
            $body ="";
            $footer = "";
            $print_subjects =1;
            $print_register_number="";
            $new_stu_flag=0;
            $print_stu_data="";
            
            $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();

            $detain_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Detain%'")->queryScalar();
            
            //$countStuVal = count($studentwiseregular);
            $stu_print_vals = 0;
                $month = Categorytype::findOne($_POST['MarkEntry']['month']);
               $header .="<table  style='overflow-x:auto;'  border=1 align='center' class='table table-striped '>";
                    $header .= '<tr>
                    <td style="border: none;" colspan=9>
                    <table width="100%" align="center" border="0">                    
                   
                    <tr>
                        <td colspan=11><center> <font size="3px"> REGULAR APPEARED COUNT FOR </font> <b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)).' YEAR '.$_POST['mark_year'].' AND '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)).' MONTH '.$month['description'].'</b> </center></td>
                    </tr>
                    <tr>
                        <td colspan=11><center> <font size="3px">'.$org_address.'</font> Phone : <b>'.$org_phone.'</b> </center></td>
                    </tr>
                     <tr>
                        <td colspan=11><center class="tag_line"><b>'.$org_tagline.'</b></center></td>
                    </tr>
                    </table></td></tr>';
                    $header .="
                    <tr>
                      <th align='center'>SNO</th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE))." NAME</th>
                       <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH))." </th>
                         <th align='center'>SEMESTER</th>
                      
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." CODE </th>
                        
                       <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." NAME </th>
                        <th align='center'>EXAM DATE & SESSION</th>
                        <th align='center'>NO OF ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT))."  </th>
                      <th align='center'>NO OF ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT))." APPEARED </th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT))." </th>
                      <th align='center'>MISSING ENTRIES </th>
                      <th align='center'>FAILED</th>
                      <th align='center'>PASSED</th>
                      <th align='center'>PASS%</th>
                    </tr>";
                    $i=1;
                   $footer .='</table>';
                  $overAllTotal = $overallMissg = $overallAbs = $OverallFail = $overallApr = $OverallPass = $overallPassPer = 0;
                 
                  
                  foreach($total_appearred as $rows) 
                  { 
                    $sem = ConfigUtilities::semCaluclation($rows['year'], $rows['month'], $rows['batch_mapping_id']);

                        $getFailCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result != "Absent"  and A.year_of_passing="" and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();

                       $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result like "%pass%"  and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();
                   

                        $getTotalCount = Yii::$app->db->createCommand('select count(DISTINCT A.register_number) FROM coe_hall_allocate as A JOIN coe_exam_timetable as E ON E.coe_exam_timetable_id=A.exam_timetable_id and E.exam_year=A.year and E.exam_month=A.month JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=E.subject_mapping_id JOIN coe_subjects as D ON D.coe_subjects_id=C.subject_id JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id JOIN coe_student as bcd ON bcd.coe_student_id=abc.student_rel_id and bcd.register_number=A.register_number where A.register_number NOT IN (SELECT register_number FROM coe_student_mapping a JOIN coe_student b ON b.coe_student_id=a.student_rel_id WHERE a.course_batch_mapping_id="'.$rows['batch_mapping_id'].'" AND a.semester_detain="'.$sem.'") and C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and E.exam_type=27 and E.exam_year ="'.$rows["year"].'" and E.exam_month ="'.$rows["month"].'"   and status_category_type_id!="'.$det_disc_type.'" ')->queryScalar();
                      
                         
                        
                        $getTotalCountApr = Yii::$app->db->createCommand('select count(DISTINCT absent_student_reg) FROM coe_absent_entry as A JOIN coe_exam_timetable as D ON D.exam_year=A.exam_year and D.subject_mapping_id=A.exam_subject_id and D.exam_month=A.exam_month  JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=D.subject_mapping_id JOIN coe_subjects as B ON B.coe_subjects_id=C.subject_id  JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id and abc.coe_student_mapping_id=A.absent_student_reg  where A.exam_date is NOT NULL AND C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.exam_year ="'.$rows["year"].'" and A.exam_month ="'.$rows["month"].'" and D.exam_year ="'.$rows["year"].'" and D.exam_month ="'.$rows["month"].'" and A.exam_type=27  and status_category_type_id!="'.$det_disc_type.'"')->queryScalar();
                          
                           $examsession = Yii::$app->db->createCommand("select category_type from coe_category_type where  coe_category_type_id='".$rows["exam_session"]."'")->queryScalar();
                      
                       
                       $getAbCount = $getTotalCountApr;
                        $overAllTotal = $getTotalCount+$overAllTotal;
                        $overallAbs = $overallAbs+$getAbCount;
                        
                        $OverallPass = $OverallPass+$getPassCount;
                        $OverallFail = $OverallFail+$getFailCount;
                        

                        $disp_total = empty($getTotalCount)?'-':$getTotalCount;
                        
                        $disp_fail = empty($getFailCount)?'-':$getFailCount;
                        $disp_pass = empty($getPassCount)?'-':$getPassCount;
                        $disp_ab = empty($getAbCount)?'-':$getAbCount;
                       
                        
                        $passPerc ='-';
                         $disp_data = '';
                         $miss_reg = '';
                         $disp_total_apr =0;
                        $MISSING_entry =0;

                         $passPerc = ($disp_total_apr==0 || $disp_total_apr=='-')?'-':round((($disp_pass/$disp_total_apr)*100),2);

                        $getdataCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();

                        if($getdataCount>0)
                        {

                           $getappered = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27  and category_type_id =89 ')->queryScalar();

                          $disp_total_apr = $getappered;

                          $overallApr = $overallApr+($disp_total_apr);
                           
                         //$MISSING_entry = $disp_fail+( ( ($disp_pass==0 || $disp_pass=='-')?0:$disp_pass) + ( ($disp_ab==0 || $disp_ab=='-' ) ? 0 :$disp_ab) );
                         
                         //$MISSING_entry = ( $disp_total == $MISSING_entry ) ?'-': ($disp_total - $MISSING_entry );

                        $MISSING_entry = Yii::$app->db->createCommand('select count(register_number) FROM coe_hall_allocate as A JOIN coe_exam_timetable as E ON E.coe_exam_timetable_id=A.exam_timetable_id and E.exam_year=A.year and E.exam_month=A.month JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=E.subject_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id  join coe_bat_deg_reg as x ON x.coe_bat_deg_reg_id=B.batch_mapping_id join coe_programme as y ON y.coe_programme_id=x.coe_programme_id join coe_degree as z ON z.coe_degree_id=x.coe_degree_id  WHERE  register_number NOT IN( SELECT register_number FROM coe_mark_entry_master cmem JOIN coe_student_mapping csm ON 
                            csm.coe_student_mapping_id=cmem.student_map_id JOIN coe_student cs ON cs.coe_student_id=csm.student_rel_id WHERE cmem.subject_map_id=E.subject_mapping_id  AND year="'.$rows["year"].'" AND month="'.$rows["month"].'") and register_number NOT IN (SELECT register_number FROM coe_student_mapping a JOIN coe_student b ON b.coe_student_id=a.student_rel_id WHERE a.course_batch_mapping_id="'.$rows['batch_mapping_id'].'" AND a.semester_detain="'.$sem.'") and B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'"  and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and E.exam_type=27  and E.subject_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and E.exam_year ="'.$rows["year"].'" and E.exam_month ="'.$rows["month"].'"  group by semester,programme_code,degree_code,coe_subjects_id')->queryScalar();

                       

                         $overallMissg += $MISSING_entry=='-'?0:$MISSING_entry;

                          $getMissingData = Yii::$app->db->createCommand('select register_number FROM coe_hall_allocate as A JOIN coe_exam_timetable as E ON E.coe_exam_timetable_id=A.exam_timetable_id and E.exam_year=A.year and E.exam_month=A.month JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=E.subject_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id  join coe_bat_deg_reg as x ON x.coe_bat_deg_reg_id=B.batch_mapping_id join coe_programme as y ON y.coe_programme_id=x.coe_programme_id join coe_degree as z ON z.coe_degree_id=x.coe_degree_id  WHERE  register_number NOT IN( SELECT register_number FROM coe_mark_entry_master cmem JOIN coe_student_mapping csm ON 
                            csm.coe_student_mapping_id=cmem.student_map_id JOIN coe_student cs ON cs.coe_student_id=csm.student_rel_id WHERE cmem.subject_map_id=E.subject_mapping_id   AND year="'.$rows["year"].'" AND month="'.$rows["month"].'" ) and register_number NOT IN (SELECT register_number FROM coe_student_mapping a JOIN coe_student b ON b.coe_student_id=a.student_rel_id WHERE a.course_batch_mapping_id="'.$rows['batch_mapping_id'].'" AND a.semester_detain="'.$sem.'") and B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'"  and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and E.exam_type=27  and E.subject_mapping_id="'.$rows["coe_subjects_mapping_id"].'"and E.exam_year ="'.$rows["year"].'" and E.exam_month ="'.$rows["month"].'"  group by semester,programme_code,degree_code,coe_subjects_id')->queryAll();
                         
                         
                         
                         if(!empty($getMissingData))
                         {
                         
                            foreach ($getMissingData as $key => $abc) 
                            {
                                $miss_reg .=$abc['register_number'].', ';
                            }
                            $miss_reg = trim($miss_reg,', ');
                         }
                         
                         if(!empty($miss_reg))
                         {
                            $disp_data = '('.$miss_reg.')';
                         }

                        
                        }
                         
                         $body .='<tr>
                                <td align="center">'.$i.'</td>
                                <td align="center">'.$rows["degree_code"]."-".$rows["programme_code"].'</td>
                                <td align="center">'.$rows["batch_name"].'</td>
                                <td align="center">'.$rows["semester"].'</td>
                                <td align="center">'.$rows["subject_code"].'</td>
                                <td align="center">'.$rows["subject_name"].'</td>
                                 <td align="center">'.date("d-m-Y",strtotime($rows["exam_date"])).' & '.$examsession.'</td>
                                <td align="center">'.$disp_total.'</td>
                                <td align="center">'.$disp_total_apr.'</td>
                                <td align="center">'.$disp_ab.'</td>
                                <td align="center">'.$MISSING_entry.' '.$disp_data.'</td>
                                <td align="center">'.$disp_fail.'</td>
                                <td align="center">'.$disp_pass.'</td>
                                <td align="center">'.$passPerc.'</td>
                            </tr>';
                   
                    $i++; 

                }
                if($overallApr !=0)
                {
                    $overallPassPer = $overAllTotal==0?'-':round((($OverallPass/$overallApr)*100),2);
                }

                 $body .='<tr>
                                <td colspan=4 >GRAND TOTAL</td>  
                                <td align="center"></td>
                                 <td align="center"></td>
                                  <td align="center"></td>
                                <td align="center">'.($overAllTotal).'</td>
                                 <td align="center">'.$overallApr.'</td>
                                <td align="center">'.$overallAbs.'</td>
                                <td align="center">'.$overallMissg.'</td>
                                <td align="center">'.$OverallFail.'</td>

                                <td align="center">'.$OverallPass.'</td>
                                <td align="center">'.$overallPassPer.'</td>
                            </tr>';

                $transfer_credit = Yii::$app->db->createCommand("select count(*) from coe_transfer_credit where year=".$_POST['mark_year']." AND month=".$_POST['MarkEntry']['month'])->queryScalar();

                 $overAllTotal = $overallMissg = $overallAbs = $OverallFail = $overallApr = $OverallPass = $overallPassPer = 0;

               if($transfer_credit>0)
               {
                  $body .='<tr>
                        <td colspan=11><center class="tag_line"><b>TRANSFER CREDIT</b></center></td>
                    </tr>';
                    $disp_total_apr=$disp_ab=$MISSING_entry=$disp_fail=$disp_pass=$passPerc=0;
                  foreach($total_tcappearred as $rows) 
                  { 
                      $getTotalCount = Yii::$app->db->createCommand('select count(DISTINCT A.student_map_id) FROM coe_transfer_credit as A 
                        JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id 
                        JOIN coe_subjects as D ON D.coe_subjects_id=C.subject_id 
                        JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id 
                        JOIN coe_student as bcd ON bcd.coe_student_id=abc.student_rel_id 
                        where A.student_map_id NOT IN (SELECT coe_student_mapping_id FROM coe_student_mapping a JOIN coe_student b ON b.coe_student_id=a.student_rel_id WHERE a.course_batch_mapping_id="'.$rows['batch_mapping_id'].'" AND a.semester_detain="'.$sem.'") and C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and status_category_type_id!="'.$det_disc_type.'" ')->queryScalar();

                      $getFailCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result != "Absent"  and A.year_of_passing="" and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();

                       $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result like "%Pass%"  and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();

                      $MISSING_entry= $getTotalCount-($getPassCount+$getFailCount);
                      $overallApr=$overallApr+($getPassCount+$getFailCount);
                      $overallMissg=$overallMissg+$MISSING_entry;
                      $OverallFail=$OverallFail+$getFailCount;
                      $OverallPass=$OverallPass+$getPassCount;

                      $tot=($getPassCount+$getFailCount);
                      $minus=$getTotalCount - $tot;
                      $body .='<tr>
                                <td align="center">'.$i.'</td>
                                <td align="center">'.$rows["degree_code"]."-".$rows["programme_code"].'</td>
                                <td align="center">'.$rows["batch_name"].'</td>
                                <td align="center">'.$rows["semester"].'</td>
                                <td align="center">'.$rows["subject_code"].'</td>
                                <td align="center">'.$rows["subject_name"].'</td>
                                 <td align="center">-</td>
                                <td align="center">'.$getTotalCount.'</td>
                                <td align="center">'.$tot.'</td>
                                <td align="center">-</td>
                                <td align="center">'.$minus.'</td>
                                <td align="center">'.$getFailCount.'</td>
                                <td align="center">'.$getPassCount.'</td>
                                <td align="center">'.$passPerc.'</td>
                            </tr>';
                            $i++;
                  }
                      $body .='<tr>
                                <td colspan=4 >GRAND TOTAL</td>  
                                <td align="center"></td>
                                 <td align="center"></td>
                                  <td align="center"></td>
                                <td align="center">'.($transfer_credit).'</td>
                                 <td align="center">'.$overallApr.'</td>
                                <td align="center">'.$overallAbs.'</td>
                                <td align="center">'.$overallMissg.'</td>
                                <td align="center">'.$OverallFail.'</td>

                                <td align="center">'.$OverallPass.'</td>
                                <td align="center">'.$overallPassPer.'</td>
                            </tr>';
                  }

                  
               
                $overAllTotal = $overallMissg = $overallAbs = $OverallFail = $overallApr = $OverallPass = $overallPassPer = 0;

                $PRACTICALoverAllTotal=$PRACTICALoverallApr=$PRACTICALoverallAbs=$PRACTICALoverallMissg=$PRACTICALOverallFail= $PRACTICALOverallPass=0; $PRACTICALoverallPassPer=0;
                  
                if(!empty($total_pracappearred))
                {
                   $body .='<tr>
                        <td colspan=11><center class="tag_line"><b>PRACTICAL</b></center></td>
                    </tr>';
                      foreach($total_pracappearred as $rows) 
                      { 
                          $sem = ConfigUtilities::semCaluclation($rows['year'], $rows['month'], $rows['batch_mapping_id']);


                               $detain_stu = Yii::$app->db->createCommand("select coe_student_mapping_id from coe_student_mapping where (status_category_type_id=83 || status_category_type_id =4) and course_batch_mapping_id='".$rows["batch_mapping_id"]."'")->queryAll();
                                
                                $not_in_stu = ''; $l='';
                                foreach ($detain_stu as $key => $value) 
                                {
                                  if($l<(count($detain_stu)-1))
                                  {
                                     $not_in_stu.=$value['coe_student_mapping_id'].",";
                                  }
                                  else
                                  {
                                     $not_in_stu.=$value['coe_student_mapping_id'];
                                  }
                                  

                                   $l++;
                                }

                                if($not_in_stu!='')
                                {
                                   $getTotalCountApr_stu = Yii::$app->db->createCommand('select DISTINCT absent_student_reg FROM coe_absent_entry as A JOIN coe_prac_exam_ttable as D ON D.exam_year=A.exam_year and D.subject_map_id=A.exam_subject_id and D.exam_month=A.exam_month  JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=D.subject_map_id JOIN coe_subjects as B ON B.coe_subjects_id=C.subject_id  JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id and abc.coe_student_mapping_id=A.absent_student_reg  where A.exam_date is NULL ANDC.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.exam_year ="'.$rows["year"].'" and A.exam_month ="'.$rows["month"].'" and D.exam_year ="'.$rows["year"].'" and D.exam_month ="'.$rows["month"].'" and A.exam_type=27')->queryAll();

                                     $not_in_stu1 = ''; $l='';
                                      foreach ($getTotalCountApr_stu as $key => $value) 
                                      {
                                        if($l<(count($getTotalCountApr_stu)-1))
                                        {
                                           $not_in_stu1.=$value['absent_student_reg'].",";
                                        }
                                        else
                                        {
                                           $not_in_stu1.=$value['absent_student_reg'];
                                        }
                                        

                                         $l++;
                                      }

                                       if($not_in_stu1!='')
                                       {

                                          $getFailCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result != "Absent"  and A.year_of_passing="" and A.student_map_id NOT IN('.$not_in_stu.') and A.student_map_id NOT IN('.$not_in_stu1.') ')->queryScalar();

                                          $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result like "%pass%"  and A.student_map_id NOT IN('.$not_in_stu.') and A.student_map_id NOT IN('.$not_in_stu1.') ')->queryScalar();
                                      }
                                      else
                                      {
                                        $getFailCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result != "Absent"  and A.year_of_passing="" and A.student_map_id NOT IN('.$not_in_stu.') ')->queryScalar();

                                        $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result like "%pass%"  and A.student_map_id NOT IN('.$not_in_stu.') ')->queryScalar();
                                      }
                                    
                                    $getTotalCount = Yii::$app->db->createCommand('select count(DISTINCT A.student_map_id) FROM coe_prac_exam_ttable as A JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects as D ON D.coe_subjects_id=C.subject_id JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id JOIN coe_student as bcd ON bcd.coe_student_id=abc.student_rel_id where  C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.mark_type=27 and A.exam_year ="'.$rows["year"].'" and A.exam_month ="'.$rows["month"].'" and A.student_map_id NOT IN('.$not_in_stu.') ')->queryScalar();


                                    $getTotalCountApr = Yii::$app->db->createCommand('select count(DISTINCT absent_student_reg) FROM coe_absent_entry as A JOIN coe_prac_exam_ttable as D ON D.exam_year=A.exam_year and D.subject_map_id=A.exam_subject_id and D.exam_month=A.exam_month  JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=D.subject_map_id JOIN coe_subjects as B ON B.coe_subjects_id=C.subject_id  JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id and abc.coe_student_mapping_id=A.absent_student_reg  where A.exam_date is NULL AND C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.exam_year ="'.$rows["year"].'" and A.exam_month ="'.$rows["month"].'" and D.exam_year ="'.$rows["year"].'" and D.exam_month ="'.$rows["month"].'" and A.exam_type=27 and A.absent_student_reg NOT IN('.$not_in_stu.') ')->queryScalar();
                                }
                                else
                                {
                                    $getTotalCount = Yii::$app->db->createCommand('select count(DISTINCT A.student_map_id) FROM coe_prac_exam_ttable as A JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects as D ON D.coe_subjects_id=C.subject_id JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id JOIN coe_student as bcd ON bcd.coe_student_id=abc.student_rel_id where  C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.mark_type=27 and A.exam_year ="'.$rows["year"].'" and A.exam_month ="'.$rows["month"].'" ')->queryScalar();

                                    $getTotalCountApr = Yii::$app->db->createCommand('select count(DISTINCT absent_student_reg) FROM coe_absent_entry as A JOIN coe_prac_exam_ttable as D ON D.exam_year=A.exam_year and D.subject_map_id=A.exam_subject_id and D.exam_month=A.exam_month  JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=D.subject_map_id JOIN coe_subjects as B ON B.coe_subjects_id=C.subject_id  JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id and abc.coe_student_mapping_id=A.absent_student_reg  where A.exam_date is NULL AND C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.exam_year ="'.$rows["year"].'" and A.exam_month ="'.$rows["month"].'" and D.exam_year ="'.$rows["year"].'" and D.exam_month ="'.$rows["month"].'" and A.exam_type=27')->queryScalar();

                                    $getTotalCountApr_stu = Yii::$app->db->createCommand('select DISTINCT absent_student_reg FROM coe_absent_entry as A JOIN coe_prac_exam_ttable as D ON D.exam_year=A.exam_year and D.subject_map_id=A.exam_subject_id and D.exam_month=A.exam_month  JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=D.subject_map_id JOIN coe_subjects as B ON B.coe_subjects_id=C.subject_id  JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id and abc.coe_student_mapping_id=A.absent_student_reg  where A.exam_date is NULL AND C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.exam_year ="'.$rows["year"].'" and A.exam_month ="'.$rows["month"].'" and D.exam_year ="'.$rows["year"].'" and D.exam_month ="'.$rows["month"].'" and A.exam_type=27')->queryAll();

                                     $not_in_stu1 = ''; $l='';
                                      foreach ($getTotalCountApr_stu as $key => $value) 
                                      {
                                        if($l<(count($getTotalCountApr_stu)-1))
                                        {
                                           $not_in_stu1.=$value['absent_student_reg'].",";
                                        }
                                        else
                                        {
                                           $not_in_stu1.=$value['absent_student_reg'];
                                        }
                                        

                                         $l++;
                                      }

                                     if($not_in_stu1!='')
                                       {

                                          $getFailCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result != "Absent"  and A.year_of_passing="" and A.student_map_id NOT IN('.$not_in_stu1.') ')->queryScalar();

                                          $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result like "%pass%" and A.student_map_id NOT IN('.$not_in_stu1.') ')->queryScalar();
                                      }
                                      else
                                      {
                                        $getFailCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result != "Absent"  and A.year_of_passing=""')->queryScalar();

                                        $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result like "%pass%" ')->queryScalar();
                                      }
                                }                                                                                  
                                
                              
                              $examsession = Yii::$app->db->createCommand("select category_type from coe_category_type where  coe_category_type_id='".$rows["exam_session"]."'")->queryScalar();

                              $disp_total = empty($getTotalCount)?'-':$getTotalCount;
                            
                            
                              $disp_total=$disp_total;
                              $getTotalCount=$getTotalCount;
                            
                             
                             $getAbCount = $getTotalCountApr;
                              $overAllTotal = $getTotalCount+$overAllTotal;
                              $overallAbs = $overallAbs+$getAbCount;
                              
                              $OverallPass = $OverallPass+$getPassCount;
                              $OverallFail = $OverallFail+$getFailCount;
                              
                              
                              $disp_fail = empty($getFailCount)?'-':$getFailCount;
                              $disp_pass = empty($getPassCount)?'-':$getPassCount;
                              $disp_ab = empty($getAbCount)?'-':$getAbCount;
                             
                             // $overallApr = $overallApr+($disp_fail+$disp_pass);
                              $passPerc ='-';
                               $disp_data = '';
                               $miss_reg = '';
                               $disp_total_apr =0;
                              $MISSING_entry =0;

                              

                               $passPerc = ($disp_total_apr==0 || $disp_total_apr=='-')?'-':round((($disp_pass/$disp_total_apr)*100),2);

                              $getdataCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and status_category_type_id NOT IN("'.$det_disc_type.'") and status_category_type_id NOT IN("'.$detain_type.'") ')->queryScalar();

                              if($getdataCount>0)
                              {

                                $getappered = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_practical_entry as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27')->queryScalar();

                                $disp_total_apr = $getappered;

                                $overallApr = $overallApr+$disp_total_apr;
                              

                               $disp_total_apr = $disp_total_apr==0?'-':$disp_total_apr;

                            
                             
                              $MISSING_entry = Yii::$app->db->createCommand('select count(DISTINCT A.student_map_id) FROM coe_practical_entry as A JOIN coe_prac_exam_ttable as E ON E.subject_map_id=A.subject_map_id and E.exam_year=A.year and E.exam_month=A.month JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=E.subject_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id  join coe_bat_deg_reg as x ON x.coe_bat_deg_reg_id=B.batch_mapping_id join coe_programme as y ON y.coe_programme_id=x.coe_programme_id join coe_degree as z ON z.coe_degree_id=x.coe_degree_id
                                 JOIN coe_student_mapping STU ON A.student_map_id=STU.coe_student_mapping_id WHERE  A.student_map_id NOT IN( SELECT student_map_id FROM coe_mark_entry_master cmem JOIN coe_student_mapping csm ON 
                                  csm.coe_student_mapping_id=cmem.student_map_id JOIN coe_student cs ON cs.coe_student_id=csm.student_rel_id WHERE E.exam_date != "-1" AND  cmem.subject_map_id=E.subject_map_id  AND year="'.$rows["year"].'" AND month="'.$rows["month"].'" ) and B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'"  and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and E.mark_type=27  and E.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and E.exam_year ="'.$rows["year"].'" and E.exam_month ="'.$rows["month"].'"  and STU.status_category_type_id NOT IN("'.$det_disc_type.'") and STU.status_category_type_id NOT IN("'.$detain_type.'") group by E.semester,programme_code,degree_code,coe_subjects_id')->queryScalar();

                                  
                               $overallMissg += $MISSING_entry==''?0:$MISSING_entry;

                                 $getMissingData = Yii::$app->db->createCommand('select S.register_number FROM coe_practical_entry as A JOIN coe_prac_exam_ttable as E ON E.subject_map_id=A.subject_map_id and E.exam_year=A.year and E.exam_month=A.month JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=E.subject_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id  join coe_bat_deg_reg as x ON x.coe_bat_deg_reg_id=B.batch_mapping_id join coe_programme as y ON y.coe_programme_id=x.coe_programme_id join coe_degree as z ON z.coe_degree_id=x.coe_degree_id
                                 JOIN coe_student_mapping STU ON A.student_map_id=STU.coe_student_mapping_id JOIN coe_student S ON S.coe_student_id=STU.student_rel_id WHERE  A.student_map_id NOT IN( SELECT student_map_id FROM coe_mark_entry_master cmem JOIN coe_student_mapping csm ON 
                                  csm.coe_student_mapping_id=cmem.student_map_id JOIN coe_student cs ON cs.coe_student_id=csm.student_rel_id WHERE E.exam_date != "-1" AND cmem.subject_map_id=E.subject_map_id  AND year="'.$rows["year"].'" AND month="'.$rows["month"].'" ) and B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'"  and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and E.mark_type=27  and E.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and E.exam_year ="'.$rows["year"].'" and E.exam_month ="'.$rows["month"].'"  and STU.status_category_type_id NOT IN("'.$det_disc_type.'") and STU.status_category_type_id NOT IN("'.$detain_type.'") group by E.semester,programme_code,degree_code,coe_subjects_id')->queryScalar();
                             
                               
                               if(!empty($getMissingData))
                               {
                               
                                  foreach ($getMissingData as $key => $abc) 
                                  {
                                      $miss_reg .=$abc['register_number'].', ';
                                  }
                                  $miss_reg = trim($miss_reg,', ');
                               }
                               
                               if(!empty($miss_reg))
                               {
                                  $disp_data = '('.$miss_reg.')';
                               }

                              
                              }

                              
                               
                               $body .='<tr>
                                      <td align="center">'.$i.'</td>
                                      <td align="center">'.$rows["degree_code"]."-".$rows["programme_code"].'</td>
                                      <td align="center">'.$rows["batch_name"].'</td>
                                      <td align="center">'.$rows["semester"].'</td>
                                      <td align="center">'.$rows["subject_code"].'</td>
                                      <td align="center">'.$rows["subject_name"].'</td>
                                       <td align="center">'.date("d-m-Y",strtotime($rows["exam_date"])).' & '.$examsession.'</td>
                                      <td align="center">'.$disp_total.'</td>
                                      <td align="center">'.$disp_total_apr.'</td>
                                      <td align="center">'.$disp_ab.'</td>
                                      <td align="center">'.$MISSING_entry.' '.$disp_data.'</td>
                                      <td align="center">'.$disp_fail.'</td>
                                      <td align="center">'.$disp_pass.'</td>
                                      <td align="center">'.$passPerc.'</td>
                                  </tr>';
                         
                          $i++; 

                      }
                      if($overallApr !=0)
                      {
                          $overallPassPer = $overAllTotal==0?'-':round((($OverallPass/$overallApr)*100),2);
                      }
                      $body .='<tr>
                                <td colspan=4 >GRAND TOTAL</td>  
                                <td align="center"></td>
                                 <td align="center"></td>
                                  <td align="center"></td>
                                <td align="center">'.($overAllTotal).'</td>
                                 <td align="center">'.$overallApr.'</td>
                                <td align="center">'.$overallAbs.'</td>
                                <td align="center">'.$overallMissg.'</td>
                                <td align="center">'.$OverallFail.'</td>

                                <td align="center">'.$OverallPass.'</td>
                                <td align="center">'.$overallPassPer.'</td>
                            </tr>';      
                      
                      $PRACTICALoverAllTotal=$overAllTotal;
                      $PRACTICALoverallApr=$overallApr;
                      $PRACTICALoverallAbs=$overallAbs;
                      $PRACTICALoverallMissg=$overallMissg;
                      $PRACTICALOverallFail= $OverallFail;
                      $PRACTICALOverallPass=$OverallPass;
                      $PRACTICALoverallPassPer=$overallPassPer;

                  }

          
              /* $overAllTotal = $overallMissg = $overallAbs = $OverallFail = $overallApr = $OverallPass = $overallPassPer = 0;
                  
                if(!empty($total_swbasedappearred))
                {
                  $body .='<tr>
                        <td colspan=11><center class="tag_line"><b>SOFTWARE BASED</b></center></td>
                    </tr>';
                        foreach($total_swbasedappearred as $rows) 
                  { 
                    $sem = ConfigUtilities::semCaluclation($rows['year'], $rows['month'], $rows['batch_mapping_id']);

                        $getFailCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result != "Absent"  and A.year_of_passing="" and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();

                       $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and A.result like "%pass%"  and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();
                   

                        $getTotalCount = Yii::$app->db->createCommand('select count(DISTINCT A.register_number) FROM coe_hall_allocate as A JOIN coe_exam_timetable as E ON E.coe_exam_timetable_id=A.exam_timetable_id and E.exam_year=A.year and E.exam_month=A.month JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=E.subject_mapping_id JOIN coe_subjects as D ON D.coe_subjects_id=C.subject_id JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id JOIN coe_student as bcd ON bcd.coe_student_id=abc.student_rel_id and bcd.register_number=A.register_number where A.register_number NOT IN (SELECT register_number FROM coe_student_mapping a JOIN coe_student b ON b.coe_student_id=a.student_rel_id WHERE a.course_batch_mapping_id="'.$rows['batch_mapping_id'].'" AND a.semester_detain="'.$sem.'") and C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and E.exam_type=27 and E.exam_year ="'.$rows["year"].'" and E.exam_month ="'.$rows["month"].'"   and status_category_type_id!="'.$det_disc_type.'" ')->queryScalar();
                      
                         
                        
                        $getTotalCountApr = Yii::$app->db->createCommand('select count(DISTINCT absent_student_reg) FROM coe_absent_entry as A JOIN coe_exam_timetable as D ON D.exam_year=A.exam_year and D.subject_mapping_id=A.exam_subject_id and D.exam_month=A.exam_month  JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=D.subject_mapping_id JOIN coe_subjects as B ON B.coe_subjects_id=C.subject_id  JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id and abc.coe_student_mapping_id=A.absent_student_reg  where C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.exam_year ="'.$rows["year"].'" and A.exam_month ="'.$rows["month"].'" and D.exam_year ="'.$rows["year"].'" and D.exam_month ="'.$rows["month"].'" and A.exam_type=27  and status_category_type_id!="'.$det_disc_type.'"')->queryScalar();
                          
                           $examsession = Yii::$app->db->createCommand("select category_type from coe_category_type where  coe_category_type_id='".$rows["exam_session"]."'")->queryScalar();
                      
                       
                       $getAbCount = $getTotalCountApr;
                        $overAllTotal = $getTotalCount+$overAllTotal;
                        $overallAbs = $overallAbs+$getAbCount;
                        
                        $OverallPass = $OverallPass+$getPassCount;
                        $OverallFail = $OverallFail+$getFailCount;
                        

                        $disp_total = empty($getTotalCount)?'-':$getTotalCount;
                        
                        $disp_fail = empty($getFailCount)?'-':$getFailCount;
                        $disp_pass = empty($getPassCount)?'-':$getPassCount;
                        $disp_ab = empty($getAbCount)?'-':$getAbCount;
                       
                        $overallApr = $overallApr+($disp_fail+$disp_pass);
                        $passPerc ='-';
                         $disp_data = '';
                         $miss_reg = '';
                         $disp_total_apr =0;
                        $MISSING_entry =0;

                         $passPerc = ($disp_total_apr==0 || $disp_total_apr=='-')?'-':round((($disp_pass/$disp_total_apr)*100),2);

                        $getdataCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=27 and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();

                        if($getdataCount>0)
                        {

                            //$disp_total_apr = empty($getTotalCountApr)?$getTotalCount:(empty($getTotalCount)?'-':(($getTotalCount-$getTotalCountApr)==0?'-':($getTotalCount-$getTotalCountApr) ) );
                        

                         $disp_total_apr = $disp_fail+$disp_pass;
                           
                         //$MISSING_entry = $disp_fail+( ( ($disp_pass==0 || $disp_pass=='-')?0:$disp_pass) + ( ($disp_ab==0 || $disp_ab=='-' ) ? 0 :$disp_ab) );
                         
                         //$MISSING_entry = ( $disp_total == $MISSING_entry ) ?'-': ($disp_total - $MISSING_entry );

                        $MISSING_entry = Yii::$app->db->createCommand('select count(register_number) FROM coe_hall_allocate as A JOIN coe_exam_timetable as E ON E.coe_exam_timetable_id=A.exam_timetable_id and E.exam_year=A.year and E.exam_month=A.month JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=E.subject_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id  join coe_bat_deg_reg as x ON x.coe_bat_deg_reg_id=B.batch_mapping_id join coe_programme as y ON y.coe_programme_id=x.coe_programme_id join coe_degree as z ON z.coe_degree_id=x.coe_degree_id  WHERE  register_number NOT IN( SELECT register_number FROM coe_mark_entry_master cmem JOIN coe_student_mapping csm ON 
                            csm.coe_student_mapping_id=cmem.student_map_id JOIN coe_student cs ON cs.coe_student_id=csm.student_rel_id WHERE cmem.subject_map_id=E.subject_mapping_id  AND year="'.$rows["year"].'" AND month="'.$rows["month"].'") and register_number NOT IN (SELECT register_number FROM coe_student_mapping a JOIN coe_student b ON b.coe_student_id=a.student_rel_id WHERE a.course_batch_mapping_id="'.$rows['batch_mapping_id'].'" AND a.semester_detain="'.$sem.'") and B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'"  and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and E.exam_type=27  and E.subject_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and E.exam_year ="'.$rows["year"].'" and E.exam_month ="'.$rows["month"].'"  group by semester,programme_code,degree_code,coe_subjects_id')->queryScalar();

                       

                         $overallMissg += $MISSING_entry=='-'?0:$MISSING_entry;

                          $getMissingData = Yii::$app->db->createCommand('select register_number FROM coe_hall_allocate as A JOIN coe_exam_timetable as E ON E.coe_exam_timetable_id=A.exam_timetable_id and E.exam_year=A.year and E.exam_month=A.month JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=E.subject_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id  join coe_bat_deg_reg as x ON x.coe_bat_deg_reg_id=B.batch_mapping_id join coe_programme as y ON y.coe_programme_id=x.coe_programme_id join coe_degree as z ON z.coe_degree_id=x.coe_degree_id  WHERE  register_number NOT IN( SELECT register_number FROM coe_mark_entry_master cmem JOIN coe_student_mapping csm ON 
                            csm.coe_student_mapping_id=cmem.student_map_id JOIN coe_student cs ON cs.coe_student_id=csm.student_rel_id WHERE cmem.subject_map_id=E.subject_mapping_id   AND year="'.$rows["year"].'" AND month="'.$rows["month"].'" ) and register_number NOT IN (SELECT register_number FROM coe_student_mapping a JOIN coe_student b ON b.coe_student_id=a.student_rel_id WHERE a.course_batch_mapping_id="'.$rows['batch_mapping_id'].'" AND a.semester_detain="'.$sem.'") and B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'"  and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and E.exam_type=27  and E.subject_mapping_id="'.$rows["coe_subjects_mapping_id"].'"and E.exam_year ="'.$rows["year"].'" and E.exam_month ="'.$rows["month"].'"  group by semester,programme_code,degree_code,coe_subjects_id')->queryAll();
                         
                         
                         
                         if(!empty($getMissingData))
                         {
                         
                            foreach ($getMissingData as $key => $abc) 
                            {
                                $miss_reg .=$abc['register_number'].', ';
                            }
                            $miss_reg = trim($miss_reg,', ');
                         }
                         
                         if(!empty($miss_reg))
                         {
                            $disp_data = '('.$miss_reg.')';
                         }

                        
                        }
                         
                         $body .='<tr>
                                <td align="center">'.$i.'</td>
                                <td align="center">'.$rows["degree_code"]."-".$rows["programme_code"].'</td>
                                <td align="center">'.$rows["batch_name"].'</td>
                                <td align="center">'.$rows["semester"].'</td>
                                <td align="center">'.$rows["subject_code"].'</td>
                                <td align="center">'.$rows["subject_name"].'</td>
                                 <td align="center">'.date("d-m-Y",strtotime($rows["exam_date"])).' & '.$examsession.'</td>
                                <td align="center">'.$disp_total.'</td>
                                <td align="center">'.$disp_total_apr.'</td>
                                <td align="center">'.$disp_ab.'</td>
                                <td align="center">'.$MISSING_entry.' '.$disp_data.'</td>
                                <td align="center">'.$disp_fail.'</td>
                                <td align="center">'.$disp_pass.'</td>
                                <td align="center">'.$passPerc.'</td>
                            </tr>';
                   
                    $i++; 

                }
                if($overallApr !=0)
                {
                    $overallPassPer = $overAllTotal==0?'-':round((($OverallPass/$overallApr)*100),2);
                }

                 $body .='<tr>
                                <td colspan=4 >GRAND TOTAL</td>  
                                <td align="center"></td>
                                 <td align="center"></td>
                                  <td align="center"></td>
                                <td align="center">'.($overAllTotal).'</td>
                                 <td align="center">'.$overallApr.'</td>
                                <td align="center">'.$overallAbs.'</td>
                                <td align="center">'.$overallMissg.'</td>
                                <td align="center">'.$OverallFail.'</td>

                                <td align="center">'.$OverallPass.'</td>
                                <td align="center">'.$overallPassPer.'</td>
                            </tr>';       
                  }*/

                $overAllTotal = $overallMissg = $overallAbs = $OverallFail = $overallApr = $OverallPass = $overallPassPer = 0;
                  
                if(!empty($total_othersubject))
                {
                   $body .='<tr>
                        <td colspan=11><center class="tag_line"><b>OTHER COURSE(Audit,Mandatory,Internal Mode)</b></center></td>
                    </tr>';
                       foreach($total_othersubject as $rows) 
                      { 
                      
                          $batch_year=date('Y')-$rows["batch_name"];

                           $sem = ConfigUtilities::semCaluclation($mark_year, $mark_month, $rows['batch_mapping_id']); //exit();

                          if($batch_year<=$rows["degree_total_years"] && $rows["semester"] == $sem)
                          {

                            $getFailCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'" and A.year ="'.$mark_year.'" and A.month ="'.$mark_month.'" and A.mark_type=27 and A.result != "Absent"  and A.year_of_passing="" and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();

                           $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$mark_year.'" and A.month ="'.$mark_month.'" and A.mark_type=27 and A.result like "%pass%"  and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();
                       
                            $getTotalCount = Yii::$app->db->createCommand('select count(DISTINCT coe_student_mapping_id) FROM  coe_student_mapping as abc where coe_student_mapping_id NOT IN (SELECT coe_student_mapping_id FROM coe_student_mapping a JOIN coe_student b ON b.coe_student_id=a.student_rel_id WHERE a.course_batch_mapping_id="'.$rows['batch_mapping_id'].'" AND a.semester_detain<="'.$sem.'" and status_category_type_id!=6) and abc.course_batch_mapping_id="'.$rows['batch_mapping_id'].'" and status_category_type_id!="'.$det_disc_type.'" ')->queryScalar();
                          
                            
                            $getTotalCountApr = Yii::$app->db->createCommand('select count(DISTINCT absent_student_reg) FROM coe_absent_entry as A JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id JOIN coe_subjects as B ON B.coe_subjects_id=C.subject_id  JOIN coe_student_mapping as abc ON abc.course_batch_mapping_id=C.batch_mapping_id and A.exam_date is NOT NULL AND abc.coe_student_mapping_id=A.absent_student_reg  where C.coe_subjects_mapping_id="'.$rows["coe_subjects_mapping_id"].'" and A.exam_year ="'.$mark_year.'" and A.exam_month ="'.$mark_month.'" and status_category_type_id!="'.$det_disc_type.'"')->queryScalar();
                              
                               $examsession = Yii::$app->db->createCommand("select category_type from coe_category_type where  coe_category_type_id='".$rows["exam_session"]."'")->queryScalar();
                          
                           
                           $getAbCount = $getTotalCountApr;
                            $overAllTotal = $getTotalCount+$overAllTotal;
                            $overallAbs = $overallAbs+$getAbCount;
                            
                            $OverallPass = $OverallPass+$getPassCount;
                            $OverallFail = $OverallFail+$getFailCount;
                            

                            $disp_total = empty($getTotalCount)?'-':$getTotalCount;
                            
                            $disp_fail = empty($getFailCount)?'-':$getFailCount;
                            $disp_pass = empty($getPassCount)?'-':$getPassCount;
                            $disp_ab = empty($getAbCount)?'-':$getAbCount;
                           
                            $overallApr = $overallApr+($disp_fail+$disp_pass);
                            $passPerc ='-';
                             $disp_data = '';
                             $miss_reg = '';
                             $disp_total_apr =0;
                            $MISSING_entry =0;

                             $passPerc = ($disp_total_apr==0 || $disp_total_apr=='-')?'-':round((($disp_pass/$disp_total_apr)*100),2);

                            $getdataCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_student_mapping as D ON D.course_batch_mapping_id=B.batch_mapping_id and D.coe_student_mapping_id=A.student_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where A.subject_map_id="'.$rows["coe_subjects_mapping_id"].'"   and A.year ="'.$mark_year.'" and A.month ="'.$mark_month.'" and A.mark_type=27 and status_category_type_id NOT IN("'.$det_disc_type.'") ')->queryScalar();

                            if($getdataCount>0)
                            {

                             $disp_total_apr = $disp_fail+$disp_pass;  

                             $MISSING_entry=  $disp_total - $disp_total_apr;           

                             $overallMissg += $MISSING_entry=='-'?0:$MISSING_entry;

                            
                            }
                             
                             $body .='<tr>
                                    <td align="center">'.$i.'</td>
                                    <td align="center">'.$rows["degree_code"]."-".$rows["programme_code"].'</td>
                                    <td align="center">'.$rows["batch_name"].'</td>
                                    <td align="center">'.$rows["semester"].'</td>
                                    <td align="center">'.$rows["subject_code"].'</td>
                                    <td align="center">'.$rows["subject_name"].'</td>
                                     <td align="center">-</td>
                                    <td align="center">'.$disp_total.'</td>
                                    <td align="center">'.$disp_total_apr.'</td>
                                    <td align="center">'.$disp_ab.'</td>
                                    <td align="center">'.$MISSING_entry.'</td>
                                    <td align="center">'.$disp_fail.'</td>
                                    <td align="center">'.$disp_pass.'</td>
                                    <td align="center">'.$passPerc.'</td>
                                </tr>';
                       
                        $i++; 

                      }

                    }
                      if($overallApr !=0)
                      {
                          $overallPassPer = $overAllTotal==0?'-':round((($OverallPass/$overallApr)*100),2);
                      }
                      $body .='<tr>
                                <td colspan=4 >GRAND TOTAL</td>  
                                <td align="center"></td>
                                 <td align="center"></td>
                                  <td align="center"></td>
                                <td align="center">'.($overAllTotal+$overallAbs).'</td>
                                 <td align="center">'.$overallApr.'</td>
                                <td align="center">'.$overallAbs.'</td>
                                <td align="center">'.$overallMissg.'</td>
                                <td align="center">'.$OverallFail.'</td>

                                <td align="center">'.$OverallPass.'</td>
                                <td align="center">'.$overallPassPer.'</td>
                            </tr>';          

                      $PRACTICALoverAllTotal=$PRACTICALoverAllTotal+$overAllTotal;
                      $PRACTICALoverallApr=$PRACTICALoverallApr+$overallApr;
                      $PRACTICALoverallAbs=$PRACTICALoverallAbs+$overallAbs;
                      $PRACTICALoverallMissg=$PRACTICALoverallMissg+$overallMissg;
                      $PRACTICALOverallFail= $PRACTICALOverallFail+ $OverallFail;
                      $PRACTICALOverallPass=$PRACTICALOverallPass+$OverallPass;
                      $PRACTICALoverallPassPer=round((($PRACTICALOverallPass/$PRACTICALoverallApr)*100),2); 
                  }

                   $body .='<tr>
                                <td colspan=4 >GRAND TOTAL(PRACTICAL & OTHER COURSE)</td>  
                                <td align="center"></td>
                                 <td align="center"></td>
                                  <td align="center"></td>
                                <td align="center">'.($PRACTICALoverAllTotal).'</td>
                                 <td align="center">'.$PRACTICALoverallApr.'</td>
                                <td align="center">'.$PRACTICALoverallAbs.'</td>
                                <td align="center">'.$PRACTICALoverallMissg.'</td>
                                <td align="center">'.$PRACTICALOverallFail.'</td>

                                <td align="center">'.$PRACTICALOverallPass.'</td>
                                <td align="center">'.$PRACTICALoverallPassPer.'</td>
                            </tr>';

                $html = $header .$body.$footer; 
                $print_stu_data .= $html;

                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('regular-count-overall-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('regular-count-overall-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$print_stu_data.'</div>
                            </div>
                        </div>
                      </div>'; 
                      
                 $_SESSION['regular-count-overall'] = $print_stu_data;
               
        }// If no Content found for Institution
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');            
        }
    } 
?>
