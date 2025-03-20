<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
echo Dialog::widget();

$this->title = 'Delete Equalent';
$this->params['breadcrumbs'][] = ['label' => 'Marks', 'url' => ['mark-entry/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h3 class="box-title"><i class="fa fa-plus"> <?= Html::encode($this->title) ?></i></h3>
<div class="box box-primary">
  <div class="box-body">
    <?php $form = ActiveForm::begin([
                                   'id' => 'mark-entry',
                                   'method' => 'post',
                                   'enableAjaxValidation' => true,
                                    'fieldConfig' => [
                                          'template' => "{label}{input}{error}",
                                          ],
           ]); ?>
    <div class="row">
    
  <div class="col-xs-12">

    <div class="col-xs-4 col-sm-3 col-lg-3">
      <?= $form->field($model, 'mark_view_register_number')->textInput(['required'=>'required','name'=>'new_reg_num','id'=>'new_reg_num','style'=>'text-transform:uppercase'])->hint('Enter New Register Number')->label('New Register Number') ?>
    </div>
    <div class="col-xs-4 col-sm-3 col-lg-3"> <br />
       <?= Html::Button('Submit', ['class' => 'btn btn-success', 'onclick'=>'getDelDetails( $("#new_reg_num").val() );']) ?>
    </div>

  </div>
</div>

  

<div class="row" id='hide_this_div'>
    <div class="col-xs-12 add_data">
    </div>
    
</div>
<?php ActiveForm::end(); ?>
</div>
</div>
