<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\HallAllocate;
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

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Malpractice Punishment";

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
        ]); 
    ?>

	<div class="col-xs-12 col-sm-12 col-lg-12">
		<div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year']) ?>
        </div>

        <!--div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'stu_batch_id')->widget(
                Select2::classname(), [
                    'data' => ConfigUtilities::getBatchDetails(),
                    'options' => [
                        'placeholder' => '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ---',
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
            <?php echo $form->field($model, 'stu_programme_id')->widget(
                Select2::classname(), [
                'data'=>ConfigUtilities::getDegreedetails(),
                    'options' => [
                        'placeholder' => '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ---',
                        'id' => 'stu_programme_selected',
                        'name'=>'bat_map_val',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
            ?>
        </div-->
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'month')->widget(
                Select2::classname(), [
                    'data' => HallAllocate::getMonth(),
                    'options' => [
                        'placeholder' => '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ---',
                        'id' => 'mal_month1',
                        'name'=>'mal_month1',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>
	     
        <div class="col-lg-2 col-sm-2">

                <?php echo $form->field($model,'register_number')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '--- Select Register Number ---',
                            'id' => 'withheld_stu_reg_num',
                            'class'=>'student_disable',
                            'name'=>'withheld_stu_reg_num',
                            //'onchange'=>'getStuRgNumber(this.value);',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
        </div>

         <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($markentrymaster,'withheld')->widget(
                Select2::classname(), [
                    'data' => HallAllocate::getmalpracticetype(),
                    'options' => [
                        'placeholder' => '--- Select ---',
                        'id' => 'malpractice_type_id',
                        'name'=>'malpractice_type_id',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Malpractice Type');
            ?>
        </div>
	
        <div class="form-group col-lg-2 col-sm-2"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['value'=>'Submit','name'=>"withheld_btn2" ,'id'=>"withheld_btn2",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['mark-entry/malpracticeupdate']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>            
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-lg-8 col-sm-8">
            &nbsp;
        </div>
        <div class="col-lg-4 col-sm-4" id="disp_name_of_stu">

        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12 tbl_n_submit_withheld">
        <div class="col-xs-12 col-sm-12 col-lg-12">
          &nbsp;
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">                
            <div id = "stu_withheld_tbl"></div>
        </div>

        <div class="form-group col-lg-3 col-sm-3 withheld_done_btn">

           <?= Html::submitButton('Update', ['onClick'=>"spinner();validateEmptyFields();",'id'=>"withheld_submit_btn", 'class' => 'btn btn-group-lg btn-group btn-success','name'=>'withheld_submit_btn','data-confirm' => 'Are you sure you want to Update this records <br /> This can not be Un-Done once the values were changed Until you <b>CONTACT THE SUPPORT TEAM?</b> Please re-check your Submission and Click <b>OK</b> to proceed.']) ?>

        </div>
    </div>


<?php ActiveForm::end(); ?>

</div>
</div>
</div>