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
use kartik\time\TimePicker;

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
$exam_date=isset($model->exam_date)?date('d-m-Y',strtotime($model->exam_date)):"";
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
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'batch_mapping_id')->widget(
                    Select2::classname(), [
                    //'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
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
                            'class'=>'form-control student_disable',                              
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
            </div> 
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'exam_year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'exam_month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'mark_type')->radioList(ExamTimetable::getExamType(),['onchange'=>'mark_typeid()']); ?>
           
        </div>
            
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'semester')->textInput(['onblur'=>'getPracticalSubsOnly(this.value);',]) ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-lg-2">
                <?php 
                    echo '<label>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date </label>';
                    echo DatePicker::widget([
                        'name' => 'exam_date',
                        'value' => $exam_date,   
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [                        
                            'placeholder' => '-- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date ...',
                            'onchange'=>'CheckThisDate(this.id)',
                            'autocomplete' => 'OFF',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'autoclose'=>true,
                            'format' => 'dd-mm-yyyy',
                        ],
                                           
                    ]);
                ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-lg-2">

                <?php echo $form->field($model, 'exam_session')->widget(
                        Select2::classname(), [
                            'data'=>ConfigUtilities::getPracExamSessions(),                                    
                            'options' => [
                                'placeholder' => '-----Select ----',
                                'class'=>'form-control student_disable',                                    
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]); 
                    ?>
            </div>
            
            <div class="col-lg-4 col-sm-4">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',                                  
                            //'onchange'=>'getPracticalstudents();'               
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>

             <div class="col-lg-2 col-sm-2">
                        <?= $form->field($model, 'term')->textInput(['id'=>'maximum_students','required'=>'required','value'=>38])->label('Max '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Count'); ?>
            </div> 

               
        </div>
        

        <div class="col-xs-12 col-sm-12 col-lg-12">
                   
                    <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                    <br />
                    <?= Html::Button('Get '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), ['onClick'=>'getPracticalOnlyStuList()','id'=>'change_name','class' => 'btn btn-success' ]) ?>
                
                    <?= Html::a("Reset", Url::toRoute(['practical-exam-timetable-new/create']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>                     
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
                <?= Html::submitButton('Confirm', ['id'=>'update_comp','class' => 'btn pull-right btn-success' ,'data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once Submitted.','formtarget'=>"_blank"]) ?>
            </div>
        </div>
        
        
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>