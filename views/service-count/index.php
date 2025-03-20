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
/* @var $searchModel app\models\CreditDistributionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$visible =Yii::$app->user->can("/service-count/view") || Yii::$app->user->can("/service-count/update") || Yii::$app->user->can("/service-count/approve") ? true : false;


$this->title = 'Service Course Request to Other Dept.';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="service-count-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'degree_type',
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
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{approve}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/service-count/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) 
                    {
                        
                            return ((Yii::$app->user->can("/service-count/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
