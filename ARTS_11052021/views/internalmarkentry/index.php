<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InternalMarkentrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Internal Markentries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="internal-markentry-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Internal Markentry', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'mark_entry_id',
            'student_map_id',
            'subject_map_id',
            'category_type_id',
            'mark_out_of',
            // 'category_type_id_marks',
            // 'year',
            // 'month',
            // 'term',
            // 'mark_type',
            // 'status_id',
            // 'attendance_percentage',
            // 'attendance_remarks',
            // 'is_updated',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
