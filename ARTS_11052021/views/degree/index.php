<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel app\models\DegreeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="degree-index">
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php $visible = Yii::$app->user->can("/degree/view") || Yii::$app->user->can("/degree/update") ? true : false; ?>    
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'coe_degree_id',
            'degree_code',
            'degree_name',
            'degree_type',
            'degree_total_years',
            'degree_total_semesters',
            //'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/degree/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/degree/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/degree/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
