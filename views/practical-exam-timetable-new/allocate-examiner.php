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
$this->title = 'Allocate External Examinar';
$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(), 'register_number', 'register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number);
$from_reg_no = isset($_POST['from_reg']) ? $_POST['from_reg'] : '';
$to_reg_no = isset($_POST['to_reg']) ? $_POST['to_reg'] : '';
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
$exam_date=isset($model->exam_date)?date('d-m-Y',strtotime($model->exam_date)):"";
?>
<h1><?= Html::encode($this->title) ?></h1>
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
                <?= $form->field($model, 'exam_year')->textInput(['value'=>date("Y"),'id'=>'mark_year']) ?>
               
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'exam_month')->widget(
                    Select2::classname(), [                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'onchange'=>'getPracExamDatesAllocat(this.value)',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            
            <div class="col-xs-12 col-sm-2 col-lg-2">
                <?php echo $form->field($model, 'exam_date')->widget(
                        Select2::classname(), [
                            'options' => [
                                'placeholder' => '-----Select ----',
                                'class'=>'form-control student_disable', 
                                'id'=>'prac_exam_date',                      
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label('Exam Date(Optional)');  
                    ?>
            </div>

        </div>

<div class="col-xs-12 col-sm-12 col-lg-12">
<div class="col-xs-12 col-sm-2 col-lg-2">

            <?php echo $form->field($model, 'exam_session')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getPracExamSessions(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'class'=>'form-control student_disable',
                           'id'=>'exam_session',  
                           'onchange'=>'getPracticalSubsOnly1(this.value);',                                  
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Exam Session(Optional)');  
                ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">

            <?php echo $form->field($model, 'unique_prac_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'unique_prac_id',                                   
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Practical Batch(Optional)'); 
                ?>
        </div>

        <div class="col-xs-12 col-lg-2 col-sm-2"><br>
            <label for="additional_staff">
             <input type="checkbox" id="additional_staff" name="additional_staff">
             For Additional Faculty</label>
        </div>

            <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                <br />
                <?= Html::Button('Get '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), ['onClick'=>'getPracticalExaminer1()','class' => 'btn btn-success' ]) ?>
            
                <?= Html::a("Reset", Url::toRoute(['practical-exam-timetable-new/allocate-examiner']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
            </div>
            

        </div>
        
        <div id='hide_dum_sub_data' class="row">
        <div  class="col-xs-12"> <br /><br />
            
            </div>
            <div id='disp_show_details_subs' class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x: auto;">
                <div id='show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">

                </div>
                <?= Html::submitButton('Save & Print', ['id'=>'change_name_get_stu','class' => 'btn pull-right btn-success' ,'data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once Submitted.','formtarget'=>"_blank"]) ?>
            </div>
        </div> <!-- Row Closed --><br />
      
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>