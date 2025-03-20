<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PracticalExamTimetable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="practical-exam-timetable-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'batch_mapping_id')->textInput() ?>

    <?= $form->field($model, 'student_map_id')->textInput() ?>

    <?= $form->field($model, 'subject_map_id')->textInput() ?>

    <?= $form->field($model, 'exam_year')->textInput() ?>

    <?= $form->field($model, 'exam_month')->textInput() ?>

    <?= $form->field($model, 'mark_type')->textInput() ?>

    <?= $form->field($model, 'term')->textInput() ?>

    <?= $form->field($model, 'exam_date')->textInput() ?>

    <?= $form->field($model, 'exam_session')->textInput() ?>

    <?= $form->field($model, 'out_of_100')->textInput() ?>

    <?= $form->field($model, 'ESE')->textInput() ?>

    <?= $form->field($model, 'internal_examiner_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'external_examiner_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'approve_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
