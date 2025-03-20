<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\MarkEntry;
use app\models\HallAllocate;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="With Held Report";

?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
        ]); 
    ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year']) ?>
        </div>


        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'month')->widget(
                Select2::classname(), [
                    'data' => HallAllocate::getMonth(),
                    'options' => [
                        'placeholder' => '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ---',
                        'id' => 'exam_month',
                        'class'=>'student_disable',
                        'name'=>'month',
                        'onchange'=>'getmaldate();',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>

          <div class="col-lg-2 col-sm-2 exam_wise">
         <?php 
       
        echo $form->field($exam_model,'exam_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date----',  
                        'onchange'=>'getmalsession();',
                        'id' => 'exam_date1',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
    
    <div class="col-lg-2 col-sm-2 exam_wise">

        <?php echo $form->field($exam_model,'exam_session')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' ----',  
                        'id' => 'exam_session',                     
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
    </div> 
    
        <div class="form-group col-lg-2 col-sm-2"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Show', ['value'=>'report','name'=>"withheld_report" ,'id'=>"withheld_report",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['mark-entry/withheldreport']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>            
        </div>
    </div>
    

<?php ActiveForm::end(); ?>


<div class="col-xs-12 col-sm-12 col-lg-12 tbl_n_submit_withheld">

        <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                 echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/mark-entry/malpracticepdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
    </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">                
            <div id = "stu_withheld_tbl"></div>
        </div>
    </div>

</div>
</div>
</div>