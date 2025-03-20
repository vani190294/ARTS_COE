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

            
                <div class="col-md-3">
                       <?= $form->field($model1, 'degree_type')->dropDownList($model1->getDegreeType(), ['id' => 'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'checksem();']) ?>
                </div>
           
            <?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {?>

                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= Yii::$app->user->getDeptId();?>">


                <div class="col-md-3">
                    
                        <?= $form->field($model1, 'coe_regulation_id')->widget(
                                Select2::classname(), [  
                                    'data' => $model1->getRegulationDetails(),                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_regulation_id',
                                        'onchange'=>'getcoredept();',
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]); //assigntodeptelective(); ?>
                   
                </div>

            <?php } else { ?>

                <div class="col-md-2">
                    
                        <?= $form->field($model1, 'coe_regulation_id')->widget(
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
                    
                        <?= $form->field($model1, 'coe_dept_id')->widget(
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
                                    'onchange'=>'getcoresubjects();getverticalstream1();',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label("To Other Dept."); ?>
             </div>

             <div class="col-md-2">
                       <?= $form->field($model, 'subject_type_new')->dropDownList($model->getSubjectTypeNew1(), ['id' => 'subject_newsyllabus','name' => 'subject_newsyllabus','prompt' => Yii::t('app', '--- Select---'),'onClick'=>'showservicecourse();']) ?>
            </div>
             
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12" id="showservicecourseexist" style="display: none;">
           
           <div class="col-xs-12 col-sm-12 col-lg-12">

                <div class="col-md-3">
               
                    <?= $form->field($model1, 'subject_code')->widget(
                                Select2::classname(), [  
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'subject_code',
                                        'name' => 'subject_code',
                                        'onchange'=>'getcoursetype();'
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]) ?>
                 </div>


                 <div class="col-md-3">
               
                    <?= $form->field($model1, 'coe_elective_option')->widget(
                                Select2::classname(), [  
                                    //'data' => $model->getElectivetypeDetails(),                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_elective_option',
                                        'name' => 'coe_elective_option_e',
                                        'onchange'=>'getsubjectnewsyprefix();getsubjectnewprefix();' 
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ])->label("Course Category"); ?>
                 </div>


                <div class="col-md-3" id="electivetodeptsem" style="display: none;">

                        <?= $form->field($model1, 'semester')->widget(
                                Select2::classname(), [                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'semester',
                                        'name' => 'semester_e',
                                        //'onchange'=>'getcoursetype();'
                                        'onchange'=>'getsubjectnewprefix()'
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]) ?>
                       
                </div>


                <div class="col-md-3">
                           <?= $form->field($model, 'subject_type_new')->hiddenInput(['id' => 'subject_type_new','name' => 'subject_type_new'])->label(false); ?>
                </div>

              

            </div>
            <div class="col-xs-12 col-sm-12 col-lg-12">
                 
                 <div class="col-md-6" id="new_subject_code" style="display: none;">
                     <div class="col-md-6">
                       <label>Subject Prefix</label> <input class="form-control" type="text" name="subject_prefix_new" id='subject_prefix_new' readonly="readonly">
                    </div>
                     <div class="col-md-6">
                        <?= $form->field($model, 'subject_code_new')->textInput(['id' => 'subject_code_new','name' => 'subject_code_new','Autocomplete'=>"off"]) ?>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12" id="showservicecoursenew" style="display: none !important;">

            <div class="col-xs-12 col-sm-12 col-lg-12">

                                   

                     <div class="col-md-2">
                   
                            <?= $form->field($model1, 'coe_elective_option')->widget(
                                    Select2::classname(), [  
                                        'data' => $model1->getElectivetypeDetails1(),                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => 'coe_elective_option1',
                                            'onchange'=>'getsubjectnewsyprefix1();getLTPdetails();' 
                                        ],
                                       'pluginOptions' => [
                                           'allowClear' => true,
                                        ],
                                    ])->label("Course Category"); ?>
                     </div>

                      <div class="col-md-2" id="electivetodeptsem1" style="display: none;">

                        <?= $form->field($model1, 'semester')->widget(
                                Select2::classname(), [                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'semester1',
                                        'onchange'=>'getsubjectnewprefix();checksubjectprefix2();'
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]) ?>
                       
                    </div>    

                    <div class="col-md-2">
                   
                        <?= $form->field($model1, 'coe_ltp_id')->widget(
                                    Select2::classname(), [  
                                        //'data' => $model->getLTPdetails(),                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => 'coe_ltp_id', 
                                            'onchange'=>'getsubjecttype();checksubjectprefix2();',
                                        ],
                                       'pluginOptions' => [
                                           'allowClear' => true,
                                        ],
                                    ]) ?>
                     </div>

                     <div class="col-md-2">
                           <input type="hidden" id="subject_type_id"  name="subject_type_id">
                        <?= $form->field($model1, 'subject_type_id')->textInput(['readonly'=>'readonly']) ?>
                    </div>

                     <div class="col-md-2">
                         <input type="hidden" id="subject_category_type_id"  name="subject_category_type_id">
                        <?= $form->field($model1, 'subject_category_type_id')->textInput(['readonly'=>'readonly']) ?>
                    </div>

                   <div class="col-md-2"><br>
                        <label>
                            <input type="checkbox" id="servicecourse" onclick="checksubjectprefix();">
                            GE Course (Click)
                        </label>
                    </div>

            </div>


            <div class="col-xs-12 col-sm-12 col-lg-12">

                     <div class="col-md-2">
                    <?= $form->field($model1, 'internal_mark')->textInput(['readonly'=>'readonly']) ?>
                     </div>

                    <div class="col-md-2">

                    <?= $form->field($model1, 'external_mark')->textInput(['readonly'=>'readonly']) ?>
                     </div>

                    <div class="col-md-2">
                        <label class="control-label">Credit Point</label><input class="form-control" type="text" id="credit_point" readonly="readonly">
                     </div>

                    <div class="col-md-2">
                        <label class="control-label">Contact Hrs</label><input class="form-control" type="text" id="contact_hrs" readonly="readonly">
                     </div>

                     
                     <div class="col-md-2">
                        <label class="control-label">Subject Prefix</label> <input class="form-control" type="text" id="subjectprefix" name="subjectprefix" readonly="readonly">
                    </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-lg-12">

                    <div class="col-md-2">
                        <?= $form->field($model1, 'subject_code')->textInput(['maxlength' => true,'Autocomplete'=>"off"]) ?>
                    </div>

                    <div class="col-md-3">
                    <?= $form->field($model1, 'subject_name')->textarea(['cols' => 3,'rows' => 3,'Autocomplete'=>"off"]) ?>
                    </div>

                    <div class="col-md-3" id="vertical_stream" style="display: none;">
                   
                        <?= $form->field($model1, 'cur_vs_id')->widget(
                                    Select2::classname(), [  
                                        //'data' => $model->getLTPdetails(),                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => 'cur_vs_id', 
                                           
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
                     <?= Html::a("Cancel", Url::toRoute(['electivetodept/index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                </div>
        </div>

    </div>    
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
