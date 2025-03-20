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
/* @var $searchModel app\models\CDCFrontpageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CDC Vision Mission';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cdcfrontpage-index">

    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Mapping', ['copy'], ['class' => 'btn btn-success pull-right']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Batch',
                'value' => 'batchname.batch_name',
                'vAlign'=>'middle',

            ],
            [
                'attribute' => 'coe_regulation_id',
                'value' => 'regulation.regulation_year',

            ],
            'degree_type',            
            [
                'attribute' => 'coe_dept_id',
                'value' => 'dept.dept_code',
                'vAlign'=>'middle',

            ],
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{delete}',
                'buttons' => [

                    'view' => function ($url, $model) 
                    {
                       
                        return ((Yii::$app->user->can("/cdc-frontpage/view")) ? Html::a('<span class="fa fa-search increase_size"></span>', ['/cdc-frontpage/view','id'=>$model->cur_fp_id], ['title' => 'view',]) : '');
                        
                    },
                    

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/cdc-frontpage/deletedata")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/cdc-frontpage/deletedata','id'=>$model->cur_fp_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
            ],
        ],
    ]); ?>
</div>
