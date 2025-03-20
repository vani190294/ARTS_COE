<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Servicesubjecttodept */

$this->title = $model->coe_servtodept_id;
$this->params['breadcrumbs'][] = ['label' => 'Servicesubjecttodepts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicesubjecttodept-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_servtodept_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_servtodept_id], [
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
            'coe_servtodept_id',
            'coe_cur_subid',
            'coe_dept_ids',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
