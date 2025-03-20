<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClassificationsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="classifications-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_classifications_id') ?>

    <?= $form->field($model, 'regulation_year') ?>

    <?= $form->field($model, 'percentage_from') ?>

    <?= $form->field($model, 'percentage_to') ?>

    <?= $form->field($model, 'grade_name') ?>

    <?php // echo $form->field($model, 'classification_text') ?>

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
