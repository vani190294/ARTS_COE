<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
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

$this->title="Additional Credits Mark Entry";

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
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">      
          
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($add_credits, 'subject_code')->textInput(['id'=>'arts_add_sub_code','name'=>'add_sub_code','onblur'=>'getArtsSubInfoUpdate(this.value);']) ?>
            </div>
            <div class="col-lg-4 col-sm-4">
                <?= $form->field($add_credits, 'subject_name')->textInput(['id'=>'add_sub_name_update','name'=>'add_sub_name']) ?>
            </div>
            <div class="col-xs-4 col-sm-12 col-lg-4">
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <div class="form-group col-lg-6 col-sm-6"> <br />
                    <div class="btn-group" role="group" aria-label="Actions to be Perform">

                        <?= Html::Button('UPDATE', ['value'=>'Submit','onClick'=>"updateSubjectName($('#arts_add_sub_code').val());",'class' => 'btn  btn-group-lg btn-group btn-success']) ?>
                        
                        <?= Html::a("Reset", Url::toRoute(['mark-entry/additionalcredits-update']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>                

                    </div>                
                </div>
            </div>
        </div>
    </div>
        </div>
        

    

    
<?php ActiveForm::end(); ?>

</div>
</div>
</div>