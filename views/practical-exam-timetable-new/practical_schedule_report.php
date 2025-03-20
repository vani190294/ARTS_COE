<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\HallAllocate;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use app\models\ExamTimetable;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Practical Schedule Pre Report';


?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
           
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)."<span style='color:red;'>*</span>"); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry, 'stu_programme_id')->widget(
                    Select2::classname(), [
                    
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)."<span style='color:red;'>*</span>"); 
                ?>
            </div>

             
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'exam_year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
                <input type="hidden" name="" id="mark_subject_code" value="" >
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'exam_month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'mark_type')->radioList(ExamTimetable::getExamType()); ?>
           
            </div>
            
            <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                <br />
                <?= Html::Button('Get Report', ['onClick'=>'getpracticalreport()','class' => 'btn btn-success' ]) ?>
            
                <?= Html::a("Reset", Url::toRoute(['practical-exam-timetable-new/practical-schedule-report']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
            </div>
            

    </div>

    <div id='hide_dum_sub_data' class="row">
        <div  class="col-xs-12"> <br /><br />
                <?php 
                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/practical-exam-timetable-new/pre-external-examiner-report-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/practical-exam-timetable-new/pre-excel-external-examiner'], [
                    'class'=>'pull-right btn btn-block btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                ?>
                <div class="col-lg-3 pull-right" > <?php echo $print_excel." ".$print_pdf; ?> </div>
            </div>
        <div id='disp_show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">
            <div id='show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">

            </div>
            
        </div>
    </div> <!-- Row Closed --><br />
        
      
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>