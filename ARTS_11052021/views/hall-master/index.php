<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\dialog\Dialog;
echo Dialog::widget();
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $searchModel app\models\HallMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hall Master';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<div class="hall-master-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php $visible = Yii::$app->user->can("/hall-master/view") || Yii::$app->user->can("/hall-master/update") ? true : false; ?> 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            'hall_name',
            'description',
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_HALLTYPE),
                'attribute' => 'hall_type_id',
                'value' => 'hallType.category_type',
                
            ],
            //'hall_type_id',
            
            

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/hall-master/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/hall-master/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/hall-master/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
