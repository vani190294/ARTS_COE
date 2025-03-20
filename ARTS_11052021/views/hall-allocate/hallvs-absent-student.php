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

$this->title = 'Hall Vs '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT);
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
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'qp_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($exam, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date ----', 
                            'id' => 'qp_exam_date',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($exam, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION).' ----', 
                            'id' => 'qp_exam_session',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]); ?>
        </div>        
        <br />
    	<div class="col-xs-12 col-sm-3 col-lg-3">
 			<input type="button" class="btn btn-success" name="hallvsstu" value="Submit" id="hallstuabsent">
            <?= Html::a("Reset", Url::toRoute(['hall-allocate/hallvs-absent-student']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
    	</div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
    	&nbsp;
    </div>

     <div class="show_hall_vs_stu_print row">
        <div class="pull-right col-md-4">
            <?php 

                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('hallvsabsentstudentpdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excelhallvsabsentstudent','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff'));

                ?>
        </div>
    </div>
    <div id="hall_stu_tbl" class="col-xs-12 col-sm-12 col-lg-12">
        
    	<div class="panel box box-primary">
            <div class="box-header with-border">
                <div class="row">

                    <div class="col-md-10">
                        <h4 class="show_hall_vs_stu padding box-title">

                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseDegree">Hall Vs <?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT) ?> Information </a>
                        </h4>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>  
    
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>



