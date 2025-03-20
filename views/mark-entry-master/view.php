<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntryMaster */

$this->title = $model->coe_mark_entry_master_id;
$this->params['breadcrumbs'][] = ['label' => 'Mark Entry Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-entry-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_mark_entry_master_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_mark_entry_master_id], [
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
            'coe_mark_entry_master_id',
            'student_map_id',
            'subject_map_id',
            'CIA',
            'ESE',
            'total',
            'result',
            'grade_point',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
