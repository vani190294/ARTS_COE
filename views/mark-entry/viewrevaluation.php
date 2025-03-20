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

$this->title="View Revaluation";

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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year']) ?>
        </div>

        <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'reval_entry_month',   
                            'name' => 'month',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) 
            ?>
            
        </div>
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($markentry, 'stu_programme_id')->widget(
                Select2::classname(), [
                'data'=>ConfigUtilities::getProgrammeDetails(),

                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                        'id' => 'stu_programme_selected',
                        'name'=>'bat_map_val',
                        //'value'=> $programme,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
            ?>
        </div>
       <div class="col-lg-6 col-sm-6">
        <div class="form-group col-lg-12 col-sm-12"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['value'=>'Submit','name'=>"view_reval_btn" ,'id'=>"view_reval_btn",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['mark-entry/viewrevaluation']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>            
            </div>
        </div>
       
    </div>

    

<?php ActiveForm::end(); ?>

<?php
    include_once("revaluation_view_pdf.php");
?>

</div>
</div>
</div>