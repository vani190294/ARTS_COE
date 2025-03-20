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

$visible =Yii::$app->user->can("/elective-stu-subject/deletembadata") || Yii::$app->user->can("/elective-stu-subject/mba-update") ? true : false;

$this->title = 'MBA Elective Course Student Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-stu-subject-index">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Upload', ['mba-create'], ['class' => 'btn btn-success']) ?>
    </p>
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
            'semester',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{delete}',
                'buttons' => [

                    'view' => function ($url, $model) 
                    {
                       
                        return ((Yii::$app->user->can("/elective-stu-subject/viewmba")) ? Html::a('<span class="fa fa-search increase_size"></span>', ['/elective-stu-subject/viewmba','id'=>$model->cur_erss_id], ['title' => 'view',]) : '');
                        
                    },
                    
                    'delete' => function ($url, $model) {
                        if($model->approve_status==0)
                        {
                            return ((Yii::$app->user->can("/elective-stu-subject/deletembadata")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/elective-stu-subject/deletembadata','id'=>$model->cur_erss_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
