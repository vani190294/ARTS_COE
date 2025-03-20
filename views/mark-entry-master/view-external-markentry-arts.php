<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\ExamTimetable;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="View External Marks";
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

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
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            
                
        
            <div class="col-lg-2 col-sm-2">  
                <?php $model->term='34';$model->mark_type='27'; ?>              
                <?= $form->field($model, 'term')->radioList(ExamTimetable::getExamTerm()); ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'mark_type')->radioList(ExamTimetable::getExamType()); ?>
                
            </div> 
            </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'semester')->textInput(['onblur'=>'getExternalModeSubs(this.value);','name'=>'exam_semester']); ?>
                
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',
                            'name'=>'sub_val',
                            'onchange' => 'getExternalMarksStudeList();',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,

                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>
                      
        <br />
                <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                    
                    <?= Html::a("Reset", Url::toRoute(['mar-entry-master/view-external-markentry-arts']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>
                
        </div>

        
        <div class="row">
            <div id='disp_show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">
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
                                        <th><?php echo strtoupper("CIA Minimum"); ?></th>
                                        <th><?php echo strtoupper("CIA Maximum"); ?></th>
                                        <th><?php echo strtoupper("ESE Minimum"); ?></th>
                                        <th><?php echo strtoupper("ESE Maximum"); ?></th>
                                        <th><?php echo strtoupper("Min Pass"); ?></th>
                                    </tr>               
                                </thead> 
                                <tbody id="show_dummy_entry">     

                                </tbody>
                            </table> 
                        </div>
                    <div class="col-xs-1"> &nbsp; </div>
                    </div>
                </div> <!-- Row Closed -->

                <div id='show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">

                </div>
                
            </div>
        </div>
            
        
    <?php ActiveForm::end(); ?>
</div>


</div>
</div>

