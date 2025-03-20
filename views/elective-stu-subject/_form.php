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
/* @var $model app\models\ElectiveStuSubject */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="elective-stu-subject-form">

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
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'getdeptid();checksem();']) ?>
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
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'checksem();']) ?>
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

                    <?= $form->field($model, 'coe_elective_option')->widget(
                            Select2::classname(), [   
                                'data' => $model->getElectivetypeDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_elective_option',
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
                                    'onchange'=>'get_registerd_elective();'
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
                                    'id' => 'electsubject_code',
                                    'onchange'=>'getstudentregnum();'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                   
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <input type="hidden" name="batch_map_id" id="batch_map_id">
           <!--  <div class="col-sm-4">
                 <?= $form->field($student, 'register_number')->widget(
                            Select2::classname(), [                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'multiple'=>'multiple',
                                    'placeholder' => '-----Select----',
                                    'id' => 'electregister_number',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                      
            </div> -->
             <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton($model->isNewRecord ? 'Next' : 'Next', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
           
        </div>
    
    <?php ActiveForm::end(); ?>
</div>
</div>

</div>
