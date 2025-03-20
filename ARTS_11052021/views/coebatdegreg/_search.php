<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeBatDegRegSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-bat-deg-reg-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_bat_deg_reg_id') ?>

    <?= $form->field($model, 'coe_degree_id') ?>

    <?= $form->field($model, 'coe_programme_id') ?>

    <?= $form->field($model, 'coe_batch_id') ?>

    <?= $form->field($model, 'no_of_section') ?>

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
