<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeActivityMarksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coe Activity Marks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-activity-marks-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Coe Activity Marks', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'batch',
            'programme',
            'register_number',
            'section',
            // 'subject_code',
            // 'duration',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
