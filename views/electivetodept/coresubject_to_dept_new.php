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
$visible =Yii::$app->user->can("/electivetodept/deletecorenew") ? true : false;

$this->title = 'Service Courses to Other Dept (New Syllabi)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="electivetodept-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-md-6">
            <p>
                <?= Html::a('Create New Syllabi Course (One Time)', ['new-syllabus-index'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-md-6">
            <p>
                <?= Html::a('Assign New Syllabi Course', ['core-existing-newsyllabi'], ['class' => 'btn btn-success pull-right']) ?>
            </p>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
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
            [
                'attribute' => 'subject_code',
                'value' => 'subject_code',                
                'vAlign'=>'middle',

            ],
           
             [
                'attribute' => 'Elective Option',
                'value' => 'electivetypeold.stream_name',
                'vAlign'=>'middle',

            ],

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
                'attribute' => 'Elective Option New',
                'value' => 'electivetype.description',
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
                            return ((Yii::$app->user->can("/electivetodept/deletecorenew")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/electivetodept/deletecorenew','id'=>$model->coe_electivetodept_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
</div>
