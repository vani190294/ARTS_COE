<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\BatDegReg;
use app\models\Categorytype;
use app\models\Batch;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
$batch_id=isset($model->coe_batch_id)?$model->coe_batch_id:'';
$batch_map_id=isset($model->batch_map_id)?$model->batch_map_id:'';
$credit_pints=isset($model->credit_points)?$model->credit_points:'1';
$this->title = 'Update Paper Number';

?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="subjects-form">
<div class="box box-success">
<div class="box-body">
     <?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div>&nbsp;</div>

    <?php 
    $form = ActiveForm::begin([
                    'id' => 'categories-form',
            ]); ?>
    <div class="row"> 
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12"> 
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($subjects,'coe_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'value'=> $batch_id,
                            'class'=>'form-control student_disable',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>      
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'batch_map_id')->widget(
                    Select2::classname(), [
                    'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ---',
                            'id' => 'stu_programme_selected',
                            'onChange' => 'getSubjectsListPaper($("#stu_batch_id_selected").val(), this.value);',
                            'class'=>'form-control student_disable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>      
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'man_subject_id')->widget(
                    Select2::classname(), [
                    'data'=>$mandatorySubjects->getAllSubjects(),

                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' ----',
                            
                            'class'=>'form-control student_disable',
                            'id'=>'man_subject_id',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label("Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); 
                ?>
            </div>
            <div class="col-lg-3 col-sm-3">
               <br />
                <div class="col-lg-12 col-sm-12">
                    <?= Html::Button('Get' , ['onclick'=>'getManSubCodes($("#stu_batch_id_selected").val(), $("#stu_programme_selected").val(), $("#man_subject_id").val())','class' => 'btn btn-success']) ?>

                    <?= Html::a('Reset', ['update-paper-number'], ['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-lg-12" id='display_data' >

    </div>
</div>
    <?php ActiveForm::end(); ?>
</div>
</div>