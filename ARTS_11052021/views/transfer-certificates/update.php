<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\TransferCertificates */

$this->title = 'Update Transfer Certificates: ' . $model->register_number;
$this->params['breadcrumbs'][] = ['label' => 'Transfer Certificates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->register_number, 'url' => ['view', 'id' => $model->register_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transfer-certificates-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
