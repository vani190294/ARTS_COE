<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveWaiver */

$this->title = 'Update Elective Waiver: ' . $model->coe_elective_waiver_id;
$this->params['breadcrumbs'][] = ['label' => 'Elective Waivers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_elective_waiver_id, 'url' => ['view', 'id' => $model->coe_elective_waiver_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="elective-waiver-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
