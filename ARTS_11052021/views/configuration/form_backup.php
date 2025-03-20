<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
use app\models\Configuration;
use yii\web\JsExpression;

$configDesc = empty($model->config_name) ? '' : Configuration::findOne($model->config_desc);
$url = \yii\helpers\Url::to(['config-list']);
?>

<div class="configuration-form">
    <div class="box box-primary">
        <div class="box-body">    

            <?php Yii::$app->ShowFlashMessages->showFlashes();?>  

            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                        <?php echo $form->field($model, 'config_name')->widget(
                                Select2::classname(), [
                                'initValueText' => $configDesc, 
                                'data' => array_merge($model->configurationList()),
                                'disabled'=>!$model->isNewRecord,
                                'options' => [
                                    'placeholder' => '-----Select Category----',
                                    'onchange' => 'changeVal(this.value)',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 2,
                                    'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                    ],
                                    'ajax' => [
                                    'url' => $url,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')

                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(config_name) { return config_name.text; }'),
                                    'templateSelection' => new JsExpression('function (config_name) { return config_name.text; }'),
                                ],
                                
                            ]); 


                        ?>
                    </div>                  
                    <div class="col-xs-12 hide_value col-sm-3 col-lg-3">
                        <?= $form->field($model, 'config_value')->textInput(['maxlength' => true,]) ?>
                    </div>

                    <div class="col-xs-12 show_dates col-sm-3 col-lg-3">
                        <?php 
                            echo '<label>Start Date</label>';
                            echo DatePicker::widget([
                                'name' => 'start_date',
                                'convertFormat' => true,
                                'value' => date('d-M-Y', strtotime('+2 days')),
                                'readonly' => true,
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'options' => ['placeholder' => 'Select Lock Start date ...',],
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'dd-M-yyyy',
                                    
                                ]
                            ]);
                         ?>
                    </div>
                   
                    <div class="col-xs-12 show_dates col-sm-3 col-lg-3">
                        <?php 
                        echo '<label>End Date</label>';
                        echo DatePicker::widget([
                                'name' => 'end_date', 
                                'convertFormat' => true,
                                'readonly' => true,
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'value' => date('d-M-Y', strtotime('+2 days')),
                                'options' => ['placeholder' => 'Select Lock End date ...'],
                                'pluginOptions' => [                                   
                                    'autoclose'=>true,
                                    'format' => 'dd-M-yyyy',
                                   
                                ]
                            ]);
                         ?>
                    </div>

                     <div class="col-xs-12 dropdown_is_status col-sm-3 col-lg-3">
                        <?php 
                           echo '<label class="control-label">Status</label>';
                                echo Select2::widget([
                                    'name' => 'is_status',
                                    'hideSearch' => true,
                                    'data' => [0 => 'Active', 1 => 'Inactive'],
                                    'options' => ['placeholder' => '----Select status---- '],
                                    'pluginOptions' => [
                                        'allowClear' => true,

                                    ],
                                ]);

                         ?>
                    </div>

                </div>
            </div>
            
            <br />
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-sm-1 col-lg-2">
                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id'=>'config_submit','class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-block btn-primary']) ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-lg-2">
                        <div class="form-group">
                            <?= Html::a("Reset Data", Url::toRoute(['']), ['class' => 'btn btn-warning btn-default btn-block','style'=>'color: #fff;']) ?>
                        </div>
                    </div>
                </div>                
            </div>
            <?php ActiveForm::end(); ?>
            
        </div>
    </div>
</div>
