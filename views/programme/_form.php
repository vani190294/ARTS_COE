<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\dialog\Dialog;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\Programme */
/* @var $form yii\widgets\ActiveForm */
?>
<div>&nbsp;</div>
<div class="programme-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>
<?php Yii::$app->ShowFlashMessages->showFlashes();?>  
    <?php $form = ActiveForm::begin([
                    'id' => 'programme-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-4 col-lg-4">
  <?= $form->field($model, 'programme_code')->textInput(['maxlength' => true,'id'=>'prgm_code']) ?>
        </div>

        <div class="col-xs-12 col-sm-4 col-lg-4 prgm_name">
            <?= $form->field($model, 'programme_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div>&nbsp;</div>

    <div class="col-xs-12 col-sm-12 col-lg-12 prgm_sub_btn">
        <div class="col-xs-12 col-sm-4 col-lg-4">
            <input type="button" id="prgm_sub" name="prgm_sub" class="btn btn-success <?php echo !$model->isNewRecord?'showBatch':''; ?>" value="<?php echo $model->isNewRecord?'Submit':'Update'; ?>">
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 prgm_btn">
        <div class="form-group col-xs-12 col-sm-4 col-lg-4">
        <?= Html::submitButton($model->isNewRecord ? 'Done' : 'Update', ['onClick'=>"spinner();",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

  <div>&nbsp;</div>

    <div class="col-xs-12 col-sm-12 col-lg-12 prgm_back_btn">
        <div class="col-xs-12 col-sm-4 col-lg-4">
            <input type="button" id="<?php echo $model->isNewRecord?'prgm_back':'prgm_back_update'; ?>" name="prgm_back" class="btn btn-success" value="<?php echo $model->isNewRecord?'Back':'Update'; ?>">
        </div>
    </div>

  <div>&nbsp;</div>

    <div class="col-xs-12 col-sm-12 col-lg-12 prgm_tbl">
        <div class="col-xs-12 col-sm-8 col-lg-8">
            <div id = "stu_tbl"></div>
        </div>
    </div> 

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>