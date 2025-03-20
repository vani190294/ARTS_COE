<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CoreFacultys */

$this->title = $model->cur_cf_id;
$this->params['breadcrumbs'][] = ['label' => 'Core Facultys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="core-facultys-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cur_cf_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cur_cf_id], [
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
            'cur_cf_id',
            'degree_type',
            'coe_regulation_id',
            'coe_dept_id',
            'subject_code',
            'semester',
            'faculty_ids',
            'no_of_section',
            'approve_status',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
