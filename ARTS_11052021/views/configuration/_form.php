<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\date\DatePicker;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();

?>

<div class="configuration-form">
    <div class="box box-primary">
        <div class="box-body">    
            
            <?php Yii::$app->ShowFlashMessages->showFlashes();?>  

            <?php 

            $form = ActiveForm::begin([
                'id' => 'form-order-article', 
                'enableClientValidation' => true, 
                'enableAjaxValidation' => false,
                ]); ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                        <?php echo $form->field($model, 'config_name')->widget(
                                Select2::classname(), [
                                'data' => array_merge($model->configurationList()),
                                'disabled'=>!$model->isNewRecord,
                                'options' => [
                                    'placeholder' => '----- Select ----',
                                    'onchange' => 'changeVal(this.value)',
                                    'id'=>'config_name_id',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                                
                            ]); 
                        ?>
                    </div>                  
                    <div class="col-xs-12 hide_value hide_photo col-sm-3 col-lg-3">
                        <?= $form->field($model, 'config_value')->textInput(['id'=>'config_value_assign','maxlength' => true,]) ?>
                    </div>
                  
                    <div class="col-xs-12 show_dates col-sm-3 col-lg-3">
                        <?php 
                            echo '<label>Start Date</label>';
                            echo DatePicker::widget([
                                'name' => 'start_date',
                                'readonly' => true,
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'options' => ['placeholder' => 'Select Lock Start date ...',],
                                 'pluginOptions' => [
                                    'autoclose'=>true,
                                ],
                               
                            ]);
                         ?>
                    </div>
                   
                    <div class="col-xs-12 show_dates col-sm-3 col-lg-3">
                        <?php 
                        echo '<label>End Date</label>';
                        echo DatePicker::widget([
                                'name' => 'end_date', 
                                'readonly' => true,
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'options' => [
                                    'placeholder' => 'Select Lock End date ...',
                                    'onchange' => 'validateDate(this.value)',
                                    'data-alert' => 'Date Not Matched',
                                ],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                    ],
                                
                            ]);
                         ?>
                    </div>

                     <div class="col-xs-12 dropdown_is_status col-sm-3 col-lg-3">
                        <?php 

                            $data = ["Active" => 'Active', "Inactive" => 'Inactive'];
                            echo $form->field($model, 'is_status')->widget(Select2::classname(), [
                                    'data' => $data,
                                    'options' => [
                                        'placeholder' => 'Select Status ...',
                                        'class'=>'nominal_status_clear',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
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

                            <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['onClick'=>"spinner();",'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-block btn-primary', 'data-confirm' => 'Are you sure you want to Update this Value <br /> This will change the Values in <b>'.Yii::$app->params['app_name'].'</b>?']) ?>

                            <?= Html::a("Reset Data", Url::toRoute(['']), ['onClick'=>"spinner();",'class' => 'btn btn-warning btn-default ','style'=>'color: #fff;']) ?>
                        </div>
                    </div>
                    
                </div>                
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
