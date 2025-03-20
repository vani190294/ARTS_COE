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
use app\models\ValuationFacultyAllocate;
echo Dialog::widget();

$this->title= "Abstraction For Claim";
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
            <label><input type="checkbox" id="paid" name="paid">Paid Only</label>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_year')->textInput(['onkeypress'=>'numbersOnly(event)','name'=>'exam_year','id'=>'exam_year','min'=>4,'max'=>4,'value'=>date('Y'),'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'exam_month')->widget(
                Select2::classname(), [  
                    'data' => ConfigUtilities::getMonth(),                      
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => '-----Select Month ----',
                        'id' => 'ab_exam_month',
                        'name' => 'ab_exam_month',       
                    ],
                   'pluginOptions' => [
                       'allowClear' => true,
                    ],
                ]) ?>
                     
        </div>         

        <div class="col-xs-12 col-sm-2 col-lg-2">
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
    <div class="col-xs-12 col-sm-12 col-lg-12" id="showclaim1"> 
        <br />
        <div class="btn-group col-lg-4 col-sm-4" role="group" aria-label="Actions to be Perform">
                <?= Html::Button( 'Show' , ['onClick'=>"getclaimabstract();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['coe-val-claim-amt/consolidate-abstract-claim']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div><br /><br />
    <div class="col-xs-12 col-sm-12 col-lg-12" id="claimpdf" style="display: none"> 
    <div class="col-xs-12 col-sm-12 col-lg-12" id="claimpdf1">
         <?php  echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/coe-val-claim-amt/abstractclaimpdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
                
                 ?>
            <?php /* echo Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/coe-val-claim-amt/abstractexcel'], [
                    'class'=>'pull-right btn btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated Excel file in a new window'
                    ]);*/
            ?>
        
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <br>
        <div id="claim_data" style="overflow-x: auto;">
            
        </div>
    </div>
    </div>

     <div class="col-xs-12 col-sm-12 col-lg-12" id="saveclaim"  style="display: none"> 
        <br />
        <div class="col-xs-4 col-sm-4 col-lg-4"></div>
        <div class="col-xs-4 col-sm-4 col-lg-4">
             <label>
                Payment Release Date
            </label>
            <input type="date" id="paid_date" name="paid_date">
        </div>
        <div class="btn-group col-lg-4 col-sm-4" role="group" aria-label="Actions to be Perform">
                <?= Html::SubmitButton( 'Save' , ['onClick'=>'spinner();','class' => 'btn btn-success' ]) ?>

                <?= Html::a("Cancel", Url::toRoute(['coe-val-claim-amt/consolidate-abstract-claim']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div>


</div>
<?php ActiveForm::end(); ?>
</div>
</div>