<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\Categorytype;

echo Dialog::widget();
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\ApplyLeave */
/* @var $form yii\widgets\ActiveForm */


$date = isset($model->raised_date) && $model->date!="" ? date('m/d/Y',strtotime($model->date)):date('m/d/Y');


?>

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
   
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'task_tittle')->textInput(['row' => 6,'placeholder'=>'Enter Title']) ?>
        </div>
        <div class="col-xs-12 col-sm-9 col-lg-9">
            <?= $form->field($model, 'task_description')->textarea(['maxlength' => true,'placeholder'=>'Enter Task Description']) ?>
        </div>
       <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php echo $form->field($model, 'priority')->widget(
                Select2::classname(), [
                'data' => ['High'=>'High','Medium'=>'Medium','Low'=>'Low'],                               
                'options' => [
                    'placeholder' => '-----Select priority----',
                    'id' => 'priority',                                    
                    //'onChange'=>'addFields(this.id);',
                    'class'=>'form-control', 
                ],
                ]); 
            ?>
        </div>
        
       
         <div class="col-xs-12 col-sm-3 col-lg-3">
             <?php 
                    echo '<label for="date" class="required">Date</label>';
                    echo DatePicker::widget([
                        'name' => 'date',
                        'value' => $date,   
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [                                            
                            'placeholder' => '--Select Date----',
                           // 'onChange' => "isDate(this.id); ", 
                            'class'=>'form-control ',
                        ],
                         'pluginOptions' => [
                            'autoclose'=>true,
                            'rangeSelect'=> true,
                        ],
                    ]);
                 ?>
        </div> 

        <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php echo $form->field($model, 'task_type')->widget(
                Select2::classname(), [
               'data' => ['New Requirement'=>'New Requirement','New Requirement (Update)'=>'New Requirement (Update)','Old Requirement (Update)'=>'Old Requirement (Update)','Bug'=>'Bug/Error'],                                   
                'options' => [
                    'placeholder' => '-----Select Task Type----',
                    'id' => 'task_type',                                   
                    //'onChange'=>'addFields(this.id);',
                    'class'=>'form-control', 
                ],
                ]); 
            ?>
           </div>
          
        </div>
        
             
        <div class="col-xs-12 col-sm-12 col-lg-12">
        <?php
        if(Yii::$app->user->getId()=='1')
        {?>

          <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php echo $form->field($model, 'status')->widget(
                Select2::classname(), [
                'data' => ['Pending'=>'Pending','Progress'=>'Progress','Completed'=>'Completed','Hold'=>'Hold'],                                
                'options' => [
                    'placeholder' => '-----Select Status----',
                    'id' => 'status',                                    
                    //'onChange'=>'addFields(this.id);',
                    'class'=>'form-control', 
                ],
                ]); 
            ?>
          </div>
          <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'remark')->textInput(['row' => 6,'placeholder'=>'Enter remark']) ?>
         </div>
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php echo $form->field($model, 'developed_by')->widget(
                Select2::classname(), [
                'data' => ['Divya'=>'Divya','Prabhakaran'=>'Prabhakaran','Vanitha'=>'Vanitha'],                                
                'options' => [
                    'placeholder' => '-----Select Status----',
                    'id' => 'developed_by', 
                    'class'=>'form-control', 
                ],
                ]); 
            ?>
          </div>
        
        <?php }?>
        
</div>
</div>
</div>
<div class="row">
   <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-6 col-lg-6">            
            <br />
            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                <?= Html::SubmitButton( $model->isNewRecord ? 'Create' : 'Update' ,['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['tracker-sheet/create']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>                
            </div> 
        </div>
    </div>
    </div>
<?php ActiveForm::end(); ?>

</div>
</div>
</div>

