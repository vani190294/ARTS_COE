<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\Batch;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
$batch_id = isset($model->man_batch_id)?$model->manBatch->coe_batch_id:"";
?>

<div class="subjects-form">
<div class="box box-success">
<div class="box-body">
     <?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div>&nbsp;</div>

    <?php 
    $condition = $model->isNewRecord?true:false;
    $form = ActiveForm::begin([
                    'id' => 'categories-form',
                    'enableAjaxValidation' => $condition,
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",

                    ],
            ]); ?>
    <div class="row"> 
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">    
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'man_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'value' =>$batch_id,
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
                    'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ---',
                            'id' => 'stu_programme_selected',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>  
            <div class="col-sm-2 col-lg-2">
            <?= $form->field($model, 'semester')->textInput(['maxlength' => true,'placeholder'=>'Eg : Numbers Only','autocomplete'=>'off']) ?>
            </div>
            <div class="col-sm-2 col-lg-2">
            <?= $form->field($model, 'subject_code')->textInput(['maxlength' => true,'placeholder'=>'Eg : Enter Alpha Numeric Characters','autocomplete'=>'off']) ?>
            </div>

            <div class="col-sm-2 col-lg-2">
                <?= $form->field($model, 'subject_name')->textInput(['maxlength' => true,'placeholder'=>'Enter the '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name','autocomplete'=>'off']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">   
                <?= $form->field($model, 'created_at')->checkbox(array(
                    'label'=>'',
                    'labelOptions'=>array('style'=>'padding:5px;'),                    
                    ))
                    ->label('Additional Credit '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); ?>
            </div>
            <div class="col-lg-2 col-sm-2">  
                <?php 
                    echo $form->field($model,'updated_at')->widget(
                    Select2::classname(), [
                        'data' => $model->getAllSubjects(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' ----',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('MIGRATE '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." FROM "); 
                ?>           
            </div>
           
        </div>
    </div>
</div>

<div class="row"> 
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
                <div class="col-sm-2 col-lg-2">
                    <?php 
                        $model->end_semester_exam_value_mark = $model->CIA_max=100;
                        $model->ESE_min = $model->ESE_max =0; 
                     ?>

                    <?= $form->field($model, 'CIA_min')->textInput(['placeholder'=>'Only Numbers','autocomplete'=>'off']) ?>
            </div>
            <div class="col-sm-2 col-lg-2">
                <?= $form->field($model, 'CIA_max')->textInput(['placeholder'=>'Only Numbers','autocomplete'=>'off']) ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-lg-2">
                    <?= $form->field($model, 'total_minimum_pass')->textInput(['placeholder'=>'Only Numbers','autocomplete'=>'off']) ?>
                </div>
                <div class="col-sm-2 col-lg-2">
                    <?= $form->field($model, 'end_semester_exam_value_mark',['labelOptions' => [ 'class' => 'control-label' ,'for'=>'no_name']])->textInput(['placeholder'=>'Only Numbers','autocomplete'=>'off'])->label('End Semester Marks') ?>
                </div>
               
           </div>
    </div>
</div>

<div class="row"> 
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <br />
            <div class="form-group col-xs-12 col-sm-4 col-lg-4">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id' => $model->isNewRecord ? 'checkValidated' : '','data-confirm' => 'Are you sure you want to Update this record <br /> This can not be Un-Done once the values were changed Until you <b>CONTACT THE SUPPORT TEAM?</b> Please re-check your Submission and Click <b>OK</b> to proceed.']) ?>

                <?= Html::a('Reset', ['create'], ['class' => 'btn btn-default']) ?>
            </div>
        
        </div>
    </div>
</div>
    <?php ActiveForm::end(); ?>
</div>
</div>