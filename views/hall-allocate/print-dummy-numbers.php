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
$this->title = 'Report Register Numbers';
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
                            'id' => 'dummyexam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
      <div class="col-lg-2 col-sm-2">
        <?php 

        echo $form->field($valfaclall,'valuation_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date----', 
                        'id'=>'valuation_date',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ]); 
            ?>        
        
    </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
           <?php echo $form->field($valfaclall,'valuation_session')->widget(
                Select2::classname(), [
                    'data' => ['FN'=>'FN','AN'=>'AN'],
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' ----', 
                        'id'=>'valuation_session',                       
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
 			<?= Html::Button('Show & Print' , ['onClick'=>"spinner();",'class' => 'btn btn-group-lg btn-group btn-success','id'=>'print_dummynumber_btn' ]) ?>
            <?= Html::a("Reset", Url::toRoute(['hall-allocate/print-dummy-numbers']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
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

            $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['hall-allocate/print-dummy-numbers-pdf'], [
                        'class' => 'pull-right btn btn-block btn-primary',
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Will open the generated PDF file in a new window'
                ]);
                

                 echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > ' . $print_pdf. ' </div></div></div></div></div>';
            ?>
           <div id="register_answer_packets">
           </div>

        </div>
    </div>

</div>
