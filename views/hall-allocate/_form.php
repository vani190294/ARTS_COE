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

// echo "<pre>";
// print_r($exam->attributes);exit;
?>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  
    <div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12">                
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y')]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
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

        <div class="col-xs-12 col-sm-3 col-lg-3">
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
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($exam, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Session ----', 
                            'id' => 'exam_session',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12">       
        
        <div class="col-xs-12 col-sm-3 col-lg-3"> 
            <?= $form->field($model, 'arrangement_type')->widget(
                    Select2::classname(), [
                    'data' => $model->getHallarrangement(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                            'placeholder' => '-----Select Arrangement Type ----',
                            'id'=>'hall_arrangement', 
                            'name' => 'arrangement_type',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>            
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3" id="subjectwise"> 
            <?= $form->field($exam, 'subject_code')->widget(
                    Select2::classname(), [

                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----', 
                            'id' => 'subject',
                            'name' => 'galley_subject_wise',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                           'multiple' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3"> 
            <?= $form->field($hallmaster, 'hall_type_id')->widget(
                    Select2::classname(), [
                        //'data' => $categorytype->getCategorytype(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Hall Type ----', 
                            'id' => 'method',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
          <div class="col-lg-2 col-sm-3">
            <?php echo $form->field($exam,'exam_type')->widget(
                Select2::classname(), [
                    'data' => $exam->ExamType,
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' ----',
                        'id' => 'exam_type',
                        'class'=>'student_disable',
                        //'value'=> $batch_id,
                        //'name'=>'exam_type',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'seat_arrangement')->widget(
                    Select2::classname(), [  
                        'data' => $model->getArrangement(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Seat Arrangement ----',
                            'id' => 'seat_arr',
                            'name' => 'seat_arrr',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12"> 

        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'student_count')->textArea(['readonly' => 'readonly']) ?>
        </div>
        

    </div> 
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-5 col-lg-5">
            <form name="selection" method="post" onSubmit="return selectAll()">
            <!-- <body onload="displayCount();"> -->
            <select name="multi_halls" multiple size="10" id="from" class="form-control">
            </select>
        </div>
        <div class="col-lg-2 col-sm-2 col-xs-12 text-center">
            <a href="javascript:moveSelected('from', 'to');" id="get" class="btn btn-success">&gt;</a>
                <br><br>
            <a href="javascript:moveSelected('to', 'from');" id="get" class="btn btn-primary">&lt;</a>
                <br><br>
            <a href="javascript:moveAll('from', 'to');" id="get" class="btn btn-success">&gt;&gt;</a>
                <br><br>            
            <a href="javascript:moveAll('to', 'from');" id="get" class="btn btn-primary">&lt;&lt;</a>
        </div>
        <div class="col-xs-12 col-sm-5 col-lg-5">
            <select multiple id="to" size="10" name="topics[]" id='to' class="form-control"></select>
        </div>
    </div>   
    </div>
<div class="row">
    <br />
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-5 col-lg-5">
            Available Hall(s)
            <input type="text" name="countFrom" id="countFrom" class="form-control" readonly>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2" style="text-align: center;">
            <br />
            <!-- <a onclick="javascript:shuffle();" id="shuffle" class="btn btn-block btn-primary" >Shuffle</a> -->
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Load' ,['class' => 'btn btn-group btn-group-lg btn-success' , 'onclick'=>'js:getValues();spinner();']) ?>  
                <?= Html::Button("Reset", ['class' => 'btn btn-group btn-group-lg btn-primary ','value'=>'reset_data','name'=>'reset_data','id'=>'clear_id','onclick'=>'js:resetHalls();']) ?>
               
                <?php //Html::a("Reset Form", Url::toRoute(['hall-allocate/create']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>
            
        </div>

        <div class="col-xs-12 col-sm-5 col-lg-5">
            Alloted Hall(s)
            <input type="text" name="countTo" id="countTo" class="form-control" readonly>
        </div>
    </div>
    </div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-lg-12"> 
        &nbsp;
    </div>
</div>
</div>
<div class="row">

    <div class="col-xs-12 col-sm-12 col-lg-12 stu_cnt_text">
       <div class="col-xs-12 col-sm-2 col-lg-2">
            
            <input type='text' name='stu_cout' id='stu_cnt' >
      </div>
    </div>
</div>
    <?= Html::textInput('hallName',"",['id'=>'hallName','type'=>"hidden"]); ?>
    <?= Html::textInput('hallCount',"",['id'=>'hall_cnt','type'=>"hidden"]); ?>
 
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>