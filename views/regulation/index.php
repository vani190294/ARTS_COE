<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\dialog\Dialog;
echo Dialog::widget();
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RegulationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Regulations';
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/regulation/view") || Yii::$app->user->can("/regulation/update") ? true : false; 
Yii::$app->ShowFlashMessages->showFlashes();
?>
<div class="regulation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('New Regulation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH),
                  'attribute' => 'coe_batch_id',
                  'value' => 'coeBatch.batch_name',
            ],
            'regulation_year',
            'grade_point_from',
            'grade_point_to',
             'grade_name',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/regulation/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/regulation/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/regulation/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
