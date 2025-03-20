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

            <div class="col-xs-6 col-sm-6 col-lg-6">

                 <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="8">
                
                 <div class="col-md-6">
           
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id',                                     
                                    'onchange'=>'getservicesubject()'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>
            

                <div id="course_objectives" style="display: block;">

                    <div class="col-md-11">
                        <?= $form->field($model, 'course_objectives1')->textInput(['Autocomplete'=>"off",'name'=>'course_objectives[]']) ?>
                                                          
                       
                    </div>

                    <div class="col-md-1"><br>
                        <input type="hidden" id="addcobj" value="1">
                        <?= Html::Button('+', ['id'=>'cobj','class' => 'pull-right btn btn-primary','onClick'=>'additional_cobj()']) ?>
                    </div>

                      <div class="col-md-12" id="additional_cobj"></div>

                    <div class="col-md-8">
                        <?= $form->field($model, 'course_outcomes1')->textarea(['Autocomplete'=>"off",'name'=>'course_outcomes[]','value'=>' ']) ?>
                                       
                    </div>
                    
                    <div class="col-md-3">
                         <?= $form->field($model, 'rpt1')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRptDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '----Select----',
                                    'id' => 'rpt1',
                                    'name'=>'rpt[]'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>             
                    </div>
                    <div class="col-md-1">
                        <br>
                        <input type="hidden" id="addcout" value="1">
                        <?= Html::Button('+', ['id'=>'cout','class' => 'pull-right btn btn-primary','onClick'=>'additional_cout()']) ?>
                    </div>
               
                    <div id="additional_cout"></div>

                </div>
            </div>
            <!-- divide -->
            <div class="col-xs-6 col-sm-6 col-lg-6">
                 <div class="col-md-6">
           
                    <?= $form->field($model, 'prerequisties')->widget(
                            Select2::classname(), [  
                                'data' => $model->getPresubjectlist(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'prerequisties',
                                    'name' => 'prerequisties[]',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>

                <div class="col-md-6">
               
                    <?= $form->field($model, 'subject_code')->widget(
                                Select2::classname(), [  
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'subject_id',
                                    'onchange'=>'hideshow_course_objectives()'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                </div>

                <label class='control-label' style="padding-left: 14px;">Course Content</label>
                 <div id="course_objectives" style="display: block;">
                    <div class="col-md-9">
                        <?= $form->field($model, 'module_title1')->textInput(['Autocomplete'=>"off",'name'=>'module_title[]','value'=>' ']) ?>
                                       
                    </div>

                    <div class="col-md-2">
                          <?= $form->field($model, 'module_hr1')->textInput(['maxlength' => true,'Autocomplete'=>"off",'name'=>'module_hr[]','onkeyup'=>'checkmodulehr();','class'=>'form-control checkmodulehr','value'=>'0']) ?>             
                    </div>
                    <div class="col-md-1">
                        <br>
                        <input type="hidden" id="addcont" value="1">
                        <?= Html::Button('+', ['id'=>'cont','class' => 'pull-right btn btn-primary','onClick'=>'additional_cont()']) ?>
                    </div>
                    <div class="col-md-12">
                         <?= $form->field($model, 'cource_content_mod1')->textarea(['Autocomplete'=>"off",'name'=>'cource_content_mod[]','value'=>' ']) ?>           
                    </div>
                    
               
                    <div id="additional_cont"></div>               

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="col-xs-6 col-sm-6 col-lg-6">
                  <div class="col-md-11">
                        <?= $form->field($model, 'text_book1')->textInput(['Autocomplete'=>"off",'name'=>'text_book[]']) ?>
                    </div>

                    <div class="col-md-1"><br>
                        <input type="hidden" id="addtxtbook" value="1">
                        <?= Html::Button('+', ['id'=>'txtbook','class' => 'pull-right btn btn-primary','onClick'=>'additional_txtbook()']) ?>
                    </div>

                      <div class="col-md-12" id="additional_txtbook"></div>
                    
                    <div class="col-md-11">
                        <?= $form->field($model, 'web_reference1')->textInput(['Autocomplete'=>"off",'name'=>'web_reference[]']) ?>
                    </div>

                    <div class="col-md-1"><br>
                        <input type="hidden" id="addwebbook" value="1">
                        <?= Html::Button('+', ['id'=>'webbook','class' => 'pull-right btn btn-primary','onClick'=>'additional_webbook()']) ?>
                    </div>

                    <div class="col-md-12" id="additional_webbook"></div>
            </div>

            <div class="col-xs-6 col-sm-6 col-lg-6">

                    <div class="col-md-11">
                        <?= $form->field($model, 'reference_book1')->textInput(['Autocomplete'=>"off",'name'=>'reference_book[]']) ?>
                    </div>

                    <div class="col-md-1"><br>
                        <input type="hidden" id="addreferbook" value="1">
                        <?= Html::Button('+', ['id'=>'referbook','class' => 'pull-right btn btn-primary','onClick'=>'additional_referbook()']) ?>
                    </div>

                      <div class="col-md-12" id="additional_referbook"></div>

                    <div class="col-md-11">
                        <?= $form->field($model, 'online_reference1')->textInput(['Autocomplete'=>"off",'name'=>'online_reference[]']) ?>
                    </div>

                    <div class="col-md-1"><br>
                        <input type="hidden" id="addonlinebook" value="1">
                        <?= Html::Button('+', ['id'=>'onlinebook','class' => 'pull-right btn btn-primary','onClick'=>'additional_onlinebook()']) ?>
                    </div>

                      <div class="col-md-12" id="additional_onlinebook"></div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
                
            <div class="form-group pull-right"><br>
                <?= Html::submitButton('Next', ['id'=>'nextsyllabus','class' => 'btn btn-primary']) ?>
                 <?= Html::a("Cancel", Url::toRoute(['syllabus/service-index']), ['onClick'=>"spinner();",'class' => ' btn btn-warning']) ?>
            </div>
        </div>
    </div>    
    <?php ActiveForm::end(); ?>

</div>
</div>

</div>
