<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LTPSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ltp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_ltp_id') ?>

    <?= $form->field($model, 'coe_regulation_id') ?>

    <?= $form->field($model, 'L') ?>

    <?= $form->field($model, 'T') ?>

    <?= $form->field($model, 'P') ?>

    <?php // echo $form->field($model, 'contact_hrsperweek') ?>

    <?php // echo $form->field($model, 'credit_point') ?>

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
