<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeValueSubjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coe Value Subjects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-value-subjects-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Coe Value Subjects', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_val_sub_id',
            'subject_code',
            'subject_name',
            'subject_fee',
            'CIA_min',
            // 'CIA_max',
            // 'ESE_min',
            // 'ESE_max',
            // 'total_minimum_pass',
            // 'credit_points',
            // 'part_no',
            // 'end_semester_exam_value_mark',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
