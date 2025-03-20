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

$this->title = 'Syllabi Mapping From Existing';
$this->params['breadcrumbs'][] = $this->title;

$visible =Yii::$app->user->can("/syllabus/view") || Yii::$app->user->can("/syllabus/update") || Yii::$app->user->can("/syllabus/delete-se") ? true : false;

?>
<div class="cur-syllabus-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Assign Existing Syllabus', ['create-existing'], ['class' => 'btn btn-success']) ?>
    </p>
     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'degree_type',
             [
                'attribute' => 'from_batch_id',
                'value' => 'frombatch.batch_name',
                'vAlign'=>'top',

            ],
           [
                'attribute' => 'from_regulation_id',
                'value' => 'regulation.regulation_year',                
                'vAlign'=>'middle',

            ],
            'from_subject_code',
            'degree_type',
             [
                'attribute' => 'to_batch_id',
                'value' => 'tobatch.batch_name',
                'vAlign'=>'top',

            ],
             [
                'attribute' => 'to_regulation_id',
                'value' => 'toRegulation.regulation_year',                
                'vAlign'=>'middle',

            ],
            'to_subject_code',

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{delete}',
                'buttons' => [                    

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/syllabus/delete-se")) ? Html::a('<span class="fa fa-ban increase_size"></span>', ['/syllabus/delete-se','id'=>$model->cur_se_id], ['title' => 'Delete', 'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                        }
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
