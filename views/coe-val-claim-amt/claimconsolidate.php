<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\DummyNumbers;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use kartik\date\DatePicker;
echo Dialog::widget();

$this->title= "Consolidate Claim From Indiviual";
?>

<div class="exam-timetable-form">
    <h1><?php echo $this->title; ?></h1>
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 

$form = ActiveForm::begin(); ?>
<div>&nbsp;</div>
<div class="row">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 

        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <br>
            <label><input type="checkbox" id="notpaid" name="notpaid" onclick="hidedates();">Paid Only</label>
        </div>
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_year')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'cc_exam_year','min'=>4,'max'=>4,'value'=>date('Y'),'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'exam_month')->widget(
                Select2::classname(), [  
                    'data' => ConfigUtilities::getMonth(),                      
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => '-----Select Month ----',
                        'id' => 'cc_exam_month', 
                    ],
                   'pluginOptions' => [
                       'allowClear' => true,
                    ],
                ]) ?>
                     
        </div> 


         <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'claim_type')->widget(
                Select2::classname(), [ 
                      
                    'data' => ['1'=>'Practical','2'=>'Theory Valuation','3'=>'Theory ReValuation','6'=>'Theory Valuation Scrutiny','5'=>'QP Setting','4'=>'QP Scrutiny','11'=>'Chief Examiner'],                      
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => '-----Select Month ----',
                        'id' => 'claim_type',                              
                    ],
                   'pluginOptions' => [
                       'allowClear' => true,
                    ],
                ]) ?>
                     
        </div> 

       <div class="col-xs-12 col-sm-2 col-lg-2" id="from_claim_date1">
            <?php 
            echo $form->field($model,'claim_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date----', 
                        'id' => 'from_claim_date',  
                        'name' => 'from_claim_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ])->label('Claim From Date'); 
            ?>       
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2" id="to_claim_date1">
            <?php 
            echo $form->field($model,'claim_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date----', 
                        'id' => 'to_claim_date',  
                        'name' => 'to_claim_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ])->label('Claim To Date'); 
            ?>       
        </div>

         <div class="col-xs-12 col-sm-2 col-lg-2" id="abstractdate" style="display: none;">
            <?php 
            echo $form->field($model,'claim_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select----', 
                        'id' => 'claim_date',  
                        'name' => 'claim_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ])->label('Claim Date'); 
            ?>       
        </div>

       
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <br />
        <div class="btn-group col-lg-4 col-sm-4" role="group" aria-label="Actions to be Perform">
                <?= Html::Button( 'Show' , ['onClick'=>'get_claimconsolidate();','class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['coe-val-claim-amt/claimconsolidate']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div><br /><br />
    <div class="col-xs-12 col-sm-12 col-lg-12" id="claimpdf"> 
    <?php  echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/coe-val-claim-amt/cclaimpdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
    
                ?>
    </div>
     <div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x: auto;">
        <div id="claim_data" style="display: none;">
            
        </div>
    </div>
  

  
</div>
<?php ActiveForm::end(); ?>
</div>
</div>