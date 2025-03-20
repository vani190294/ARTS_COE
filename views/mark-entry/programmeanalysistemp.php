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

use app\models\ValuationSettings;
echo Dialog::widget();
$ValuationSettings = ValuationSettings::findOne(1);
/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title=ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)." Result Analysis TEMP";

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
                    //'data'=>ConfigUtilities::getDegreedetails(),

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
                <?= $form->field($model, 'year')->textInput(['id'=>'course_year','name'=>'year','value'=>$ValuationSettings['current_exam_year']]) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
	            <?php echo $form->field($model,'month')->widget(
	                Select2::classname(), [
                        'data' => $model->getMonth(), 
	                    'options' => [
	                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
	                        'id'=>'exam_month_1',
	                        'name'=>'month',
                            'value'=> $ValuationSettings['current_exam_month'] 
	                    ],
	                    'pluginOptions' => [
	                        'allowClear' => true,
	                    ],
	                ]) 
	                ?>
        	</div> 
            <!--div class="col-lg-2 col-sm-2">
                <?php 
                echo $form->field($model, 'section')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getSectionnames(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id'=>'stu_section_select',
                            //'value'=>$sec,
                            'name'=>'sec',
                            'class'=>'form-control',                                    
                        ],
                                                             
                    ]); 
                ?>
            </div-->

           

            
                <?php 
                $model->mark_type='27';
               
                ?>
            
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
       		<br />
            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">

                <input type="button" id="pgmanalysistemp" name="crseanalysisbutton" class="btn btn-success" value="Submit">
                <?= Html::a("Reset", Url::toRoute(['mark-entry/programmeanalysistemp']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        
       	</div>
        <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>
    
    <div class="col-xs-12 col-sm-12 col-lg-12 pgm_analysis_print_btn">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-programmeanalysistemp','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/programme-analysistemp-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div id = "programme_result" style="overflow:auto !important"></div>
    </div>

    <?php ActiveForm::end(); ?>

	

    </div>
	</div>
	</div>