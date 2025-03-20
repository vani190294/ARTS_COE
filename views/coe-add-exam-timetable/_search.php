<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeAddExamTimetableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-add-exam-timetable-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_add_exam_timetable_id') ?>

    <?= $form->field($model, 'subject_mapping_id') ?>

    <?= $form->field($model, 'exam_year') ?>

    <?= $form->field($model, 'exam_month') ?>

    <?= $form->field($model, 'exam_type') ?>

    <?php // echo $form->field($model, 'exam_term') ?>

    <?php // echo $form->field($model, 'exam_date') ?>

    <?php // echo $form->field($model, 'exam_session') ?>

    <?php // echo $form->field($model, 'qp_code') ?>

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
