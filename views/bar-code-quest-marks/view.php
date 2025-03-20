<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BarCodeQuestMarks */

$this->title = $model->coe_bar_code_quest_marks_id;
$this->params['breadcrumbs'][] = ['label' => 'Bar Code Quest Marks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bar-code-quest-marks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_bar_code_quest_marks_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_bar_code_quest_marks_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coe_bar_code_quest_marks_id',
            'student_map_id',
            'subject_map_id',
            'dummy_number',
            'year',
            'month',
            'question_no',
            'question_no_marks',
            'mark_type',
            'term',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
