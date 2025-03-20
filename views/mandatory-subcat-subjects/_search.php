<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MandatorySubcatSubjectsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mandatory-subcat-subjects-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_mandatory_subcat_subjects_id') ?>

    <?= $form->field($model, 'man_subject_id') ?>

    <?= $form->field($model, 'batch_map_id') ?>

    <?= $form->field($model, 'sub_cat_code') ?>

    <?= $form->field($model, 'sub_cat_name') ?>

    <?php // echo $form->field($model, 'course_type_id') ?>

    <?php // echo $form->field($model, 'paper_type_id') ?>

    <?php // echo $form->field($model, 'subject_type_id') ?>

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
