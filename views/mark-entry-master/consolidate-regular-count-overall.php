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

$this->title ="CONSOLIDATE REGULAR ABSENT STUDENT COUNT";
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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label("Exam Year") ?>
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
                            'placeholder' => '-----SelectBatch ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('Batch'); 
                ?>
            </div>

             <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['name'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---')])->label("Degree Type (optional)") ?>
          </div>

        <br />  
            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Show', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry/consolidate-regular-count-overall']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>




<?php
if(isset($deptall))
{

   
    echo '<div class="col-xs-12">';
    echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('consolidate-regular-count-overall-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
    echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('consolidate-regular-count-overall-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
    echo '</div>';

    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $month = Categorytype::findOne($_POST['MarkEntry']['month']);

    $header = "";
    $body ="";

    $header .="<table  style='overflow-x:auto;'  border=1 align='center' class='table table-striped headertd'>";
    $header .= '
     <tr>
          <td> 
            <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
          </td>

          <td colspan=2 align="center"> 
              <center><b><font size="6px">' . $org_name . '</font></b></center>
              <center> <font size="1px">' . $org_address . '</font></center>
              
              <center class="tag_line"><b>' . $org_tagline . '</b></center> 
              <center> <font size="1px">CONSOLIDATE REGULAR APPEARED COUNT </font> <b>'.strtoupper($month['category_type']).' - '.$_POST['mark_year'].' EXAMINATION</b> </center>
              <center> <font size="1px">BATCH: </font> <b> '.$deptall[0]['batch_name'].'</b> </center>
         </td>
          <td align="center">  
            <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
          </td>
        </tr>
    </table>';

    $body .="<table  style='overflow-x:auto;' border=1 align='center' class='table table-striped '>";
    $body .="<tr>
                <th align='center'>PROGRAMME NAME</th>
                <th align='center'>REGISTERED STUDENT COUNT</th>
                <th align='center'>APPEARED STUDENT COUNT</th>
                <th align='center'>SUBJECT COUNT (WITHOUT ADD. COURSE)</th>
                <th align='center'>ABSENT ALL SUBJECT STUDENT COUNT</th>
                <th align='center'>ABSENT STUDENT COUNT<br>(ONE OR MORE SUBJECTS)</th>
                <th align='center'>PASSED STUDENT COUNT</th>
            </tr>";
    foreach ($deptall as $kvalue) 
    {
        $get_registered = Yii::$app->db->createCommand('SELECT count(DISTINCT coe_student_mapping_id) FROM coe_student_mapping A INNER JOIN coe_student B ON B.coe_student_id=A.student_rel_id WHERE A.course_batch_mapping_id="'.$kvalue["coe_bat_deg_reg_id"].'" AND A.status_category_type_id IN (3,6,7,253)')->queryScalar();
       
        $get_appeared = Yii::$app->db->createCommand('SELECT count(DISTINCT A.student_map_id) FROM coe_mark_entry_master A INNER JOIN coe_student_mapping B ON B.coe_student_mapping_id=A.student_map_id WHERE B.course_batch_mapping_id="'.$kvalue["coe_bat_deg_reg_id"].'" AND A.year="'.$_POST['mark_year'].'" AND A.month="'.$_POST['MarkEntry']['month'].'" AND A.mark_type=27 AND B.status_category_type_id IN (3,6,7,253)')->queryScalar();
        
        $get_subject_count = Yii::$app->db->createCommand('SELECT count(DISTINCT paper_no) FROM coe_subjects_mapping WHERE batch_mapping_id="'.$kvalue["coe_bat_deg_reg_id"].'" AND semester="'.$semester.'" AND subject_type_id!=233')->queryScalar();

        $get_absent = Yii::$app->db->createCommand('SELECT if(count(A.subject_map_id)=8, A.student_map_id,"0") as student_map_id FROM coe_mark_entry_master A INNER JOIN coe_student_mapping B ON B.coe_student_mapping_id=A.student_map_id WHERE B.course_batch_mapping_id="'.$kvalue["coe_bat_deg_reg_id"].'" AND A.year="'.$_POST['mark_year'].'" AND A.month="'.$_POST['MarkEntry']['month'].'" AND A.mark_type=27 AND B.status_category_type_id IN (3,6,7,253) AND A.result="Absent" GROUP BY A.student_map_id')->queryAll(); //AND A.result="Absent"

        $get_absentdata = array_column($get_absent, 'student_map_id');
        $final_abs = array_filter($get_absentdata);

        $get_absent_subjects = Yii::$app->db->createCommand('SELECT count(DISTINCT A.student_map_id) FROM coe_mark_entry_master A INNER JOIN coe_student_mapping B ON B.coe_student_mapping_id=A.student_map_id WHERE B.course_batch_mapping_id="'.$kvalue["coe_bat_deg_reg_id"].'" AND A.year="'.$_POST['mark_year'].'" AND A.month="'.$_POST['MarkEntry']['month'].'" AND A.mark_type=27 AND B.status_category_type_id IN (3,6,7,253) AND A.result="Absent"')->queryScalar();

        $get_passcount = Yii::$app->db->createCommand('SELECT count(DISTINCT A.student_map_id) FROM coe_mark_entry_master A INNER JOIN coe_student_mapping B ON B.coe_student_mapping_id=A.student_map_id WHERE B.course_batch_mapping_id="'.$kvalue["coe_bat_deg_reg_id"].'" AND A.year="'.$_POST['mark_year'].'" AND A.month="'.$_POST['MarkEntry']['month'].'" AND A.mark_type=27 AND B.status_category_type_id IN (3,6,7,253) AND A.result="Pass" AND A.year_of_passing!="" AND A.student_map_id NOT IN (SELECT AA.student_map_id FROM coe_mark_entry_master AA INNER JOIN coe_student_mapping BB ON BB.coe_student_mapping_id=AA.student_map_id WHERE BB.course_batch_mapping_id="'.$kvalue["coe_bat_deg_reg_id"].'" AND AA.year="'.$_POST['mark_year'].'" AND AA.month="'.$_POST['MarkEntry']['month'].'" AND AA.mark_type=27 AND BB.status_category_type_id IN (3,6,7,253) AND AA.year_of_passing="")')->queryScalar();
        
        if($get_appeared>0)
        {
            $body.="<tr>";
            $body.="<td>".$kvalue['programme_name']."</td>";
            $body.="<td>".$get_registered."</td>";
            $body.="<td>".$get_appeared."</td>";
            $body.="<td>".$get_subject_count."</td>";
            $body.="<td>".count($final_abs)."</td>";
            $body.="<td>".$get_absent_subjects."</td>";
            $body.="<td>".$get_passcount."</td>";
            $body.="</tr>";
        }
    }
    $body.="</table>";

    echo $html=$header.$body;

     if (isset($_SESSION['consolidate_regular-count-overall'])) {
            unset($_SESSION['consolidate_regular-count-overall']);
        }
        $_SESSION['consolidate_regular-count-overall'] = $html;

     if (isset($_SESSION['consolidate_regular-count-overallxl'])) {
            unset($_SESSION['consolidate_regular-count-overallxl']);
        }
        $_SESSION['consolidate_regular-count-overallxl'] = $body;
} 
?>

</div>
</div>
</div>

