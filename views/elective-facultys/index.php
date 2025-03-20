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
/* @var $searchModel app\models\ElectiveFacultysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/elective-facultys/deletedata") || Yii::$app->user->can("/elective-facultys/update") || Yii::$app->user->can("/elective-facultys/view") ? true : false;

$this->title = 'Elective Faculty Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-facultys-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',],

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
                'attribute' => 'degree_type',
                'value' => 'degree_type',
                'width'=>'190px',
            ],
            
            [
                'attribute' => 'coe_dept_id',
                'value' => 'dept.dept_code',
                'width'=>'190px',
            ],
            [
                'attribute' => 'semester',
                'value' => 'semester',
                'width'=>'190px',
            ],
            [
                'attribute' => 'subject_code',
                'value' => 'subject_code',
                'width'=>'190px',
            ],
           
             [
                'attribute' => 'faculty_ids',
                'value' => 'facultysdetails.facultydatas',
                'width'=>'200px',
            ],
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}{delete}',
                'buttons' => [

                   
                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/elective-facultys/update")) ? Html::a('<span class="fa fa-edit increase_size"></span>', ['/elective-facultys/update','id'=>$model->cur_ersf_id], ['title' => 'update',]) : '');
                        }
                    },

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/elective-facultys/deletedata")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/elective-facultys/deletedata','id'=>$model->cur_ersf_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
