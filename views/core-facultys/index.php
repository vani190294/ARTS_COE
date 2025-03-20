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
/* @var $searchModel app\models\CoreFacultysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Core Course Faculty Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="core-facultys-index">

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
                'attribute' => 'coe_regulation_id',
                'value' => 'regulation.regulation_year',
                'width'=>'190px',
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
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}{delete}',
                'buttons' => [

                   
                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/core-facultys/update")) ? Html::a('<span class="fa fa-edit increase_size"></span>', ['/core-facultys/update','id'=>$model->cur_cf_id], ['title' => 'update',]) : '');
                        }
                    },

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/core-facultys/deletedata")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/core-facultys/deletedata','id'=>$model->cur_cf_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
            ],
        ],
    ]); ?>
</div>
