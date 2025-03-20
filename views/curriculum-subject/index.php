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
/* @var $searchModel app\models\CurriculumSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/curriculum-subject/delete") || Yii::$app->user->can("/curriculum-subject/update") ? true : false;
$this->title = 'Professional Core Courses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curriculum-subject-index">
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
            // [
            //     'attribute' => 'coe_dept_id',
            //     'value' => 'dept.dept_code',
            //     'vAlign'=>'top',

            // ],
            
            'degree_type',
            'semester',
            'subject_code',
            'subject_name',
             
            [
                'attribute' => 'subject_type_id',
                'value' => 'subjecttype.category_type',
                'vAlign'=>'top',

            ],
            
            [
                'attribute' => 'subject_category_type_id',
                'value' => 'subjectctype.category_type',
                'vAlign'=>'top',

            ],

            [
                'attribute' => 'stream_id',
                'value' => 'stream.stream_name',
                'vAlign'=>'top',

            ],

            [
                'attribute' => 'coe_ltp_id',
                'value' => 'ltp.LTP',
                'vAlign'=>'top',

            ],
             [
                'attribute' => 'Credit Point',
                'value' => 'ltp.credit_point',
                'vAlign'=>'top',

            ],
            [
                'attribute' => 'Contact Hrs/week',
                'value' => 'ltp.contact_hrsperweek',
                'vAlign'=>'top',

            ],

            'internal_mark',
            'external_mark',
            
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{delete}',
                'buttons' => [
                  
                    'delete' => function ($url, $model) 
                    {
                        if($model->approve_status==0)
                        {
                             
                            return ((Yii::$app->user->can("/curriculum-subject/delete-core")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/curriculum-subject/delete-core','id'=>$model->coe_cur_id], ['title' => 'Delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
