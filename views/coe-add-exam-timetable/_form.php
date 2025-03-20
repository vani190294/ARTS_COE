<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\Batch;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use kartik\date\DatePicker;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

if(isset($model->exam_date))
{
    $this->registerJs("$(document).ready(function() { $('.student_disable').attr('disabled',true)});");
}


?>

<div>&nbsp;</div>

<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 
$exam_date=isset($model->exam_date)?date('d-m-Y',strtotime($model->exam_date)):"";
$batch_id = isset($model->subject_mapping_id)?$model->subjectMapping->batchMapping->coeBatch->coe_batch_id:"";
$batch_map_id = isset($model->subject_mapping_id)?$model->subjectMapping->batchMapping->coeProgramme->coe_programme_id:"";
$subject_code = isset($model->subject_mapping_id)?$model->subjectMapping->coeSubjects->subject_code:"";
$subject_name = isset($model->subject_mapping_id)?$model->subjectMapping->coeSubjects->subject_name:"";
$semester = isset($model->subject_mapping_id)?$model->subjectMapping->semester:"";
//$month = Categorytype::find('category_type')->where(['coe_category_type_id'=>$model->exam_month])->one();
$month = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='".$model->exam_month."'")->queryScalar();
//$exam_month = isset($month)?$model->exam_month:"";
//echo $month;exit;
$condition = $model->isNewRecord?true:false;
$form = ActiveForm::begin([
                            'enableClientValidation' => true, 
                            'enableAjaxValidation' => $condition,
                            'id' => 'student_form_required_page',
                            'fieldConfig' => [
                                'template' => "{label}{input}{error}",
                            ],
                        ]); ?>
<div>&nbsp;</div>

<div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'coe_batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getBatchDetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                        'id' => 'stu_batch_id_selected',
                        'value'=> $batch_id,
                        'class'=>'student_disable',
                        'name'=>'bat_val',

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
                'data'=>ConfigUtilities::getDegreedetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                        'id' => 'stu_programme_selected',
                        'value'=>$batch_map_id,
                        'class'=>'student_disable',
                        'name'=>'bat_map_val',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
            ?>
        </div> 
        <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'exam_year')->textInput(['class'=>'form-control student_disable','value'=>date('Y')]) ?>
        </div>
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'exam_month')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                        'id' => 'exam_month',
                        'class'=>'student_disable',
                        'value'=>$month,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
                ?>
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
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                    ],
                                       
                ]);
            ?>
        </div>
    
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'exam_term')->widget(
                Select2::classname(), [
                    'data' => $model->ExamTerm,
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TERM).' ----',
                        'id' => 'exam_term',
                        'class'=>'student_disable',
                        //'value'=> $batch_id,
                        //'name'=>'exam_term',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>
</div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'exam_type')->widget(
                Select2::classname(), [
                    'data' => $model->ExamType,
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' ----',
                        'id' => 'exam_type',
                        'class'=>'student_disable',
                        //'value'=> $batch_id,
                        //'name'=>'exam_type',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>
        
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'exam_session')->widget(
                Select2::classname(), [
                    'data' => $model->ExamSession,
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' ----',
                        'id' => 'exam_session',
                        //'value'=> $batch_id,
                        //'name'=>'exam_session',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>

        <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'semester')->textInput(['id'=>'exam_semester','name'=>'exam_semester','class'=>'form-control student_disable','value'=>$semester]) ?>
        </div>
   
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'subject_code')->widget(
                Select2::classname(), [
                    //'data' => $model->BatchDetails,
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----',
                        'id' => 'add_exam_subject_code',
                        'value'=> $subject_code,
                        'class'=>'student_disable',
                        'name'=>'add_exam_subject_code',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
            ?>
        </div>

        <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'subject_name')->textInput(['readonly'=>'readonly','id'=>'add_exam_subject_name','name'=>'add_exam_subject_name','value'=>$subject_name])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name'); ?>
        </div>

        <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'qp_code')->textInput(['maxlength' => true]) ?>
        </div>

        
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group col-lg-3 col-sm-3">
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['onClick'=>"spinner();",'class' => $model->isNewRecord ? 'btn btn-group-lg btn-group btn-success' : 'btn  btn-group-lg btn-group btn-primary']) ?>
               
                <?= Html::a("Reset", Url::toRoute(['coe-add-exam-timetable/create']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>
            
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

</div>
</div>
</div>
