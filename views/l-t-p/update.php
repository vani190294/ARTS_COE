<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LTP */

$this->title = 'Update Ltp: ' . $model->coe_ltp_id;
$this->params['breadcrumbs'][] = ['label' => 'Ltps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_ltp_id, 'url' => ['view', 'id' => $model->coe_ltp_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ltp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
