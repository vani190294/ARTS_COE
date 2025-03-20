<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CoeBatDegRegSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ELECTIVE WAIVER DETAILS';
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/elective-waiver/view") || Yii::$app->user->can("/elective-waiver/update") ? true : false;
?>
<div class="coe-bat-deg-reg-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
                'attribute' => 'student_map_id',
                'value' => 'student.register_number',
                    
            ],
            [
                'label' =>"WAIVER ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)),
                'attribute' => 'removed_sub_map_id',
                'value' => 'subjects.subject_code',
                    
            ],
            [
                'label' =>strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' COMPLETED',
                'attribute' => 'subject_codes',
                'value' => 'subject_codes',
                    
            ],            
            [
                'label' =>'WAIVER REASON',
                'attribute' => 'waiver_reason',
                'value' => 'waiver_reason',
                    
            ],
            [
                'label' =>'YEAR',
                'attribute' => 'year',
                'value' => 'year',
                    
            ],
            [
                'label' =>'MONTH',
                'attribute' => 'month',
                'value' => 'month0.description',
                    
            ],
            [
                'label' =>'TOTAL WAIVER',
                'attribute' => 'total_studied',
                'value' =>'total_studied',
                    
            ],
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/elective-waiver/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/elective-waiver/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/coebatdegreg/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
</div>