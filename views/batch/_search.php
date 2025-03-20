<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BatchSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="batch-search">
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php //$form->field($model, 'coe_batch_id') ?>

    <?= $form->field($model, 'batch_name') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'created_by') ?>

    <?= $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
