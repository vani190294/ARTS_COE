<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CurSyllabusSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cur-syllabus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cur_syllabus_id') ?>

    <?= $form->field($model, 'subject_id') ?>

    <?= $form->field($model, 'subject_type') ?>

    <?= $form->field($model, 'course_objectives1') ?>

    <?= $form->field($model, 'course_objectives2') ?>

    <?php // echo $form->field($model, 'course_objectives3') ?>

    <?php // echo $form->field($model, 'course_objectives4') ?>

    <?php // echo $form->field($model, 'course_objectives5') ?>

    <?php // echo $form->field($model, 'course_objectives6') ?>

    <?php // echo $form->field($model, 'course_outcomes1') ?>

    <?php // echo $form->field($model, 'course_outcomes2') ?>

    <?php // echo $form->field($model, 'course_outcomes3') ?>

    <?php // echo $form->field($model, 'course_outcomes4') ?>

    <?php // echo $form->field($model, 'course_outcomes5') ?>

    <?php // echo $form->field($model, 'course_outcomes6') ?>

    <?php // echo $form->field($model, 'rpt1') ?>

    <?php // echo $form->field($model, 'rpt2') ?>

    <?php // echo $form->field($model, 'rpt3') ?>

    <?php // echo $form->field($model, 'rpt4') ?>

    <?php // echo $form->field($model, 'rpt5') ?>

    <?php // echo $form->field($model, 'rpt6') ?>

    <?php // echo $form->field($model, 'cource_content_mod1') ?>

    <?php // echo $form->field($model, 'cource_content_mod2') ?>

    <?php // echo $form->field($model, 'cource_content_mod3') ?>

    <?php // echo $form->field($model, 'module_title1') ?>

    <?php // echo $form->field($model, 'module_title2') ?>

    <?php // echo $form->field($model, 'module_title3') ?>

    <?php // echo $form->field($model, 'module_hr1') ?>

    <?php // echo $form->field($model, 'module_hr2') ?>

    <?php // echo $form->field($model, 'module_hr3') ?>

    <?php // echo $form->field($model, 'text_book1') ?>

    <?php // echo $form->field($model, 'text_book2') ?>

    <?php // echo $form->field($model, 'text_book3') ?>

    <?php // echo $form->field($model, 'reference_book1') ?>

    <?php // echo $form->field($model, 'reference_book2') ?>

    <?php // echo $form->field($model, 'reference_book3') ?>

    <?php // echo $form->field($model, 'web_reference1') ?>

    <?php // echo $form->field($model, 'web_reference2') ?>

    <?php // echo $form->field($model, 'web_reference3') ?>

    <?php // echo $form->field($model, 'online_reference1') ?>

    <?php // echo $form->field($model, 'online_reference2') ?>

    <?php // echo $form->field($model, 'approve_status') ?>

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
