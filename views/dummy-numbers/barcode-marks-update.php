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

$this->title = "SCRUTINY MARK ENTRY UPDATE";
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
            <?= $form->field($model, 'student_map_id')->passwordInput(['placeholder'=>'Place Cursor and Scan'  ,'onfocus'=>" if (this.value==this.defaultValue) this.value = '' " ,'autocomplete'=>"off",'onblur'=>'getscrutinyentry($("#exam_year").val(),$("#exam_month").val())'])->label('Scan Barcode Here') ?>
             
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/barcode-marks-update']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
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

