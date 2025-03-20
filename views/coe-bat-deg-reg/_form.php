<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\CoeBatDegReg */
/* @var $form yii\widgets\ActiveForm */
$superAdmin = $checkAccess = ConfigUtilities::HasSuperAccess(Yii::$app->user->getId());
$reg_update = $superAdmin=='Yes'?['readonly'=>'false']:['readonly'=>'true'];
Yii::$app->ShowFlashMessages->showFlashes();
?>

<div class="coe-bat-deg-reg-form">

    <?php $form = ActiveForm::begin(); ?>
<table border="1" class="table table-responsive-xl table-responsive ">
    <tr>       
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?></th>
        <td><?= Html::encode($model->coeBatch->batch_name) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?></th>
        <td><?= Html::encode($model->coeDegree->degree_code) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME); ?></th>
        <td><?= Html::encode($model->coeProgramme->programme_code) ?></td>
    </tr>
    <tr>       
        <th>Regulation Year</th>
        <td><?= $form->field($model, 'regulation_year')->textInput([$reg_update ,'value'=>$model->regulation_year])->label(false) ?></td>
        <th><?php echo "No Of ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION); ?></th>
        <td><?= $form->field($model, 'no_of_section')->textInput(['value'=>$model->no_of_section])->label(false) ?>
        </td>
        
    </tr>
</table>
    <?php 
        $model->coe_degree_id=$model->coe_degree_id;
        $model->coe_programme_id=$model->coe_programme_id;
        $model->coe_batch_id=$model->coe_batch_id;

      ?>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','data-confirm' => 'Are you sure you want to Update this records <br /> This can not be Un-Done once the values were changed Until you <b>CONTACT THE SUPPORT TEAM?</b> Please re-check your Submission and Click <b>OK</b> to proceed.']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
