<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BarCodeQuestMarksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bar Code Quest Marks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bar-code-quest-marks-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Bar Code Quest Marks', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_bar_code_quest_marks_id',
            'student_map_id',
            'subject_map_id',
            'dummy_number',
            'year',
            // 'month',
            // 'question_no',
            // 'question_no_marks',
            // 'mark_type',
            // 'term',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
