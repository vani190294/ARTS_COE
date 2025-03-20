<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransferCertificatesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transfer-certificates-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_transfer_certificates_id') ?>

    <?= $form->field($model, 'register_number') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'parent_name') ?>

    <?= $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'nationality') ?>

    <?php // echo $form->field($model, 'religion') ?>

    <?php // echo $form->field($model, 'community') ?>

    <?php // echo $form->field($model, 'caste') ?>

    <?php // echo $form->field($model, 'admission_date') ?>

    <?php // echo $form->field($model, 'class_studying') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'is_qualified') ?>

    <?php // echo $form->field($model, 'conduct_char') ?>

    <?php // echo $form->field($model, 'date_of_tc') ?>

    <?php // echo $form->field($model, 'date_of_app_tc') ?>

    <?php // echo $form->field($model, 'date_of_left') ?>

    <?php // echo $form->field($model, 'serial_no') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
