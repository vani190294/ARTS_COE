<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdditionalCourseRejoinSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/additional-course-rejoin/deletedata") || Yii::$app->user->can("/additional-course-rejoin/update") ? true : false;
$this->title = 'Additional Course Rejoin Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="additional-course-rejoin-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Additional Course Rejoin', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'coe_batch_id',
                'value' => 'batch.batch_name',
            ],

            [
                'attribute' => 'Regulation',
                'value' => 'regulation.regulation_year',

            ],
            [
                'attribute' => 'coe_dept_id',
                'value' => 'dept.dept_code',

            ],
            'semester',
            'register_number',
            'subject_code',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{approve}{delete}',
                'buttons' => [

                    'approve' => function ($url, $model) 
                    {
                        if($model->approve_status==0)
                        {
                        return ((Yii::$app->user->can("/additional-course-rejoin/approve")) ? Html::a('<span class="fa fa-search increase_size"></span>', ['/additional-course-rejoin/approve','id'=>$model->cur_acrj_id], ['title' => 'approve',]) : '');
                        }
                    },
               

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/additional-course-rejoin/deletedata")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/additional-course-rejoin/deletedata','id'=>$model->cur_acrj_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
