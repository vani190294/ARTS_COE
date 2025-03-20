<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DummyNumbersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dummy-numbers-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                  'label' => "Register Number",
                  'attribute' => 'register_number',
                  'value' => 'studentDetails.register_number',
                  
            ],
            [
                  'label' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." CODE",
                  'attribute' => 'subject_code',
                  'value' => 'subjectDetails.subject_code',
                  
            ],
            // [
            //       'label' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." NAME",
            //       'attribute' => 'subject_name',
            //       'value' => 'subjectDetails.subject_name',
                  
            // ],

            //'student_map_id',
            //'subject_map_id',
            'dummy_number',
            'year',
            [
                  'label' => " Month",
                  'attribute' => 'description',
                  'value' => 'monthDetails.description',
                  
            ],
            //'month',
           
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
