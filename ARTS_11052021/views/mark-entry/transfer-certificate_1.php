<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\date\DatePicker;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use Da\QrCode\QrCode;

$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(),'register_number','register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number); 


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */
$this->title="Transfer Certificate";
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
                               
                            <?php 

                                echo $form->field($student, 'register_number_from')->widget(Select2::classname(), [
                                'initValueText' => $register_numbers, // set the initial display text
                                'options' => ['placeholder' => 'Search for a register_number ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 1,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => $url,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params,programme) { return {q:params.term,programme:$("#stu_programme_selected").val()}; }')
                                    ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                                ],
                            ]);


                            ?>

                        </div> 
                        <div class="col-lg-2 col-sm-2">

                            <?php 

                                echo $form->field($student, 'register_number_to')->widget(Select2::classname(), [
                                'initValueText' => $register_numbers, // set the initial display text
                                'options' => ['placeholder' => 'Search for a register_number ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 1,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme) { return {q:params.term,programme:$("#stu_programme_selected").val()}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                            ]);


                            ?>
                        </div>
                        
                    <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?php 
                            echo '<label>Date Of Issue</label>';
                            echo DatePicker::widget([
                                'name' => 'date_issue',
                                'id'=>'date_issue',
                                'value'=>date('m/d/Y'),
                                'type' => DatePicker::TYPE_INPUT,
                                'options' => ['placeholder' => 'Select Mark Statement Date ...',],
                                 'pluginOptions' => [
                                    'autoclose'=>true,
                                ],
                            ]);
                         ?>
                    </div>
        <div class="col-xs-12 col-sm-2 col-lg-2"> <br />
            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                <input type="button" id="transfer_cert_button" name="transfer_btn" class="btn btn-success" value="Submit">
                <?= Html::a("Reset", Url::toRoute(['mark-entry/transfer-certificate']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
        </div>
        </div>

        

        <div class="col-xs-12 col-sm-12 col-lg-12 university_report_tbl">
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <div class="col-xs-10"></div>
                <div class="col-xs-2 pull-right">
                  <?php
                        
                      

                        echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/transfer-certificate-pdf'], [
                            'class'=>'pull-right btn btn-primary', 
                            'target'=>'_blank', 
                            'data-toggle'=>'tooltip', 
                            'title'=>'Will open the generated PDF file in a new window'
                            ]);
                    ?>
                </div>
            </div>

            <div id = "uni_rep_tbl"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
