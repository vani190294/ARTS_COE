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

$this->title = "Scrutiny Mark Entry Details";
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
         <?php if($item_name=='ValuatorAccess'){

            echo $form->field($model, 'year')->hiddenInput(['id'=>'barcode_exam_year','name'=>'barcode_exam_year','value'=> $ValuationSettings['current_exam_year']])->label(false);
             echo $form->field($model, 'month')->hiddenInput([ 'id' => 'barcode_exam_month','name'=>'barcode_exam_month','value'=> $ValuationSettings['current_exam_month']])->label(false);
            ?>
                <!--input type="hidden" id="barcode_exam_year" name="barcode_exam_year" value="<?= $ValuationSettings['current_exam_year'];?>">
                 <input type="hidden" id="barcode_exam_month" name="barcode_exam_month" value="<?= $ValuationSettings['current_exam_month'];?>"-->
            <?php }else{?>
         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'barcode_exam_year','name'=>'barcode_exam_year']) ?>

        </div>
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'barcode_exam_month',  
                            'name'=>'barcode_exam_month'
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <?php }?>
        <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 
                echo $form->field($factallModel,'valuation_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select valuation Date----',
                        'id' => 'barcode_valuation_date',         
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
                        'placeholder' => '-----Select valuation session----',
                        'id' => 'barcode_valuation_session',         
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
                        'id'=>'barcode_val_faculty_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div> 

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($factallModel, 'subject_pack_i')->textInput(['placeholder' => 'Enter Packet No. (1/2)','id'=>'barcode_packet_number','autocomplete'=>"off",])->label('Enter packet number');  ?>
        </div>
        
         <div><input type="hidden" name="val_faculty_all_id" id="val_faculty_all_id"/></div>
         <div id="total_script"></div>
          <div><input type="hidden" id="markentry_count" value="0"/></div>
        
    </div>

  
</div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12" id="barcode_markentry">
        <h1 align="center" >Bar Code Mark Entry Starts here </h1>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-2 col-sm-2 col-lg-2"></div>
           <div class="col-xs-8 col-sm-8 col-lg-8" id="create_div_element1"></div>
            <div class="col-xs-2 col-sm-2 col-lg-2"></div>
        </div> 
   
</div>
</div>
<div><input type="hidden" name="barcode_exam_year1"/></div>
  <div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-xs-5 col-sm-5 col-lg-5"></div>
            <div class="btn-group col-lg-7 col-sm-7" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Show',['class' => 'btn btn-success','id'=>'show_scrutiny_details']) ?>

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

