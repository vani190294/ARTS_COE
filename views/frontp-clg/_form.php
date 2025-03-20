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
/* @var $model app\models\FrontpClg */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="frontp-clg-form">

<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">

                <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---')]) ?>
                </div>
                
                <div class="col-md-2">
                        
                            <?= $form->field($model, 'coe_regulation_id')->widget(
                                    Select2::classname(), [  
                                        'data' => $model->getRegulationDetails(),                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => 'coe_regulation_id', 
                                        ],
                                       'pluginOptions' => [
                                           'allowClear' => true,
                                        ],
                                    ]) ?>
                       
                </div>

      

            <div class="col-md-2">

                   <?= $form->field($model, 'mission_count')->textInput(['autocomplete' => 'off']) ?>
                   
            </div>

            <div class="col-md-2">

                   <?= $form->field($model, 'po_count')->textInput(['autocomplete' => 'off']) ?>
                   
            </div>


    </div>

     <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton($model->isNewRecord ? 'Next' : 'Next', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
</div>

</div>
