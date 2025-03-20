<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;

use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
$this->title= ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY);

?>
<?= Html::hiddenInput('sub_value', $model->subject_map_id,['id'=>'sub_value']); ?>
<?= Html::hiddenInput('month', $model->month,['id'=>'month']); ?>

<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes(); $form = ActiveForm::begin(); ?> 

<div>&nbsp;</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>$model->year,'disabled'=>'disabled']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->textInput(['value'=>$model->monthName->description,'disabled'=>'disabled']) ?>
        </div>
        <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'subject_map_id')->textInput(['value'=>$model->subjectDet->subject_code,'disabled'=>'disabled']) ?>
        </div>
        <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'dummy_from')->textInput(['value'=>$model->dummy_from,'onBlur'=>'checkSequenceDum($("#sub_value").val(),$("#dummysequence-year").val(),$("#month").val(),this.value,this.id)','onkeypress'=>"numbersOnly(event);allowEntr(event,this.id);"]) ?>
        </div> 
        <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'dummy_to')->textInput(['value'=>$model->dummy_to,'onBlur'=>'checkSequenceDum($("#sub_value").val(),$("#dummysequence-year").val(),$("#month").val(),this.value,this.id)','onkeypress'=>"numbersOnly(event);allowEntr(event,this.id);"]) ?>
        </div> 
        <div  class="col-lg-2 col-sm-2" class="form-group" role="group" aria-label="Actions to be Perform" > <br />
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-group btn-group-sm btn-primary ', 'id'=>'update_sequence']) ?>
            <?= Html::a("Back", Url::toRoute(['dummy-sequence/index']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-sm btn-warning ']) ?>

        </div>
    </div>
</div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>


