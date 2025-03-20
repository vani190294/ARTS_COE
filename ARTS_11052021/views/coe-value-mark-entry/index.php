<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeValueMarkEntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coe Value Mark Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-value-mark-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Coe Value Mark Entry', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_value_mark_entry_id',
            'student_map_id',
            'subject_map_id',
            'CIA',
            'ESE',
            // 'total',
            // 'result',
            // 'grade_point',
            // 'grade_name',
            // 'year',
            // 'month',
            // 'term',
            // 'mark_type',
            // 'status_id',
            // 'year_of_passing',
            // 'attempt',
            // 'withheld',
            // 'withheld_remarks',
            // 'withdraw',
            // 'is_updated',
            // 'fees_paid',
            // 'result_published_date',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
