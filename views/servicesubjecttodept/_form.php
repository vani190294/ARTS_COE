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
/* @var $model app\models\Servicesubjecttodept */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicesubjecttodept-form">
    <div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">

             <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id' => 'degree_type','name' => 'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'checksem();']) ?>
            </div>

            <div class="col-md-2">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id',
                                    'name' => 'coe_regulation_id',
                                     'onchange'=>'getshcoresubjects();',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>
           
             <div class="col-md-2">
           
                <?= $form->field($model, 'coe_cur_subid')->widget(
                            Select2::classname(), [  
                                //'data' => $model->getCurSubjectDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_cur_subid',
                                    'name' => 'coe_cur_subid',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
             </div>

              <div class="col-md-2">
                   
                     <?= $form->field($model, 'semester')->widget(
                            Select2::classname(), [  
                                'data' => $model->getSemester(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'semester', 
                                    'name' => 'semester',                                     
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
            </div>

            <div class="col-md-2">
           
                <?= $form->field($model, 'coe_dept_ids')->widget(
                            Select2::classname(), [  
                                'data' => $model->getDepartmentdetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'multiple'=>'multiple',
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_dept_ids',
                                    'name' => 'coe_dept_ids[]', 
                                    //'value'=> explode(',', $model->coe_dept_ids),
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
             </div>

            <div class="col-md-2"> 
                <div class="form-group"><br>
                    <?= Html::submitButton($model->isNewRecord ? 'Assign' : 'Update Assigned', [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                     <?= Html::a("Cancel", Url::toRoute(['servicesubjecttodept/index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                </div>
            </div>
        </div>
    </div>    
    <?php ActiveForm::end(); ?>

</div>
</div>

</div>
