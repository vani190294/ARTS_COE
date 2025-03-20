<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueNominal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-value-nominal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'course_batch_mapping_id')->textInput() ?>

    <?= $form->field($model, 'coe_student_id')->textInput() ?>

    <?= $form->field($model, 'coe_subjects_id')->textInput() ?>

    <?= $form->field($model, 'section_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'semester')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
