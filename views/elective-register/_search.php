<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveRegisterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="elective-register-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cur_elect_id') ?>

    <?= $form->field($model, 'degree_type') ?>

    <?= $form->field($model, 'coe_regulation_id') ?>

    <?= $form->field($model, 'coe_dept_id') ?>

    <?= $form->field($model, 'pec_paper') ?>

    <?php // echo $form->field($model, 'oec_paper') ?>

    <?php // echo $form->field($model, 'eec_paper') ?>

    <?php // echo $form->field($model, 'mc_paper') ?>

    <?php // echo $form->field($model, 'semester') ?>

    <?php // echo $form->field($model, 'approve_status') ?>

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
