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

$this->title= "THEORY VALUATION SCRUTINY CLAIM";
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
            <?= $form->field($model, 'exam_year')->textInput(['onkeypress'=>'numbersOnly(event)','name'=>'exam_year','id'=>'exam_year','min'=>4,'max'=>4,'value'=>date('Y'),'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
        <?= $form->field($model, 'exam_month')->widget(
                Select2::classname(), [  
                    'data' => ConfigUtilities::getMonth(),                      
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => '-----Select Month ----',
                        'id' => 'tvscru_exam_month',
                        'name' => 'tvscru_exam_month',       
                    ],
                   'pluginOptions' => [
                       'allowClear' => true,
                    ],
                ]) ?>
                     
        </div>         

    <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
        echo $form->field($model,'scrutiny_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date----', 
                        'id' => 'scrutiny_date',  
                        'name' => 'scrutiny_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>       
        </div>

       
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <br />
        <div class="btn-group col-lg-4 col-sm-4" role="group" aria-label="Actions to be Perform" id="showclaim">
                <?= Html::Button( 'Show' , ['onClick'=>"gettheoryscrutinyclaim();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['coe-val-claim-amt/theory-scrutinyclaim']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div><br /><br />
    <div class="col-xs-12 col-sm-12 col-lg-12" id="claimpdf" style="display: none"> 
         <?php  echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/coe-val-claim-amt/revaluationclaimpdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]); ?>

    <div id="claim_data">
        
    </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>