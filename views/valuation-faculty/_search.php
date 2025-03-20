<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ValuationFacultySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="valuation-faculty-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_val_faculty_id') ?>

    <?= $form->field($model, 'faculty_name') ?>

    <?= $form->field($model, 'faculty_designation') ?>

    <?= $form->field($model, 'faculty_board') ?>

    <?= $form->field($model, 'faculty_mode') ?>

    <?php // echo $form->field($model, 'faculty_experience') ?>

    <?php // echo $form->field($model, 'bank_accno') ?>

    <?php // echo $form->field($model, 'bank_name') ?>

    <?php // echo $form->field($model, 'bank_branch') ?>

    <?php // echo $form->field($model, 'bank_ifsc') ?>

    <?php // echo $form->field($model, 'phone_no') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'college_code') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'out_session') ?>

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
