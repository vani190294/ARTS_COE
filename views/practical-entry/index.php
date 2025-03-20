<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PracticalEntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Practical Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practical-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Practical Entry', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_practical_entry_id',
            'student_map_id',
            'subject_map_id',
            'out_of_100',
            'ESE',
            // 'year',
            // 'month',
            // 'term',
            // 'mark_type',
            // 'approve_status',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
