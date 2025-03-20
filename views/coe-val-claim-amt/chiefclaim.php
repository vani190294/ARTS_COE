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

$this->title= "CHIEF EXAMINER CLAIM";
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
                        'id' => 'chief_month1',
                        'name' => 'chief_month1',       
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
                        'placeholder' => '-----Select Date----', 
                        'id' => 'claim_date',  
                        'name' => 'claim_date',         
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
                <?= Html::Button( 'Show' , ['onClick'=>"get_chiefclaimreport();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['coe-val-claim-amt/chiefclaim']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>        
    </div><br /><br />
    <div class="col-xs-12 col-sm-12 col-lg-12" id="claimpdf" style="display: none"> 
        

         <div id="claim_data"></div>
    </div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>