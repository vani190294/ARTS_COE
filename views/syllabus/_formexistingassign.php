<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\AicteNorms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="aicte-norms-form">
<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">

            <?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {?>
                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= Yii::$app->user->getDeptId();?>">

            <?php } else { ?>

                  <div class="col-md-2">
           
                    <?= $form->field($model, 'coe_dept_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getDepartmentdetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_dept_id',
                                    'name' => 'coe_dept_id',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>

            <?php } ?> 

            <div class="col-md-2">
                
                    <?= $form->field($model, 'from_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'from_regulation_id', 
                                    'name' => 'from_regulation_id', 
                                    'onchange'=>'getregulation(); fromregulationsubject();'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label("From Regulation") ?>
               
            </div>

             <div class="col-md-2">
               
                    <?= $form->field($model, 'from_subject_code')->widget(
                                Select2::classname(), [  
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'from_subject_code',
                                    'name' => 'from_subject_code',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
            </div>

             <div class="col-md-2">
                
                    <?= $form->field($model, 'to_regulation_id')->widget(
                            Select2::classname(), [                     
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'to_regulation_id', 
                                    'name' => 'to_regulation_id', 
                                    'onchange'=>'toregulationsubject();'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label("To Regulation") ?>
               
            </div>

            <div class="col-md-2">
               
                    <?= $form->field($model, 'to_subject_code')->widget(
                                Select2::classname(), [  
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'to_subject_code',
                                    'name' => 'to_subject_code',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
            </div>

           <div class="col-md-2 form-group"><br>
            <?= Html::submitButton($model->isNewRecord ? 'Assign' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['existing-index'], ['class' => 'btn btn-warning']) ?>
        </div>
        </div>
        
    </div>
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>