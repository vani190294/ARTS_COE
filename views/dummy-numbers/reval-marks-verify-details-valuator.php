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

$item_name = Yii::$app->db->createCommand("SELECT item_name FROM auth_assignment WHERE  user_id='" . $userid . "'")->queryScalar();

/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Revalution Mark Entry Details";
$this->params['breadcrumbs'][] = $this->title;

 $this->registerJs("$(document).ready(function() { $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; } }); });");
?>

<div>&nbsp;</div>
<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 

$form = ActiveForm::begin(); ?>
<div>&nbsp;</div>
 <?php if(empty($verify_stu_data))
{?>
    <h1><?php echo $this->title; ?></h1>

<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
         <?php //if($item_name=='ValuatorAccess'){

            echo $form->field($model, 'year')->hiddenInput(['id'=>'revall_exam_year','value'=> $ValuationSettings['current_exam_year']])->label(false);
             echo $form->field($model, 'month')->hiddenInput([ 'id' => 'revall_exam_month','value'=> $ValuationSettings['current_exam_month']])->label(false);
            ?>
                <!--input type="hidden" id="barcode_exam_year" name="barcode_exam_year" value="<?= $ValuationSettings['current_exam_year'];?>">
                 <input type="hidden" id="barcode_exam_month" name="barcode_exam_month" value="<?= $ValuationSettings['current_exam_month'];?>"-->
            <?php //}else{?>
        <?php //}?>
        <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 
                echo $form->field($factallModel,'valuation_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date----',
                        'id' => 'revall_valuation_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>      
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
              <?php 
                echo $form->field($factallModel,'valuation_session')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select session----',
                        'id' => 'revall_valuation_session',         
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
                        'placeholder' => '-----Select----',      
                        'id'=>'revall_faculty_all_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label("Valution Subject");
            ?>  
        </div> 

    </div>

  
</div>
</div>

<div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-xs-5 col-sm-5 col-lg-5"></div>
            <div class="btn-group col-lg-7 col-sm-7" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Show',['class' => 'btn btn-success','id'=>'show_revalscrutiny_details']) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/barcode-marks-verify-details-valuator']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>    
            
        </div> 
    </div>

<?php }?>
  <div  class="col-xs-12" style="overflow-x: auto; display: none;" id="scrutiny_details_data">
        <div class="col-xs-12 col-sm-12 col-lg-12" style="padding-bottom:20px;display: none;" id="scrutiny_details_pdf">
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
                <div id='scrutiny_details'></div>
    </div>

<?php ActiveForm::end(); ?>


</div>

