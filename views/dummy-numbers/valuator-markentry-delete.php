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

$this->title = "VALUATOR MARK ENTRY DELETE";
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
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

         <?php }?>

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'student_map_id')->passwordInput(['placeholder'=>'Place Cursor and Scan'  ,'onfocus'=>" if (this.value==this.defaultValue) this.value = '' " ,'autocomplete'=>"off",'onblur'=>'getvaluatorentrydelete(this.id,this.value,$("#exam_year").val(),$("#exam_month").val())'])->label('Scan Barcode Here') ?>
             
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <?= Html::a("Reset", Url::toRoute(['dummy-numbers/valuator-markentry-delete']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        
    </div>
    
    <div>
</div>

<?php ActiveForm::end(); ?>


</div>

