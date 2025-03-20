<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DegreeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="degree-search">
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_degree_id') ?>

    <?= $form->field($model, 'degree_code') ?>

    <?= $form->field($model, 'degree_name') ?>

    <?= $form->field($model, 'degree_total_years') ?>

    <?= $form->field($model, 'degree_total_semesters') ?>

    <?= $form->field($model, 'degree_type') ?>

    <?= $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
