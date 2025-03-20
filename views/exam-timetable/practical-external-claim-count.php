<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
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
$this->title= 'Practical External Claim Count';
$batch_id = isset($model->batch_id)?$model->batch_id:"";
$degree_batch_mapping_id = isset($model->degree_batch_mapping_id)?$model->degree_batch_mapping_id:"";
$section_name = isset($model->stu_section_name)?$model->stu_section_name:"";
?>
<h1><?php echo $this->title; ?></h1>
<div class="exam-timetable-absent">
<div class="box box-success">
<div class="box-body">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin(['options'=>['id'=>'view-ab-form']]); ?>

<div class="row">
<div class="col-12">
    
    <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_year')->textInput(['value'=>date('Y'),'id'=>'prac_exam_year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_month')->widget(
                    Select2::classname(), [  
                        'data' => ConfigUtilities::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'prac_exam_month',  
                            'onchange'=>'getPracticalExamDates();'                          
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

    <div class="col-lg-2 col-sm-2 exam_wise">
       
        <?php 
       // $exam_year = isset($model->year)?$_POST['year']:date("Y");

        echo $form->field($model,'exam_date')->widget(
                Select2::classname(), [
                    //'data' => $model->getExamDates($exam_year),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date----',  
                        //'onchange'=>'showSessions(this.value, $("#hallallocate-year").val(), $("#exam_month").val());',
                        'id' => 'prac_exam_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>

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
    
    <!--div class="col-lg-2 col-sm-2 exam_wise">

        <?php echo $form->field($model,'exam_session')->widget(
                Select2::classname(), [
                    'data' => $examTimetable->getExamSession(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' ----',   
                         'id' => 'prac_exam_session',                            
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
    </div-->   
  </div>
</div>

<div class="row">
<div class="col-12">
    
    <div class="col-lg-3 col-sm-3"> 
        <br />
           

            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['onClick'=>'showPracticalexternalcount($("#prac_exam_year").val(), $("#prac_exam_month").val(), $("#prac_exam_date").val(),$("#prac_exam_session").val(), $("#stu_batch_id_selected").val() );','class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable/consolidate-absent-list']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
</div>
</div>
 <?php ActiveForm::end(); ?>
    
<div class="row hide_ab_list">
    <div class="col-12">
        <div class="col-lg-12 col-sm-12">
            <?php 

            echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('consolidate-absent-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
            
            echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('consolidate-excel-ab-pdf','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff'));

            ?>
           <div class="show_ab_data">
           </div>

        </div>
    </div>

</div>


</div>
</div>
</div><!-- exam-timetable-absent -->