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
$this->title= 'Internal Exam Scorecard PDF';
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
        

        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry, 'section')->widget(
                    Select2::classname(), [
                        //'data'=>ConfigUtilities::getSectionnames(),                                    
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
             <div class="col-xs-12 col-sm-2 col-lg-2" > 
            <?= $form->field($examTimetable, 'subject_code')->widget(
                    Select2::classname(), [

                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----', 
                            'id' => 'subject2',
                            'name' => 'galley_subject_wise',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                         
                        ],
                    ]) ?>
        </div> 
         
  </div>
</div>

<div class="row">
<div class="col-12">
    

    
    <div class="col-lg-3 col-sm-3"> 
        <br />
           

            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['onClick'=>'getinternalscorecard($("#intexam_year").val(), $("#intexam_month").val(),  $("#internal_number").val(), $("#stu_programme_selected").val(), $("#stu_section_select").val(),$("#subject2").val());','class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable-int/internalscorecard']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
</div>
</div>
 <?php ActiveForm::end(); ?>
    
<div class="row hide_ab_list">
    <div class="col-12">
        <div class="col-lg-12 col-sm-12">
            <?php 

            echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('internalscorecard-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
            
            echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('internalscorecard-excel'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff'));

            ?>
           <div class="show_ab_data">
           </div>

        </div>
    </div>

</div>


</div>
</div>
</div><!-- exam-timetable-absent -->