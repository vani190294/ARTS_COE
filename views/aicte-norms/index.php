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
/* @var $searchModel app\models\AicteNormsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/aicte-norms/view") || Yii::$app->user->can("/aicte-norms/update") ? true : false;
$this->title = 'Curriculum Stream Name';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aicte-norms-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-6">
            <p>
                <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                <?= Html::a('Create Stream From Existing', ['create-existing'], ['class' => 'btn btn-success pull-right']) ?>
            </p>
        </div>
    </div>

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
                'vAlign'=>'middle',

            ],
            'degree_type',
            
            'stream_name',
            'stream_fullname',

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}',
                'buttons' => [
                   
                    'update' => function ($url, $model) {
                    return ((Yii::$app->user->can("/aicte-norms/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
