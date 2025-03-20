<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueMarkEntrySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coe-value-mark-entry-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'coe_value_mark_entry_id') ?>

    <?= $form->field($model, 'student_map_id') ?>

    <?= $form->field($model, 'subject_map_id') ?>

    <?= $form->field($model, 'CIA') ?>

    <?= $form->field($model, 'ESE') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'result') ?>

    <?php // echo $form->field($model, 'grade_point') ?>

    <?php // echo $form->field($model, 'grade_name') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'term') ?>

    <?php // echo $form->field($model, 'mark_type') ?>

    <?php // echo $form->field($model, 'status_id') ?>

    <?php // echo $form->field($model, 'year_of_passing') ?>

    <?php // echo $form->field($model, 'attempt') ?>

    <?php // echo $form->field($model, 'withheld') ?>

    <?php // echo $form->field($model, 'withheld_remarks') ?>

    <?php // echo $form->field($model, 'withdraw') ?>

    <?php // echo $form->field($model, 'is_updated') ?>

    <?php // echo $form->field($model, 'fees_paid') ?>

    <?php // echo $form->field($model, 'result_published_date') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
