<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exam-timetable-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_exam_timetable_id') ?>

    <?= $form->field($model, 'subject_mapping_id') ?>

    <?= $form->field($model, 'exam_type') ?>

    <?= $form->field($model, 'exam_year') ?>

    <?= $form->field($model, 'exam_month') ?>

    <?php // echo $form->field($model, 'term') ?>

    <?php // echo $form->field($model, 'qp_code') ?>

    <?php // echo $form->field($model, 'exam_date') ?>

    <?php // echo $form->field($model, 'session') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
