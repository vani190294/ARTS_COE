<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\Categorytype;
use yii\helpers\ArrayHelper;

$url = Url::to(['register-numbers']);
// Get the initial city description
$numbers = ArrayHelper::map(Student::find('register_number')->all(),'register_number','register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number); 

echo Dialog::widget();
$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Application";
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(isset($model->stu_section_name))
{
    $this->registerJs("$(document).ready(function() { $('.student_disable').attr('readonly',true)});");
}
$batch_id = isset($model->stu_batch_id)?$model->stu_batch_id:"";
$section_name = isset($model->stu_section_name)?$model->stu_section_name:"";
$degree_batch_mapping_id = isset($model->stu_programme_id)?$model->stu_programme_id:"";
$year = isset($model->app_year)?$model->app_year:date('Y');
if(isset($model->app_month))
{
    $app_month = Categorytype::find()->where(['coe_category_type_id'=>$model->app_month])->one();
    $month = !empty($app_month)?$app_month->description:"";
}
else
{
    $month = "";
}

?>
<h1><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Application"; ?></h1>
<br /><br />
<div id="student_update_edit_page" class="configuration-form">
    <div class="box box-primary">
        <div class="box-body">          
               
            <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
            <?php 
                    $condition = $model->isNewRecord?true:false;
                    $form = ActiveForm::begin(); 
            ?>
            
            <div class="row">
                <div  class="col-xs-12">  

                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'stu_batch_id')->widget(
                                Select2::classname(), [
                                'data' => ConfigUtilities::getBatchDetails(),
                                
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_batch_id_selected', 
                                    'value'=> $batch_id,    
                                    'class'=>'form-control student_disable',                              
                                ],
                                 'pluginOptions' => [
                                        'allowClear' => true,
                                    ],

                               
                                
                            ]); 
                        ?>
                        </div>                  
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'stu_programme_id')->widget(
                                Select2::classname(), [
                                    'data'=>ConfigUtilities::getDegreedetails(),                                    
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_programme_selected',
                                    'value'=>$degree_batch_mapping_id,
                                    'class'=>'form-control student_disable',
                                ],
                                 'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                                               
                            ]); 
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'stu_section_name')->widget(
                                Select2::classname(), [
                                    'data'=>ConfigUtilities::getSectionnames(),                                    
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id'=>'stu_section_select',
                                    'value'=>$section_name,
                                    'class'=>'form-control student_disable',                                    
                                ],
                                 'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                                             
                            ]); 
                        ?>
                        </div> 
                        <div class="col-lg-2 col-sm-2">
                        <?php echo $form->field($model,'exam_type')->widget(
                                Select2::classname(), [
                                    'data' => $model->getExamType(),
                                    'options' => [
                                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' ----',                        
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]); 
                            ?>
                    </div>                
                        
                       
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'app_year')->textInput(['value'=> $year]); 
                        ?>
                        </div>  
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'app_month')->widget(
                                Select2::classname(), [
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'exam_month', 
                                    'value' => $month,
                                    'class'=>'form-control student_disable',                              
                                ],
                                 'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                

                            ]); 
                        ?>
                        </div> 
                        </div>
                   </div>
                   <div class="row">
                <div  class="col-xs-12">       
                            <div class="col-xs-12 col-sm-2 col-lg-2">
                               
                            <?php 

                                echo $form->field($model, 'register_number_from')->widget(Select2::classname(), [
                                'initValueText' => $register_numbers, // set the initial display text
                                'options' => ['placeholder' => 'Search for a register_number ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 1,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => $url,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:$("#stu_section_select").val()}; }')
                                    ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                                ],
                            ]);


                            ?>

                        </div> 
                        <div class="col-xs-12 col-sm-2 col-lg-2">

                            <?php 

                                echo $form->field($model, 'register_number_to')->widget(Select2::classname(), [
                                'initValueText' => $register_numbers, // set the initial display text
                                'options' => ['placeholder' => 'Search for a register_number ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 1,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:$("#stu_section_select").val()}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                            ]);


                            ?>
                        </div> 
                            
                    
                        <div class="col-xs-12 col-sm-3 col-lg-3"><br />
                        
                        <div class="btn-group btn-block" role="group" aria-label="Actions to be Perform">
                            <?= Html::submitButton('Show', ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg  btn-success' ]) ?>
                            <?= Html::a("Reset", Url::toRoute(['student/student-exam-application']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg  btn-warning ','onClick'=>"spinner();"]) ?>
                            
                        </div>                                
                     </div> 
                          
                    </div>

                </div>


                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php 
include_once("print-application-pdf.php");
?>
