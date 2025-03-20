<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CoeAddExamTimetable */

$this->title = $model->coe_add_exam_timetable_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Add Exam Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-add-exam-timetable-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_add_exam_timetable_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_add_exam_timetable_id], [
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
            'coe_add_exam_timetable_id:datetime',
            'subject_mapping_id',
            'exam_year',
            'exam_month',
            'exam_type',
            'exam_term',
            'exam_date',
            'exam_session',
            'qp_code',
            'cover_number',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
