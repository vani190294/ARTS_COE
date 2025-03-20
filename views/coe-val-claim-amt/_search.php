<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValClaimAmtSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-val-claim-amt-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'claim_id') ?>

    <?= $form->field($model, 'ug_amt') ?>

    <?= $form->field($model, 'pg_amt') ?>

    <?= $form->field($model, 'ta_amt_half_day') ?>

    <?= $form->field($model, 'ta_amt_full_day') ?>

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
