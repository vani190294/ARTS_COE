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
$visible =Yii::$app->user->can("/electivetodept/deletecore") || Yii::$app->user->can("/electivetodept/updatecore") ? true : false;

$this->title = 'Service Courses to Other Dept (From Common/Exisiting Syllabus)';
$this->params['breadcrumbs'][] = 'Index';
?>
<div class="electivetodept-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Assign', ['createcore-existing'], ['class' => 'btn btn-success']) ?>
    </p> 
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
                'attribute' => 'subject_code',
                'value' => 'subject_code',                
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
                'attribute' => 'subject_code_new',
                'value' => 'subject_code_new',                
                'vAlign'=>'middle',

            ],

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{delete}',
                'buttons' => [
                    
                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/electivetodept/deletecore")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/electivetodept/deletecore','id'=>$model->coe_electivetodept_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
