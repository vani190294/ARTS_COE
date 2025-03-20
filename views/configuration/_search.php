<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConfigurationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="configuration-search">
<?php Yii::$app->ShowFlashMessages->showFlashes();?>  
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php //$form->field($model, 'coe_config_id') ?>

    <?= $form->field($model, 'config_name') ?>
    <?= $form->field($model, 'config_desc') ?>
    <?= $form->field($model, 'config_value') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
