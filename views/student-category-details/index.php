<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $searchModel app\models\StudentCategoryDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Transfer '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT);
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $visible = Yii::$app->user->can("/student-category-details/view") || Yii::$app->user->can("/student-category-details/update") ? true : false; ?> 
<div class="student-category-details-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Add', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,


        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
          
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
                'attribute' => 'student_map_id',
                'value' => 'studentDetails.name',

            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
                'attribute' => 'student_map_id',
                'value' => 'studentDetails.register_number',

            ],
            'old_clg_reg_no',
            'subject_code',
            'subject_name',
            
             'result',
             'grade_point',
             'grade_name',
           
           

           [
                'class' => 'app\components\CustomActionColumn',
                'header'=> 'Actions',
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                            return ((Yii::$app->user->can("/student-category-details/view")) ? Html::a('<span class="fa fa-hand-o-right increase_size"></span>', $url, ['title' => 'View',]) : '');
                        },
                    'update' => function ($url, $model) {
                            return ((Yii::$app->user->can("/student-category-details/update")) ? Html::a('<span class="fa fa-pencil-square-o increase_size"></span>', $url, ['title' => 'Update',]) : '');
                        },
                    'delete' => function ($url, $model) {
                            return ((Yii::$app->user->can("/student-category-details/delete")) ? Html::a('<span class="fa fa-ban increase_size"></span>', $url, ['title' => 'Delete', 
                                'data' => ['confirm' => 'Are you sure you want to delete this item? This action can not be undo once deleted.'
                                ,'method' => 'post'],]) : '');
                        }
                ],
            'visible' => $visible,
            ],
        ],
        
    ]); ?>
<?php Pjax::end(); ?></div>
