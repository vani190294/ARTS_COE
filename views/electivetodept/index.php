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
/* @var $searchModel app\models\ElectivetodeptSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/electivetodept/view") || Yii::$app->user->can("/electivetodept/update") ? true : false;

$this->title = 'Elective Course To Departments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="electivetodept-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Assign', ['create'], ['class' => 'btn btn-success']) ?>
    </p> 

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                'attribute' => 'Subject Code',
                'value' => 'electivesubject.subject_code',                
                'vAlign'=>'middle',

            ],
            
            [
                'attribute' => 'coe_elective_option',
                'value' => 'electivetype.category_type',
                'vAlign'=>'middle',

            ],
            'semester',
            [
                'attribute' => 'coe_dept_ids',
                'value' => 'deptassignlist.depts',
                'vAlign'=>'middle',

            ],
            

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}',
                'buttons' => [
                    
                    'update' => function ($url, $model) {
                    return ((Yii::$app->user->can("/electivetodept/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>

     <?php Pjax::end(); ?>
</div>
