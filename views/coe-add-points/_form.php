<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\dialog\Dialog;
use yii\helpers\Url;
use kartik\widgets\Select2;


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\CoeAddPoints */
/* @var $form yii\widgets\ActiveForm */
$this->title = "Create Activity Points";
//$this->params['breadcrumbs'][] = ['label' => "OVERALL ARREAR COUNT", 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="CoeAddPoints-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>
<?php Yii::$app->ShowFlashMessages->showFlashes();?>  
    <?php $form = ActiveForm::begin(); ?>

<div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                
                <?= $form->field($model, 'subject_code')->textInput(['maxlength'=>50,'autocomplete'=>"off"]) ?>
            </div>
            
            <div class="col-xs-12 col-sm-2 col-lg-2">
                 <?= $form->field($model, 'subject_name')->textInput(['maxlength'=>300,'autocomplete'=>"off"]) ?>
            
            </div> 

              

        <br />  
            <div class="form-group">
       
         <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', 
                ['onClick'=>"spinner();",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
         <?= Html::a("Reset", Url::toRoute(['coe-add-points/create']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
    </div>

               
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
