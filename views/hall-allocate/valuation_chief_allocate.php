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
$this->title = 'Valuation Chief Examinor Allocate';
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
            <?= $form->field($model, 'year')->textInput(['id'=>'exam_year','value'=>date('Y')]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'chief_month',                          
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-lg-2 col-sm-2">
       
            <?php 
            echo $form->field($factallModel,'valuation_date')->widget(
                    Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select Valutation Date----', 
                        'id' => 'valuation_date',   
                        'onchange'=>'getvaluationsubject()'      
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'subject_code')->widget(
                Select2::classname(), [
                    'options' => [
                        'multiple'=>'multiple',
                        'placeholder' => '-----Select Subject----',      
                        'id'=>'subject_code',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div> 
         <div class="col-xs-12 col-sm-3 col-lg-3">
           <?php echo $form->field($factallModel,'coe_val_faculty_id')->widget(
                Select2::classname(), [
                    'data' => $factallModel->getfaculty(),
                    'options' => [
                        //'multiple'=>'multiple',
                        'placeholder' => '-----Select Chief----',      
                        'id'=>'val_faculty_id',                   
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Chief Examiner'); 
            ?>  
        </div>


    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">

    	<div class="col-xs-12 col-sm-3 col-lg-3">
            <br>
 			<?= Html::Button('Assign' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'chiefallocate_assign' ]) ?>
            <?= Html::Button('View Status' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'chiefallocate_view' ]) ?>
            
            <?= Html::a("Reset", Url::toRoute(['hall-allocate/valuationchiefallocate']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
    	</div>
    </div>

    
    <?php ActiveForm::end(); ?>

    <div id="answer_packets_div1">

    <div class="col-12">

        <div class="col-lg-12 col-sm-12">
            <?php 

            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['hall-allocate/valuationchiefallocate-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
                ]);
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/hall-allocate/valuationchiefallocate-excel'], [
                            'class' => 'pull-right btn btn-block btn-warning',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                ]);

                 echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';
            ?>

             <div id="answer_packets1">
           </div>

        </div>
    </div>

</div>

</div>
</div>
</div>

