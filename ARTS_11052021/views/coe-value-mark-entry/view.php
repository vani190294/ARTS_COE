<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueMarkEntry */

$this->title = $model->coe_value_mark_entry_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Value Mark Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-value-mark-entry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_value_mark_entry_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_value_mark_entry_id], [
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
            'coe_value_mark_entry_id',
            'student_map_id',
            'subject_map_id',
            'CIA',
            'ESE',
            'total',
            'result',
            'grade_point',
            'grade_name',
            'year',
            'month',
            'term',
            'mark_type',
            'status_id',
            'year_of_passing',
            'attempt',
            'withheld',
            'withheld_remarks',
            'withdraw',
            'is_updated',
            'fees_paid',
            'result_published_date',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
