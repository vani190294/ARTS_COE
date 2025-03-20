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

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

?>

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
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => ConfigUtilities::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'semester')->textInput(['id'=>'internal_semester','name'=>'exam_semester']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <input type="hidden" name="cat_type_val" id="mark_type_selected" value="46">
                <?php 
                $model->category_type_id='46';

                echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'internal_subject_code',
                            'name'=>'sub_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>


        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform">
                <input type="button" id="cia_btn" name="cia_btn" class="btn btn-success" value="Submit">
                <?= Html::a("Reset", Url::toRoute(['mark-entry/create']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12 mark_tbl">
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <div><input type="hidden" name="stu_count" id="stu_count"></div>
                <div><input type="hidden" name="cia_max_mark" id="cia_max_mark"></div>
                <div><input type="hidden" name="attendance_percent" id="attendance_percent"></div>
                <div id = "stu_mark_tbl"></div>
            </div>

            <div class="form-group col-lg-12 col-sm-12 cia_mark_done_btn">
                <?= Html::submitButton($model->isNewRecord ? 'Done' : 'Update', ['onClick'=>"spinner();",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

                <?= Html::submitButton($model->isNewRecord ? 'Done' : 'Update', ['onClick'=>"spinner();",'class' => $model->isNewRecord ? 'pull-right btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>