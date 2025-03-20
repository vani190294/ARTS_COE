<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\Batch;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\HallAllocate;
use app\models\ExamTimetable;
use kartik\date\DatePicker;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Edit Exam Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Hall Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
 <h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 
$form = ActiveForm::begin([
                            'enableClientValidation' => true, 
                            'id' => 'student_form_required_page',
                            'fieldConfig' => [
                                'template' => "{label}{input}{error}",
                            ],
                        ]); ?>
<div>&nbsp;</div>

<div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-xs-12 col-sm-12 col-lg-12">
        
        <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'exam_year')->textInput(['id' => 'exam_year','value'=>date('Y')]) ?>
        </div>
        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model,'exam_month')->widget(
                Select2::classname(), [
                    'data' => HallAllocate::getMonth(),
                    'options' => [
                        'placeholder' => '-----Select Exam Month ----',
                        'id' => 'exam_month',
                        'onchange'=>'geteditExamDate(this.value,$("#exam_year").val())',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
                ?>
        </div> 

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php echo $form->field($model,'exam_date')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-- Select Exam Date ...',
                        'autocomplete' => 'OFF',
                        'id' => 'exam_date',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) 
            ?>
            
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo '<label>New Exam Date </label>';
                echo DatePicker::widget([
                    'name' => 'new_exam_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => [
                        'placeholder' => '-- Select Exam Date ...',
                        'autoChange' => 'OFF',
                        'onchange'=>'getPreconfirm(this.value, $("#exam_date").val(), $("#exam_month").val(), $("#exam_year").val())',
                    ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                    ],
                                       
                ]);
            ?>
        </div>
</div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group col-lg-3 col-sm-3">
            <div class="btn-group" role="group" aria-label="Actions to be Perform">                
               
                <?= Html::a("Reset", Url::toRoute(['exam-timetable/edit-exam-timetable']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>

            </div>
            
        </div>
    </div>
    <div class="row">
        <div id='disp_show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">
            <div id='show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">

            </div>
            <?= Html::submitButton('Confirm', ['id'=>'update_comp','class' => 'btn pull-right btn-success' ,'data-confirm' => 'Are you sure you want to Continue <br /> This can not be Un-Done once Submitted.','formtarget'=>"_blank"]) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

</div>
</div>
</div>
