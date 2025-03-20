<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\HallAllocate;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */


//$max_value = isset($check_max_digists) && $check_max_digists!='' ? $check_max_digists : '';
$this->title= "Empty Mark Sheet ";
?>
<h1><?php echo $this->title; ?></h1>
<div>&nbsp;</div>
<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 


<?php 

$form = ActiveForm::begin(); ?>
<div>&nbsp;</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',    
                            'onchange' => 'bringYearMonthSubs(this.value,$("#exam_year").val());',                        
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
         <div class="col-xs-12 col-sm-2 col-lg-2">
             <?php 

             echo $form->field($model,'subject_map_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----',
                        'id' => 'dummy_exam_subject_code',
                        'name'=>'exam_subject_code',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);


            ?>
        </div>
        
        
    </div>

</div>
</div>
<div class="row">
   <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-6 col-lg-6">            
            <br />

            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/empty-mark-sheet']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>  
        </div>
    </div>
    <?php ActiveForm::end(); ?>
<?php 
if(isset($full_html) && !empty($full_html))
{
?>    
    <div id='hide_dum_data_external' class="row">
        <div class="col-xs-12"> <br /><br />
            <div class="col-xs-1"> &nbsp; </div>
            <div class="col-xs-10">
        <?php
            echo Html::a('<i class="fa fa-file-pdf-o"></i> Export Pdf', ['/dummy-numbers/dummy-external-pdf'], [
                'class'=>'pull-right btn btn-success', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
            ]);
        ?>
        <div class="show_data_dummy_external">
            <?php echo $full_html; ?>
        </div>
    </div>
    <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div>

<?php 
}
?>



</div>
</div>
</div>