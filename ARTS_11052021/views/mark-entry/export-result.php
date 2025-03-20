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

$this->title="Result Publish";
/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

?>


<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 

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
                <?php echo $form->field($model, 'section')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getSectionnames(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id'=>'stu_section_select',
                            'name'=>'sec',
                            'class'=>'form-control',                                    
                        ],
                                                             
                    ]); 
                ?>
            </div> 
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-3 col-sm-3">                
                <?= $form->field($model, 'year')->textInput(['value'=>date("Y"),'readonly'=>'readonly']) ?>
            </div>

            <div class="col-lg-3 col-sm-3">
            <?php echo $form->field($model,'month')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                        'id'=>'exam_month',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
                ?>
        </div> 
        <div class="form-group col-lg-3 col-sm-3"><br />
                <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

    </div>   

    <?php ActiveForm::end(); ?>
    <?php 
    if(isset($send_result) && !empty($send_result))
    {
        echo Html::a('<i class="fa fa-file-pdf-o"></i> Print Excel', ['/mark-entry/export-result'], [
                'class'=>'pull-right btn btn-warning', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated Excel file in a new window'
            ]);
        echo Html::a('<i class="fa fa-file-pdf-o"></i> Reset', ['/mark-entry/result-publish'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will refreshes the page with no data'
            ]); 
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found');
    }
    ?>
</div>
</div>
</div>