<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdditionalCourseRejoinSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="additional-course-rejoin-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cur_acrj_id') ?>

    <?= $form->field($model, 'batch_map_id') ?>

    <?= $form->field($model, 'degree_type') ?>

    <?= $form->field($model, 'coe_regulation_id') ?>

    <?= $form->field($model, 'coe_dept_id') ?>

    <?php // echo $form->field($model, 'register_number') ?>

    <?php // echo $form->field($model, 'subject_code') ?>

    <?php // echo $form->field($model, 'semester') ?>

    <?php // echo $form->field($model, 'approve_status') ?>

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
