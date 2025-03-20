<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel app\models\HallAllocateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hall Allocates';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $visible = Yii::$app->user->can("/degree/view") || Yii::$app->user->can("/degree/update") ? true : false; ?>  
<div class="hall-allocate-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Coe Add Hall Allocate', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_add_hall_allocate_id',
            'hall_master_id',
             'year',
            'month',
            //'exam_type',
           'exam_date',
           'session',
           //'subject_code',
            //'register_number',
            // 'row',
            //'columnn',
            // 'seat_no',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/hall-allocate/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/hall-allocate/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/hall-allocate/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
