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
$this->title = 'ReValuation Faculty Allocate';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div style="text-align: center; color: #000;">Note: If Delete Record Please Click Valuation Status and View </div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  

    <div class="col-xs-12 col-sm-12 col-lg-12">                
       <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date("Y")]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'month_valfac',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
     
       
        <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'subject_code')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Subject----',      
                        'id'=>'subject_code',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div> 

        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($factallModel,'board')->widget(
                Select2::classname(), [
                    'data' => ['CIVIL'=>'CIVIL','CSE/IT'=>'CSE/IT','ECE'=>'ECE','EEE'=>'EEE','ICE'=>'ICE','MECH'=>'MECH','MBA'=>'MBA','MATHS'=>'MATHS','PHYSICS'=>'PHYSICS','CHEMISTRY'=>'CHEMISTRY','ENGLISH'=>'ENGLISH'],
                    'options' => [
                        'placeholder' => '----- Select Board ----',   
                        'id'=>'board',          
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Board'); 
            ?>  
        </div>

         <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Faculty----',      
                        'id'=>'val_faculty_id',                   
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
            <?php 
                echo '<label>Valuation Date </label>';
                echo DatePicker::widget([
                    'name' => 'valuation_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'id'=>'val_faculty_date',  
                    'options' => [
                        
                        'placeholder' => '-- Select Valuation Date ...',
                        'autocomplete' => 'OFF',
                    ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                    ],
                                       
                ]);
            ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($factallModel,'valuation_session')->widget(
                Select2::classname(), [
                    'data' => ['FN'=>'FN','AN'=>'AN'],
                    'options' => [
                        'placeholder' => '-- Select Session --',   
                        'id'=>'valuation_session',                 
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Valuation Session'); 
            ?>  
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($scrutinymodel,'coe_scrutiny_id')->widget(
                Select2::classname(), [
                    'data' => $scrutinymodel->getScrutiny(),  
                    'options' => [
                        'placeholder' => '-----Select Scrutiny----',      
                        'id'=>'coe_scrutiny_id1',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div> 


       
         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo '<label>Scrutiny Date </label>';
                echo DatePicker::widget([
                    'name' => 'scrutiny_date', 
                    'type' => DatePicker::TYPE_INPUT,
                    'id'=>'scrutiny_date1',  
                    'options' => [
                        
                        'placeholder' => '-- Select Scrutiny Date ...',
                        'autocomplete' => 'OFF',
                    ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                    ],
                                       
                ]);
            ?>
        </div>

        
        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($factallModel,'scrutiny_session')->widget(
                Select2::classname(), [
                    'data' => ['FN'=>'FN','AN'=>'AN'],
                    'options' => [
                        'placeholder' => '-- Select Session --',   
                        'id'=>'scrutiny_session1',                 
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Scrutiny Session'); 
            ?>  
        </div>


        <div class="col-xs-12 col-sm-2 col-lg-2">
            <br>
            <?= $form->field($factallModel, 'valuation_status')->checkbox(['value'=>1,'id'=>'valuation_status']) ?>
        </div>

         <div class="col-xs-12 col-sm-12 col-lg-12" style="display: none;" id="val_faculty_id22">

         <div class="col-xs-12 col-sm-2 col-lg-2" >
           <?php echo $form->field($factallModel,'coe_val_faculty_id2')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Faculty2----',      
                        'id'=>'val_faculty_id2',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('2nd Faculty'); 
            ?>  
        </div>

    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= Html::Button('Assign' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'valuationrevalallocate' ]) ?>
            <?= Html::Button('View' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'valuationrevalallocate_view' ]) ?>
            
            <?= Html::a("Reset", Url::toRoute(['hall-allocate/valuationrevalallocate']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<div id="answer_packets_div1">

    <div class="col-12">
        <div class="col-lg-12 col-sm-12">
            <?php             
            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['hall-allocate/valuation-revalallocate-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
                ]);
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/hall-allocate/valuationrevalallocate-excel'], [
                            'class' => 'pull-right btn btn-block btn-warning',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                ]);

                 echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';
            ?>

            <div style="text-align: center; color: #F00;">Note: If Mark entered, Valuator can not be delete</div>
           <div id="answer_packets1">
           </div>

        </div>
    </div>

</div>
