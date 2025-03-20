<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $searchModel app\models\BatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH);
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/batch/view") || Yii::$app->user->can("/batch/update") ? true : false; 
?>
<div class="batch-index">
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'coe_batch_id',
            'batch_name',
            /*'created_at',
            'created_by',*/
            //'updated_at',
            // 'updated_by',

           [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/batch/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/batch/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/batch/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
