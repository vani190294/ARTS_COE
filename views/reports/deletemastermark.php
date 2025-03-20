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

$this->title= "Master Mark Delete";
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
            <?= $form->field($model, 'year')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'year','min'=>4,'max'=>4,'value'=>2023,'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'month')->widget(
                Select2::classname(), [  
                    'data' => ConfigUtilities::getMonth(),                      
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => '-----Select Month ----',
                        'id' => 'export_exam_month', 
                        'onchange' => 'getmigratesubject(this.value,$("#year").val());',                           
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
                ])->label('Course');


            ?>
        </div>
       
       <br />
        <div class="btn-group col-lg-4 col-sm-4" role="group" aria-label="Actions to be Perform">
                <?= Html::Button( 'Download' , ['onClick'=>'get_deletemark_info();','class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['reports/deletemastermark']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                <?= Html::Button('Delete', ['onClick'=>"deletemigratemastermark();",'class' => 'btn btn-success' ]) ?> 
            </div>        
    </div>
    <div id="hide_dum_repo_data">
        
    </div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>