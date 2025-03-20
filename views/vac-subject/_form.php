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
/* @var $model app\models\CurriculumSubject */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="curriculum-subject-form">
<div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">



            <?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {?>

                <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'getdeptid();checksem();']) ?>
                </div>

                <input type="hidden" name="coe_dept_id" id="coe_dept_id">
               
                <div class="col-md-3">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id', 
                                    //'value'=>$model->coe_regulation_id,
                                    'onchange'=>'getvacprefix()',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>

            <?php } else { ?>


            <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id' => 'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'checksem();']) ?>
            </div>
            
            <div class="col-md-2">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id', 
                                    'onchange'=>'getvacprefix()',
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
                                    'name' => 'coe_dept_id', 
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>  


            <?php } ?> 

            
             <div class="col-md-2">
                <?= $form->field($model, 'course_hours')->textInput(['id' => 'course_hours','placeholder' => Yii::t('app', '--- Course Hours ---'),'onchange'=>'getvaccredit();']) ?>
                
             </div>

              <div class="col-md-2">
                <?= $form->field($model, 'credit_point')->textInput(['id' => 'credit_point','placeholder' => Yii::t('app', '--- Credit Point ---'),'readonly'=>'readonly']) ?>                
             </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            


             <div class="col-md-5">
                 <div class="col-md-6">
             
               
                     <?= $form->field($model, 'subject_code')->widget(
                            Select2::classname(), [                     
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'subject_prefix', 
                                    'name' => 'subject_prefix', 
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label('Course Prefix'); ?>
                    
                </div>

                 <div class="col-md-6">
                    <?= $form->field($model, 'subject_code')->textInput(['maxlength' => true,'Autocomplete'=>"off"]) ?>
                </div>
            </div>
           
            <div class="col-md-3">
            <?= $form->field($model, 'subject_name')->textarea(['cols' => 3,'rows' => 3,'Autocomplete'=>"off"]) ?>
            </div>

        </div>

        

        <div class="col-xs-12 col-sm-12 col-lg-12">
               <div class="form-group pull-right ">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'pull-right btn btn-primary']) ?>
                     <?= Html::a("Cancel", Url::toRoute(['vac-subject/index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                </div>
            
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
</div>
</div>