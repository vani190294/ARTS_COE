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

<div class="elective-subject-form">
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
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---'),'onchange'=>'getdeptid();']) ?>
                </div>

                <input type="hidden" name="coe_dept_id" id="coe_dept_id" value="<?= $model->coe_dept_id;?>">

                <div class="col-md-3">
                  
                      <?= $form->field($model, 'coe_regulation_id')->widget(
                              Select2::classname(), [  
                                  'data' => $model->getRegulationDetails(),                      
                                  'theme' => Select2::THEME_BOOTSTRAP,
                                  'options' => [
                                      'placeholder' => '-----Select----',
                                      'id' => 'coe_regulation_id', 
                                      'value'=>$model->coe_regulation_id,
                                      'onchange'=>'getLTPdetails()',
                                  ],
                                 'pluginOptions' => [
                                     'allowClear' => true,
                                  ],
                              ]) ?>
                 
              </div>
            <?php } else { ?>

                <div class="col-md-3">
                       <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['id' => 'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---')]) ?>
                </div>

            <div class="col-md-3">
                
                    <?= $form->field($model, 'coe_regulation_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getRegulationDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_regulation_id', 
                                    'value'=>$model->coe_regulation_id,
                                    'onchange'=>'getLTPdetails()',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>


            <div class="col-md-3">
                
                    <?= $form->field($model, 'coe_dept_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getDepartmentdetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_dept_id', 
                                    'name' => 'coe_dept_id', 
                                    'value'=>$model->coe_dept_id,
                                    'onchange'=>'checkcreditdistrubtion();',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
               
            </div>
            
            <?php } ?>
            <!-- <div class="col-md-3">
                    <?php //$form->field($model, 'semester')->textInput(['maxlength'=>1,'placeholder'=>'Only Numbers','Autocomplete'=>"off",'onkeyup'=>'checksem();']) ?>
            </div> -->

        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="col-md-3">
            <?= $form->field($model, 'subject_code')->textInput(['maxlength' => true,'Autocomplete'=>"off",'onkeyup'=>'checksubjectprefix1();']) ?>
             </div>

            <div class="col-md-3">
            <?= $form->field($model, 'subject_name')->textarea(['cols' => 3,'rows' => 3,'Autocomplete'=>"off"]) ?>
            </div>

            <div class="col-md-3">
           
             <?= $form->field($model, 'coe_ltp_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getLTPdetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_ltp_id', 
                                    'value'=>$model->coe_ltp_id,
                                    'onchange'=>'getsubjecttype()',
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
             </div>

            <div class="col-md-3">
                   <input type="hidden" id="subject_type_id"  name="subject_type_id" value="<?php echo $model->subject_type_id;?>">
                <?= $form->field($model, 'subject_type_id')->textInput(['readonly'=>'readonly','value'=>$ltpdetails['subjecttype']]) ?>
            </div>
        </div>

         <div class="col-xs-12 col-sm-12 col-lg-12">
            
            <div class="col-md-3">
                 <input type="hidden" id="subject_category_type_id"  name="subject_category_type_id" value="<?php echo $model->subject_category_type_id;?>">
                <?= $form->field($model, 'subject_category_type_id')->textInput(['readonly'=>'readonly','value'=>$ltpdetails['subjectctype']]) ?>
            </div>

            <div class="col-md-2">
            <?= $form->field($model, 'internal_mark')->textInput(['readonly'=>'readonly']) ?>
             </div>

            <div class="col-md-2">

            <?= $form->field($model, 'external_mark')->textInput(['readonly'=>'readonly']) ?>
             </div>

              <div class="col-md-2">
                <label class="control-label">Credit Point</label><input class="form-control" type="text" id="credit_point" readonly="readonly" value="<?php echo $ltpdetails['credit_point']?>">
             </div>

             <div class="col-md-2">
                <label class="control-label">Contact Hrs</label><input class="form-control" type="text" id="contact_hrs" readonly="readonly" value="<?php echo $ltpdetails['contact_hrsperweek']?>">
             </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
                <div class="form-group pull-right">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id'=>'savecurriculum','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                     <?= Html::a("Cancel", Url::toRoute(['elective-subject/pec-index']), ['onClick'=>"spinner();",'class' => 'btn btn-warning']) ?>
                </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
</div>
</div>