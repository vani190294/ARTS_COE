<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
echo Dialog::widget();
$this->title = 'Print Register Numbers';
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
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y')]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
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
      <div class="col-lg-2 col-sm-2 exam_wise">
        <input type="hidden" id='exam_year' name="year" value="">
        <?php 
        $exam_year = isset($model->year)?$_POST['year']:date("Y");

        echo $form->field($absentModel,'exam_date')->widget(
                Select2::classname(), [
                    'data' => $absentModel->getExamDates($exam_year),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date----',  
                        'onchange'=>'showSessions(this.value, $("#hallallocate-year").val(), $("#exam_month").val());',
                        'id'=>'exam_date',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($absentModel,'exam_session')->widget(
                Select2::classname(), [
                    'data' => $examTimetable->getExamSession(),
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' ----',                        
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); 
            ?>  
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($absentModel,'exam_session')->widget(
                Select2::classname(), [
                    'data' => ['10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50','60'=>'60','70'=>'70','80'=>'80','90'=>'90','100'=>'100'],
                    'options' => [
                        'placeholder' => '----- Register Number Count ----',   
                        'id'=>'total_print_reg',                     
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('Print Count'); 
            ?>  
        </div> 
     
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
    	<div class="col-xs-12 col-sm-3 col-lg-3">
 			<?= Html::Button('Show & Print' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'print_packets_btn' ]) ?>
            <?= Html::a("Reset", Url::toRoute(['hall-allocate/print-register-numbers']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
    	</div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
    	&nbsp;
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<div id="register_date_print_div">

    <div class="col-12">
        <div class="col-lg-12 col-sm-12">
            <?php 

            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['hall-allocate/print-register-numbers-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
                ]);
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/hall-allocate/print-register-numbers-excel'], [
                            'class' => 'pull-right btn btn-block btn-warning',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                ]);

                 echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf. " ".$print_excel . ' </div></div></div></div></div>';
            ?>
           <div id="register_answer_packets">
           </div>

        </div>
    </div>

</div>
