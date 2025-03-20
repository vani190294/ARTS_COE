<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\HallAllocate;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */


//$max_value = isset($check_max_digists) && $check_max_digists!='' ? $check_max_digists : '';
$this->title= ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY);
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
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($examModel,'coe_batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getBatchDetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                        'id' => 'stu_batch_id_selected',
                        'class'=>'student_disable',
                        'name'=>'bat_val',

                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)."<span style='color: #F00;' >  (OPTIONAL) </span>"); 
            ?>
        </div> 

        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($examModel, 'batch_mapping_id')->widget(
                Select2::classname(), [
                'data'=>ConfigUtilities::getDegreedetails(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                        'id' => 'stu_programme_selected',
                        'class'=>'student_disable',
                        'name'=>'bat_map_val',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)."<span style='color: #F00;' >  (OPTIONAL) </span>"); 
            ?>
        </div> 
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
                        'onchange' => 'get_sub_status(this.value,$("#exam_year").val(), $("#exam_month").val());',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);


            ?>
        </div>
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'start_number')->textInput(['onkeypress'=>'numbersOnly(event)', 'id'=>'start_number','onchange'=>'getAllnumbers(this.value,$("#exam_year").val(), $("#exam_month").val());','min'=>4,'max'=>10,'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'end_number')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'end_number','min'=>4,'max'=>10,'required'=>'required','autocomplete'=>"off"]); ?>
                     
        </div>
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model, 'created_by')->widget(
                Select2::classname(), [
                'data'=>[1,2,3,4,5,6,7,8],
                    'options' => [
                        'placeholder' => '-----Select SEMEMSTER ----',
                        'id' => 'semester_val',
                        'name'=>'semester_val',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label("SEMESTER <span style='color: #F00;' >  (OPTIONAL) </span>"); 
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
                            
                            <th>SNO</th>
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE"); ?></th>
                            
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME"); ?></th>
                            <th><?php echo strtoupper("Galley Arranged"); ?></th>
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)); ?></th>
                            
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)." Arranged"); ?></th>
                            <th><?php echo strtoupper("Remaining"); ?></th>
                        </tr>               
                    </thead> 
                    <tbody id="show_dummy_sub_info">     

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
                <?= Html::Button($model->isNewRecord ? 'Save' : 'Update', ['onClick'=>" generate_numbers();",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn-block btn btn-primary','id'=>'change_text_button']) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/create']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>           

            
        </div>
        

       
    </div>
    <div id='hide_dum_data' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-1"> &nbsp; </div>
            <div class="col-xs-10">
        <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
            <thead class="thead-inverse">
            <tr class="table-danger">
                
                <th>SNO</th>
                <th><?php echo strtoupper("Register Number"); ?></th>
                
                <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Name"); ?></th>
                <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)); ?></th>
            </tr>               
            </thead> 
            <tbody id="show_dummy_numbers">     

            </tbody>
        </table>
        <?= Html::submitButton('Submit' , ['class' =>'submit_dummy', 'class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('Submit' , ['class' =>'submit_dummy', 'class' => 'pull-right btn btn-primary']) ?>
    </div>
    <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div>



<?php ActiveForm::end(); ?>

</div>
</div>
</div>