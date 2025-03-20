<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */

$this->title = $model->studentMap->studentRel->register_number;
$this->params['breadcrumbs'][] = ['label' => 'Mark Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-entry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_mark_entry_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_mark_entry_id], [
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
            //'coe_mark_entry_id',
            //'student_map_id',
            [
                'label' =>'Reg No',
                'attribute' => 'student_map_id',
                'value' => $model->studentMap->studentRel->register_number,
            ],
            //'subject_map_id',
            [
                'label' =>'Sub Code',
                'attribute' => 'subject_map_id',
                'value' => $model->subjectMap->coeSubjects->subject_code,
            ],
            //'category_type_id',
            [
                'label' =>'Category Type',
                'attribute' => 'category_type_id',
                'value' => $model->categoryType->category_type,
            ],
            'category_type_id_marks',
            'year',
            'month',
            'term',
            //'status_id',
            //'created_by',
            //'created_at',
            //'updated_by',
            //'updated_at',
        ],
    ]) ?>

</div>
