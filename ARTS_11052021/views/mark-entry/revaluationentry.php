<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\HallAllocate;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\MarkEntry;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Revaluation / Transparency Application";

?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
        ]); 
    ?>

	<div class="col-xs-12 col-sm-12 col-lg-12">
		<div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['id'=>'reval_entry_year','value'=>date('Y'),'name'=>'reval_entry_year']) ?>
        </div>

        <div class="col-xs-12 col-lg-2 col-sm-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'reval_entry_month',   
                            'name' => 'reval_entry_month',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-lg-2 col-sm-2">
            <?= $form->field($student, 'register_number')->textInput(['id'=>'stu_reg_num','name'=>'stu_reg_num','onblur'=>'getStuRgNumber(this.value);']) ?>
            <input type="hidden" name="max_reval_papers" id="max_reval_papers" value="<?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_MAX_REVAL_SUBJECTS); ?>" >
        </div>
        
	
        <div class="form-group col-lg-3 col-sm-3"> <br />
            <div class="btn-group" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Submit', ['value'=>'Submit','name'=>"revaluationentry_btn" ,'id'=>"revaluationentry_btn",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                
                <?= Html::a("Reset", Url::toRoute(['mark-entry/revaluationentry']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>            
        </div>
        <div class="col-lg-2 col-sm-2" id="disp_name_of_stu">
            
            
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 reval_pdf_button">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-revaluationamount','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/mark-entry/revaluation-amount-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12 revaluationentry">
        <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">                
            <div id = "stu_revaluation_entry_tbl"></div>
        </div>
        
        <div class="form-group col-lg-2 col-sm-2 pull-right revaluation_entry_done_btn">
            <?= Html::submitButton('Finish & Print' , ['id' =>'revaluation_entry_btn', 'name'=>'revaluation_entry_btn' , 'class' => 'btn btn-primary','data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once the values were Submitted.','formtarget'=>"_blank"]) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

</div>
</div>
</div>