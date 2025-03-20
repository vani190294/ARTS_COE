<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubjectPrefix */

$this->title = $model->coe_prefix_id;
$this->params['breadcrumbs'][] = ['label' => 'Subject Prefixes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-prefix-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_prefix_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_prefix_id], [
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
            'coe_prefix_id',
            'coe_dept_id',
            'prefix_name',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
