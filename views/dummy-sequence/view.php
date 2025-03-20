<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DummySequence */

$this->title = $model->coe_store_dummy_mapping;
$this->params['breadcrumbs'][] = ['label' => 'Dummy Sequences', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dummy-sequence-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_store_dummy_mapping], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_store_dummy_mapping], [
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
            'coe_store_dummy_mapping',
            'subject_map_id',
            'year',
            'month',
            'dummy_from',
            'dummy_to',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
