<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\CoeBatDegReg */

$this->title = 'Update : ' . $model->coeDegree->degree_code." ".$model->coeProgramme->programme_code;
$this->params['breadcrumbs'][] = ['label' => 'Bat Deg Regs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coeDegree->degree_code." ".$model->coeProgramme->programme_code, 'url' => ['view', 'id' => $model->coe_bat_deg_reg_id]];
$this->params['breadcrumbs'][] = 'Update';
Yii::$app->ShowFlashMessages->showFlashes();
?>
<div class="coe-bat-deg-reg-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
