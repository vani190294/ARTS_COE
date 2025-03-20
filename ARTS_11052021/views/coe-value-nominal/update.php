<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueNominal */

$this->title = 'Update Coe Value Nominal: ' . $model->coe_nominal_val_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Value Nominals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_nominal_val_id, 'url' => ['view', 'id' => $model->coe_nominal_val_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-value-nominal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
