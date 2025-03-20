<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\HallAllocate;
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
$this->title='Edit Practical Exam Dates';
$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(), 'register_number', 'register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number);
$from_reg_no = isset($_POST['from_reg']) ? $_POST['from_reg'] : '';
$to_reg_no = isset($_POST['to_reg']) ? $_POST['to_reg'] : '';
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
$exam_date=isset($model->exam_date)?date('d-m-Y',strtotime($model->exam_date)):"";
$this->params['breadcrumbs'][] = ['label' => 'Practical Exam Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div>&nbsp;</div>
<div class="mark-entry-form">
     <h1><?= Html::encode($this->title) ?></h1>
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
                <?= $form->field($model, 'exam_year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'exam_month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'onchange'=>'getPracExamSubs(this.value,$("#mark_year").val())',
                            'class'=>'student_disable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>

                <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',    
                            'onchange'=>'getPracExamSubsDa(this.value,$("#exam_month").val() , $("#mark_year").val())',           
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>
                <div class="col-xs-12 col-sm-2 col-lg-2">
                    <?php 

                        echo $form->field($model, 'exam_date')->widget(
                        Select2::classname(), [
                            'options' => [
                                'placeholder' => '-----Select Date ----',
                                'id' => 'exam_date',    
                                'onchange'=>'getPracExamSubsDaSess(this.value,$("#mark_subject_code").val() ,$("#exam_month").val(), $("#mark_year").val())',           
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date');
                    ?>
                </div>
                <div class="col-xs-12 col-sm-2 col-lg-2">

                    <?php echo $form->field($model, 'exam_session')->widget(
                            Select2::classname(), [
                                'data'=>ConfigUtilities::getPracExamSessions(),                                    
                                'options' => [
                                    'id' => 'exam_session_prac',    
                                    'placeholder' => '-----Select ----',
                                    'class'=>'form-control student_disable',                                    
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ]); 
                        ?>
                </div>

        </div>

            <div class="col-xs-12 col-sm-12 col-lg-12">
                    
                    <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                    <br />
                    <?= Html::Button('Get '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), ['onClick'=>'EditPractDates($("#exam_date").val(), $("#exam_session_prac").val() , $("#mark_subject_code").val() ,$("#exam_month").val(), $("#mark_year").val())','id'=>'change_name','class' => 'btn btn-success' ]) ?>
                
                    <?= Html::a("Reset", Url::toRoute(['practical-exam-timetable-new/edit-dates']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>                     
        </div>
       
        <div id='hide_dum_sub_data' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-1"> &nbsp; </div>
                <div class="col-xs-10">
                    <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <thead class="thead-inverse">
                            <tr class="table-danger">
                                <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." DATE"); ?></th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>               
                        </thead> 
                        <tbody id="show_dummy_entry">     
                            <tr class="table-danger">
                                <td><input type="date" class="form-control" name="new_exam_date" value="" placeholder="Select New date" required="required"></td>
                                <td><input type="text"  class="form-control" name="reason" value="" placeholder="Reason for Change" required="required" minlength="10" maxlength="250" ></td>
                                <td><?= Html::submitButton('UPDATE', ['id'=>'update_comp','class' => 'btn pull-right btn-success']) ?></td>
                            </tr>
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
                
            </div>
        </div>
        
        
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>