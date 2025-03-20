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

use app\models\ValuationSettings;
$ValuationSettings = ValuationSettings::findOne(1);

$userid=Yii::$app->user->getId();

$item_name = Yii::$app->db->createCommand("SELECT item_name FROM auth_assignment WHERE user_id='" . $userid . "'")->queryScalar();


/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = "VALUATOR MARK ENTRY REPORT ADMIN";
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

         <?php if($item_name=='ValuatorAccess'){?>
         
           <?= $form->field($model, 'year')->hiddenInput(['id'=>'exam_year','value'=> $ValuationSettings['current_exam_year']])->label(false); ?>

            <?= $form->field($model, 'month')->hiddenInput(['id'=>'exam_month','value'=> $ValuationSettings['current_exam_month']])->label(false); ?>

         <?php }else{?>

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
                            'onchange'=>'valuator_exam_month1();'                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>


         <?php }?>

        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($mark_entry,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select Batch ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('Batch'); 
                ?>
        </div>

         
        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <input type="button" id="student_res_export" onclick="getvaluatoradminreport();" class="btn btn-success" value="Submit">
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/valuator-markentryreport']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    
 </div> <!-- Row Closed -->

   <div  class="col-xs-12" style="overflow-x: auto;" id="verification_details_data">
        <div class="col-xs-12 col-sm-12 col-lg-12" style="padding-bottom:20px;" id="verification_details_pdf">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
               
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/dummy-numbers/verifydetails-pdf2'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);

                 echo Html::a('<i class="fa fa-file-pdf-o"></i> Excel', ['/dummy-numbers/verifydetails-excel'], [
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

