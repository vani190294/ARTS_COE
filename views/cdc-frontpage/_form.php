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
/* @var $model app\models\CDCFrontpage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cdcfrontpage-form">

<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">

        <?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {
                
                ?>
                
                <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'getdeptid();setpo();']) ?>
                </div>
   
                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= Yii::$app->user->getDeptId();?>">
               
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

        <?php } else { ?>

        <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'setpo();']) ?>
        </div>
        <div class="col-md-2">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id', 
                                    'onchange'=>'getregdept()'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>

            <div class="col-md-2">
                
                    <?= $form->field($model, 'coe_dept_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getDepartmentdetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_dept_id', 
                                    'name'=>'coe_dept_id'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>
        <?php } ?>

            <div class="col-md-2">

                   <?= $form->field($model, 'mission_count')->textInput(['autocomplete' => 'off']) ?>
                   
            </div>

            <div class="col-md-2" id="setpo" style="display: none;">

                   <?= $form->field($model, 'po_count')->textInput(['autocomplete' => 'off','value'=>'0']) ?>
                   
            </div>

            <div class="col-md-2">

                   <?= $form->field($model, 'peo_count')->textInput(['autocomplete' => 'off']) ?>
                   
            </div>

            <div class="col-md-2">

                   <?= $form->field($model, 'pso_count')->textInput(['autocomplete' => 'off']) ?>
                   
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
