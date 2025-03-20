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
$this->title = 'Answer Cover Generate';
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
                            'id' => 'ans_exam_month',    
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
                        'id' => 'ans_exam_date',         
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
                        'id' => 'ans_exam_session',                      
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div> 
        
       
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">

        

         <div class="col-xs-12 col-sm-3 col-lg-3">
            <?=$form->field($model, 'total_answer_scripts')->textInput(['value'=>'50','placeholder' => 'Script: Min 40 Max 50','id'=>'ans_pack_script','name'=>'ans_pack_script','maxlength' => 50])->label('Answer Script(Min 40, Max 50)'); ?>
        </div>
       <div class="col-xs-12 col-sm-2 col-lg-2">
                <?php echo $form->field($model,'exam_type')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamType(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Type ----',
                            'id' => 'exam_type',
                            'class'=>'student_disable',
                            'name'=>'mark_type',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>

        <div class="col-xs-12 col-sm-4 col-lg-4">
            <br>
            <?= Html::Button('Show & Print' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'answer_packets_btn' ]) ?>
             <?= Html::Button("Reset Answer Cover", ['onClick'=>"deleteanswerpacket();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            <?= Html::a("Reset Selection", Url::toRoute(['hall-allocate/answer-packets']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>
    
    <?php ActiveForm::end(); ?>
    
</div>
</div>
</div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body">
<div class="col-xs-12 col-sm-12 col-lg-12">
        <div id="answer_packets_div">

            <div class="col-12">
                <div class="col-lg-12 col-sm-12">
                    <?php 

                    $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['hall-allocate/answer-scripts-pdf'], [
                                'class' => 'pull-right btn btn-block btn-primary',
                                'target' => '_blank',
                                'data-toggle' => 'tooltip',
                                'title' => 'Will open the generated PDF file in a new window'
                        ]);
                        $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/hall-allocate/answer-scripts-excel'], [
                                    'class' => 'pull-right btn btn-block btn-warning',
                                    'target' => '_blank',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Will open the generated PDF file in a new window'
                        ]);

                      echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';
                    ?>
                   <div id="answer_packets">
                   </div>

                </div>
            </div>

        </div>
    </div>
</div>
</div>
</div>