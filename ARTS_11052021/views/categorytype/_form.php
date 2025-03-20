<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Categorytype */
/* @var $form yii\widgets\ActiveForm */
?>
<div>&nbsp;</div>
<div class="categorytype-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>


    <?php $form = ActiveForm::begin([
                    'id' => 'degree-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-4 col-lg-4">
            
            <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(app\models\Categories::find()->all(),'coe_category_id','category_name'),['prompt'=>Yii::t('app', '--- Select Category ---')]); ?>
        </div>

        <div class="col-xs-12 col-sm-4 col-lg-4">
            <?= $form->field($model, 'category_type')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-xs-12 col-sm-4 col-lg-4">
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div>&nbsp;</div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group col-xs-12 col-sm-4 col-lg-4">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>