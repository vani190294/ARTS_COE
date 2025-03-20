<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DelQualentSubjectsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="del-qualent-subjects-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_del_qualent_subjects_id') ?>

    <?= $form->field($model, 'stu_map_id') ?>

    <?= $form->field($model, 'sub_map_id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
