<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Servicesubjecttodept */

$this->title = 'Update Assinged Service subject to dept: ';
$this->params['breadcrumbs'][] = ['label' => 'Servicesubjecttodepts', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->coe_servtodept_id, 'url' => ['view', 'id' => $model->coe_servtodept_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="servicesubjecttodept-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
