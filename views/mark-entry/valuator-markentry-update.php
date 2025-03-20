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

$this->title = "EVALUATOR MARK ENTRY UPDATE";
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
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'student_map_id')->textInput(['placeholder'=>'Enter Register Number', 'id' => 'dummy_number'])->label('Enter Register Number') ?>
             
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::Button('Show' , ['id' =>'eval_dummy', 'class' => 'btn btn-primary','onClick'=>'getvaluatorentryupdate($("#exam_year").val(),$("#exam_month").val(),$("#dummy_number").val());']) ?>
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/valuator-markentry-update']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    
    <div>
</div>

 <div  class="col-xs-12" id='valuator_entry' style="display: none;">
                
        <div  class="col-xs-12" style="padding-top:10px;"> 
            <div  class="col-xs-2"></div>
            <div  class="col-xs-4" style="text-align:right; "> 
                 <div  class="col-sm-12" id="parttotal"> <br>
                     <label class="control-label" style="text-align:right">Mark: </label>
                     <input type="text" class="control-label" id="valuator_entrymark" placeholder="Mark" required/>
                </div>
            </div>
            <div class="col-xs-4" style="padding-top:10px; display: none !important;" id='savevaluatorentryid'> 
                <div  class="col-xs-8"> 
                    <?= Html::Button('Save',['class' => 'btn btn-success', 'onClick'=>'savevaluatorentryupdate()']) ?>
                </div>
                <div  class="col-xs-2"></div>
            </div>
        </div>

    

</div>

<?php ActiveForm::end(); ?>


</div>

