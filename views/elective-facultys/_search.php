<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveFacultysSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="elective-facultys-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cur_ersf_id') ?>

    <?= $form->field($model, 'cur_elect_id') ?>

    <?= $form->field($model, 'degree_type') ?>

    <?= $form->field($model, 'coe_regulation_id') ?>

    <?= $form->field($model, 'coe_dept_id') ?>

    <?php // echo $form->field($model, 'coe_elective_option') ?>

    <?php // echo $form->field($model, 'elective_paper') ?>

    <?php // echo $form->field($model, 'subject_code') ?>

    <?php // echo $form->field($model, 'semester') ?>

    <?php // echo $form->field($model, 'faculty_ids') ?>

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
