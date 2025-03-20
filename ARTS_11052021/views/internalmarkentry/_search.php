<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InternalMarkentrySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="internal-markentry-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mark_entry_id') ?>

    <?= $form->field($model, 'student_map_id') ?>

    <?= $form->field($model, 'subject_map_id') ?>

    <?= $form->field($model, 'category_type_id') ?>

    <?= $form->field($model, 'mark_out_of') ?>

    <?php // echo $form->field($model, 'category_type_id_marks') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'term') ?>

    <?php // echo $form->field($model, 'mark_type') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'attendance_percentage') ?>

    <?php // echo $form->field($model, 'attendance_remarks') ?>

    <?php // echo $form->field($model, 'is_updated') ?>

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
