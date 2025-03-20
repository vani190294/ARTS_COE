<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeBatDegRegSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coe Bat Deg Regs';
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/coe-bat-deg-reg/view") || Yii::$app->user->can("/coe-bat-deg-reg/update") ? true : false;
?>
<div class="coe-bat-deg-reg-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Coe Bat Deg Reg', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_bat_deg_reg_id',
            'coe_degree_id',
            'coe_programme_id',
            'coe_batch_id',
            'no_of_section',
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
                            return ((Yii::$app->user->can("/coebatdegreg/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/coebatdegreg/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/coebatdegreg/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
