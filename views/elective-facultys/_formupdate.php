<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ElectiveFacultys */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="elective-facultys-form">

    <div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">

        <div class="col-xs-12 col-sm-12 col-lg-12">
        <?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {
                
                ?>
                
                <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'getdeptid();checksem();','disabled' => true]) ?>
                </div>
   
                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= Yii::$app->user->getDeptId();?>">
               
                <div class="col-md-2">
                        
                            <?= $form->field($model, 'coe_regulation_id')->widget(
                                    Select2::classname(), [  
                                        'data' => $model->getRegulationDetails(),                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => 'coe_regulation_id','disabled' => true 
                                        ],
                                       'pluginOptions' => [
                                           'allowClear' => true,
                                        ],
                                    ]) ?>
                       
                    </div>

        <?php } else { ?>

        <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'checksem();','disabled' => true]) ?>
        </div>
        <div class="col-md-2">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id', 
                                    'onchange'=>'getregdept()','disabled' => true
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
                                    'name'=>'coe_dept_id','disabled' => true
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>
        <?php } ?>

            <div class="col-md-2">

                    <?= $form->field($model, 'coe_elective_option')->widget(
                            Select2::classname(), [   
                                'data' => $model->getElectivetypeDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_elective_option','disabled' => true
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                   
            </div>

            <div class="col-md-2">

                    <?= $form->field($model, 'semester')->widget(
                            Select2::classname(), [                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'semester',
                                    'onchange'=>'get_studregisterd_elective();','disabled' => true
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                   
            </div>

             <div class="col-md-2">

                    <?= $form->field($model, 'subject_code')->widget(
                            Select2::classname(), [                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'electsubject_code','disabled' => true
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                   
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-sm-4">
                 <?= $form->field($model, 'faculty_ids')->widget(
                            Select2::classname(), [   
                                'data' => $model->getCDCelectivefacutly(),                    
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'multiple'=>'multiple',
                                    'placeholder' => '-----Select----',
                                    'id' => 'faculty_ids',
                                    'name' => 'faculty_ids[]',
                                    'value'=>explode(",", $model->faculty_ids)
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                      
            </div>
             <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
           
        </div>
    
    <?php ActiveForm::end(); ?>
</div>
</div>

</div>
