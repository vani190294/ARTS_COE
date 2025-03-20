<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\dialog\Dialog;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\CoeActivityMarks;
use app\models\CoeAddPoints;

$this->registerCssFile("@web/style/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    'media' => 'print',
], 'css-print-theme');


echo Dialog::widget();
$this->title = "Delete Activity Marks";
$this->params['breadcrumbs'][] = ['label' =>$this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?php echo $this->title; ?></h1>
<br /><br />

<div id="student_update_edit_page" class="configuration-form">
    <div class="box box-primary">
        <div class="box-body">          
               
            <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
            <?php 
                       
                     $form = ActiveForm::begin();
             ?>
 
            <div class="row">
                <div  class="col-xs-12">                 
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'batch')->widget(
                                Select2::classname(), [
                                'data' => ConfigUtilities::getBatchDetails(),
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_batch_id_selected', 
                                    'class'=>'form-control student_disable',                              
                                ],

                               
                                
                            ]); 
                        ?>
                        </div>                  
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'programme')->widget(
                                Select2::classname(), [
                                                                       
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_programme_selected',
                                    'class'=>'form-control student_disable', 
                                    'onchange'=>'getactivitysubjects()'                                  
                                    
                                ],
                                                               
                            ]); 
                            ?>
                        </div>    
               

                        <div class="col-lg-2 col-sm-2">
                            <?php echo $form->field($model,'subject_code')->widget(
                                Select2::classname(), [ 
                                    'options' => [
                                       'placeholder' => '-----Select Subject Code----',
                                        'id'=>'subject_code',
                                        'name'=>'subject_code',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]) 
                                ?>
                        </div>
                        
                     
                
                        <div class="col-xs-12 col-sm-3 col-lg-3">                 
                        
                            <div class="col-xs-12 col-sm-6 col-lg-6"><br />
                                <?= Html::Button('Get', ['onClick'=>"getactivitystudent();",'name'=>'get_students','class' => 'btn btn-block btn-success' ]) ?>
                                <br />
                            </div>

                            <div class="col-xs-12 col-sm-6 col-lg-6"><br />
                            <?= Html::a("Reset", Url::toRoute(['coe-activity-marks/deletereport']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
                            <br />
                        </div> 
                      </div>  
                </div>
            </div>

             <div class="col-lg-12 col-sm-12" id="showqpsettingreceived" style="display: none;">
            
                <div id="viewdata"></div>

                <div class="col-xs-12 col-sm-2 col-lg-2 pull-right"><br>
                    <?= Html::submitButton('Delete' , ['class' => 'btn btn-group-lg btn-group btn-success']) ?>          
                    
                    <?= Html::a("Cancel", Url::toRoute(['coe-activity-marks/deletereport']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>
            </div>

             <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>