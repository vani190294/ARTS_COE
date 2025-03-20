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

$user_id=Yii::$app->user->getId();

/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = "PRACTICAL MARK GRADE UPDATE";
$this->params['breadcrumbs'][] = $this->title;

 $this->registerJs("$(document).ready(function() { $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; } }); });");
?>
<h1><?php echo $this->title; ?></h1>

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
            <?= $form->field($model, 'year')->textInput(['value'=>2023,'id'=>'exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
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
         <div class="col-xs-12 col-sm-2 col-lg-2">
          
             <label class="control-label">Enter Subject Code</label>
           <input type="text" id="dummynumbers-subject_map_id" class="form-control" name="dummynumbers-subject_map_id" placeholder="Enter Subject Code" aria-required="true">
        </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
           <label class="control-label">Enter Semester</label>
           <input type="text" id="semester" class="form-control" name="semester" placeholder="Enter semester" aria-required="true">
        </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'student_map_id')->textInput(['placeholder'=>'Enter Reg.No.'  ,'onfocus'=>" if (this.value==this.defaultValue) this.value = '' " ,'autocomplete'=>"off",'onblur'=>'getpracticalmarkupdate(this.id,this.value,$("#exam_year").val(),$("#exam_month").val(),$("#dummynumbers-subject_map_id").val(),$("#semester").val())'])->label('Enter Register Number') ?>
             
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/barcode-marks-practical']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    <div id='hide_bar_code_data' class="row">
    <div  class="col-xs-12" style="text-align: center">
            
            <div class="col-xs-12" >
                <h2>
                <div id="show_scrutiny_entry_head">  </div></h2>
            </div>
             <div class="col-xs-12" >
                <h3>
                <div id="show_scrutiny_entry_master">  </div></h3>
            </div>
            <div class="col-xs-12">
                <div id="show_scrutiny_entry">  </div>
            </div>
        
    </div> <!-- Row Closed -->
    </div>

    <div><input type="hidden" id="markdesign"/></div>
</div>

<?php ActiveForm::end(); ?>


</div>

