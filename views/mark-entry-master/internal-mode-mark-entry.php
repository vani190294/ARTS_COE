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

$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(),'register_number','register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number); 

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Internal Mode Entry";
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>
<input type="hidden" value="All" name="section" id='stu_section_select' class='form-control student_disable' />
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
                <?= $form->field($markEntry, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry,'month')->widget(
                    Select2::classname(), [
                        'options' => [
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
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'term')->radioList(ExamTimetable::getExamTerm()); ?>
                
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'mark_type')->radioList(ExamTimetable::getExamType()); ?>
                
            </div> 
                
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'semester')->textInput(['onblur'=>'getInternalModeSubs(this.value);','name'=>'exam_semester']); ?>
                
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry, 'subject_map_id')->widget(
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
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry, 'attendance_remarks')->widget(
                    Select2::classname(), [
                        'data'=>['0'=>'PASS','1'=>'COMPLETED','2'=>'Activity'],
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'subject_type',
                            'name'=>'sub_type',
                            'onchange' => 'getIntStuList();'

                        ],
                        'pluginOptions' => [
                            'allowClear' => true,

                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' TYPE');
                ?>
            </div>
            <br />

                <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                   
                    <?= Html::a("Reset", Url::toRoute(['mark-entry-master/internal-mode-mark-entry']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>
            

        </div>

        <div class="row">
            <div id='disp_show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">
                <div id='show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">

                </div>
                <?= Html::submitButton('Updated & Save', ['id'=>'update_comp','class' => 'btn btn-success' ]) ?>
            </div>
        </div>
            
        
    <?php ActiveForm::end(); ?>
</div>


</div>
</div>

