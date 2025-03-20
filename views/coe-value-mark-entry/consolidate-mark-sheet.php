<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\ExamTimetable;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use kartik\date\DatePicker;

$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(),'register_number','register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number); 

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="VALUE ADDED MARK SHEET";
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry, 'stu_programme_id')->widget(
                    Select2::classname(), [
                    'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                               
                            <?php 

                                echo $form->field($student, 'register_number_from')->widget(Select2::classname(), [
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
            <div class="col-lg-2 col-sm-2">

                            <?php 

                                echo $form->field($student, 'register_number_to')->widget(Select2::classname(), [
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
            <div class="col-xs-12 col-sm-2 col-sm-2">
                <?php 
                    echo '<label>From Date</label>';
                    echo DatePicker::widget([
                        'name' => 'from_date',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ['placeholder' => 'Select From Date ...',],
                         'pluginOptions' => [
                            'autoclose'=>true,
                        ],
                    ]);
                 ?>
            </div>

            <div class="col-xs-12 col-sm-2 col-sm-2">
                <?php 
                    echo '<label>To Date</label>';
                    echo DatePicker::widget([
                        'name' => 'to_date',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ['placeholder' => 'Select To Date ...',],
                         'pluginOptions' => [
                            'autoclose'=>true,
                        ],
                    ]);
                 ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'semester')->textInput(['onblur'=>'getValueAdd(this.value);','name'=>'semester']); ?>
                
            </div>

              <div class="col-sm-2">
                        <?= $form->field($model, 'year')->textInput(['value' => date('Y'),'name'=>'year']) ?>

                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $galley->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month', 
                            'name' => 'month',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>

                    </div>

             <div class="col-xs-12 col-sm-2 col-sm-2">
                <?php 
                    echo '<label>Publication Date</label>';
                    echo DatePicker::widget([
                        'name' => 'publication_date',
                        'value'=>date('m/d/Y'),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ['placeholder' => 'Select Publication Date ...',],
                         'pluginOptions' => [
                            'autoclose'=>true,
                        ],
                    ]);
                 ?>
            </div>


            <div class="col-xs-12 col-sm-4 col-lg-4">            
                
                </br>
                <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                    <?= Html::submitButton('Submit', ['onClick'=>"spinner(); ", 'name'=>'get_marks','class' => 'btn btn-success' ]) ?>
                    <?= Html::a("Reset", Url::toRoute(['coe-value-mark-entry/consolidate-mark-sheet']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>
             
                        
                    
                   
            </div> 
            
        </div>
           
        <input type="hidden" value="All" name="section" id='stu_section_select' class='form-control student_disable' />
               
        
        
    <?php ActiveForm::end(); ?>



</div>
</div>
</div>

<?php
require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

if(isset($get_console_list) && !empty($get_console_list))
{
     echo Html::a('<i class="fa fa-file-pdf-o"></i> Print Pdf', ['/coe-value-mark-entry/consolidate-mark-sheet-vadd-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
            ]); 
     echo "<br><br>";
    include_once("consolidate-mark-sheet-vadd.php");
}

?>