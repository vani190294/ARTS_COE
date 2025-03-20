<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\HallAllocate;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\MarkEntry;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="CONSOLIDATE PASS REPORT";

?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
        ]); 
    ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'stu_batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getBatchDetails(),
                    
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                        'id' => 'stu_cs_batch_id',
                        'name'=>'bat_val',
                    ],
                   'pluginOptions' => [
                       'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
            ?>
        </div>
        <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['id'=>'withdraw_year','value'=>date('Y'),'name'=>'withdraw_year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'consolidate_month',   
                            'name' => 'consolidate_month',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => ['UG'=>'UG','PG'=>'PG']
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Degree ----',
                            'id' => 'degree_name',   
                            'name' => 'degreee_info',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <br />
        <div class="form-group col-lg-3 col-sm-3"> 
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['value'=>'Submit','name'=>"consolidate_btn" ,'id'=>"consolidate_pass_repo",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['batch/consolidate-pass-report']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>            
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 withdraw">
        <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">                
            <div id = "withdraw_entry_tbl"></div>
        </div>
      
    </div>

<?php ActiveForm::end(); ?>

</div>
</div>
</div>