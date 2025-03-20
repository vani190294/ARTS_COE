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

$visible =Yii::$app->user->can("/dept-pso/view") || Yii::$app->user->can("/dept-pso/update") || Yii::$app->user->can("/dept-pso/delete") ? true : false;

$this->title = 'PSO Setting';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vertical-stream-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create PSO', ['create'], ['class' => 'btn btn-success']) ?>
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
                'vAlign'=>'middle',

            ],
            'degree_type',
            
            //'pso_title',
            'no_of_pso',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}',
                'buttons' => [
                   
                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                             return ((Yii::$app->user->can("/dept-pso/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
