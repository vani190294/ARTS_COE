<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeBatDegReg */

$this->title = 'Update Coe Bat Deg Reg: ' . $model->coe_bat_deg_reg_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Bat Deg Regs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_bat_deg_reg_id, 'url' => ['view', 'id' => $model->coe_bat_deg_reg]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-bat-deg-reg-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
