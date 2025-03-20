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
use app\models\ExamTimetable;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

//$max_value = isset($check_max_digists) && $check_max_digists!='' ? $check_max_digists : '';

$this->title ='VERIFICATION MARKS';
?>
<h1><?= Html::encode("Verification Marks") ?></h1>
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
                        'name'=>'subject_map_id',
                        'id' => 'dummy_exam_subject_code',
                        'onchange' => 'get_sub_status(this.value,$("#exam_year").val(), $("#exam_month").val());',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT));


            ?>
        </div>
        
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'mark_type')->widget(
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
       
        
    </div>
    
</div>
</div>
<div class="row">
    
   <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-6 col-lg-6">            
            <br />

            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                <?= Html::Button($model->isNewRecord ? 'Submit' : 'Update', ['onClick'=>'getVerifyMarks($("#exam_year").val(), $("#exam_month").val());','class' => $model->isNewRecord ? 'btn btn-success' : 'btn-block btn btn-primary']) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/verify-marks']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>  
        </div>
       
    </div>


<?php ActiveForm::end(); ?>

</div>
</div>
</div>

<div id="display_or_hiddent" ></div>