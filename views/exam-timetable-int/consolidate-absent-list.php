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
$this->title= ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT);
$batch_id = isset($model->batch_id)?$model->batch_id:"";
$degree_batch_mapping_id = isset($model->degree_batch_mapping_id)?$model->degree_batch_mapping_id:"";
$section_name = isset($model->stu_section_name)?$model->stu_section_name:"";
?>
<h1><?php echo "Consolidate ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT); ?></h1>
<div class="exam-timetable-absent">
<div class="box box-success">
<div class="box-body">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin(['options'=>['id'=>'view-ab-form']]); ?>

<div class="row">
<div class="col-12">
    
    <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id' => 'intexam_year', ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'intexam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

         <div class="col-xs-12 col-lg-2 col-sm-2">
             <?php echo $form->field($examTimetable, 'internal_number')->widget(
            Select2::classname(), [
            'data' =>ConfigUtilities::internalNumbers(),
            'options' => [
                'placeholder' => '-----Select----',
                'class'=>'form-control',
                'id' => 'internal_number',
            ],
            ])->label('Internal Exam Type'); 
        ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'exam_date',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Session ----', 
                            'id' => 'intexam_session',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]); ?>
        </div> 
         <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($examTimetable,'time_slot')->widget(
                Select2::classname(), [
                   // 'data' => $exam->Examtimeslot,
                    'options' => [
                        'placeholder' => '-----Select ----',
                        'id' => 'time_slot',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>

       
         
  </div>
</div>

<div class="row">
<div class="col-12">


    <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($examTimetable,'coe_batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getBatchDetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                        'id' => 'stu_batch_id_selected',
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
            <?php echo $form->field($examTimetable, 'batch_mapping_id')->widget(
                Select2::classname(), [
                
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                        'id' => 'stu_programme_selected',
                        'class'=>'student_disable',
                        'name'=>'bat_map_val',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
            ?>
        </div> 

    
    <div class="col-lg-3 col-sm-3"> 
        <br />
           

            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['onClick'=>'showConsolidateAbdata1($("#intexam_year").val(), $("#intexam_month").val(), $("#exam_date").val(), $("#intexam_session").val(), $("#internal_number").val(), $("#stu_batch_id_selected").val(), $("#stu_programme_selected").val(),$("#time_slot").val());','class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable-int/consolidate-absent-list']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

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
</div>
<!-- exam-timetable-absent -->