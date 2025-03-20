<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DelQualentSubjects */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="del-qualent-subjects-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'stu_map_id')->textInput() ?>

    <?= $form->field($model, 'sub_map_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
