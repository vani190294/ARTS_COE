<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeTransferCreditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coe Transfer Credits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-transfer-credit-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Coe Transfer Credit', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_tc_id',
            'student_map_id',
            'removed_sub_map_id',
            'waiver_reason',
            'total_studied',
            // 'subject_codes',
            // 'year',
            // 'month',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
