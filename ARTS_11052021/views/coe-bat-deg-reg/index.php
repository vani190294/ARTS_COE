<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeBatDegRegSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME);
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/coe-bat-deg-reg/view") || Yii::$app->user->can("/coe-bat-deg-reg/update") ? true : false;
?>
<div class="coe-bat-deg-reg-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Yii::$app->ShowFlashMessages->showFlashes(); // echo $this->render('_search', ['model' => $searchModel]); ?>

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
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE),
                  'attribute' => 'coe_degree_id',
                  'value' => 'coeDegree.degree_code',
            ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME),
                  'attribute' => 'coe_programme_id',
                  'value' => 'coeProgramme.programme_code',
            ],
            'regulation_year',
            'no_of_section',
            

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/coe-bat-deg-reg/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/coe-bat-deg-reg/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    /*'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/coe-bat-deg-reg/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }*/
                ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
</div>
