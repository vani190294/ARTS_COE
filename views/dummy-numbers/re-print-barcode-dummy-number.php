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
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Re Print ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY);
$this->params['breadcrumbs'][] = $this->title;


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
                            'onchange' => 'bringYearMonthSubs(this.value,$("#exam_year").val());',                        
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 

             echo $form->field($model,'subject_map_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----',
                        'id' => 'dummy_exam_subject_code',
                        'name'=>'exam_subject_code',
                        'onchange' => 'getBarcodeExaminerName(this.value,$("#exam_year").val(), $("#exam_month").val())',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);


            ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo $form->field($model,'examiner_name')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Examiner ----',
                        'id' => 'examiner_name',
                        'name'=>'examiner_name',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);
                ?>
                     
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo $form->field($model,'exam_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date ----',
                        'id' => 'exam_date',
                        'name'=>'exam_date',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Entry Date');
                ?>
                     
        </div>       
    </div>
    <div id='hide_dum_sub_data' class="row">
    <div  class="col-xs-12"> <br /><br />
        <div class="col-xs-1"> &nbsp; </div>
            <div class="col-xs-10">
                <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
                    <thead class="thead-inverse">
                        <tr class="table-danger">
                            
                            <th>S.NO</th>
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE"); ?></th>
                            
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME"); ?></th>
                            <th><?php echo strtoupper("Minimum"); ?></th>
                            <th><?php echo strtoupper("Maximum"); ?></th>
                            <th><?php echo strtoupper("Min Pass"); ?></th>
                        </tr>               
                    </thead> 
                    <tbody id="show_dummy_entry">     

                    </tbody>
                </table> 
            </div>
        <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div> <!-- Row Closed -->
</div>
</div>

<div class="row">
    
   <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-6 col-lg-6">            
            <br />

            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit' , ['id' =>'submit_dummy', 'class' => 'btn btn-primary','formtarget'=>"_blank"]) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/re-print-dummy-number']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>           

            
        </div>
        

       
    </div>
    
<?php ActiveForm::end(); ?>

</div>
</div>
</div>