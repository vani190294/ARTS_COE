<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\MarkEntry;
use app\models\HallAllocate;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Moderation Mark Entry";

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
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
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
                <?php echo $form->field($model, 'stu_programme_id')->widget(
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
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',    
                            'onchange' => 'bringYearMonthSubs(this.value,$("#exam_year").val());',                        
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'marks_out_of')->textInput(['value'=>5,'id'=>'moderation_marks'])->label('Max Moderation Marks') ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'marks_out_of')->textInput(['name'=>'max_moderation_marks','value'=>3,'id'=>'max_moderation_marks'])->label('Marks Per Sub') ?>
        </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group col-lg-6 col-sm-6"> <br />
                <div class="btn-group" role="group" aria-label="Actions to be Perform">
                    <?= Html::Button('Submit', ['value'=>'Submit','name'=>"moderation_btn" ,'id'=>"moderation_btn",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                    
                    <?= Html::a("Reset", Url::toRoute(['mark-entry/moderation']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                    <?= Html::a("View Moderation", Url::toRoute(['mark-entry/viewmoderation']), ['class' => 'btn btn-group btn-group-lg btn-primary ']) ?>

                </div>
                
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            &nbsp;
        </div>
        

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <div id = "stu_mod_tbl"></div>
            </div>

            <div class="form-group col-lg-3 col-sm-3 mod_done_btn">
                <input onClick="spinner();" type="submit" id="mod_submit_btn" name="mod_submit_btn" class="btn btn-success" value="Done">
            </div>
        </div>
 
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>