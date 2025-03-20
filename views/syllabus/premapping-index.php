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

$this->title = 'Prerequisties Mapping From Mapped Syllabi';
$this->params['breadcrumbs'][] = $this->title;

$visible =Yii::$app->user->can("/syllabus/delete-pre-map") ? true : false;

?>
<div class="cur-syllabus-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Assign Prerequisties', ['create-premapping'], ['class' => 'btn btn-success']) ?>
    </p>
     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'degree_type',

             [
                'attribute' => 'Batch',
                'value' => 'frombatch.batch_name',
                'vAlign'=>'top',

            ],
            
           [
                'attribute' => 'Regulation',
                'value' => 'regulation.regulation_year',                
                'vAlign'=>'middle',

            ],
            [
                'attribute' => 'Course',
                'value' => 'from_subject_code',                
                'vAlign'=>'middle',

            ],
            [
                'attribute' => 'Prerequisties Courses',
                'value' => 'to_subject_code',                
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
                            return ((Yii::$app->user->can("/syllabus/delete-pre-map")) ? Html::a('<span class="fa fa-ban increase_size"></span>', ['/syllabus/delete-pre-map','id'=>$model->cur_se_id], ['title' => 'Delete', 'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                        }
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
