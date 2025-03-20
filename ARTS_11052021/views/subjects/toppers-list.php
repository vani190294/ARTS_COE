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
$this->title = 'Toppers List'

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
   <h3><?php echo $this->title; ?></h3>
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
            <div class="col-lg-2 col-sm-2">
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

            <div class="col-lg-2 col-sm-2">
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
            <div class="col-xs-12 col-sm-2 col-lg-2">
                <?= $form->field($model, 'part_no')->textInput(['placeholder'=>'Only Numbers','value'=>'3']) ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-lg-2">
                <?= $form->field($subjects, 'paper_no')->textInput(['placeholder'=>'Only Numbers'])->label('Limit') ?>
            </div>
            <div class="form-group col-xs-12 col-sm-4 col-lg-4">
                <br />
                <?= Html::Button('Show', ['class' => 'btn btn-success' ,'onclick'=>'getToppersListPart();']) ?>

                <?= Html::a('Reset', ['create'], ['class' => 'btn btn-default']) ?>
            </div>
            
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
         &nbsp;
        </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 mark_percent_print_btn">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-topperslist','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/categorytype/toppers-list-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div id = "mark_percent"></div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
</div>