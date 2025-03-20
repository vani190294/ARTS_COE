<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueNominalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-value-nominal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_nominal_val_id') ?>

    <?= $form->field($model, 'course_batch_mapping_id') ?>

    <?= $form->field($model, 'coe_student_id') ?>

    <?= $form->field($model, 'coe_subjects_id') ?>

    <?= $form->field($model, 'section_name') ?>

    <?php // echo $form->field($model, 'semester') ?>

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
