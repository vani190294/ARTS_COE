<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\HallAllocate;    
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

$this->title="View Moderation";

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
     
		<div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['name'=>'view_mod_mark_year','value'=>date('Y')]) ?>
        </div>
        <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '--- Select Month ---',
                            'id'=>'exam_month',
                            'name'=>'mod_month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
        </div>
        <br />
	<div class="col-sm-3 col-lg-3">
		<div class="form-group col-lg-12 col-sm-12">
			<input onClick="spinner();" type="submit" id="view_mod" name="view_mod" class="btn btn-success" value="Submit">
            <?= Html::a("Reset", Url::toRoute(['mark-entry/viewmoderation']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
		</div>
	</div>
   
    </div>
	<?php ActiveForm::end(); ?>

<?php
    include_once("moderation_pdf.php");
?>

</div>
</div>
</div>


