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
$this->title= "Exam Date Wise ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT);
$batch_id = isset($model->batch_id)?$model->batch_id:"";
$degree_batch_mapping_id = isset($model->degree_batch_mapping_id)?$model->degree_batch_mapping_id:"";

?>
<h1><?php echo "Exam Date Wise ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." Entry"; ?></h1>
<div class="exam-timetable-absent">
<div class="box box-success">
<div class="box-body">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin(); ?>

<div class="row">
<div class="col-12">
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
    
    <div class="col-lg-2 col-sm-2">
        <input type="hidden" id='exam_year' name="year" value="">
        <?php 
        $exam_year = isset($model->year)?$_POST['year']:date("Y");

        echo $form->field($model,'exam_date')->widget(
                Select2::classname(), [
                    'data' => $model->getExamDates($exam_year),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date----',  
                        'onchange' =>'showSessions(this.value, $("#hallallocate-year").val(), $("#exam_month").val());',
                        'id'=>'exam_date',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
    
    <div class="col-lg-2 col-sm-2">

        <?php echo $form->field($model,'exam_session')->widget(
                Select2::classname(), [
                    'data' => $examTimetable->getExamSession(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' ----', 
                        'onchange'=>'getChangeExamSubjects(this.value,$("#exam_date").val(), $("#hallallocate-year").val(), $("#exam_month").val());'  
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
    </div>
    
    <div class="col-lg-2 col-sm-2">       
        <?php 
        echo $form->field($model,'exam_subject_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select ----',     
                        'id'=>'exam_subject_id',
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
    
    <div class="col-lg-8 col-sm-8"> 
        <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['onClick'=>'getExamSubStudents($("#exam_subject_id").val(), $("#exam_date").val(), $("#absententry-exam_session").val(), $("#hallallocate-year").val(), $("#exam_month").val(),);','class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable/hall-wise-absent']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
    <div class="col-lg-4 pull-right hide_hall_submit col-sm-4"> 
        <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('UPDATE', ['class' => 'btn  btn-group-lg btn-group btn-danger']) ?>
            </div>
            
    </div>
</div>
</div>
<br /><br />
<div class="show_hall_result_data">
    
</div>

 <?php ActiveForm::end(); ?>

</div>
</div>
</div><!-- exam-timetable-absent -->


 <?php 


$this->registerJsFile(
    '@web/style/bower_components/datatables.net/js/jquery.dataTables.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(    
    '@web/style/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJs(<<<JS
    $(function () {
    $('#exam_practical_edit').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
       'scrollY': '400',
       "scrollX": true,
       "responsive": "true",
       "pageLength": "1500",
       language: {
            searchPlaceholder: "Register Number to filter"
        }
       
    })
  })
JS
);


?>