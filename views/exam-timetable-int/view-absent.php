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
<h1><?php echo "View ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT); ?></h1>
<div class="exam-timetable-absent">
<div class="box box-success">
<div class="box-body">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?php $form = ActiveForm::begin(['options'=>['id'=>'view-ab-form']]); ?>

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

    <div class="col-lg-2 col-sm-2 exam_wise">
        <input type="hidden" id='exam_year' name="year" value="">
        <?php 
        $exam_year = isset($model->year)?$_POST['year']:date("Y");

        echo $form->field($model,'exam_date')->widget(
                Select2::classname(), [
                    'data' => $model->getExamDates($exam_year),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date----',  
                        'onchange'=>'showSessions(this.value, $("#hallallocate-year").val(), $("#exam_month").val());',
                        'id'=>'exam_date',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
    
    <div class="col-lg-2 col-sm-2 exam_wise">

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

    <div class="col-xs-12 col-lg-2 col-sm-2">
             <?php echo $form->field($examTimetable, 'internal_number')->widget(
            Select2::classname(), [
            'data' =>ConfigUtilities::internalNumbers(),
            'options' => [
                'placeholder' => '-----Select----',
                'class'=>'form-control',
                'id' => 'internal_number',
            ],
            ])
        ?>
        </div>
        
  </div>
</div>

<div class="row">
<div class="col-12">
    
    <div class="col-lg-3 col-sm-3"> 
        <br />
           

            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['onClick'=>'showAbdata($("#hallallocate-year").val(), $("#exam_month").val());','class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable/view-absent']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
</div>
</div>
 <?php ActiveForm::end(); ?>
    
<div class="row hide_ab_list">
    <div class="col-12">
        <div class="col-lg-12 col-sm-12">
            <?php 

            echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('view-ab-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
            
            echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excel-view-ab','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff'));

            ?>
           <table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                    <thead class="thead-inverse">
                    <tr class="table-danger">
                        <th>SNO</th>
                        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?></th>
                        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?></th>
                        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Type"; ?></th>
                        <th>Register Number</th>
                        <th>Name</th>
                        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code"; ?></th>
                        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Name"; ?></th>
                        <th>Semester</th>

                    </tr>               
                    </thead> 
                    <tbody class="show_ab_data">

                    </tbody>
                </table>

        </div>
    </div>

</div>


</div>
</div>
</div><!-- exam-timetable-absent -->