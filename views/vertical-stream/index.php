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
/* @var $searchModel app\models\VerticalStreamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$visible =Yii::$app->user->can("/vertical-stream/view") || Yii::$app->user->can("/vertical-stream/update") || Yii::$app->user->can("/vertical-stream/delete") ? true : false;

$this->title = 'Vertical Name Minor';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vertical-stream-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Vertical Name', ['create'], ['class' => 'btn btn-success']) ?>
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
            'degree_type',
            [
                'attribute' => 'coe_dept_id',
                'value' => 'deptassignlist.depts',
                'vAlign'=>'middle',

            ],
            'vertical_name',
            'vertical_count',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}',
                'buttons' => [
                   
                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                             return ((Yii::$app->user->can("/vertical-stream/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
