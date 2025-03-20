<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\dialog\Dialog;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\AbsentEntry */
/* @var $form ActiveForm */
$this->title= "External Score Card";

$degree_batch_mapping_id = isset($_POST['AbsentEntry']['course_batch_id'])?$_POST['AbsentEntry']['course_batch_id']:"";
$semester = isset($_POST['AbsentEntry']['exam_semester_id'])?$_POST['AbsentEntry']['exam_semester_id']:"";
$sub_id = Yii::$app->db->createCommand("select subject_code from coe_subjects as A,coe_subjects_mapping as B where A.coe_subjects_id=B.subject_id and B.coe_subjects_mapping_id='".$model->exam_subject_id."'")->queryScalar();
//$exam_subject_id = isset($model->exam_subject_id)?$model->exam_subject_id:"";
$exam_subject_id = isset($sub_id)?$sub_id:"";
$sel_year = isset($_POST['AbsentEntry']['year'])?$_POST['AbsentEntry']['year']:date('Y');
$batch_id = isset($_POST['AbsentEntry']['batch_id'])?$_POST['AbsentEntry']['batch_id']:'';
$exam_month = isset($_POST['AbsentEntry']['exam_month'])?$_POST['AbsentEntry']['exam_month']:'';
$exam_type = isset($_POST['AbsentEntry']['exam_type'])?$_POST['AbsentEntry']['exam_type']:'';
$exam_term = isset($_POST['AbsentEntry']['absent_term'])?$_POST['AbsentEntry']['absent_term']:'';


?>
<h1><?php echo "EXTERNAL SCORE CARD OUT OF MAXIMUM MARKS"; ?></h1>
<div class="exam-timetable-absent">
<div class="box box-success">
<div class="box-body">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin(); ?>

<div class="row">
<div class="col-12">
     <div class="col-lg-2 col-sm-2">
        <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
    </div>
    <div class="col-lg-2 col-sm-2">
        <?php echo $form->field($model,'batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getBatchDetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',   
                        'id' => 'stu_batch_id_selected', 
                        'value'=> $batch_id,                     
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>

        
    </div>
    <div class="col-lg-2 col-sm-2">
        <?php echo $form->field($model,'course_batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getDegreedetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',  
                        'id' => 'stu_programme_selected',
                        'value'=>$degree_batch_mapping_id,   
                        'onChange' => 'getSemesters(this.value);',                      
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>

        
    </div>
    <div class="col-lg-2 col-sm-2">
        <?php echo $form->field($model, 'stu_section_name')->widget(
            Select2::classname(), [
                'data'=>ConfigUtilities::getSectionnames(),                                    
                'options' => [
                    'placeholder' => '-----Select ----',
                    'id'=>'stu_section_select',
                    'class'=>'form-control student_disable',                                    
                ],
            ]); 
        ?>
    </div> 
    <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'exam_month')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                        'id' => 'exam_month',
                        'class'=>'student_disable',
                        'value'=>$exam_month, 
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
                ?>
        </div>
    <div class="col-lg-2 col-sm-2">
        <?php echo $form->field($model,'exam_semester_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select ----', 
                        'value'=>$semester,       
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
<div class="col-12">
    <div class="col-lg-2 col-sm-2">
        <?php echo $form->field($model,'exam_type')->widget(
                Select2::classname(), [
                    'data' => $examTimetable->getExamType(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' ----', 
                        'value'=>$exam_type,                        
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>
    </div>
    <div class="col-lg-2 col-sm-2">
        <?php echo $form->field($model,'absent_term')->widget(
                Select2::classname(), [
                    'data' => $examTimetable->getExamTerm(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TERM).' ----',  
                        'class'=>'absent_type',
                        'onChange' => 'ExternalSubjectCodes(this.value);',
                        'value'=>$exam_term,  
                                          
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>
       
    </div>

    <div class="col-lg-2 col-sm-2">
        <?php echo $form->field($model,'exam_subject_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'value'=>$exam_subject_id,
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----',               
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>
        
    </div>
    
    <div class="col-lg-2 col-sm-2"> 
        <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable/external-format']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>            
    </div>
</div>
</div>
 <?php ActiveForm::end(); ?>
<?php 
    if(isset($external_score) && !empty($external_score))
    {
        
        include('external-score-pdf-out-of-max.php');
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found');
    }
?>    

</div>
</div>
</div><!-- exam-timetable-absent -->

