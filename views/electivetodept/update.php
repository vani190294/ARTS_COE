<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Electivetodept */

$this->title = 'Update Assinged Elective to Dept.';
$this->params['breadcrumbs'][] = ['label' => 'Electivetodepts', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => 'View', 'url' => ['view', 'id' => $model->coe_electivetodept_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="electivetodept-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdate', [
        'model' => $model,
    ]) ?>

</div>
