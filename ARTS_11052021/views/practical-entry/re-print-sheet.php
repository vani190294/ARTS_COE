<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\HallAllocate;
use app\models\ExamTimetable;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title ="Re Print Practical Mark Entry";
$this->params['breadcrumbs'][] = $this->title;


?>
<h1><?php echo "Re Print Practical Mark Entry"; ?></h1>

<div>&nbsp;</div>
<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 

$form = ActiveForm::begin(); ?>
<div>&nbsp;</div>
<div class="row">
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
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
         </div>
           
         <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),  
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'onchange' => 'getSubjectInfoPracGET($("#stu_batch_id_selected").val(), $("#stu_programme_selected").val(), this.id,this.value);',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
         </div>  
        
        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',
                            'name'=>'sub_val',
                            'onchange' => 'getExaminerNamesRePrint(this.value);',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'examiner_name')->textInput(['name'=>'register_num_from','required'=>'required','id'=>'register_num_from','onBLur'=>'getStudentInfoPracts(this.value); '])->label('Reg Num From'); ?>
                     
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'examiner_name')->textInput(['required'=>'required','id'=>'examiner_name'])->label('Examiner Name'); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'chief_exam_name')->textInput(['required'=>'required','id'=>'chief_exam_name'])->label('Chief Examiner Name'); ?>
        </div> 
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12">
              
    </div>
    <div id='hide_dum_sub_data' class="row">
    <div  class="col-xs-12"> <br /><br />
        <div class="col-xs-1"> &nbsp; </div>
            <div class="col-xs-10">
                <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
                    <thead class="thead-inverse">
                        <tr class="table-danger">
                            <th>SNO</th>
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE"); ?></th>
                            
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME"); ?></th>
                            <th><?php echo strtoupper("Minimum"); ?></th>
                            <th><?php echo strtoupper("Maximum"); ?></th>
                        </tr>               
                    </thead> 
                    <tbody id="show_dummy_entry">     

                    </tbody>
                </table> 
            </div>
        <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div> <!-- Row Closed -->
</div>
</div>

<div class="row">
    
   <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-6 col-lg-6">            
            <br />
            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                <?= Html::a("Reset", Url::toRoute(['practical-entry/re-print-sheet']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                
            </div>             
        </div>
    </div>
    <div id='hide_dum_data_send' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-2"> <?php echo $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/practical-entry/reprint-sheet-practical-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                echo "<br />";
                echo  $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/practical-entry/excel-reprint-practical-sheet'], [
                    'class'=>'pull-right btn btn-block btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                ?> </div>
            <div class="col-xs-10" id="pract_show_dummy_numbers">

            </div>
        </div>
    </div>


<?php ActiveForm::end(); ?>

</div>
</div>
</div>