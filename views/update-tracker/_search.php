<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UpdateTrackerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="update-tracker-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_update_tracker_id') ?>

    <?= $form->field($model, 'student_map_id') ?>

    <?= $form->field($model, 'subject_map_id') ?>

    <?= $form->field($model, 'exam_year') ?>

    <?= $form->field($model, 'exam_month') ?>

    <?php // echo $form->field($model, 'updated_ip_address') ?>

    <?php // echo $form->field($model, 'updated_link_from') ?>

    <?php // echo $form->field($model, 'data_updated') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
