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
$this->title= "Delete ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." Internal Exam";
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
            ])
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

    

         
  </div>
</div>

<div class="row">
<div class="col-12">
    
    <div class="col-lg-3 col-sm-3"> 
        <br />
           

            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['onClick'=>'showAbdatadelete1( $("#intexam_year").val(), $("#intexam_month").val(),$("#exam_date").val(), $("#intexam_session").val(), $("#internal_number").val());','class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['exam-timetable-int/delete-absent']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>

            
    </div>
</div>
</div>
 <?php ActiveForm::end(); ?>
    
<div class="row hide_ab_list_del">
    <div class="col-12">
        <div class="col-lg-12 col-sm-12">
           
           <table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                    <thead class="thead-inverse">
                    <tr class="table-danger">
                        <th>SNO</th>
                        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?></th>
                        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?></th>
                        
                        <th>Register Number</th>
                        <th>Name</th>
                        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code"; ?></th>
                        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Name"; ?></th>
                        <th>Semester</th>
                        <th>Action</th>
                        

                    </tr>               
                    </thead> 
                    <tbody class="show_ab_data_del">

                    </tbody>
                </table>

        </div>
    </div>

</div>


</div>
</div>
</div><!-- exam-timetable-absent -->