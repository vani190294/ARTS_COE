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
/* @var $searchModel app\Models\ElectiveCountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$visible =Yii::$app->user->can("/elective-count/view") || Yii::$app->user->can("/elective-count/update") || Yii::$app->user->can("/elective-count/delete") ? true : false;


$this->title = 'EEC Count (Self Dept.)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-count-index">
     <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create EEC Count', ['create'], ['class' => 'btn btn-success']) ?>
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
            'degree_type',
            [
                'attribute' => 'coe_dept_id',
                'value' => 'dept.dept_code',
                'vAlign'=>'middle',

            ],
            [
                'attribute' => 'elective_type',
                'value' => 'electivetype.category_type',
                'vAlign'=>'middle',

            ],
            'elective_count',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}',
                'buttons' => [
                   
                    'update' => function ($url, $model) 
                    {
                        if($model->approve_status==0 || Yii::$app->user->getId()==11 || Yii::$app->user->getId()==1 || Yii::$app->user->getId()==924)
                        {
                            return ((Yii::$app->user->can("/elective-count/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
