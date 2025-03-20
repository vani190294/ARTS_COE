<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\Batch;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\ValuationFaculty */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="valuation-faculty-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-lg-3 col-sm-3">
        <?= $form->field($model, 'faculty_name')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-3 col-sm-3">
        <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-3 col-sm-3">
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>

     <div class="col-lg-3 col-sm-3">
    <?= $form->field($model, 'college_code')->textInput(['maxlength' => true]) ?>
    </div> 
   
    <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($model, 'faculty_designation')->widget(
                    Select2::classname(), [
                    'data'=>['Assistant Professor'=>'Assistant Professor','Associate Professor'=>'Associate Professor','Professor'=>'Professor','Industry Expert'=>'Industry Expert'],

                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'faculty_designation',
                            'name'=>'faculty_designation',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
    </div> 
    <div class="col-lg-3 col-sm-3">
        <?php echo $form->field($model, 'faculty_board')->widget(
                    Select2::classname(), [
                    'data'=>['CIVIL'=>'CIVIL','CSE/IT'=>'CSE/IT','ECE'=>'ECE','EEE'=>'EEE','ICE'=>'ICE','MECH'=>'MECH','MBA'=>'MBA','MATHS'=>'MATHS','PHYSICS'=>'PHYSICS','CHEMISTRY'=>'CHEMISTRY','ENGLISH'=>'ENGLISH'],

                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'faculty_board',
                            'name'=>'faculty_board',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>

    </div>
        <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($model, 'faculty_mode')->widget(
                    Select2::classname(), [
                    'data'=>['EXTERNAL'=>'EXTERNAL','INTERNAL'=>'INTERNAL'],

                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'faculty_mode',
                            'name'=>'faculty_mode',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
    </div> 
     <div class="col-lg-3 col-sm-3">
    <?= $form->field($model, 'faculty_experience')->textInput() ?>
    </div> 
     <div class="col-lg-3 col-sm-3">
    <?= $form->field($model, 'bank_accno')->textInput(['maxlength' => true]) ?>
    </div> 
     <div class="col-lg-3 col-sm-3">
    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
    </div> 
     <div class="col-lg-3 col-sm-3">
    <?= $form->field($model, 'bank_branch')->textInput(['maxlength' => true]) ?>
    </div> 
     <div class="col-lg-3 col-sm-3">
    <?= $form->field($model, 'bank_ifsc')->textInput(['maxlength' => true]) ?>
    </div> 
    
    <div class="col-lg-3 col-sm-3">
         <?php echo $form->field($model, 'out_session')->widget(
                    Select2::classname(), [
                    'data'=>['NO'=>'NO','YES'=>'YES'],

                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'out_session',
                            'name'=>'out_session',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
    </div> 

    <div class="col-lg-3 col-sm-3 form-group">
        <br>
        <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>

        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
