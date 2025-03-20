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

 $this->registerJs("$(document).ready(function() { $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; } }); });");
/* @var $this yii\web\View */
/* @var $model app\models\AbsentEntry */
/* @var $form ActiveForm */
$this->title = "Bar Code Absent Entry";
$batch_id = isset($model->batch_id)?$model->batch_id:"";
$degree_batch_mapping_id = isset($model->degree_batch_mapping_id)?$model->degree_batch_mapping_id:"";

?>
<h1><?php echo "Hall Wise ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." Entry"; ?></h1>
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
                        'id'=>'exam_session',
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
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <h1 align="center" >Bar Code <b style="color: #F00;" >ABSENT </b> Starts here </h1>
    <div class="col-xs-12 col-sm-12 col-lg-12"  id="create_div_element">

        <div class="row create_stu_div_element">
            <div class='col-xs-12 col-sm-3 col-lg-3'>&nbsp; </div>
            <div class='col-xs-12 col-sm-6 col-lg-6'>  
                    <div class="col-sm-6 col-lg-6"> 
                        <?= $form->field($model, 'absent_student_reg[]')->passwordInput(['maxlength' => true,'autocomplete'=>"off",'class'=>'form-control','placeholder'=>'Place Cursor and Scan'  ,'onfocus'=>" if (this.value==this.defaultValue) this.value = ''; addNewRowsAb(this.id); " ,'onblur'=>'checkDupliCateNumAbsent(this.id,this.value); ','autocomplete'=>'off'])->label('Barcode Scan Here')  ?>
                    </div>                
                    <div class="col-sm-6 col-lg-6">  
                        <?= $form->field($model, 'created_at[]')->textInput(['autocomplete'=>"off",'class'=>'form-control', 
                        'autocomplete'=>"off",'required'=>'required', 'readonly'=>'readonly', 'value'=>'YES', 'name'=>"ese_marks[]"])->label('Absent Status')  ?>
                        <span id="stu_id" >&nbsp;</span>
                    </div>
            </div>   
            <div class='col-xs-12 col-sm-3 col-lg-3'>&nbsp; </div>         
        </div>

    </div> 
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-9 col-lg-9">
            &nbsp;
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">           
        <br />
        <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
            <?= Html::SubmitButton('Confirm' ,['data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once the values were Submitted.','class' => 'btn btn-success','target'=>'_blank' ]) ?>

            <?= Html::a("Reset", Url::toRoute(['exam-timetable/bar-code-absent']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>                
        </div>             
    </div>
    </div> 
</div>
</div>
<br /><br />


 <?php ActiveForm::end(); ?>

</div>
</div>
</div><!-- exam-timetable-absent -->

