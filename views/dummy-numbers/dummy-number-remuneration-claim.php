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

$this->title= "REMUNEARATION CLAIM REPORT";
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
            <?= $form->field($model, 'exam_year')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'dum_year','min'=>4,'max'=>4,'value'=>date('Y'),'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'exam_month')->widget(
                Select2::classname(), [  
                    'data' => ConfigUtilities::getMonth(),                      
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => '-----Select Month ----',
                        'id' => 'dum_exam_month',       
                    ],
                   'pluginOptions' => [
                       'allowClear' => true,
                    ],
                ]) ?>
                     
        </div> 

        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($model,'valuation_session')->widget(
                Select2::classname(), [
                    'data' => ['entry'=>'Entry or Single Date Report','report'=>'Date Range Report'],
                    'options' => [
                        'placeholder' => '-- Select Type --',   
                        'id'=>'claimreporttype',          
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Report type'); 
            ?>  
        </div>

         <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($model,'valuation_session')->widget(
                Select2::classname(), [
                    'data' => ['Valuator'=>'Valuator','Scrutiny'=>'Scrutiny'],
                    'options' => [
                        'placeholder' => '-- Select --',   
                        'id'=>'selectclaimreport', 
                        'name'=>'report_type',          
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Report Option'); 
            ?>  
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2" id="idvalfacultydate">
            <?php echo $form->field($model,'valuation_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date----',      
                        'id'=>'val_faculty_date',    
                        'name'=>'val_faculty_date',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div>


        <div class="col-xs-12 col-sm-2 col-lg-2" id="idscrutinydate">
             <?php echo $form->field($model,'scrutiny_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date----',      
                        'id'=>'scrutiny_date',
                        'name'=>'scrutiny_date',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?> 
        </div> 

         <div class="col-xs-12 col-sm-2 col-lg-2" id="valfdate" style="display: none;">
            <?php echo $form->field($model,'valuation_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select FromDate----',      
                        'id'=>'val_from_date',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('From Date'); 
            ?>  
        </div>
         <div class="col-xs-12 col-sm-2 col-lg-2" id="valtdate" style="display: none;">
            <?php echo $form->field($model,'valuation_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select ToDate----',      
                        'id'=>'val_to_date',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('To Date'); 
            ?>  
        </div>

         <div class="col-xs-12 col-sm-2 col-lg-2" id="sfdate" style="display: none;">
            <?php echo $form->field($model,'valuation_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select FromDate----',      
                        'id'=>'s_from_date',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('From Date'); 
            ?>  
        </div>
         <div class="col-xs-12 col-sm-2 col-lg-2" id="stdate" style="display: none;">
            <?php echo $form->field($model,'valuation_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select ToDate----',      
                        'id'=>'s_to_date',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('To Date'); 
            ?>  
        </div>
        

       
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <br />
        <div class="btn-group col-lg-4 col-sm-4" role="group" aria-label="Actions to be Perform" id="showclaim">
                <?= Html::Button( 'Show' , ['onClick'=>'get_dummy_renum_claim();','class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/dummy-number-remuneration-claim']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div><br /><br />
    <div class="col-xs-12 col-sm-12 col-lg-12" id="claimpdf"> 
    <?php  echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/dummy-numbers/printclaimpdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
     echo Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/dummy-numbers/printclaim-excel'], [
                            'class' => 'pull-right btn btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated EXCEL file in a new window'
                ]);
                ?>
    </div>
    <div id="hide_dum_repo_data">
        
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <br />
        <div class="col-xs-8 col-sm-8 col-lg-8"></div>
        <div class="btn-group col-lg-4 col-sm-4" role="group" aria-label="Actions to be Perform" id="submitclaim">
                <?= Html::SubmitButton( 'Save' , ['onClick'=>'spinner();','class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/dummy-number-remuneration-claim']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>