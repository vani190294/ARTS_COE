<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeValClaimAmtSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible = Yii::$app->user->can("/coe-val-claim-amt/view") || Yii::$app->user->can("/coe-val-claim-amt/update") ? true : false; 
$this->title = 'Claim Amount List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-val-claim-amt-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Claim Amount', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'exam_type',
            'ug_amt',
            'pg_amt',
            'ta_amt_half_day',
            'ta_amt_full_day',
            'out_session',
           
             [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}',
                'buttons' => [
                    'view' => function ($url, $model) {
                    return ((Yii::$app->user->can("/coe-val-claim-amt/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                    },
                    'update' => function ($url, $model) {
                    return ((Yii::$app->user->can("/coe-val-claim-amt/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                    },
                    
                    ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
