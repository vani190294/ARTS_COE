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

$batch_id = isset($subjects->batch_mapping_id)?$subjects->batchMapping->coeBatch->coe_batch_id:"";
$batch_mapping_id = isset($subjects->batch_mapping_id)?$subjects->batch_mapping_id:"";


?>

<style type="text/css">
.left-padding
{
    margin-left: -10px; 
    padding-right: -0px;
}
.righh-padding
{
    padding-right: -0px;
}
</style>

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

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-4 col-sm-4">
                <?php echo $form->field($model,'coe_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'value'=> $batch_id,
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div> 

            <div class="col-lg-4 col-sm-4">
                <?php echo $form->field($model, 'batch_mapping_id')->widget(
                    Select2::classname(), [
                    'data'=>ConfigUtilities::getDegreedetails(),

                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'value'=>$batch_mapping_id,
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div> 

            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($subjects, 'semester')->textInput(['maxlength'=>1,'placeholder'=>'Only Numbers']) ?>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-4 col-lg-4">
      <?= $form->field($model, 'subject_code')->textInput(['maxlength' => true,'placeholder'=>'Eg : Enter Alpha Numeric Characters','id'=>'sub_code','onblur'=>'getSubjectNameSubCode(this.value)']) ?>
            </div>

            <div class="col-xs-12 col-sm-4 col-lg-4">
      <?= $form->field($model, 'subject_name')->textInput(['maxlength' => true,'placeholder'=>'Enter the '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' description']) ?>
            </div>

            <div class="col-xs-12 col-lg-4 col-sm-4">
                <?php echo $form->field($subjects,'subject_type_id')->widget(
                    Select2::classname(), [
                        'data' => $subjects->getSubjectType(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT_TYPE).' ----',
                            'name'=>'sub_type_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT_TYPE)); 
                ?>
            </div> 
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-4 col-sm-4">
                <?php echo $form->field($subjects,'course_type_id')->widget(
                    Select2::classname(), [
                        'data' => $subjects->getProgrammeType(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME_TYPE).' ----',
                            'name'=>'prgm_type_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME_TYPE)); 
                ?>
            </div>

            <div class="col-lg-4 col-sm-4">
                <?php echo $form->field($subjects,'paper_type_id')->widget(
                    Select2::classname(), [
                        'data' => $subjects->getPaperType(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PAPER_TYPE).' ----',
                            'name'=>'paper_type_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PAPER_TYPE)); 
                ?>
            </div>

            <div class="col-xs-12 col-sm-2 col-lg-2">
                <?= $form->field($subjects, 'paper_no')->textInput(['placeholder'=>'Only Numbers']) ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-lg-2">
                <?= $form->field($model, 'part_no')->textInput(['placeholder'=>'Only Numbers','value'=>'3']) ?>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-4 col-lg-4">
                <div class="col-xs-12 col-sm-6 col-lg-6 left-padding">
                    <?= $form->field($model, 'CIA_min')->textInput(['placeholder'=>'Only Numbers']) ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-lg-6 righh-padding">
                    <?= $form->field($model, 'CIA_max')->textInput(['placeholder'=>'Only Numbers']) ?>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-lg-4">
                <div class="col-xs-12 col-sm-6 col-lg-6 left-padding">
                    <?= $form->field($model, 'ESE_min')->textInput(['placeholder'=>'Only Numbers']) ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-lg-6 righh-padding">
                    <?= $form->field($model, 'ESE_max')->textInput(['placeholder'=>'Only Numbers']) ?>
                </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'total_minimum_pass')->textInput(['placeholder'=>'Only Numbers']) ?>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'end_semester_exam_value_mark',['labelOptions' => [ 'class' => 'control-label' ,'for'=>'no_name']])->textInput(['placeholder'=>'Only Numbers'])->label('End Semester Marks') ?>
            </div>

            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'credit_points')->textInput(['placeholder'=>'Only Numbers']) ?>
            </div>

            <div class="col-xs-12 col-sm-4 col-lg-4">
                <?= $form->field($model, 'subject_fee')->textInput(['placeholder'=>'Only Numbers']) ?>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group col-xs-12 col-sm-4 col-lg-4">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                <?= Html::a('Reset', ['create'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>