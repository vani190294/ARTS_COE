<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HallAllocate */

$this->title = 'Update Hall Allocate: ' . $model->coe_hall_allocate_id;
$this->params['breadcrumbs'][] = ['label' => 'Hall Allocates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_hall_allocate_id, 'url' => ['view', 'id' => $model->coe_hall_allocate_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hall-allocate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
