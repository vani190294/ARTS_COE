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
$this->title = 'External Mark Wightage';
/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

?>

<div>&nbsp;</div>
<div class="mark-entry-form">
<h1><?= Html::encode($this->title) ?></h1>
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
                <?= $form->field($model, 'created_by')->textInput()->label('Sem From') ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'semester')->textInput()->label('Sem To') ?>
            </div>
            


        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform">
            <br />
            <input type="button" id="get_subjects" onclick="getSemsterSubjects();" class="btn btn-success" value="Get <?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT); ?>">
                <input type="button" id="covid_btn" onclick="getConversionDetails();" class="btn btn-primary" value="Get Marks">
                <?= Html::a("Reset", Url::toRoute(['mark-entry/external-wightage']), 
                ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12 subjects_screen_to_hide">
            <div class="col-xs-12 col-sm-12 col-lg-12">               
                <div id="conversion_subjects_display">

                </div>
                <div>
                    <input type="button" id="covid_btn1" onclick="getConversionDetails();" class="btn pull-right btn-primary" value="Get Marks">
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12 conversion_screen">
            
            <div class="form-group col-lg-12 col-sm-12">
                <?php
                    echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-convert-marks','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                ?>

                <?= Html::submitButton('Migrate' , ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::submitButton('Migrate' , ['onClick'=>"spinner();",'class' => 'pull-right btn btn-success' ]) ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-12">               
                <div id="conversion_marks_display">

                </div>
            </div>

            <div class="form-group col-lg-12 col-sm-12">
            
                <?= Html::submitButton('Migrate' , ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::submitButton('Migrate' , ['onClick'=>"spinner();",'class' => 'pull-right btn btn-success' ]) ?>
            </div>
        </div>


    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>