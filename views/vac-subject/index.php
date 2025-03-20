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
/* @var $searchModel app\models\VacSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$visible =Yii::$app->user->can("/vac-subject/delete") || Yii::$app->user->can("/vac-subject/update") ? true : false;
$this->title = 'Value Added Courses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vac-subject-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Course', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute' => 'coe_regulation_id',
                'value' => 'regulation.regulation_year',
                'vAlign'=>'top',

            ],
            
            'degree_type',
            'subject_code',
            'subject_name',
           
            
            [
                'attribute' => 'course_hours',
                'value' => 'course_hours',
                'vAlign'=>'top',

            ],
            
            [
                'attribute' => 'Credit Point',
                'value' => 'credit_point',
                'vAlign'=>'top',

            ],

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/vac-subject/update")) ? Html::a('<span class="fa fa-edit increase_size"></span>', ['/vac-subject/update','id'=>$model->coe_vac_id], ['title' => 'Update',]) : '');
                        }
                        },

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/vac-subject/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                        }
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
