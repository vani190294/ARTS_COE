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


/* @var $this yii\web\View */
/* @var $model app\models\Subjects */
/* @var $form yii\widgets\ActiveForm */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MIGRATE_STATUS);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<h1><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MIGRATE_STATUS) ?></h1>
<div>&nbsp;</div>
    

<div class="migrate-subjects-form">
<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div>&nbsp;</div>

    <?php 
    
    $form = ActiveForm::begin(); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-3 col-sm-3">
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
                    ])->label("From ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div> 

            <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($model, 'batch_mapping_id')->widget(
                    Select2::classname(), [
                    'data'=>ConfigUtilities::getDegreedetails(),

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

            <div class="col-xs-12 col-sm-3 col-lg-3">
                <?= $form->field($subjects, 'semester')->textInput(['maxlength'=>1,'placeholder'=>'Only Numbers','id'=>'sem','name'=>'sem']) ?>
            </div>

            <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($model,'mig_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MIGRATE_STATUS).' '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_migrate_id_selected',
                            'name'=>'mig_bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MIGRATE_STATUS)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div> 

        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div>&nbsp;</div>
            <div class="col-xs-12 col-lg-4 col-sm-4">
                <input type="button" class="btn btn-success" value="Get Report" id="mig_btn">   
                <?= Html::a("Reset", Url::toRoute(['subjects/migrate']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>

        <div>&nbsp;</div>
        <div>&nbsp;</div>

        <div class="col-xs-12 col-sm-12 col-lg-12 mig_tbl">
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <div id = "stu_tbl"></div>
            </div>
        </div> 

        <div class="col-xs-12 col-sm-12 col-lg-12 mig_div">
            <div class="col-xs-12 col-sm-12 col-lg-12">
            
            
            
                <div>
                    <input type="submit" class="btn btn-success" value="Done" id="mig_done" name="mig_done_btn">
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    </div>
</div>
</div>
</div>