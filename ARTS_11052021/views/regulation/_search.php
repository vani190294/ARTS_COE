<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RegulationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="regulation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_regulation_id') ?>

    <?= $form->field($model, 'coe_batch_id') ?>

    <?= $form->field($model, 'regulation_year') ?>

    <?= $form->field($model, 'grade_point_from') ?>

    <?= $form->field($model, 'grade_point_to') ?>

    <?php // echo $form->field($model, 'grade_name') ?>

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
