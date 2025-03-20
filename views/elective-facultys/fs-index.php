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
/* @var $searchModel app\models\ElectiveFacultysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/elective-facultys/viewallocated") || Yii::$app->user->can("/elective-facultys/faculty-student-allocate") ? true : false;

$this->title = 'Faculty Student Allocation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-facultys-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
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
                'attribute' => 'subject_code',
                'value' => 'subject_code',
                'width'=>'190px',
            ],
           
             [
                'attribute' => 'faculty_id',
                'value' => 'facultysdetails.facultydatas',
                'width'=>'200px',
            ],
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{allocate}',
                'buttons' => [

                    'view' => function ($url, $model) 
                    {
                       
                        return ((Yii::$app->user->can("/elective-facultys/viewfacultystudent")) ? Html::a('<span class="fa fa-search increase_size"></span>', ['/elective-facultys/viewfacultystudent','id'=>$model->cur_ef_id], ['title' => 'view',]) : '');
                        
                    },
                    
                    'allocate' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/elective-facultys/faculty-student-allocate")) ? Html::a('<span class="fa fa-edit increase_size"></span>', ['/elective-facultys/faculty-student-allocate','id'=>$model->cur_ef_id], ['title' => 'allocate',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
