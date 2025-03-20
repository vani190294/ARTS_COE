<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeAddHallAllocateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-add-hall-allocate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_add_hall_allocate_id') ?>

    <?= $form->field($model, 'hall_master_id') ?>

    <?= $form->field($model, 'exam_timetable_id') ?>

    <?= $form->field($model, 'year') ?>

    <?= $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'register_number') ?>

    <?php // echo $form->field($model, 'row') ?>

    <?php // echo $form->field($model, 'row_column') ?>

    <?php // echo $form->field($model, 'seat_no') ?>

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
