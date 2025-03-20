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

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="External Mark Entry";
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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
            </div>

            <div class="col-lg-3 col-sm-3">
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

            <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($model, 'stu_programme_id')->widget(
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
            <div class="col-lg-3 col-sm-3">
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
            
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($model,'term')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamTerm(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Term ----',
                            'id' => 'exam_term',
                            'class'=>'student_disable',
                            'name'=>'term',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>

            <div class="col-lg-3 col-sm-3">
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
        
            <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($model, 'section')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getSectionnames(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id'=>'stu_section_select',
                            'name'=>'sec',
                            'class'=>'form-control student_disable',                                    
                        ],
                    ]); 
                ?>
            </div> 
            <div class="col-lg-3 col-sm-3">
                <?= $form->field($model, 'semester')->textInput(['id'=>'mark_semester','name'=>'exam_semester']) ?>
            </div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-3 col-sm-3">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',
                            'onchange' => 'getExternalEnggStudeList();',
                            'name'=>'sub_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>

            <div class="col-lg-3 col-sm-3 select_model_type">
                <?php echo $form->field($model, 'model_type')->widget(
                    Select2::classname(), [
                        'data'=>MarkEntry::getModel(),   
                        'options' => [
                            'placeholder' => '--- Select Model Type---',
                            'id' => 'select_mod_type',
                            'name'=>'select_mod_type',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Model Type');
                ?>
            </div>

            <div class="col-lg-3 col-sm-3 mod_type1">
                <?= $form->field($model, 'model_1')->textInput(['onchange'=>'checkTotal(this.value);',"onkeypress"=>"numbersOnly(event);",'id'=>'mod_1','name'=>'mod_1','autocomplete'=>"off"]) ?>
            </div>

            <div class="col-lg-3 col-sm-3 mod_type2">
                <?= $form->field($model, 'model_2')->textInput(['onchange'=>'checkTotal(this.value);',"onkeypress"=>"numbersOnly(event);",'id'=>'mod_2','name'=>'mod_2','autocomplete'=>"off"]) ?>
            </div>
            
            <div class="form-group col-lg-9 col-sm-9"> <br />
                <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform">
                <input type="button" id="ese_btn" name="ese_btn" class="btn btn-success" value="Submit">
                    <?php /*new*/ ?>
                    <?= Html::a("Reset", Url::toRoute(['mark-entry/externalmarkentry']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>
                    <?php //new ?>
                <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
                </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12 ese_mark_tbl">
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <div><input type="hidden" name="stu_count1" id="stu_count1"></div>
                <div><input type="hidden" name="ese_max_mark" id="ese_max_mark"></div>

                <div><input type="hidden" name="ese_min_mark" id="ese_min_mark"></div>
                <div><input type="hidden" name="ese_total_mark" id="ese_total_mark"></div>

                <div><input type="hidden" name="cat_model1_ese_val" id="cat_model1_ese_val"></div>
                <div><input type="hidden" name="cat_model2_ese_val" id="cat_model2_ese_val"></div>
                <div id = "stu_mark_tbl"></div>
            </div>

            <div class="form-group col-lg-3 col-sm-3 cia_mark_done_btn">
                <input type="submit" id="ese_submit_btn" name="ese_submit_btn" onClick="spinner();" class="btn btn-success" value="Done">
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>