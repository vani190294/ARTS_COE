<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueSubjectsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-value-subjects-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_val_sub_id') ?>

    <?= $form->field($model, 'subject_code') ?>

    <?= $form->field($model, 'subject_name') ?>

    <?= $form->field($model, 'subject_fee') ?>

    <?= $form->field($model, 'CIA_min') ?>

    <?php // echo $form->field($model, 'CIA_max') ?>

    <?php // echo $form->field($model, 'ESE_min') ?>

    <?php // echo $form->field($model, 'ESE_max') ?>

    <?php // echo $form->field($model, 'total_minimum_pass') ?>

    <?php // echo $form->field($model, 'credit_points') ?>

    <?php // echo $form->field($model, 'part_no') ?>

    <?php // echo $form->field($model, 'end_semester_exam_value_mark') ?>

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
