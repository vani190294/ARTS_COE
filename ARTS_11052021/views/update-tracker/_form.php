<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UpdateTracker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="update-tracker-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'student_map_id')->textInput() ?>

    <?= $form->field($model, 'subject_map_id')->textInput() ?>

    <?= $form->field($model, 'exam_year')->textInput() ?>

    <?= $form->field($model, 'exam_month')->textInput() ?>

    <?= $form->field($model, 'updated_ip_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_link_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_updated')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
