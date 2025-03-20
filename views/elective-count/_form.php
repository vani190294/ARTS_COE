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
/* @var $model app\Models\ElectiveCount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="elective-count-form">

    <?php $form = ActiveForm::begin(); ?>

     <div class="col-xs-12 col-sm-12 col-lg-12">
           

        <?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {?>
                <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'getdeptid();']) ?>
                </div>
                <?php if($model->isNewRecord){?>
                <input type="hidden" name="coe_dept_id" id="coe_dept_id">
            <?php }else{?>
                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= $model->coe_dept_id;?>">
            <?php }?>
                <div class="col-md-2">
                        
                            <?= $form->field($model, 'coe_regulation_id')->widget(
                                    Select2::classname(), [  
                                        'data' => $model->getRegulationDetails(),                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => 'coe_regulation_id', 
                                            'value'=>$model->coe_regulation_id,
                                        ],
                                       'pluginOptions' => [
                                           'allowClear' => true,
                                        ],
                                    ]) ?>
                       
                    </div>

        <?php } else { ?>

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
                                    'value'=>$model->coe_regulation_id,
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
                                    //'multiple'=>'multiple',
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_dept_id', 
                                    'name'=>'coe_dept_id',
                                    'value'=>$model->coe_dept_id,
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>
        <?php } ?>

            <!--  <div class="col-md-2">
           <?= $form->field($model, 'elective_type')->widget(
                            Select2::classname(), [  
                                'data' => $model->getElectivetypeDetails1(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                   
                                    'placeholder' => '-----Select----',
                                    'id' => 'elective_type', 
                                    //'name'=>'elective_type',
                                     'value'=>$model->elective_type,
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                        </div> -->

            <input type="hidden" name="elective_type" value="193">
             <div class="col-md-2">
                <?= $form->field($model, 'elective_count')->textInput(['maxlength' => true,'Autocomplete'=>"off"]) ?>
            </div>
            
            <div class="col-xs-2 col-sm-2 col-lg-2">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
