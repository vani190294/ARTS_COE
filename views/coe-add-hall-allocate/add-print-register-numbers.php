<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use yii\grid\GridView;
use yii\helpers\Url;
echo Dialog::widget();
$this->title = 'Print Register Numbers';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  

    <div class="col-xs-12 col-sm-12 col-lg-12">   
         <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'coe_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'ans_batch_id',
                            'name'=>'ans_batch_id',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
        </div>             
       <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_year')->textInput(['value'=>date('Y'),'id'=>'ans_exam_year','name'=>'ans_exam_year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'exam_month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                       'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'ans_exam_month1',    
                            'name'=>'ans_exam_month'                        
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
      <div class="col-lg-2 col-sm-2">
       
        <?php 
        echo $form->field($model,'exam_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date----',  
                        'id' => 'ans_exam_date1',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($model,'exam_session')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Session ----',   
                        'id' => 'ans_exam_session1',                      
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div> 

        <div class="col-xs-12 col-sm-2 col-lg-2">
                <?php echo $form->field($model,'exam_type')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamType(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Type ----',
                            'id' => 'exam_type1',
                            'class'=>'student_disable',
                            'name'=>'mark_type',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
     
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= Html::Button('Show & Print' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'print_packets_btn1' ]) ?>
            <?= Html::a("Reset", Url::toRoute(['coe-add-hall-allocate/add-print-register-numbers']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>
    
    <?php ActiveForm::end(); ?>

    <div id="register_date_print_div">

    <div class="col-12">
        <div class="col-lg-12 col-sm-12">
            <?php 

            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['hall-allocate/print-register-numbers-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
                ]);
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/hall-allocate/print-register-numbers-excel'], [
                            'class' => 'pull-right btn btn-block btn-warning',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                ]);

                 echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';
            ?>
           <div id="register_answer_packets">
           </div>

        </div>
    </div>

</div>
</div>
</div>
</div>

