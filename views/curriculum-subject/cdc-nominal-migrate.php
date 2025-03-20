<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\Batch;
use app\models\StudentMapping;
use app\models\CoeBatDegReg;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Regulation;
use kartik\dialog\Dialog;
use yii\db\Query;


echo Dialog::widget();
$batch_id = isset($subjects->batch_mapping_id)?$subjects->batchMapping->coeBatch->coe_batch_id:"";
$batch_mapping_id = isset($subjects->batch_mapping_id)?$subjects->batch_mapping_id:"";
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');

$this->title = "Nominal Migrate CDC to Eval";
?>
<h1><?php echo $this->title;     ?></h1>
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

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'coe_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
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
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div> 


            <div class="col-sm-2">
               <?= $form->field($model, 'semester')->textInput(['id'=>'sem','name'=>'sem']) ?>

                    </div>
           
            
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform">
                <br />

                <?= Html::Button('Show', ['id'=>'cdcnominalmigrate','class' => 'btn btn-success']) ?>

                <?= Html::a('Reset', ['cdc-nominal-migrate'], ['class' => 'btn btn-default']) ?> 
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12 subject_information_tbl">           

            <div id = "sub_info_tbl"></div>

          <?= Html::submitButton('Migrate', ['id'=>'mig_done','class' => 'btn btn-success pull-right']) ?>

        </div>
        
    </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>


