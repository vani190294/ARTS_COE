<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueMarkEntry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-value-mark-entry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'coe_value_mark_entry_id')->textInput() ?>

    <?= $form->field($model, 'student_map_id')->textInput() ?>

    <?= $form->field($model, 'subject_map_id')->textInput() ?>

    <?= $form->field($model, 'CIA')->textInput() ?>

    <?= $form->field($model, 'ESE')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'result')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'grade_point')->textInput() ?>

    <?= $form->field($model, 'grade_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'month')->textInput() ?>

    <?= $form->field($model, 'term')->textInput() ?>

    <?= $form->field($model, 'mark_type')->textInput() ?>

    <?= $form->field($model, 'status_id')->textInput() ?>

    <?= $form->field($model, 'year_of_passing')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'attempt')->textInput() ?>

    <?= $form->field($model, 'withheld')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'withheld_remarks')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'withdraw')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_updated')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fees_paid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'result_published_date')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
