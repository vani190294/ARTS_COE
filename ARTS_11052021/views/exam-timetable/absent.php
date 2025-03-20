<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\AbsentEntry;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\dialog\Dialog;
echo Dialog::widget();

$this->registerCssFile("@web/style/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    'media' => 'print',
], 'css-print-theme');


/* @var $this yii\web\View */
/* @var $model app\models\AbsentEntry */
/* @var $form ActiveForm */
$this->title= ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT);
$batch_id = isset($model->batch_id)?$model->batch_id:"";
$degree_batch_mapping_id = isset($model->degree_batch_mapping_id)?$model->degree_batch_mapping_id:"";

?>
<h1><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." Entry"; ?></h1>
<div class="exam-timetable-absent">
<div class="box box-success">
<div class="box-body">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin(); ?>

<div class="row">
<div class="col-12">
    <div class="col-lg-2 col-sm-2">

        <?php echo $form->field($model,'absent_type')->widget(
                Select2::classname(), [
                    'data' => $model->getAbTypes(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' ----', 
                        'onChange' => 'changeFields(this.value);'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>
    </div>
    <div class="col-lg-2 col-sm-2 ab_hide_default remove_section_for_hall hide_semester">
        <?php echo $form->field($model,'batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getBatchDetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',   
                        'id' => 'stu_batch_id_selected', 
                        //'value'=> $batch_id,                     
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>        
    </div>
    <div class="col-lg-2 col-sm-2 ab_hide_default remove_section_for_hall hide_semester">
        <?php echo $form->field($model,'course_batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getDegreedetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',  
                        'id' => 'stu_programme_selected',
                        //'value'=>$degree_batch_mapping_id,
                        'onchange'=>'get_semester(this.value)',                      
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>
    </div>
    <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_year')->textInput(['value'=>date('Y'),'id'=>'hallallocate-year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_month')->widget(
                    Select2::classname(), [  
                        'data' => ConfigUtilities::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
    
    <div class="col-lg-2 col-sm-2 ab_hide_default exam_wise">
        <input type="hidden" id='exam_year' name="year" value="">
        <?php 
        $exam_year = isset($model->year)?$_POST['year']:date("Y");

        echo $form->field($model,'exam_date')->widget(
                Select2::classname(), [
                    'data' => $model->getExamDates($exam_year),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date----',  
                        'onchange' =>'showSessions(this.value, $("#hallallocate-year").val(), $("#exam_month").val());getHalls(this.value, $("#hallallocate-year").val(), $("#exam_month").val());',
                        'id'=>'exam_date',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
    
    <div class="col-lg-2 col-sm-2 ab_hide_default exam_wise">

        <?php echo $form->field($model,'exam_session')->widget(
                Select2::classname(), [
                    'data' => $examTimetable->getExamSession(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' ----',   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
    </div>
    
    <div class="col-lg-2 col-sm-2 ab_hide_default ab_hall_wise">       
        <?php 
        $exam_year = isset($model->year)?$_POST['year']:date("Y");

        echo $form->field($model,'halls')->widget(
                Select2::classname(), [
                    'data' => $model->getExamDates($exam_year),
                    'options' => [
                        'placeholder' => '-----Select Halls----',   
                        'class' =>'removecommon',                     
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>        
        
    </div>
    <div class="col-lg-2 col-sm-2 ab_hide_default ab_common_all">
        <?php echo $form->field($model,'exam_type')->widget(
                Select2::classname(), [
                    'data' => $examTimetable->getExamType(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' ----',                        
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>
    </div>
    <div class="col-lg-2 col-sm-2 ab_hide_default remove_section_for_hall hide_semester">
        <?php echo $form->field($model,'exam_semester_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select ----',               
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        
                       
                    ],
                ]); 
            ?>
        
    </div>
    <div class="col-lg-2 col-sm-2 ab_hide_default ab_common_all">
        <?php echo $form->field($model,'absent_term')->widget(
                Select2::classname(), [
                    'data' => $examTimetable->getExamTerm(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TERM).' ----',  
                        'class'=>'absent_type',
                        'onChange' => 'showSubjectCodes(this.value);'                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>
       
    </div>

    <div class="col-lg-2 col-sm-2 ab_hide_default ab_common_all">
        <?php echo $form->field($model,'exam_subject_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----',               
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
    
    <div class="col-lg-3 col-sm-3"> 
        <br />
           

            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable/absent']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
</div>
</div>
 <?php ActiveForm::end(); ?>

    <?php 
    if(isset($send_result) && !empty($send_result))
    {
        include('practical_entry.php');
    }
    else if (isset($exam_result) && !empty($exam_result)) {
        include('exam_entry.php');
    }
    else{
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found');
    }

    ?>


</div>
</div>
</div><!-- exam-timetable-absent -->

