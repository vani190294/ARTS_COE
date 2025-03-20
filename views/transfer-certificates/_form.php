<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\TransferCertificates */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transfer-certificates-form">

    <?php $form = ActiveForm::begin(); ?>

   <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'register_number')->textInput(['readonly'=>'readonly']) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'name')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'dob')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'parent_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'nationality')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'religion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'community')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'caste')->textInput(['readonly' => true]) ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?php 
                echo '<label for="student-dob" class="required">'.$model->getAttributeLabel('admission_date').'</label>';
                echo DatePicker::widget([
                    'name' => 'admission_date',
                    'value' => date('d-m-Y',strtotime($model->admission_date)),   
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'options' => [                                            
                        'placeholder' => '-- Select '.$model->getAttributeLabel('admission_date').' ...',
                        'required'=>'required',  
                        'class'=>'form-control'
                    ],
                     'pluginOptions' => [
                        'autoclose'=>true,
                        'rangeSelect'=> true,
                        'format' =>'dd-mm-yyyy'
                    ],
                ]);
             ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'class_studying')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'is_qualified')->textInput(['readonly' => true]) ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'conduct_char')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?php 
                echo '<label for="student-dob" class="required">'.$model->getAttributeLabel('date_of_tc').'</label>';
                echo DatePicker::widget([
                    'name' => 'date_of_tc',
                    'value' => date('d-m-Y',strtotime($model->date_of_tc)),   
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'options' => [                                            
                        'placeholder' => '-- Select '.$model->getAttributeLabel('date_of_tc').' ...',
                        'required'=>'required',  
                        'class'=>'form-control'
                    ],
                     'pluginOptions' => [
                        'autoclose'=>true,
                        'rangeSelect'=> true,
                        'format' =>'dd-mm-yyyy'
                    ],
                ]);
             ?>

        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?php 
                echo '<label for="student-dob" class="required">'.$model->getAttributeLabel('date_of_app_tc').'</label>';
                echo DatePicker::widget([
                    'name' => 'date_of_app_tc',
                    'value' => date('d-m-Y',strtotime($model->date_of_app_tc)),   
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'options' => [                                            
                        'placeholder' => '-- Select '.$model->getAttributeLabel('date_of_app_tc').' ...',
                        'required'=>'required',  
                        'class'=>'form-control'
                    ],
                     'pluginOptions' => [
                        'autoclose'=>true,
                        'rangeSelect'=> true,
                        'format' =>'dd-mm-yyyy'
                    ],
                ]);
             ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?php 
                echo '<label for="student-dob" class="required">'.$model->getAttributeLabel('date_of_left').'</label>';
                echo DatePicker::widget([
                    'name' => 'date_of_left',
                    'value' => date('d-m-Y',strtotime($model->date_of_left)),   
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'options' => [                                            
                        'placeholder' => '-- Select '.$model->getAttributeLabel('date_of_left').' ...',
                        'required'=>'required',  
                        'class'=>'form-control'
                    ],
                     'pluginOptions' => [
                        'autoclose'=>true,
                        'rangeSelect'=> true,
                        'format' =>'dd-mm-yyyy'
                    ],
                ]);
             ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
