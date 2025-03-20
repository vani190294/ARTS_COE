<!DOCTYPE html>
<html ng-app>

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


?>

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
                <?= $form->field($model, 'year')->textInput(['id'=>'elective_wa_year','value'=>date('Y'),'ng-model'=>'yourName']) ?>
        </div>
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'elective_wai_month',   
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-lg-2 col-sm-2">
            <?= $form->field($student, 'register_number')->textInput(['id'=>'stu_reg_num','name'=>'stu_reg_num']) ?>
        </div>

        <div class="form-group col-lg-3 col-sm-3"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['value'=>'Submit','name'=>"revaluation_btn" ,'onclick'=>"showSubjectsOfEle();",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['elective-waiver/create']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>            
        </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 elective_waiver_sub">

        <div class="col-xs-12 col-sm-12 col-lg-12">                
            <div id = "elective_waiver_sub_in"></div>
        </div>
        
        <div class="form-group col-lg-3 col-sm-3 electgive_sub_wai">
            <input onClick="spinner();" type="submit" name="waiver_submit_btn" class="btn btn-success" value="Delete">
        </div>
    </div>


<?php ActiveForm::end(); ?>

</div>
</div>
</div>
</html>