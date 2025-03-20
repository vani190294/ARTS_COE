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

$this->title = "Valutaion Marks Entry";
$this->params['breadcrumbs'][] = $this->title;

 $this->registerJs("$(document).ready(function() { $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; } }); });");
?>
<style> 
.dummynumber{border: 1px solid #000;}
.table1{border: 1px solid #000;}
.table1 th{border: 1px solid #000;}

</style>
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
         
          <div class="col-xs-12 col-sm-1 col-lg-1">
           <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year','name'=>'exam_year']); ?>

         </div>

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'barcodeverify_exam_month', 
                            'name' => 'barcodeverify_exam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
            </div>
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 
                echo $form->field($factallModel,'scrutiny_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Scrutiny Date----',
                        'id' => 'barcodeverify_scrutiny_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>      
                     
        </div> 
      

        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($factallModel,'coe_scrutiny_id')->widget(
                Select2::classname(), [  
                    'options' => [
                        'placeholder' => '-----Select Scrutiny----',      
                        'id'=>'barcode_scrutiny_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div> 

        <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Faculty----',      
                        'id'=>'barcodeverify_val_faculty_all_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label("Assigned Faculty");  
            ?>  
        </div> 

        <div class="col-xs-12 col-sm-2 col-lg-2"><br>
        <input type="checkbox" id="ug_spl" name="ug_spl">
        <label for="ug_spl">Design Subject</label><br>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12" id="show_reset"> 
         
        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::Button('Show' ,['onClick'=>"getVCerifyBarMarks1();",'class' => 'btn btn-success' ]) ?>
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/barcode-marks-uverify']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    
 </div> <!-- Row Closed -->

    <div class="col-xs-12 col-sm-12 col-lg-12"> 
    <div id='hide_bar_code_data1' class="row" ><br>
        <div  class="col-xs-12" style="paddding-top:20px;overflow: scroll;
  position: relative;">
                    <div id="veify_details_ug"> </div>
        </div>
        <div  class="col-xs-12" style="text-align:right; padding-top:10px;"> 
            
                <?= Html::Button('Save',['class' => 'btn btn-success','id'=>'verify_barcode1']) ?>
            
        </div>
    </div>
   </div>

   


</div>
</div>
<input type="hidden" id="val_barecode_id"/>
<input type="hidden" id="markentry_design_type"/>

<input type="hidden" id="mark-insert-success"/>

<input type="hidden" id="mark-insert-error"/>
<input type="hidden" id="mark-insert-empty_dum_no"/>

<?php ActiveForm::end(); ?>


</div>

