<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UpdateTracker */

$this->title = $model->coe_update_tracker_id;
$this->params['breadcrumbs'][] = ['label' => 'Update Trackers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="update-tracker-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_update_tracker_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_update_tracker_id], [
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
            'coe_update_tracker_id',
            'student_map_id',
            'subject_map_id',
            'exam_year',
            'exam_month',
            'updated_ip_address',
            'updated_link_from',
            'data_updated:ntext',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
