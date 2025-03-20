<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HallMaster */

$this->title = 'Update Hall Master: ' . $model->hall_name;
$this->params['breadcrumbs'][] = ['label' => 'Hall Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->hall_name, 'url' => ['view', 'id' => $model->coe_hall_master_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hall-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categorytype' => $categorytype,
    ]) ?>

</div>
