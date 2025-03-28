<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\DummyNumbers;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();

$this->title= "REMUNEARATION REPORT";
?>

<div class="exam-timetable-form">
    <h1><?php echo $this->title; ?></h1>
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 

$form = ActiveForm::begin(); ?>
<div>&nbsp;</div>
<div class="row">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'year','min'=>4,'max'=>4,'value'=>date('Y'),'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'month')->widget(
                Select2::classname(), [  
                    'data' => ConfigUtilities::getMonth(),                      
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => '-----Select Month ----',
                        'id' => 'dum_exam_month', 
                        'onchange' => 'bringYearMonthSubs(this.value,$("#year").val()); bringEntryDates(this.value,$("#year").val());',                             
                    ],
                   'pluginOptions' => [
                       'allowClear' => true,
                    ],
                ]) ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 

             echo $form->field($model,'subject_map_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----',
                        'id' => 'dummy_exam_subject_code',
                        'name'=>'exam_subject_code',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);


            ?>
        </div>
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo $form->field($model,'exam_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date ----',
                        'id' => 'exam_date',
                        'name'=>'exam_date',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Exam Date');
                ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo $form->field($model,'created_at')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date ----',
                        'id' => 'created_at',
                        'name'=>'created_at',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Entry Date');
                ?>
                     
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <br />
        <div class="btn-group col-lg-4 col-sm-4" role="group" aria-label="Actions to be Perform">
                <?= Html::Button( 'Download' , ['onClick'=>'get_dummy_num_staff_info();','class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/dummy-number-staff-remuneration']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div><br /><br />
    <div id="hide_dum_repo_data">
        
    </div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>