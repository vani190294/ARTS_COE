<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValClaimAmt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-val-claim-amt-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-2">
    <?= $form->field($model, 'exam_type')->textInput() ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'ug_amt')->textInput() ?>
    </div><div class="col-md-2">
    <?= $form->field($model, 'pg_amt')->textInput() ?>
    </div><div class="col-md-2">
    <?= $form->field($model, 'ta_amt_half_day')->textInput() ?>
    </div><div class="col-md-2">
    <?= $form->field($model, 'ta_amt_full_day')->textInput() ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'out_session')->textInput() ?>
    </div>
    <div class="col-md-2">
    <div class="form-group"><br>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
