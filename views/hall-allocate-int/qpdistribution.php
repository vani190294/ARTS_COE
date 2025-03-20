<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
echo Dialog::widget();

$this->title = 'QP Distribution';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  

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
             <?php echo $form->field($exam, 'internal_number')->widget(
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



        
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-3 col-lg-3">
    <input type="button" class="btn btn-success" name="qpsubmit1" value="Submit" id="qpsubmit1">
    <?= Html::a("Reset", Url::toRoute(['hall-allocate-int/qpdistribution']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>
    <div  id="qp_tbl_1" class="col-xs-12 col-sm-12 col-lg-12"> 
        <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: right;">

            <?php
                
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excelprint','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                ?>
                &nbsp;
                <?php 
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/hall-allocate-int/print-qp-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>                   
        </div>      
        
    </div>
    <div  id="qp_tbl" class="col-xs-12 col-sm-12 col-lg-12">        
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
