<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeBatDegReg */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-bat-deg-reg-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'coe_degree_id')->textInput() ?>

    <?= $form->field($model, 'coe_programme_id')->textInput() ?>

    <?= $form->field($model, 'coe_batch_id')->textInput() ?>

    <?= $form->field($model, 'no_of_section')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
