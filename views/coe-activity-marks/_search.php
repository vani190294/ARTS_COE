<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeActivityMarksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-activity-marks-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'batch') ?>

    <?= $form->field($model, 'programme') ?>

    <?= $form->field($model, 'register_number') ?>

    <?= $form->field($model, 'section') ?>

    <?php // echo $form->field($model, 'subject_code') ?>

    <?php // echo $form->field($model, 'duration') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
