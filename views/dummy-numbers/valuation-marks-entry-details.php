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

$this->title = "Valuation mark entry Details";
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
                ])->label("Date"); 
            ?>      
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
              <?php 
                echo $form->field($factallModel,'scrutiny_session')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Scrutiny session----',
                        'id' => 'barcodeverify_scrutiny_session1',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ])->label("Session"); 
            ?>   
                     
        </div>

        <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Faculty----',      
                        'id'=>'view_val_faculty_all_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label("Assigned Faculty");  
            ?>  
        </div> 

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <br>         
            <label>
            <input type="checkbox" id="viewsplitup" name="vehicle1" value="1">Click View Split Up Mark</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
             <?= Html::Button('Show',['class' => 'btn btn-success','id'=>'show_markdetails']) ?>
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/barcode-marks-verify']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    
 </div> <!-- Row Closed -->

    <div  class="col-xs-12" style="overflow-x: auto; display: none;" id="verification_details_data">
        <div class="col-xs-12 col-sm-12 col-lg-12" style="padding-bottom:20px;" id="verification_details_pdf">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
               
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/dummy-numbers/verifydetails-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
        </div>
                <div id='verification_details'></div>
    </div>


</div>
</div>

<?php ActiveForm::end(); ?>


</div>

