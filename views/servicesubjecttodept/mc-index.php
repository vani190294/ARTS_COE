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
/* @var $searchModel app\models\ServicesubjecttodeptSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/servicesubjecttodept/mc-delete") || Yii::$app->user->can("/servicesubjecttodept/update") ? true : false;

$this->title = 'Assign MC to Depts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicesubjecttodept-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Assign', ['mc-create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             [
                'attribute' => 'coe_cur_subid',
                'value' => 'subjectassinged.subject_code',                
                'vAlign'=>'middle',

            ],
            [
                'attribute' => 'coe_dept_ids',
                'value' => 'deptassignlist.dept_code',
                'vAlign'=>'middle',

            ],
           

             [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{delete}',
                'buttons' => [
                    
                    'delete' => function ($url, $model) 
                    {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/servicesubjecttodept/delete-mcdata")) ? Html::a('<span class="fa fa-remove increase_size"></span>',  ['/servicesubjecttodept/delete-mcdata','id'=>$model->coe_servtodept_id]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
