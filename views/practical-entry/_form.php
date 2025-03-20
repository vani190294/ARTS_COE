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

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(), 'register_number', 'register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number);
$from_reg_no = isset($_POST['from_reg']) ? $_POST['from_reg'] : '';
$to_reg_no = isset($_POST['to_reg']) ? $_POST['to_reg'] : '';
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
?>

<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
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
                <?php echo $form->field($markEntry, 'section')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getSectionnames(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id'=>'stu_section_select',
                            'name'=>'sec',
                            'class'=>'form-control student_disable',                                    
                        ],
                    ]); 
                ?>
            </div> 
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
              
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                      'options' =>

                         [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'name'=>'month',
                            
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
                              
                
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">  
                <?php $model->term='34';$model->mark_type='27'; ?>              
                <?= $form->field($model, 'term')->radioList(ExamTimetable::getExamTerm()); ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'mark_type')->radioList(ExamTimetable::getExamType()); ?>
                
            </div> 
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'semester')->textInput(['onblur'=>'getPracticalSubs(this.value);','name'=>'exam_semester']); ?>
                
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',
                            'name'=>'sub_val',
                            
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,

                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>

            <div class="col-sm-2">
                        <?php
                        echo $form->field($student, 'register_number_from')->widget(Select2::classname(), [
                            'initValueText' => $register_numbers, // set the initial display text
                            'options' => ['placeholder' => 'Search for a register_number ...', 'name' => 'from_reg', 'value' => $from_reg_no],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:"All"}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                        ]);
                        ?>
                    </div>

                    <div class="col-sm-2">
                        <?php
                        echo $form->field($student, 'register_number_to')->widget(Select2::classname(), [
                            'initValueText' => $register_numbers, // set the initial display text
                            'options' => ['placeholder' => 'Search for a register_number ...', 'name' => 'to_reg', 'id' => 'to_reg', 'value' => $to_reg_no],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:"All"}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                        ]);
                        ?>
                    </div>

                       
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'mark_type')->textInput(['name'=>'examiner_name','id'=>'examiner_name','required'=>'required'])->label('Internal Examiner Name'); ?>                
            </div> 
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'mark_type')->textInput(['name'=>'chief_exam_name','id'=>'chief_exam_name','required'=>'required','onblur' => 'getPracticalStuList();',])->label('External Examiner Name'); ?>                
            </div>
            <br />
                
        </div>
        <div id='hide_dum_sub_data' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-1"> &nbsp; </div>
                <div class="col-xs-10">
                    <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <thead class="thead-inverse">
                            <tr class="table-danger">
                                <th>SNO</th>
                                <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE"); ?></th>
                                
                                <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME"); ?></th>
                                <th><?php echo strtoupper("Minimum"); ?></th>
                                <th><?php echo strtoupper("Maximum"); ?></th>
                            </tr>               
                        </thead> 
                        <tbody id="show_dummy_entry">     

                        </tbody>
                    </table> 
                </div>
            <div class="col-xs-1"> &nbsp; </div>
            </div>
        </div> <!-- Row Closed --><br />
        <div class="row">
            <div id='disp_show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">
                <div id='show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">

                </div>
                <?= Html::submitButton('SUBMIT', ['id'=>'update_comp','class' => 'btn btn-success' ,'formtarget'=>"_blank"]) ?>
            </div>
        </div>
        
        
    <?php ActiveForm::end(); ?>
</div>


</div>
</div>

