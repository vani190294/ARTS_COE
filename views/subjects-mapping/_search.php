<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SubjectsMappingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subjects-mapping-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_subjects_mapping_id') ?>

    <?= $form->field($model, 'batch_mapping_id') ?>

    <?= $form->field($model, 'subject_id') ?>

    <?= $form->field($model, 'paper_type_id') ?>

    <?= $form->field($model, 'subject_type_id') ?>

    <?php // echo $form->field($model, 'course_type_id') ?>

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
