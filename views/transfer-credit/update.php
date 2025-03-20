<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeTransferCredit */

$this->title = 'Update Coe Transfer Credit: ' . $model->coe_tc_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Transfer Credits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_tc_id, 'url' => ['view', 'id' => $model->coe_tc_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-transfer-credit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
