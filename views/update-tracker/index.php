<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use app\models\User;
use app\models\MarkEntryMaster;
use yii\widgets\Pjax;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UpdateTrackerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Update Trackers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="update-tracker-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
                  'attribute' => 'student_map_id',
                  'value' => function($model){ return ($model->student_map_id=='' || $model->student_map_id==NULL || $model->student_map_id==0)?'--':$model->student->register_number;  },
            ],
            [
                  'label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT),
                  'attribute' => 'subject_map_id',
                  'value' => function($model){ return ($model->subject_map_id=='' || $model->subject_map_id==NULL || $model->subject_map_id==0)?'--':$model->subject->subject_code;  },
            ],
            [
                  'label' =>'EXAM YEAR',
                  'attribute' => 'exam_year',
                  'value' => function($model){ return ($model->exam_year=='' || $model->exam_year==NULL || $model->exam_year==0)?'--':$model->exam_year;  },
            ],
            [
                  'label' => 'EXAM MONTH',
                  'attribute' => 'exam_month',
                  'value' => function($model){ return ($model->exam_month=='' || $model->exam_month==NULL || $model->exam_month==0)?'--':$model->categorytype->description;  },
            ],
            [
                  'label' => 'IP',
                  'attribute' => 'updated_ip_address',
                  'value' => function($model){ return ($model->updated_ip_address=='::1' || $model->updated_ip_address=='127.0.0.1' || $model->updated_ip_address=='localhost' )?'LOCAL':$model->updated_ip_address;  },
            ],
            // [
            //       'label' => 'From',
            //       'attribute' => 'updated_link_from',
            //       'value' => 'updated_link_from',
            // ],
             [
                  'label' => 'Updated',
                  'attribute' => 'data_updated',
                  'value' => 'data_updated',
                  'format'=>'html'

            ],
            [
                  'label' => 'User',
                  'attribute' => 'updated_by',
                  'value' => function($model){ return Yii::$app->user->getUsername($model->updated_by);  },
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
