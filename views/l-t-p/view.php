<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LTP */

$this->title = $model->coe_ltp_id;
$this->params['breadcrumbs'][] = ['label' => 'Ltps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ltp-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_ltp_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_ltp_id], [
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
            'coe_ltp_id',
            'coe_regulation_id',
            'L',
            'T',
            'P',
            'contact_hrsperweek',
            'credit_point',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
