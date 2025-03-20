<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FrontpClgSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="frontp-clg-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cur_fp_id') ?>

    <?= $form->field($model, 'degree_type') ?>

    <?= $form->field($model, 'coe_regulation_id') ?>

    <?= $form->field($model, 'mission_count') ?>

    <?= $form->field($model, 'po_count') ?>

    <?php // echo $form->field($model, 'approve_status') ?>

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
