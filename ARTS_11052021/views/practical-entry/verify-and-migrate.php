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
use app\models\ExamTimetable;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title ="Verify &amp Migrate For Result";
$this->params['breadcrumbs'][] = $this->title;


?>
<h1><?php echo "Verify &amp Migrate For Result"; ?></h1>

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
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
         </div>
           
         <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),  
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'onchange' => 'getSubjectInfoPracVerify(this.id,this.value);',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
         </div>      
         <input type="hidden" name="PracticalEntry[mark_type]" id='mark_type' value="27" >
        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',
                            'name'=>'sub_val',
                            'onchange' => 'getExaminerNames(this.value);',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'examiner_name')->textInput(['required'=>'required','id'=>'examiner_name'])->label('Examiner Name'); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'chief_exam_name')->textInput(['required'=>'required','id'=>'chief_exam_name'])->label('Chief Examiner Name'); ?>
                     
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
                            <th><?php echo strtoupper("Minimum"); ?></th>
                            <th><?php echo strtoupper("Maximum"); ?></th>
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
                <?= Html::a("Reset", Url::toRoute(['practical-entry/verify-and-migrate']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                
            </div>           

            
        </div>
        

       
    </div>
    <div id='hide_dum_data' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-1"> &nbsp; </div>
            <div class="col-xs-10">
                <?= Html::submitButton('Migrate' , ['id' =>'submit_dummy', 'class' => 'btn btn-primary','data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once the values were Submitted.']) ?>

        <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
            <thead class="thead-inverse">
            <tr class="table-danger">
                <th>SNO</th>                
                <th>Register Number</th>
                <th>Marks</th>
                <th>Migrate Status</th>
            </tr>               
            </thead> 
            <tbody id="pract_show_dummy_numbers">     

            </tbody>
        </table>
        <?= Html::submitButton('Migrate' , ['id' =>'submit_dummy', 'class' => 'btn btn-primary','data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once the values were Submitted.']) ?>
    </div>
    <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div>



<?php ActiveForm::end(); ?>

</div>
</div>
</div>