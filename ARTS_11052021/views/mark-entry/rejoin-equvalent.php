<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\MarkEntry;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Rejoin Eqvalent";
$this->params['breadcrumbs'][] = ['label' => 'Marks', 'url' => ['mark-entry-master/verify-marks']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

	<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-3 col-sm-3">
            	<?= $form->field($model, 'mark_view_register_number')->textInput(['required'=>'required','name'=>'prev_reg_num','id'=>'prev_reg_num','onBlur'=>'getDetainStatus(this.id,this.value);','style'=>'text-transform:uppercase'])->hint('Enter Previous Register Number')->label('Previous Register Number') ?>
            </div>
            <div class="col-lg-3 col-sm-3">
                <?= $form->field($model, 'mark_view_register_number')->textInput(['required'=>'required','name'=>'new_reg_num','id'=>'new_reg_num','style'=>'text-transform:uppercase'])->hint('Enter New Register Number')->label('New Register Number') ?>
            </div>
       		<div class="col-xs-4 col-sm-3 col-lg-3"> <br />
               <?= Html::Button('Submit', ['class' => 'btn btn-success', 'onclick'=>'getDetails($("#prev_reg_num").val(), $("#new_reg_num").val())',]) ?>
            </div> 	
        </div>
    </div>
    <div class="row" id='hide_this_div'>
        <div class="col-xs-12 add_data">
        </div>
        <div class="col-xs-12"> <br />
           <?= Html::submitButton('Update', ['class' => 'btn btn-success pull-right','id'=>'hide_button', 'name'=>'click','value'=>'Update']) ?>
        </div>
    </div>
     <?php ActiveForm::end(); ?>

    </div>
	</div>
	</div>
    <?php if(!empty($importResults)) : ?>
    <!---Start Import Summary Results Block -->
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><i class="fa fa-list-ul"></i> <?php 'Equalent Results'; ?></h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-sm-12">
            <?php $totalError = (count($importResults['dispResults'])-$importResults['totalSuccess']); ?>
            <?php $headerTr = $content = ''; $i = 1; ?>
            
            <?php if(!empty($importResults['totalSuccess'])) : ?>
              <div class="alert alert-success">
                <h4><i class="fa fa-check"></i> <?php echo 'Success!'; ?></h4>
                <?= "{$importResults['totalSuccess']}". ' Equalent Subjects Added successfully.' ?>
              </div>
            <?php endif; ?>
            
            <?php if(!empty($totalError)) : ?>
              <div class="alert alert-danger">
                <h4><i class="fa fa-ban"></i> <?php echo 'Error!'; ?></h4>
                <?= "{$totalError}". ' Subjects importing error.' ?>
              </div>
            <?php endif; ?>
            
          </div>
        </div>
      </div><!--./box-body-->
    </div><!--./box-->
    <?php endif; ?>
