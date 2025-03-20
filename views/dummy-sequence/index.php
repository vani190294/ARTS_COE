<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $searchModel app\models\DummySequenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dummy Sequences';
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/dummy-sequence/delete") || Yii::$app->user->can("/dummy-sequence/update") ? true : false; 
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<div class="dummy-sequence-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'year',
            [
                'label' =>'Month',
                'attribute' => 'month',
                'value' => 'monthName.description',   
            ],
            [
                'label' =>'Sub Code',
                'attribute' => 'subject_map_id',
                'value' => 'subjectDet.subject_code',               

            ],            
            'dummy_from',
            'dummy_to',
            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/mark-entry-master/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/mark-entry-master/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/mark-entry-master/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
            
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
