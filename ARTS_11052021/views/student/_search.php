<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StudentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="student-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_student_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'register_number') ?>

    <?= $form->field($model, 'gender') ?>

    <?= $form->field($model, 'dob') ?>

    <?php // echo $form->field($model, 'religion') ?>

    <?php // echo $form->field($model, 'nationality') ?>

    <?php // echo $form->field($model, 'caste') ?>

    <?php // echo $form->field($model, 'sub_caste') ?>

    <?php // echo $form->field($model, 'bloodgroup') ?>

    <?php // echo $form->field($model, 'email_id') ?>

    <?php // echo $form->field($model, 'admission_year') ?>

    <?php // echo $form->field($model, 'admission_date') ?>

    <?php // echo $form->field($model, 'mobile_no') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
