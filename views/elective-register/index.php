<?php

use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\widgets\ActiveForm; 
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel app\models\ElectiveRegisterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/elective-register/deletedata") || Yii::$app->user->can("/elective-register/update") ? true : false;
$this->title = 'Elective Course Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-register-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Elective Registration', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'coe_batch_id',
                'value' => 'batch.batch_name',
                'vAlign'=>'top',

            ],

            [
                'attribute' => 'Regulation',
                'value' => 'regulation.regulation_year',
                'vAlign'=>'top',

            ],

            [
                'attribute' => 'coe_dept_id',
                'value' => 'dept.dept_code',
                'vAlign'=>'middle',

            ],
            'semester',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [

                    'view' => function ($url, $model) 
                    {
                       
                        return ((Yii::$app->user->can("/elective-register/view")) ? Html::a('<span class="fa fa-search increase_size"></span>', ['/elective-register/view','id'=>$model->cur_elect_id], ['title' => 'view',]) : '');
                        
                    },
                    
                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/elective-register/update")) ? Html::a('<span class="fa fa-edit increase_size"></span>', ['/elective-register/update','id'=>$model->cur_elect_id], ['title' => 'update',]) : '');
                        }
                    },

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/elective-register/deletedata")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/elective-register/deletedata','id'=>$model->cur_elect_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
