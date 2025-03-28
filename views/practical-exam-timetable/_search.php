<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PracticalExamTimetableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="practical-exam-timetable-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_practical_exam_timetable_id') ?>

    <?= $form->field($model, 'batch_mapping_id') ?>

    <?= $form->field($model, 'student_map_id') ?>

    <?= $form->field($model, 'subject_map_id') ?>

    <?= $form->field($model, 'exam_year') ?>

    <?php // echo $form->field($model, 'exam_month') ?>

    <?php // echo $form->field($model, 'mark_type') ?>

    <?php // echo $form->field($model, 'term') ?>

    <?php // echo $form->field($model, 'exam_date') ?>

    <?php // echo $form->field($model, 'exam_session') ?>

    <?php // echo $form->field($model, 'out_of_100') ?>

    <?php // echo $form->field($model, 'ESE') ?>

    <?php // echo $form->field($model, 'internal_examiner_name') ?>

    <?php // echo $form->field($model, 'external_examiner_name') ?>

    <?php // echo $form->field($model, 'approve_status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
