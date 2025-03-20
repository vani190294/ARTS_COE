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

$this->title= "Theory Valuation Claim Compare Report";
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
            <?= $form->field($model, 'exam_year')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'exam_year','min'=>4,'max'=>4,'value'=>date('Y'),'required'=>'required','autocomplete'=>"off"]); ?>
                     
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
        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($model,'valuation_session')->widget(
                Select2::classname(), [
                    'data' => ['1'=>'Valuator','2'=>'Scrutiny'],
                    'options' => [
                        'placeholder' => '-- Select --',   
                        'id'=>'report_type', 
                        'name'=>'report_type',          
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Report Option'); 
            ?>  
        </div>

        <?php if(Yii::$app->user->getId()==11 || Yii::$app->user->getId()==1) {?>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <br>
            <label><input type="checkbox" id="notpaid" name="notpaid" value="1">Paid Only</label>
        </div>
        <?php }?>
       
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <br />
        <div class="btn-group col-lg-4 col-sm-4" role="group" aria-label="Actions to be Perform">
                <?= Html::Button( 'Show' , ['onClick'=>'get_valuation_compare();','class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['coe-val-claim-amt/valuation-compare-report']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div><br /><br />
    <div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto !important;" id="claimpdf"> 

        <div class="col-xs-12 col-sm-12 col-lg-12">
    <?php  echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/coe-val-claim-amt/valuationcomparepdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
     echo Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/coe-val-claim-amt/valuationcompareexcel'], [
                            'class' => 'pull-right btn btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated EXCEL file in a new window'
                ]);
                ?>
    </div>                
    <div id="claim_data"></div>
    </div>
   

  
</div>
<?php ActiveForm::end(); ?>
</div>
</div>