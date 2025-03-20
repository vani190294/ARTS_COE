<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm; 
/* @var $this yii\web\View */
/* @var $searchModel app\models\HonoursSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$visible =Yii::$app->user->can("/honours/deletedata") || Yii::$app->user->can("/honours/update") ? true : false;

$checkAccess =Yii::$app->user->can("/honours/deletedata") ? true : false;

$this->title = 'Honours Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="honours-index">

    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Honours', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
     <div class="pull-right">
         <?php $form = ActiveForm::begin([
'id' => 'delete-exam',
  'method' => 'POST',
'enableAjaxValidation' => true,
'fieldConfig' => [
                 'template' => "{label}{input}{error}",
                 ],
  ]); ?>
  <input type="hidden" name="finalString" id="finalString">
    <?php
    if($checkAccess==true && $approvedstatus>0)
            {
      echo Html::submitInput('Approve', ['class' => 'btn btn-block btn-danger','value'=>'Approve', 'name'=>'Approve', 'id'=>'exam_del_butt']);
    }
    ?>
     <?php ActiveForm::end(); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             [
                'attribute' => 'coe_batch_id',
                'value' => 'batch.batch_name',

            ],

            [
                'attribute' => 'Regulation',
                'value' => 'regulation.regulation_year',

            ],
            [
                'attribute' => 'coe_dept_id',
                'value' => 'dept.dept_code',

            ],
            [
                'attribute' => 'honours_type',
                'value' => 'honourstype.category_type',

            ],
            'semester',
            'register_number',
            'subject_code',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{approve}{delete}',
                'buttons' => [

                    'approve' => function ($url, $model) 
                    {
                        if($model->approve_status==0)
                        {
                        return ((Yii::$app->user->can("/honours/approve")) ? Html::a('<span class="fa fa-search increase_size"></span>', ['/honours/approve','id'=>$model->cur_hon_id], ['title' => 'approve',]) : '');
                        }
                    },
               

                    'delete' => function ($url, $model) {
                        if($model->approve_status==0 || Yii::$app->user->getId()==1)
                        {
                            return ((Yii::$app->user->can("/honours/deletedata")) ? Html::a('<span class="fa fa-remove increase_size"></span>', ['/honours/deletedata','id'=>$model->cur_hon_id], ['title' => 'delete',]) : '');
                        }
                    },
                    
                    ],
                'visible' => $visible,
            ],
            [
                'class' => 'yii\grid\CheckboxColumn', 
                 'checkboxOptions' => function ($model) 
                 {
                    if($model->approve_status==1)
                    {
                        return ['style' => ['display' => 'none']];
                    }
            }],
        ],
    ]); ?>
</div>
