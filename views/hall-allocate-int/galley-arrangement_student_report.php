<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\ExamTimetable;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\HallAllocate */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'INTERNAL EXAM GALLEY ARRANGEMENT STUDENT REPORT';

?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php  $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  
    <div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12">                
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id' => 'intexam_year', ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'intexam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($exam, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'exam_date',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($exam, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Session ----', 
                            'id' => 'intexam_session',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]); ?>
        </div>
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($exam,'time_slot')->widget(
                Select2::classname(), [
                    //'data' => $exam->Examtimeslot,
                    'options' => [
                        'placeholder' => '-----Select ----',
                        'id' => 'time_slot',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>
         <div class="col-xs-12 col-lg-2 col-sm-2">
             <?php echo $form->field($model, 'internal_number')->widget(
            Select2::classname(), [
            'data' =>ConfigUtilities::internalNumbers(),
            'options' => [
                'placeholder' => '-----Select Internal Number ----',
            ],
            'options' => [
                'placeholder' => '-----Select----',
                'class'=>'form-control',
                'id' => 'internal_number',
            ],
            ]); 
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-lg-4"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Print' ,['class' => 'btn btn-group btn-group-lg btn-success','formtarget'=>'_blank']) ?>  
                
                <?=Html::a("Cancel", Url::toRoute(['hall-allocate-int/galley-arrangementstudentreport']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>
        </div>
    </div>
</div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>