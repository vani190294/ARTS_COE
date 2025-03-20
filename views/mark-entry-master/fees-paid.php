<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\HallAllocate;
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

$this->title=" Fees Paid";

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

        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'stu_batch_id')->widget(
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
            
        <div class="col-xs-12 col-lg-2 col-sm-2">
        	<?= $form->field($model, 'year')->textInput(['id'=>'year','name'=>'year','value'=>date('Y')]) ?>
    	</div>
        	
    	<div class="col-xs-12 col-lg-2 col-sm-2">
        <?= $form->field($model, 'month')->widget(
                Select2::classname(), [  
                    'data' => $galley->getMonth(),                      
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'placeholder' => '-----Select Month ----',
                        'id' => 'exam_month_change', 
                        'name' => 'month',
                        'onchange' => 'getfeesarrear();'                         
                    ],
                   'pluginOptions' => [
                       'allowClear' => true,
                    ],
                ]) ?>
    	</div>
    		
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($subject,'subject_code')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                        'id' => 'mark_subject_code',
                        'name'=>'sub_code',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
            ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-lg-4"> <br />
             <div class="col-lg-6 col-sm-6">
                <?= Html::Button('Get '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), ['onClick'=>'getfeesstulist()','id'=>'change_name','class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/fees-paid']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
             </div>
             
         </div>
 </div>
        


    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>
    
    <div class="row">
            <div id='disp_show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">
                <div id='show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">
                    
                </div>
                <?= Html::submitButton('Updated & Save', ['id'=>'update_comp','class' => 'btn btn-success' ]) ?>
            </div>
        </div>

   

	<?php ActiveForm::end(); ?>	
	
    </div>
	</div>
	</div>

