<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\MarkEntry;

echo Dialog::widget();

$batch_id='';
if(isset($subjects->batch_mapping_id))
{
    $get_batch_id = BatDegReg::findOne($subjects->batch_mapping_id);
    $batch_id = $get_batch_id->coe_batch_id;
}

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Mandatory  Mark Entry";

?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>
<?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">       
            <div class="col-lg-3 col-sm-3">
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>
            <div class="col-lg-3 col-sm-3">
            <?php echo $form->field($sub_model,'coe_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'value'=> $batch_id,
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
                </div>
            <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($sub_model, 'batch_map_id')->widget(
                    Select2::classname(), [
                    'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ---',
                            'id' => 'stu_programme_selected',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>         
            <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>


        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
             
            <div class="col-lg-2 col-sm-2">
                <?php 
                $model->mark_type='27';
                echo $form->field($model,'mark_type')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamType(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Type ----',
                            'id' => 'exam_type',
                            'class'=>'student_disable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
                <?php $model->term='34'; echo $form->field($model,'term')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamTerm(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Term ----',
                            'id' => 'exam_term',
                            'class'=>'student_disable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
             <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'semester')->textInput(['onChange' => 'getMandatorySubjectsList();']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($sub_model, 'man_subject_id')->widget(
                    Select2::classname(), [
                    'data'=>$mandatorySubjects->getAllSubjects(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' ----',
                            'onChange' => 'getManSubjectsList();',
                            'id'=>'manSubId'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label("Mandatory ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); 
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($sub_model, 'sub_cat_code')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' ----',
                            'onChange' => 'getManSubjectDetails();',
                            'id'=>'manSubcatId'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
            </div>
        </div>


    </div>


    <div id="man_sub_credit_btn" class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group col-lg-6 col-sm-6"> <br />
                <div class="btn-group" role="group" aria-label="Actions to be Perform">

                    <?= Html::submitButton('Complete', ['name'=>"man_sub_credit_btn",'class' => 'btn btn-group-lg btn-group btn-success','name'=>'update','data-confirm' => 'Are you sure you want to Update this records <br /> This can not be Un-Done once the values were changed Until you <b>CONTACT THE SUPPORT TEAM?</b> Please re-check your Submission and Click <b>OK</b> to proceed.']) ?>
                    
                    <?= Html::a("Reset", Url::toRoute(['mandatory-stu-marks/create']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>                

                </div>                
            </div>
        </div>
    </div>

    <div class="row" id='hide_sub_cat_info'>

    </div>
  
    
<?php ActiveForm::end(); ?>

</div>
</div>
</div>