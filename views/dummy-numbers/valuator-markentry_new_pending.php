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

$this->title = "Valuator Bar Code Mark Entry";
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
         
           <?= $form->field($model, 'year')->hiddenInput(['id'=>'exam_year','name'=>'exam_year','value'=> $ValuationSettings['current_exam_year']])->label(false); ?>

            <?= $form->field($model, 'month')->hiddenInput(['id'=>'exam_month','name'=>'exam_month','value'=> $ValuationSettings['current_exam_month']])->label(false); ?>

         <?php }else{?>


                <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year','name'=>'exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month', 
                            'name'=>'exam_month',
                            'onchange'=>'valuator_exam_month();'                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>


             <?php }?>


        <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Faculty----',      
                        'id'=>'v_val_faculty_all_id',   
                        'name'=>'val_facl_id',
                        'onchange'=>'checkvaluateentry();'                
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label("Assigned Packets");  
            ?>  
        </div> 

         
        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/valuator-markentry']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    
 </div> <!-- Row Closed -->

        <div class="row"  id='hide_bar_code_data1'>
           

            <div  class="col-xs-12" id='valuator_entry'>
                

            </div>

           

       </div>
   


</div>
</div>

<?php ActiveForm::end(); ?>


</div>

