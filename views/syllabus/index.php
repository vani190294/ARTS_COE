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
/* @var $searchModel app\models\CurSyllabusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Course Syllabi';
$this->params['breadcrumbs'][] = $this->title;

$visible =Yii::$app->user->can("/syllabus/view") || Yii::$app->user->can("/syllabus/update") || Yii::$app->user->can("/syllabus/delete") ? true : false;

?>
<div class="cur-syllabus-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Syllabus', ['create'], ['class' => 'btn btn-success']) ?>
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
            
            'subject_code',
            
           [
                'attribute' => 'Subject Name',
                'value' => 'subject.subject_name',                
                'vAlign'=>'middle',

            ],


            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}{deapprove}',
                'buttons' => [
                    
                   
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/syllabus/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },

                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/syllabus/update")) ? Html::a('<span class="fa fa-edit increase_size"></span>', ['/syllabus/update','id'=>$model->cur_syllabus_id], ['title' => 'Update',]) : '');
                        }
                        },

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/syllabus/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                        },

                        'deapprove' => function ($url, $model) 
                        {
                            if((Yii::$app->user->getId()==11 || Yii::$app->user->getId()==1 || Yii::$app->user->getId()==924) && ($model->approve_status==1))
                            {
                                return ((Yii::$app->user->can("/syllabus/deapprove")) ? Html::a('<span class="fa fa-cog increase_size"></span>', ['/syllabus/deapprove','id'=>$model->cur_syllabus_id], ['title' => 'deapprove',]) : '');
                            }
                        },
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
