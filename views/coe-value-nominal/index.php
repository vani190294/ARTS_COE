<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeValueNominalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coe Value Nominals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-value-nominal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Coe Value Nominal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_nominal_val_id',
            'course_batch_mapping_id',
            'coe_student_id',
            'coe_subjects_id',
            'section_name',
            // 'semester',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
