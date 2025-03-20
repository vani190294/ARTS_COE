<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\HallMaster */
/* @var $form yii\widgets\ActiveForm */
$value = isset($categorytype)?$categorytype->coe_category_type_id:"";
?>

<div class="hall-master-form">
<div class="box box-success">
<div class="box-body">  
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'hall_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">    
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3"> 
            <?= $form->field($model, 'hall_type_id')->widget(
                    Select2::classname(), [
                        'data' => $categorytype->getCategorytype(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_HALLTYPE).' ----',      
                            'value' => $value,                      
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
    </div>
    
<div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-xs-12 col-sm-2 col-lg-2">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

            <?= Html::a("Reset", Url::toRoute(['hall-master/create']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>
</div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>