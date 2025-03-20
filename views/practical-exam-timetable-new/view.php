<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PracticalExamTimetable */

$this->title = $model->coe_prac_exam_ttable_id;
$this->params['breadcrumbs'][] = ['label' => 'Practical Exam Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practical-exam-timetable-view">
<?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_prac_exam_ttable_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_prac_exam_ttable_id], [
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
            
            'batch_mapping_id',
            'student_map_id',
            'subject_map_id',
            'exam_year',
            'exam_month',
            'mark_type',
            'term',
            'exam_date',
            'exam_session',
            'internal_examiner_name',
            'external_examiner_name',
            'approve_status',
        ],
    ]) ?>

</div>
