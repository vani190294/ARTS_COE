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

$this->title = 'Service Course Syllabi';
$this->params['breadcrumbs'][] = $this->title;

$visible =Yii::$app->user->can("/syllabus/viewservice") || Yii::$app->user->can("/syllabus/updateservice") || Yii::$app->user->can("/syllabus/deleteservice") ? true : false;

?>
<div class="cur-syllabus-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Syllabus', ['createservice'], ['class' => 'btn btn-success']) ?>
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
            'subject_code',
            
           [
                'attribute' => 'Subject Name',
                'value' => 'subject.subject_name',                
                'vAlign'=>'middle',

            ],          
            

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    
                   
                    'view' => function ($url, $model) {
                        
                            return ((Yii::$app->user->can("/syllabus/viewservice")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', ['/syllabus/viewservice','id'=>$model->cur_syllabus_id], ['title' => 'View',]) : '');
                        
                        },

                    'update' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/syllabus/updateservice")) ? Html::a('<span class="fa fa-edit increase_size"></span>', ['/syllabus/updateservice','id'=>$model->cur_syllabus_id], ['title' => 'Update',]) : '');
                        }
                        },

                    'delete' => function ($url, $model) 
                    {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/syllabus/deleteservice")) ? Html::a('<span class="fa fa-ban increase_size"></span>', ['/syllabus/deleteservice','id'=>$model->cur_syllabus_id], ['title' => 'Delete', 
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
