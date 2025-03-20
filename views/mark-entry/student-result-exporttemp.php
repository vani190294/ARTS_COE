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

use app\models\ValuationSettings;

$ValuationSettings = ValuationSettings::findOne(1);

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Temp Internet Copy II";

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
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['name'=>'year','value'=>$ValuationSettings['current_exam_year'],'id' => 'exam_year',]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $galley->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month_ic', 
                            'name' => 'month',   
                            'value'=> $ValuationSettings['current_exam_month']                        
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
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

        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'stu_programme_id')->widget(
                    Select2::classname(), [
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
                <?= $form->field($model, 'mark_type')->radioList(ExamTimetable::getExamType()); ?>
           
            </div>

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <br>
            <label class="control-label">
                <input type="checkbox" id="valuation_status" value="1"> without AV
            </label>
           
        </div>

        <div class="form-group col-lg-2 col-sm-2"><br />
            <input type="button" id="student_res_export" onclick="getstudentresultstemp();" class="btn btn-success" value="Submit">
        </div>       
    </div>

    <?php ActiveForm::end(); ?>

    <div id="display_results_stu">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-3 col-sm-10 col-lg-10">
            </div>    
                <div class="col-xs-3 col-sm-2 col-lg-2">
                    <?php 
                       /* echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry-master/student-result-exporttemp-pdf'], [
                        'class'=>'pull-right btn btn-primary', 
                        'target'=>'_blank', 
                        'data-toggle'=>'tooltip', 
                        'title'=>'Will open the generated PDF file in a new window'
                        ]);*/

                  echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-export-student-resulttemp','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));

                 
                    ?>
                </div>
        </div>

        <div id="assign_stu_res" >

        </div>

    </div>

    </div>
	</div>
	</div>


