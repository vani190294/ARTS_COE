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
/* @var $searchModel app\models\ElectiveStuSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$visible =Yii::$app->user->can("/elective-stu-subject/deletedata") || Yii::$app->user->can("/elective-stu-subject/update") ? true : false;

$this->title = 'Elective Course Student Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-stu-subject-index">
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
                'value' => 'deptProgramme.dept_code',

            ],
            [
                'attribute' => 'coe_elective_option',
                'value' => 'electivetype.category_type',

            ],
            'semester',
            'subject_code',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [

                    'view' => function ($url, $model) 
                    {
                       
                        return ((Yii::$app->user->can("/elective-stu-subject/view")) ? Html::a('<span class="fa fa-search increase_size"></span>', ['/elective-stu-subject/view','id'=>$model->cur_erss_id], ['title' => 'view',]) : '');
                        
                    },
                    
                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/elective-stu-subject/update")) ? Html::a('<span class="fa fa-edit increase_size"></span>', ['/elective-stu-subject/update','id'=>$model->cur_erss_id], ['title' => 'update',]) : '');
                        }
                    },

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/elective-stu-subject/deletedata")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/elective-stu-subject/deletedata','id'=>$model->cur_erss_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
