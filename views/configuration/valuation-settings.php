<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use kartik\widgets\Select2;
use app\models\HallAllocate;
echo Dialog::widget();

$this->title = "Valuation Settings";


?>
<h1><?php echo $this->title; ?></h1>

<div class="subjects-form">
<div class="box box-success">
<div class="box-body"> 
   
     <?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div>&nbsp;</div>

    <?php 
    $condition = $model->isNewRecord?true:false;
    $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12">
    
        
            <div class="col-xs-12 col-lg-2 col-sm-2">
                <?= $form->field($model, 'current_exam_year')->textInput(['value'=>$model['current_exam_year']]) ?>
                
            </div> 

            <div class="col-xs-12 col-lg-3 col-sm-3">
                <?= $form->field($model, 'current_exam_month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'current_exam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
            </div>
             <div class="col-xs-12 col-lg-3 col-sm-3">
                <?= $form->field($model, 'engg_graphic_subject')->textInput(['value'=>$model['engg_graphic_subject']]) ?>
                
            </div>
             <div class="col-xs-12 col-lg-3 col-sm-3">
                <?= $form->field($model, 'valuation_script_count')->textInput(['value'=>$model['valuation_script_count']])->label("Valuation Script Count (Per Session)"); ?>
                
            </div>
        </div>
    </div>
   
        <div class="row">
                <div class="col-xs-12">
                
                    
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                    
                        <div class="btn-group" role="group" aria-label="Actions to be Perform">
                            <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['onClick'=>"spinner();",'onmouseover'=>'validateThisForm();','class' =>'btn btn-group btn-group-lg btn-success']) ?>
                            <?= Html::a("Reset", Url::toRoute(['']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning','style'=>'color: #fff;', 'data-confirm' => 'Are you sure you want Clear?']) ?>
                            
                        </div>
                    
                    
                        <div class="form-group">                           

                            
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-lg-2">
                        <div class="form-group">
                            
                        </div>
                    </div>
                </div>                
            </div>
    <?php ActiveForm::end(); ?>


</div>
</div>
</div>