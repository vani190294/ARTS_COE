<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EqualentSubjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Equalent Subjects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equalent-subjects-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Equalent Subjects', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_equalent_subjects_id',
            'prev_stu_map_id',
            'prev_sub_map_id',
            'new_stu_map_id',
            'new_sub_map_id',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
