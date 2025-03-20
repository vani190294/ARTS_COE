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

$this->title=ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Minimum Maximum Verify ';

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
        

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => ['1'=>'CIA 100','2'=>'ESE 100','3'=>'BOTH 0-0','4'=>'CIA<100','5'=>'ESE<100','6'=>'CIA>100','7'=>'ESE>100','8'=>'BOTH>100','9'=>'BOTH<100'],                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Marks ----',
                            'id' => 'min_max_info',   
                            'name' => 'min_max_info',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Marks Info') ?>
        </div>

        <br />
        <div class="form-group col-lg-3 col-sm-3"> 
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['value'=>'Submit','id'=>"course_max_min_info",'class' => 'btn  btn-group-lg btn-group btn-success','onclick'=>'getMarkSubInfoma(  $("#min_max_info").val(),$("#stu_cs_batch_id").val() );']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['batch/course-marks-info']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

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