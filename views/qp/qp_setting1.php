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
echo Dialog::widget();
$this->title = 'QP Setting';
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
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'qp_year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [
                        'data' => $model->getMonth(),                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'qpassign_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
      <div class="col-lg-2 col-sm-2">
       
        <?php 
        echo $form->field($model,'qp_code')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select QP Code----',  
                        'id' => 'qp_code',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
    <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo '<label>Qp Date </label>';
                echo DatePicker::widget([
                    'name' => 'qp_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'id'=>'qp_date',  
                    'options' => [
                        
                        'placeholder' => '-- Select QP Date ...',
                        'autocomplete' => 'OFF',
                    ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                        'allowClear' => true,
                    ],
                                       
                ]);
            ?>
        </div>
    <div class="col-lg-2 col-sm-2">
       
        <?php 
        echo $form->field($model,'faculty1_id')->widget(
                Select2::classname(), [
                    'data' => $model->getfaculty(), 
                    'options' => [
                        'placeholder' => '-----Select Faculty1----', 
                        'id' => 'faculty1_id',         
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
         
       
        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($model,'faculty2_id')->widget(
                Select2::classname(), [
                    'data' => $model->getfaculty(), 
                    'options' => [
                        'placeholder' => '-----Select Faculty2----',      
                        'id'=>'faculty2_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    
                ]); 
            ?>  
        </div> 

        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($model,'faculty1_session')->widget(
                Select2::classname(), [
                    'data' => ['1'=>'Half Day','2'=>'Full Day'],
                    'options' => [
                        'placeholder' => '-- Select Session --',   
                        'id'=>'faculty1_session',                 
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);
            ?>  
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($model,'faculty2_session')->widget(
                Select2::classname(), [
                    'data' => ['1'=>'Half Day','2'=>'Full Day'],
                    'options' => [
                        'placeholder' => '-- Select Session --',   
                        'id'=>'faculty2_session',                 
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);
            ?>  
        </div>

        

 
    </div>


    <div class="col-xs-12 col-sm-12 col-lg-12">
    	<div class="col-xs-12 col-sm-3 col-lg-3">
 			<?= Html::Button('Assign' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'qpsettingassign' ]) ?>
            <?= Html::Button('View' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'qpsettingassign_view' ]) ?>
            
            <?= Html::a("Reset", Url::toRoute(['qp/qpsetting']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
    	</div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
    	&nbsp;
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<div id="qp_div1" style="display: none;">

    <div class="col-12">
        <div class="col-lg-12 col-sm-12">
            <?php 

            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['qp/qpsetting-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
                ]);
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['qp/qpsetting-excel'], [
                            'class' => 'pull-right btn btn-block btn-warning',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                ]);

                 echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';
            ?>
             
           <div id="qp_data">
           </div>

        </div>
    </div>

</div>
