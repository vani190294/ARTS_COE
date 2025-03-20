<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeTrackingsheetSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-tracker-sheet-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_ts_id') ?>

    <?= $form->field($model, 'task_tittle') ?>

    <?= $form->field($model, 'task_description') ?>

    <?= $form->field($model, 'priority') ?>

    <?= $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'task_type') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
