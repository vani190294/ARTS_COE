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

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title=ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Information Internet Copy Engineering";

?>
<h1><?= Html::encode(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Information Internet Copy Engineering") ?></h1>
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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $galley->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'mark_month',  
                            'name' => 'mark_month',                          
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="form-group col-lg-3 col-sm-3"> <br />
            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                <input type="submit" id="sub_info_internet_engg" name="sub_info_internet" class="btn btn-success" value="Submit">

                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/subjectinformation']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>

            <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
        </div>
</div>
        
    </div>
        
    <?php ActiveForm::end(); ?>

<?php 
if(isset($table))
{
    ?>
    <div class="col-xs-12 col-sm-12 col-lg-12 ">
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <div class="col-xs-2">
                    <?php
                        echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-subjectinformation-engg-format','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                        echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry-master/subjectinformation-engg-pdf'], [
                            'class'=>'pull-right btn btn-primary', 
                            'target'=>'_blank', 
                            'data-toggle'=>'tooltip', 
                            'title'=>'Will open the generated PDF file in a new window'
                            ]);
                    ?>
                </div>
            
        
            <div class="col-xs-10">

    <?php
    echo "<br /><br /><br /><div >".$table."</div></div></div>";
}
?>
</div>
</div>
</div>