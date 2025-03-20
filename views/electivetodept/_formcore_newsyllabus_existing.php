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
/* @var $model app\models\Electivetodept */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="electivetodept-form">
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
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','name'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'getdeptid();checksem();']) ?>
                </div>

                 <input type="hidden" name="coe_dept_id" id="coe_dept_id">



                <div class="col-md-3">
                    
                        <?= $form->field($model, 'coe_regulation_id')->widget(
                                Select2::classname(), [  
                                    'data' => $model1->getRegulationDetails(),                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_regulation_id',
                                        'name' => 'coe_regulation_id',
                                        'onchange'=>'getcoredept();',
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]); //assigntodeptelective(); ?>
                   
                </div>

            <?php } else { ?>

                    <div class="col-md-2">
                   <?= $form->field($model1, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','name'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'getdeptid();checksem();']) ?>
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
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]) ?>
                   
                </div>


                <div class="col-md-2">
                    
                        <?= $form->field($model, 'coe_dept_id')->widget(
                                Select2::classname(), [  
                                    'data' => $model1->getDepartmentdetails(),                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_dept_id',
                                        'name' => 'coe_dept_id', 
                                        'onchange'=>'getcoredept();'
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ])->label("From Dept."); //assigntodeptelective(); ?>
                   
                </div>
            <?php } ?>

                <div class="col-md-2">
           
                <?= $form->field($model, 'coe_dept_ids')->widget(
                            Select2::classname(), [  
                                //'data' => $model->getDepartmentdetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    //'multiple'=>'multiple',
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_dept_ids', 
                                    'name' => 'coe_dept_ids', 
                                    'onchange'=>'getcoresubjects1();getverticalstream11();',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label("To Other Dept."); ?>
             </div>
             
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
           
           <div class="col-xs-12 col-sm-12 col-lg-12">

                <div class="col-md-3">
               
                    <?= $form->field($model, 'subject_code')->widget(
                                Select2::classname(), [  
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'subject_code',
                                        'name' => 'subject_code',
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]) ?>
                 </div>

                  <div class="col-md-3">
               
                    <?= $form->field($model, 'coe_elective_option')->widget(
                                Select2::classname(), [  
                                     'data' => $model1->getElectivetypeDetails1(),                          
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_elective_option1',
                                        'name' => 'coe_elective_option_e',
                                        'onchange'=>'getsubjectnewsyyprefix11();' 
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ])->label("Course Category"); ?>
                 </div>

                  <div class="col-md-3" id="vertical_stream" style="display: none;">
                   
                        <?= $form->field($model, 'cur_vs_id')->widget(
                                    Select2::classname(), [  
                                        //'data' => $model->getLTPdetails(),                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => 'cur_vs_id', 
                                           'name' => 'cur_vs_id', 
                                        ],
                                       'pluginOptions' => [
                                           'allowClear' => true,
                                        ],
                                    ])->label("Vertical Stream"); ?>
                     </div>

                 <div class="col-md-3" id="electivetodeptsem11" style="display: none;">

                    <?= $form->field($model, 'semester')->widget(
                                Select2::classname(), [                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'semester1',
                                        'name' => 'semester_e',
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]) ?>

                    
                </div>


            </div>
           
        </div>

        

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group pull-right"><br>
                    <?= Html::submitButton($model->isNewRecord ? 'Assign' : 'Update Assigned', ['id'=>'savecurriculum1',
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                     <?= Html::a("Cancel", Url::toRoute(['electivetodept/coresubject-to-dept-new']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                </div>
        </div>

    </div>    
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
