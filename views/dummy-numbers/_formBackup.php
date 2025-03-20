<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DummyNumbers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dummy-numbers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'student_map_id')->textInput() ?>

    <?= $form->field($model, 'subject_map_id')->textInput() ?>

    <?= $form->field($model, 'dummy_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'month')->textInput() ?>

    <?= $form->field($model, 'term')->textInput() ?>

    <?= $form->field($model, 'mark_type')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
