<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use app\models\HallAllocate;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)." Mark Entry";
$this->params['breadcrumbs'][] = $this->title;


?>
<h1><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)." Mark Entry"; ?></h1>

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
         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',    
                            'onchange' => 'bringYearMonthSubs(this.value,$("#exam_year").val());',                        
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 

             echo $form->field($model,'subject_map_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----',
                        'id' => 'dummy_exam_subject_code',
                        'name'=>'exam_subject_code',
                        'onchange' => 'get_numbers_info(this.value,$("#exam_year").val(), $("#exam_month").val());getExaminerName(this.value,$("#exam_year").val(), $("#exam_month").val())',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);


            ?>
        </div>
      
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'start_number')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'start_number','min'=>4,'autocomplete'=>'off','onchange'=>'getAllnumbersLimit(this.value,$("#exam_year").val(), $("#exam_month").val());','max'=>10,'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'end_number')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'end_number','min'=>4,'max'=>10,'autocomplete'=>'off','required'=>'required','readonly'=>'readonly']); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'limit')->textInput(['onkeypress'=>'numbersOnly(event)','autocomplete'=>"off",'id'=>'limit','min'=>4,'max'=>10,'value'=>30,'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div>       
         </div>
         <div class="col-xs-12 col-sm-12 col-lg-12"> 
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'examiner_name')->textInput(['required'=>'required','autocomplete'=>"off",'id'=>'examiner_name'])->label('Examiner Name'); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'chief_examiner_name')->textInput(['required'=>'required','id'=>'chief_examiner_name'])->label('Chief Examiner Name'); ?>
                     
        </div>       
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
                            <th><?php echo strtoupper("Min Pass"); ?></th>
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
                <?= Html::Button('Show' ,['onClick'=>"get_students_info($('#exam_year').val(), $('#exam_month').val());",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/dummy-number-entry']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>           

            
        </div>
        

       
    </div>
    <div id='hide_dum_data' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-1"> &nbsp; </div>
            <div class="col-xs-10">
        <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
            <thead class="thead-inverse">
            <tr class="table-danger">
                
                <th>SNO</th>                
                <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)); ?></th>
                <th><?php echo "Marks"; ?></th>
            </tr>               
            </thead> 
            <tbody id="show_dummy_numbers">     

            </tbody>
        </table>
        <?= Html::submitButton('Submit' , ['id' =>'submit_dummy', 'class' => 'btn btn-primary','data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once the values were Submitted.','formtarget'=>"_blank"]) ?>
    </div>
    <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div>



<?php ActiveForm::end(); ?>

</div>
</div>
</div>