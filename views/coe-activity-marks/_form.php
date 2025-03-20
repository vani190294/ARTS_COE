<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeActivityMarks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-activity-marks-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'batch')->textInput() ?>

    <?= $form->field($model, 'programme')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'register_number')->textInput() ?>

    <?= $form->field($model, 'section')->textInput() ?>

    <?= $form->field($model, 'subject_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
