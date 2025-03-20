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

$this->title="Revaluation ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)." Mark Entry";

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
                <?= $form->field($model, 'year')->textInput(['id'=>'reval_entry_year','value'=>date('Y'),'name'=>'reval_entry_year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'reval_entry_month',   
                            'name' => 'reval_entry_month',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-lg-2 col-sm-2">
            <?= $form->field($dummynumber, 'dummy_number')->textInput(['id'=>'dummy_num','name'=>'dummy_num'])->label("Dummy Number") ?>
        </div>
	
        <div class="form-group col-lg-3 col-sm-3"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['value'=>'Submit','name'=>"reval_markentry_btn" ,'id'=>"reval_markentry_btn",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry/revaluationmarkentry']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>            
        </div>
    </div>


    <div class="col-xs-12 col-sm-12 col-lg-12 revaluationmarkentry">
        <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">                
            <div id = "revaluation_mark_entry_tbl"></div>
        </div>
        
        <div class="form-group col-lg-3 col-sm-3 revaluation_mark_entry_done_btn">
            <input onClick="spinner();" type="submit" id="revaluation_entry_btn" name="revaluation_entry_btn" class="btn btn-success" value="Done">
        </div>
    </div>


<?php ActiveForm::end(); ?>

</div>
</div>
</div>