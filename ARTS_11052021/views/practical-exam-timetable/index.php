<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PracticalExamTimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Practical Exam Timetables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practical-exam-timetable-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Practical Exam Timetable', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_practical_exam_timetable_id:datetime',
            'batch_mapping_id',
            'student_map_id',
            'subject_map_id',
            'exam_year',
            // 'exam_month',
            // 'mark_type',
            // 'term',
            // 'exam_date',
            // 'exam_session',
            // 'out_of_100',
            // 'ESE',
            // 'internal_examiner_name',
            // 'external_examiner_name',
            // 'approve_status',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
