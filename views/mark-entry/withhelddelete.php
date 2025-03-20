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
use app\models\HallAllocate;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="With Held Delete";

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


        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'month')->widget(
                Select2::classname(), [
                    'data' => HallAllocate::getMonth(),
                    'options' => [
                        'placeholder' => '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ---',
                        'id' => 'exam_month',
                        'class'=>'student_disable',
                        'name'=>'month',
                        'onchange'=>'getmaldate();',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
        </div>

          <div class="col-lg-2 col-sm-2 exam_wise">
         <?php 
       
        echo $form->field($exam_model,'exam_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date----',  
                        'onchange'=>'getmalsession();',
                        'id' => 'exam_date1',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
    
    <div class="col-lg-2 col-sm-2 exam_wise">

        <?php echo $form->field($exam_model,'exam_session')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' ----',  
                        'id' => 'exam_session',  
                        'onchange'=>'getmalregnumber();',                     
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
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
                            'onchange'=>'getStuRgNumber(this.value);',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
        </div>
	
        <div class="form-group col-lg-2 col-sm-2"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Delete', ['value'=>'Delete','name'=>"withheld_del" ,'id'=>"withheld_del",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['mark-entry/withhelddelete']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>            
        </div>
    </div>
    

<?php ActiveForm::end(); ?>

</div>
</div>
</div>