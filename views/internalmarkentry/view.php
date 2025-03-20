<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InternalMarkentry */

$this->title = $model->mark_entry_id;
$this->params['breadcrumbs'][] = ['label' => 'Internal Markentries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="internal-markentry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->mark_entry_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->mark_entry_id], [
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
            'mark_entry_id',
            'student_map_id',
            'subject_map_id',
            'category_type_id',
            'mark_out_of',
            'category_type_id_marks',
            'year',
            'month',
            'term',
            'mark_type',
            'status_id',
            'attendance_percentage',
            'attendance_remarks',
            'is_updated',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
