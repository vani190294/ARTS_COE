<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DummySequenceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dummy-sequence-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_store_dummy_mapping') ?>

    <?= $form->field($model, 'subject_map_id') ?>

    <?= $form->field($model, 'year') ?>

    <?= $form->field($model, 'month') ?>

    <?= $form->field($model, 'dummy_from') ?>

    <?php // echo $form->field($model, 'dummy_to') ?>

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
