<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MandatoryStuMarksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mandatory Stu Marks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mandatory-stu-marks-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mandatory Stu Marks', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'coe_mandatory_stu_marks_id',
            'student_map_id',
            'subject_map_id',
            'CIA',
            'ESE',
            // 'total',
            // 'result',
            // 'grade_point',
            // 'grade_name',
            // 'year',
            // 'month',
            // 'term',
            // 'mark_type',
            // 'status_id',
            // 'year_of_passing',
            // 'attempt',
            // 'withheld',
            // 'withheld_remarks',
            // 'withdraw',
            // 'fees_paid',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
