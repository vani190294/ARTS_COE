<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DepartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$visible = Yii::$app->user->can("/department/update") ? true : false;

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Department', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dept_code',
            'dept_name',
            'prefix_name',
            'no_of_pso',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}',
                'buttons' => [
                    
                    'update' => function ($url, $model) {
                    return ((Yii::$app->user->can("/department/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                    },
                    
                    ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
