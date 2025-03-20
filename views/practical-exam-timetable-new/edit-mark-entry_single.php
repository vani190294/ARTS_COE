<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\ExamTimetable;
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

use app\models\ValuationSettings;
echo Dialog::widget();
$ValuationSettings = ValuationSettings::findOne(1);

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Practical Mark Entry Single Update';?>

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
                            'placeholder' => '-----Select ----',
                            'id' => 'stu_batch_id_selected',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'batch_mapping_id')->widget(
                    Select2::classname(), [
                    //'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'stu_programme_selected',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'exam_year')->textInput(['value'=>$ValuationSettings['current_exam_year'],'id'=>'mark_year']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'exam_month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            //'value'=> $ValuationSettings['current_exam_month'] 
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
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'semester')->textInput(['onblur'=>'getPracExamDates();',]) ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php echo $form->field($model, 'exam_date')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'class'=>'form-control student_disable', 
                            'id'=>'prac_exam_date',      
                            'onchange'=>'getPracSlots();',                   
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">

            <?php echo $form->field($model, 'exam_session')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getPracExamSessions(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'class'=>'form-control student_disable', 
                            'onchange'=>'getPracticalExamSubsOnly();',     
                            'id'=>'prac_exam_sess',                                   
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
        </div>
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',   
                            'onchange'=>'getPracticalstudentssingle(this.value);',                
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'student_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select----',
                            'id' => 'student_map_id',                                  
                            'onchange'=>'getPracticalstudentsmark();'               
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Register Number');
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'out_of_100')->textInput(['value'=>0,'id'=>'out_of_100']) ?>
            </div>

            <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                <br />
                <?= Html::Button('Update Mark', ['onClick'=>'updatepracticalmark()','id'=>'change_name_get_stu1','class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['practical-exam-timetable-new/edit-mark-entrysingle']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
                                 
        </div>

        <div class="col-lg-12 col-sm-12">
       
            
        </div>
        
      
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>