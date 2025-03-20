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

$this->title="Revaluation Register Number Entry";

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
            <?= $form->field($student, 'register_number')->textInput(['id'=>'stu_reg_num','name'=>'stu_reg_num']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'mark_out_of')->widget(
                    Select2::classname(), [  
                        'data' => ['2'=>'Out of Maximum'],                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'mark_out_of',   
                            'name' => 'mark_out_of',
                            'required'=>'required',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

	
        <div class="form-group col-lg-2 col-sm-2"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['value'=>'Submit','name'=>"revaluation_btn" ,'id'=>"revaluation_btn",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['mark-entry/revaluation']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>            
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 tbl_n_submit_revaluation">
        <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">                
            <div id = "stu_revaluation_tbl"></div>
        </div>
        
        <div class="form-group col-lg-2 col-sm-2 revaluation_done_btn">
            <input onClick="spinner();" type="submit" id="revaluation_submit_btn" name="revaluation_submit_btn" class="btn btn-success" value="Done">
        </div>
    </div>


<?php ActiveForm::end(); ?>

</div>
</div>
</div>