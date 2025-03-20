<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;

echo Dialog::widget();


/* @var $this yii\web\View */
/* @var $model app\models\Regulation */
/* @var $form yii\widgets\ActiveForm */
Yii::$app->ShowFlashMessages->showFlashes();
$superAdmin = $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
$reg_update = $superAdmin=='Yes'?['readonly'=>'false']:['readonly'=>'true'];
$batch_id = isset($model->coe_batch_id)?$model->coe_batch_id:'';
$enable_acc = !$model->isNewRecord?'1':'0';
?>

<div class="regulation-form">

    <?php $form = ActiveForm::begin(); ?>
<table border="<?php echo $enable_acc; ?>" class="table table-responsive-xl table-responsive ">
    <tr> 
        <td>                
            <?php echo $form->field($model,'coe_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'value'=>$batch_id,
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </td>
        <td><?= $form->field($model, 'regulation_year')->textInput([$reg_update ,'value'=>$model->regulation_year]) ?></td>

    </tr>
    
    <?php 
            $model->regulation_year=$model->regulation_year;
            $model->coe_batch_id=$model->coe_batch_id;
            $model->grade_point_from=$model->grade_point_from;
            $model->grade_point_to=$model->grade_point_to;

        ?>
    <tr>       
        
        <td><?= $form->field($model, 'grade_point_from')->textInput([$reg_update]) ?></td>
       
        <td><?= $form->field($model, 'grade_point_to')->textInput([$reg_update]) ?></td>
        <td><?= $form->field($model, 'grade_name')->textInput([$reg_update ,'maxlength' => true]) ?></td>
        <td><?= $form->field($model, 'grade_point')->textInput([$reg_update]) ?></td>
        
        

    </tr>
</table>    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data-confirm' => 'Are you sure you want to Update this records <br /> This can not be Un-Done once the values were changed Until you <b>CONTACT THE SUPPORT TEAM?</b> Please re-check your Submission and Click <b>OK</b> to proceed.']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

