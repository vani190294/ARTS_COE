<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeAddPointsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coe Add Points';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-add-points-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create activity Points', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'subject_code',
            'subject_name',
            //'activity_points',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
