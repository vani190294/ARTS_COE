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

$this->title = "ARREAR COUNT";
$this->params['breadcrumbs'][] = ['label' => "OVERALL ARREAR COUNT", 'url' => ['create']];
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
        <br />  
            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Download', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry/arrear-count-overall']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
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
    if($file_content_available=="Yes")
        {
            if(isset($_SESSION['arrear-count-overall'])){ unset($_SESSION['arrear-count-overall']);}
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
            
            //$countStuVal = count($studentwisearrear);
            $stu_print_vals = 0;
                
               $header .="<table border=1 align='center' class='table table-striped '>";
                    $header .= '<tr>
                    <td style="border: none;" colspan=9>
                    <table width="100%" align="center" border="0">                    
                    <tr>
                      <td> 
                        <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=7 align="center"> 
                          <center><b><font size="4px">'.$org_name.'</font></b></center>
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>
                    <tr>
                        <td colspan=9><center> <font size="3px">'.$org_address.'</font> Phone : <b>'.$org_phone.'</b> </center></td>
                    </tr>
                     <tr>
                        <td colspan=9><center class="tag_line"><b>'.$org_tagline.'</b></center></td>
                    </tr>
                    </table></td></tr>';
                    $header .="
                    <tr>
                      <th align='center'>SNO</th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." CODE </th>
                      
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." NAME</th>
                      <th align='center'>NO OF ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT))."  </th>
                      <th align='center'>NO OF ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT))." APPEARED </th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT))." </th>
                      <th align='center'>FAILED</th>
                      <th align='center'>PASSED</th>
                      <th align='center'>PASS%</th>
                    </tr>";
                    $i=1;
                   $footer .='</table>';
                  $overAllTotal = $overallAbs = $OverallFail = $overallApr = $OverallPass = $overallPassPer = 0;
                  foreach($total_appearred as $rows) 
                  { 

                        $getFailCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'"  and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=28 and A.result like "%Fail%" and A.year_of_passing="" ')->queryScalar();

                        $getPassCount = Yii::$app->db->createCommand('select count(DISTINCT student_map_id) FROM coe_mark_entry_master as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'"  and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and A.mark_type=28 and A.result like "%Pass%"')->queryScalar();

                        $getTotalCount = Yii::$app->db->createCommand('select count(DISTINCT register_number) FROM coe_hall_allocate as A JOIN coe_exam_timetable as E ON E.coe_exam_timetable_id=A.exam_timetable_id and E.exam_year=A.year and E.exam_month=A.month JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=E.subject_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id where B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'"  and A.year ="'.$rows["year"].'" and A.month ="'.$rows["month"].'" and E.exam_type=28 and E.exam_year ="'.$rows["year"].'" and E.exam_month ="'.$rows["month"].'" ')->queryScalar();

                        $getTotalCountApr = Yii::$app->db->createCommand('select count(DISTINCT absent_student_reg) FROM coe_absent_entry as A JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.exam_subject_id JOIN coe_subjects as C ON C.coe_subjects_id=B.subject_id JOIN coe_exam_timetable as D ON D.exam_year=A.exam_year and D.subject_mapping_id=A.exam_subject_id and D.exam_month=A.exam_month where B.subject_id="'.$rows["coe_subjects_id"].'" and C.subject_code="'.$rows["subject_code"].'" and A.exam_year ="'.$rows["year"].'" and A.exam_month ="'.$rows["month"].'" and D.exam_year ="'.$rows["year"].'" and D.exam_month ="'.$rows["month"].'" and A.exam_type=28  GROUP by exam_subject_id')->queryScalar();
                       $getAbCount = $getTotalCountApr;
                        $overAllTotal = $getTotalCount+$overAllTotal;
                        $overallAbs = $overallAbs+$getAbCount;
                        $overallApr = $overallApr+$getTotalCountApr;
                        $OverallPass = $OverallPass+$getPassCount;
                        $OverallFail = $OverallFail+$getFailCount;

                        $disp_total = empty($getTotalCount)?'-':$getTotalCount;
                        $disp_total_apr = empty($getTotalCountApr)?$getTotalCount:(empty($getTotalCount)?'-':(($getTotalCount-$getTotalCountApr)==0?'-':($getTotalCount-$getTotalCountApr) ) );
                        $disp_fail = empty($getFailCount)?'-':$getFailCount;
                        $disp_pass = empty($getPassCount)?'-':$getPassCount;
                        $disp_ab = empty($getAbCount)?'-':$getAbCount;
                        $disp_total_apr = $disp_total_apr==0?'-':$disp_total_apr;
                        $passPerc ='-';

                        if(!empty($getPassCount))
                        {
                            $passPerc = $disp_total==0?'-':round((($getPassCount/$disp_total)*100),2);
                        }
                         
                         $body .='<tr>
                                <td align="center">'.$i.'</td>
                                <td align="center">'.$rows["subject_code"].'</td>
                                <td align="center">'.$rows["subject_name"].'</td>
                                <td align="center">'.$disp_total.'</td>
                                <td align="center">'.$disp_total_apr.'</td>
                                <td align="center">'.$disp_ab.'</td>
                                <td align="center">'.$disp_fail.'</td>
                                <td align="center">'.$disp_pass.'</td>
                                <td align="center">'.$passPerc.'</td>
                            </tr>';
                   
                    $i++; 

                }
                $overallPassPer = $overAllTotal==0?'-':round((($OverallPass/$overAllTotal)*100),2);
                
                $body .='<tr>
                                <td colspan=3 >GRAND TOTAL</td>                                
                                <td align="center">'.$overAllTotal.'</td>
                                <td align="center">'.$overallApr.'</td>
                                <td align="center">'.$overallAbs.'</td>
                                <td align="center">'.$OverallFail.'</td>
                                <td align="center">'.$OverallPass.'</td>
                                <td align="center">'.$overallPassPer.'</td>
                            </tr>';
                $html = $header .$body.$footer; 
                $print_stu_data .= $html;                
                $_SESSION['arrear-count-overall'] = $print_stu_data;   
                             
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('arrear-count-overall-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('arrear-count-overall-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$print_stu_data.'</div>
                            </div>
                        </div>
                      </div>'; 
                
               
        }// If no Content found for Institution
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');            
        }
    } 
?>
