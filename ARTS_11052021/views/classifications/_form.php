<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\Classifications */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="classifications-form">
<div class="box box-success">
<div class="box-body"> 
    <?php 
    $condition = $model->isNewRecord?true:false; 
    Yii::$app->ShowFlashMessages->showFlashes(); ?> 
    <?php 
        $form = ActiveForm::begin([
            'id' => 'classifications-form',
            'enableAjaxValidation' =>$condition,
            'fieldConfig' => [
                'template' => "{label}{input}{error}",
            ],
        ]); 
    ?>
<div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'regulation_year')->widget(
            Select2::classname(), [  
                'data' => $model->getRegulations(),                      
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'placeholder' => '-----Select Regulation ----',
                ],
               'pluginOptions' => [
                   'allowClear' => true,
                ],
            ]) ?>
    </div>
    <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'percentage_from')->textInput() ?>
    </div>
    <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'percentage_to')->textInput() ?>
    </div>
    <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'grade_name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'classification_text')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-12 col-sm-2 col-lg-2"><br />
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

</div>
