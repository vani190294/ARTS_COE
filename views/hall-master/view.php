<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\HallMaster */

$this->title = $model->hall_name;
$this->params['breadcrumbs'][] = ['label' => 'Hall Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hall-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_hall_master_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_hall_master_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'hall_name',
            'description',
            //'hall_type_id',
           
        ],
    ]) ?>

</div>
