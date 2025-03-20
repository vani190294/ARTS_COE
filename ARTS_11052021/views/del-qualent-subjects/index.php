<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DelQualentSubjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Del Qualent Subjects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="del-qualent-subjects-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Del Qualent Subjects', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_del_qualent_subjects_id',
            'stu_map_id',
            'sub_map_id',
            'created_at',
            'created_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
