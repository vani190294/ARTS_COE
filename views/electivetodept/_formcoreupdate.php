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

            <input type="hidden" id="electivetodept_id" value="<?php echo $model->coe_electivetodept_id; ?>">
            <input type="hidden" id="coe_elective_option1" value="<?php echo $model->coe_elective_option; ?>">
             
             <?php if(Yii::$app->user->getDeptId()!=0 && Yii::$app->user->getDeptId()!='')
            {?>

                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= Yii::$app->user->getDeptId();?>">

                <div class="col-md-3">
                       <?= $form->field($model1, 'degree_type')->dropDownList($model1->getDegreeType(), ['id' => 'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'value'=>$codatalist['degree_type']]) ?>
                </div>

                <div class="col-md-3">
                    
                        <?= $form->field($model, 'coe_regulation_id')->widget(
                                Select2::classname(), [  
                                    'data' => $model1->getRegulationDetails(),                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_regulation_id',
                                        'value'=>$codatalist['coe_regulation_id']
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]); //assigntodeptelective(); ?>
                   
                </div>

            <?php } else { ?>


                <div class="col-md-3">
                       <?= $form->field($model1, 'degree_type')->dropDownList($model1->getDegreeType(), ['id' => 'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'value'=>$codatalist['degree_type']]) ?>
                </div>

                <div class="col-md-3">
                    
                        <?= $form->field($model, 'coe_regulation_id')->widget(
                                Select2::classname(), [  
                                    'data' => $model->getRegulationDetails(),                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_regulation_id',
                                        'value'=>$codatalist['coe_regulation_id'] 
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ]) ?>
                   
                </div>


                <div class="col-md-3">
                    
                        <?= $form->field($model1, 'coe_dept_id')->widget(
                                Select2::classname(), [  
                                    'data' => $model1->getDepartmentdetails(),                      
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'options' => [
                                        'placeholder' => '-----Select----',
                                        'id' => 'coe_dept_id',
                                        'name' => 'coe_dept_id',
                                        'value'=>$codatalist['coe_dept_id']
                                    ],
                                   'pluginOptions' => [
                                       'allowClear' => true,
                                    ],
                                ])->label("From Dept.");  ?>
                   
                </div>
            <?php } ?>

             <div class="col-md-2">
                <?= $form->field($model, 'coe_dept_ids')->widget(
                            Select2::classname(), [  
                                'data' => $model->getDepartmentdetails2(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    //'multiple'=>'multiple',
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_dept_ids', 
                                    'value'=>$model->coe_dept_ids, // explode(',', $model->coe_dept_ids),
                                    'onchange'=>'getcoresubjects();',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label("To Other Dept."); ?>

              
             </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
           

            <div class="col-md-3">
                    
                      <?= $form->field($model, 'subject_code')->widget(
                            Select2::classname(), [  
                                'data' => $model->getCoresubjectDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'subject_code',
                                    'value'=>$model->subject_code,
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
                
             </div>

            <div class="col-md-3">
                    <?= $form->field($model, 'semester')->textInput(['maxlength'=>1,'placeholder'=>'Only Numbers','Autocomplete'=>"off",'onkeyup'=>'checksem1();']) ?>
            </div>

             <div class="col-md-3">
           
                <?= $form->field($model, 'coe_elective_option')->widget(
                            Select2::classname(), [  
                                'data' => $model->getElectivetypeDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_elective_option', 
                                    'value'=>$model->coe_elective_option,
                                    'onchange'=>'getsubjectnewprefix()' 
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ])->label("Course Type"); ?>
             </div>

              <div class="col-md-3">
                       <?= $form->field($model, 'subject_type_new')->hiddenInput(['id' => 'subject_type_new','name' => 'subject_type_new'])->label(false); ?>
            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
             <?php  $prefix=substr($model->subject_code_new,0,5); $code=substr($model->subject_code_new,5,6);?>
            <?php if($model->subject_type_new=='NEW')
            { ?>
            <div class="col-md-6" id="new_subject_code">
                 <div class="col-md-6">
                   <label>Subject Prefix</label> <input class="form-control" type="text" name="subject_prefix_new" id='subject_prefix_new' readonly="readonly" value="<?= $prefix; ?>">
                </div>
                 <div class="col-md-6">
                    <?= $form->field($model, 'subject_code_new')->textInput(['id' => 'subject_code_new','name' => 'subject_code_new','Autocomplete'=>"off", 'value'=>$code]) ?>
                </div>
            </div>
        <?php } else { ?>

            <div class="col-md-6" id="new_subject_code" style="display: none;">
                 <div class="col-md-6">
                   <label>Subject Prefix</label> <input class="form-control" type="text" name="subject_prefix_new" id='subject_prefix_new' readonly="readonly">
                </div>
                 <div class="col-md-6">
                    <?= $form->field($model, 'subject_code_new')->textInput(['id' => 'subject_code_new','name' => 'subject_code_new','Autocomplete'=>"off",'value'=>'']) ?>
                </div>
            </div>

        <?php }?>
            <div class="col-md-6"> 
                <div class="form-group"><br>
                    <?= Html::submitButton($model->isNewRecord ? 'Assign' : 'Update', ['id'=>'savecurriculum1',
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                     <?= Html::a("Cancel", Url::toRoute(['electivetodept/index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                </div>
            </div>
        </div>
    </div>    
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
