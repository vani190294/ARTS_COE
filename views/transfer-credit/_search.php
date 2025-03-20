<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeTransferCreditSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-transfer-credit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_tc_id') ?>

    <?= $form->field($model, 'student_map_id') ?>

    <?= $form->field($model, 'removed_sub_map_id') ?>

    <?= $form->field($model, 'waiver_reason') ?>

    <?= $form->field($model, 'total_studied') ?>

    <?php // echo $form->field($model, 'subject_codes') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'month') ?>

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
