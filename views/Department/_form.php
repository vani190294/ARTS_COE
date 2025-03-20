<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Department */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="department-form">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    
    <div class="col-md-3">
        <?= $form->field($model, 'dept_name')->textInput(['maxlength' => true,'Autocomplete'=>"off"]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'dept_code')->textInput(['maxlength' => true,'Autocomplete'=>"off"]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'prefix_name')->textInput(['maxlength' => true,'Autocomplete'=>"off"]) ?>
    </div>
     <div class="col-md-2">
        <?= $form->field($model, 'no_of_pso')->textInput(['maxlength' => 6,'Autocomplete'=>"off"]) ?>
    </div>
    <div class="col-md-2">
        <div class="form-group"><br>
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>        
    <?php ActiveForm::end(); ?>

</div>
