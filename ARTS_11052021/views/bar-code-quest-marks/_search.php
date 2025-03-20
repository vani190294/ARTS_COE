<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BarCodeQuestMarksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bar-code-quest-marks-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_bar_code_quest_marks_id') ?>

    <?= $form->field($model, 'student_map_id') ?>

    <?= $form->field($model, 'subject_map_id') ?>

    <?= $form->field($model, 'dummy_number') ?>

    <?= $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'question_no') ?>

    <?php // echo $form->field($model, 'question_no_marks') ?>

    <?php // echo $form->field($model, 'mark_type') ?>

    <?php // echo $form->field($model, 'term') ?>

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
