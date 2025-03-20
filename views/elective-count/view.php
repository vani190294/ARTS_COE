<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\Models\ElectiveCount */

$this->title = $model->cur_ec_id;
$this->params['breadcrumbs'][] = ['label' => 'Elective Counts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-count-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cur_ec_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cur_ec_id], [
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
            'cur_ec_id',
            'degree_type',
            'coe_regulation_id',
            'coe_dept_id',
            'elective_type',
            'elective_count',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
