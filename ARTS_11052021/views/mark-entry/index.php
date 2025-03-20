<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $searchModel app\models\MarkEntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mark Entries';
$this->params['breadcrumbs'][] = $this->title;
$visible = Yii::$app->user->can("/subjects-mapping/view") || Yii::$app->user->can("/subjects-mapping/update") ? true : false; 
?>
<div class="mark-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'coe_mark_entry_id',
            /*'student_map_id',
            'subject_map_id',
            'category_type_id',*/
            [
                'label' =>" Reg No",
                'attribute' => 'student_map_id',
                'value' => 'studentMap.studentRel.register_number',
            ],
            [
                'label' =>" Sub Code",
                'attribute' => 'subject_map_id',
                'value' => 'subjectMap.coeSubjects.subject_code',
            ],
            [
                'label' =>" Category Type",
                'attribute' => 'category_type_id',
                'value' => 'categoryType.category_type',
            ],
            'category_type_id_marks',
            'year',
            'month',
            'term',
            // 'status_id',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/mark-entry/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/mark-entry/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/mark-entry/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
