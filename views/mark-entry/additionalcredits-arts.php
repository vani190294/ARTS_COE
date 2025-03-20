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

$this->title="Additional Credits Mark Entry";

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
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
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
                <?= $form->field($add_credits, 'semester')->textInput(['id'=>'semester','name'=>'semester']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($add_credits, 'subject_code')->textInput(['id'=>'arts_add_sub_code','name'=>'add_sub_code','onblur'=>"getArtsSubInfo(this.value, $('#mark_year').val() , $('#exam_month').val());"]) ?>
            </div>

           
           
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="col-lg-2 col-sm-2">
                <?= $form->field($add_credits, 'subject_name')->textInput(['id'=>'add_sub_name','name'=>'add_sub_name']) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?= $form->field($add_credits, 'credits')->textInput(['id'=>'add_credits','name'=>'add_credits']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($add_credits, 'cia_minimum')->textInput(['id'=>'add_cia_min']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($add_credits, 'cia_maximum')->textInput(['id'=>'add_cia_max']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($add_credits, 'ese_minimum')->textInput(['id'=>'add_ese_min']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($add_credits, 'ese_maximum')->textInput(['id'=>'add_ese_max']) ?>
            </div>
            </div>

             <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($add_credits, 'total_minimum_pass')->textInput(['id'=>'min_pass']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($add_credits,'course_type_id')->widget(
                    Select2::classname(), [
                        'data' => $add_credits->getProgrammeType(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME_TYPE).' ----',
                            'name'=>'prgm_type_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME_TYPE)); 
                ?>
            </div>
            </div>
        </div>

    

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group col-lg-6 col-sm-6"> <br />
                <div class="btn-group" role="group" aria-label="Actions to be Perform">

                    <?= Html::Button('Submit', ['value'=>'Submit','onClick'=>"getArtsList($('#stu_programme_selected').val(),$('#mark_year').val(),$('#exam_month').val(),$('#semester').val(),$('#arts_add_sub_code').val(),$('#add_cia_min').val(),$('#add_cia_max').val(),$('#add_ese_min').val(),$('#add_ese_max').val(),$('#min_pass').val() );",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                    
                    <?= Html::a("Reset", Url::toRoute(['mark-entry/additionalcredits-arts']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>                

                </div>                
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div id = "arts_ac_student_list"></div>
            
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group col-lg-3 col-sm-3 arts_additional_submit_btn">
            <input onClick="spinner();" type="submit" id="add_submit_btn" name="add_submit_btn" class="btn btn-success" value="Create">
        </div>
    </div>
    
<?php ActiveForm::end(); ?>

</div>
</div>
</div>