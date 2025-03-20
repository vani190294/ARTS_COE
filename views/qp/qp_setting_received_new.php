<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Categorytype;
use yii\db\Query;
use app\models\QpSetting;
use app\models\Batch;
echo Dialog::widget();
$this->title = 'QP Setting Received';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  

    <div class="col-xs-12 col-sm-12 col-lg-12">  


       <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'qp_scru_year','name'=>'qp_scru_year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [
                        'data' => $model->getMonth(),                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'qp_setting_month',
                            'name' => 'qp_setting_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>


         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo $form->field($model,'qp_setting_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Date----', 
                        'id' => 'qp_setting_date',  
                        'name' => 'qp_setting_date',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>       
        </div>   

         <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($model,'faculty1_id')->widget(
                Select2::classname(), [
                     'data' => $model->getfaculty(),
                    'options' => [
                        'placeholder' => '-----Select Faculty----',      
                        'id'=>'val_faculty_id', 
                        'name'=>'val_faculty_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Faculty');  
            ?>  
        </div> 


        <div class="col-xs-12 col-sm-2 col-lg-2"><br>
            <?= Html::Button('Show' , ['onClick'=>"getqpsettingassign();",'class' => 'btn btn-group-lg btn-group btn-success']) ?>          
            
            <?= Html::a("Reset", Url::toRoute(['qp/qpsettingreceived']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>

    </div>
    
    <div class="col-lg-12 col-sm-12" id="showqpsettingreceived" style="display: none;">
            
        <div id="viewdata"></div>

        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right"><br>
            <?= Html::submitButton('Save' , ['class' => 'btn btn-group-lg btn-group btn-success']) ?>          
            
            <?= Html::a("Cancel", Url::toRoute(['qp/qpsettingreceived']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

