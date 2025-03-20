<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveSubjectSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="elective-subject-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_cur_id') ?>

    <?= $form->field($model, 'external_mark') ?>

    <?= $form->field($model, 'internal_mark') ?>

    <?= $form->field($model, 'coe_batch_id') ?>

    <?= $form->field($model, 'coe_regulation_id') ?>

    <?php // echo $form->field($model, 'coe_dept_id') ?>

    <?php // echo $form->field($model, 'degree_type') ?>

    <?php // echo $form->field($model, 'semester') ?>

    <?php // echo $form->field($model, 'coe_elective_option') ?>

    <?php // echo $form->field($model, 'subject_code') ?>

    <?php // echo $form->field($model, 'subject_name') ?>

    <?php // echo $form->field($model, 'coe_ltp_id') ?>

    <?php // echo $form->field($model, 'subject_type_id') ?>

    <?php // echo $form->field($model, 'subject_category_type_id') ?>

    <?php // echo $form->field($model, 'remarks') ?>

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
