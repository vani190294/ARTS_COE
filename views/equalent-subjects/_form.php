<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EqualentSubjects */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equalent-subjects-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'prev_stu_map_id')->textInput() ?>

    <?= $form->field($model, 'prev_sub_map_id')->textInput() ?>

    <?= $form->field($model, 'new_stu_map_id')->textInput() ?>

    <?= $form->field($model, 'new_sub_map_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
