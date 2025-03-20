<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\HallAllocate;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use kartik\date\DatePicker;
use kartik\time\TimePicker;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */
$this->title='Practical Exam Timetable Delete';
$this->params['breadcrumbs'][] = ['label' => 'Practical Exam Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div>&nbsp;</div>
<div class="mark-entry-form">
     <h1><?= Html::encode($this->title) ?></h1>
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'exam_year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'exam_month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'onchange'=>'getPracExamSubs(this.value,$("#mark_year").val())',
                            'class'=>'student_disable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>

                <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'subject_map_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code----',
                            'id' => 'mark_subject_code',    
                            'onchange'=>'getPracExamSubsDa(this.value,$("#exam_month").val() , $("#mark_year").val())',           
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code');
                ?>
            </div>
                <div class="col-xs-12 col-sm-2 col-lg-2">
                    <?php 

                        echo $form->field($model, 'exam_date')->widget(
                        Select2::classname(), [
                            'options' => [
                                'placeholder' => '-----Select Date ----',
                                'id' => 'exam_date',    
                                'onchange'=>'getPracExamSubsDaSess(this.value,$("#mark_subject_code").val() ,$("#exam_month").val(), $("#mark_year").val())',           
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date');
                    ?>
                </div>
                <div class="col-xs-12 col-sm-2 col-lg-2">

                    <?php echo $form->field($model, 'exam_session')->widget(
                            Select2::classname(), [
                                'data'=>ConfigUtilities::getPracExamSessions(),                                    
                                'options' => [
                                    'id' => 'exam_session_prac',    
                                    'placeholder' => '-----Select ----',
                                    'class'=>'form-control student_disable',                                    
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ]); 
                        ?>
                </div>

        </div>

            <div class="col-xs-12 col-sm-12 col-lg-12">
                    
                    <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                    <br />
                    <?= Html::Button('Delete ', ['onClick'=>'deletePracticalexam($("#exam_date").val(), $("#exam_session_prac").val() , $("#mark_subject_code").val() ,$("#exam_month").val(), $("#mark_year").val())','id'=>'change_name','class' => 'btn btn-success' ]) ?>
                
                    <?= Html::a("Reset", Url::toRoute(['practical-exam-timetable-new/delete-practical-exam-timetable']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>                     
        </div>
       
      
        
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>