<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProgrammeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="programme-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_programme_id') ?>

    <?= $form->field($model, 'programme_code') ?>

    <?= $form->field($model, 'programme_name') ?>

   <!--  <?= $form->field($model, 'programme_total_years') ?>

    <?= $form->field($model, 'programme_total_semesters') ?>
 -->
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
