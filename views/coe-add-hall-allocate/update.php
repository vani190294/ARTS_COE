<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeAddHallAllocate */

$this->title = 'Update Coe Add Hall Allocate: ' . $model->coe_add_hall_allocate_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Add Hall Allocates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_add_hall_allocate_id, 'url' => ['view', 'id' => $model->coe_add_hall_allocate_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-add-hall-allocate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
