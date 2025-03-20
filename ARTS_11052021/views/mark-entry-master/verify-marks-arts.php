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
use app\models\ExamTimetable;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

//$max_value = isset($check_max_digists) && $check_max_digists!='' ? $check_max_digists : '';

$this->title ='VERIFICATION MARKS '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME))." WISE";
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
        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry, 'stu_programme_id')->widget(
                    Select2::classname(), [
                    'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry,'month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'name'=>'month',
                            'onchange' => 'getListSubjects($("#stu_programme_selected").val(),$("#mark_year").val(),this.value,$("#markentry-subject_map_id").attr("id"));',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 

             echo $form->field($markEntry,'subject_map_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----',
                        'name'=>'subject_map_id',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT));


            ?>
        </div>
        
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($markEntry,'mark_type')->widget(
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
                <?= Html::Button($markEntry->isNewRecord ? 'Submit' : 'Update', ['onClick'=>'getVerifyMarksArts();','class' => $markEntry->isNewRecord ? 'btn btn-success' : 'btn-block btn btn-primary']) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/verify-marks-arts']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

                
            </div>  
        </div>
       
    </div>


<?php ActiveForm::end(); ?>

</div>
</div>
</div>

<div id="display_or_hiddent" ></div>