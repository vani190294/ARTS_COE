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
                                ]); ?>
                   
                </div>

            <div class="col-md-2">
           
                <?= $form->field($model, 'coe_elective_option')->widget(
                            Select2::classname(), [  
                                'data' => $model->getElectivetypeDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_elective_option', 
                                    'value'=>$model->coe_elective_option,
                                    'onchange'=>'getelctivesubjects();'
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
             </div>

             <div class="col-md-2">
           
                <?= $form->field($model, 'coe_elective_id')->widget(
                            Select2::classname(), [  
                                'data' => $model->getElectivesubjectDetails(),                      
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'id' => 'coe_elective_id',
                                    'value'=>$model->coe_elective_id,
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
             </div>

              <div class="col-md-2">
                    <?= $form->field($model, 'semester')->textInput(['maxlength'=>1,'placeholder'=>'Only Numbers','Autocomplete'=>"off",'onkeyup'=>'checksem1();']) ?>
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
                                    'value'=> explode(',', $model->coe_dept_ids),
                                ],
                               'pluginOptions' => [
                                   'allowClear' => true,
                                ],
                            ]) ?>
             </div>

            <div class="col-md-2"> 
                <div class="form-group"><br>
                    <?= Html::submitButton($model->isNewRecord ? 'Assign' : 'Update Assigned', ['id'=>'savecurriculum',
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
