<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SubSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sub-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_sub_mapping_id') ?>

    <?= $form->field($model, 'batch_mapping_id') ?>

    <?= $form->field($model, 'val_subject_id') ?>

    <?= $form->field($model, 'semester') ?>

    <?= $form->field($model, 'paper_type_id') ?>

    <?php // echo $form->field($model, 'subject_type_id') ?>

    <?php // echo $form->field($model, 'course_type_id') ?>

    <?php // echo $form->field($model, 'migration_status') ?>

    <?php // echo $form->field($model, 'paper_no') ?>

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
