<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\ExamTimetable;
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
$this->title = $model->student->register_number.' - '.$model->subject->subject_code;
$this->params['breadcrumbs'][] = ['label' => 'Practical Mark Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            
            <table class="table table-responsive-xl table-responsive table-striped">
            <tr>
                
                <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?></th>
                <td><?= Html::encode($model->batch->batch_name) ?></td>
                <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?></th>
                <td><?= Html::encode($model->degree->degree_code) ?></td>
                <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME); ?></th>
                <td><?= Html::encode($model->programme->programme_code) ?></td>
                <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code'; ?></th>
                <td><?= Html::encode($model->subject->subject_code) ?></td>
                
            </tr>
            <tr>
                <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name'; ?></th>
                <td><?= Html::encode($model->subject->subject_name) ?></td>
                <th><?php echo 'Exam Year'; ?></th>
                <td><?=  Html::encode($model->exam_year) ?></td>
                <th><?php echo 'Exam Month'; ?></th>
                <td><?=  Html::encode($model->month->description) ?></td>
                <th><?php echo 'Exam Date'; ?></th>
                <td><?=  Html::encode(DATE('d-m-Y',strtotime($model->exam_date))) ?></td>
                          
            </tr>
            <tr>
                <th><?php echo 'Exam Session'; ?></th>
                <td><?=  Html::encode($model->examSess->description) ?></td>
                <th><?php echo 'Internal Examiner Name'; ?></th>
                <td><?= Html::encode($model->internal_examiner_name) ?></td> 
                <th><?php echo 'External Examiner Name'; ?></th>
                <td><?= Html::encode($model->external_examiner_name) ?></td> 
                <th><?php echo 'Mark Type'; ?></th>
                <td><?= Html::encode($model->mark_type) ?></td>           
            </tr>
            <tr>                
                <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT); ?></th>
                <td><?= Html::encode($model->student->register_number) ?></td> 
                <th><?php echo 'Marks'; ?></th>
                <td><?= $form->field($model, 'out_of_100')->textInput(['required'=>'required','style'=>'text-transform: uppercase','value'=>$model->out_of_100,'onkeypress'=>'numbersOnly(event);allowEntr(event,this.id);','onchange'=>'check_max_number(this.id,this.value);' ])->label(false); ?> </td>  
                <td colspan="4">
                    <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                        <?= Html::submitButton('Update ', ['class' => 'btn btn-success' ]) ?>
                    
                        <?= Html::a("Reset", Url::toRoute(['practical-exam-timetable-new/create']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                    </div>
                </td>              
            </tr>
          </table>
            </div>
        
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>