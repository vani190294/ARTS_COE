<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();

$this->title= ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)." Report";
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
            <?= $form->field($model, 'year')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'hallallocate-year','min'=>4,'max'=>4,'value'=>date('Y'),'required'=>'required','autocomplete'=>"off"]); ?>
                     
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
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($examModel, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'exam_date',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div><br />
        <div class="btn-group col-lg-6 col-sm-6" role="group" aria-label="Actions to be Perform">
                <?= Html::Button( 'Download' , ['onClick'=>'get_barcode_dummy_num_info();','class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/barcode-dummy-number-report']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div>
    <div id="hide_dum_repo_data">
        
    </div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>