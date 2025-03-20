<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ValuationFacultySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Valuation Faculty';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="valuation-faculty-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Valuation Faculty', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'faculty_name',
            'faculty_designation',
            'college_code',
            'faculty_board',
            'faculty_mode',
            // 'faculty_experience',
            'bank_accno',
            'bank_name',
            //'bank_branch',
            'bank_ifsc',
            'phone_no',
            'email',
            
            // 'year',
            // 'month',
            'out_session',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
