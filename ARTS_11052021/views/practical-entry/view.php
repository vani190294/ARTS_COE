<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PracticalEntry */

$this->title = $model->coe_practical_entry_id;
$this->params['breadcrumbs'][] = ['label' => 'Practical Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practical-entry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_practical_entry_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_practical_entry_id], [
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
            'coe_practical_entry_id',
            'student_map_id',
            'subject_map_id',
            'out_of_100',
            'ESE',
            'year',
            'month',
            'term',
            'mark_type',
            'approve_status',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
