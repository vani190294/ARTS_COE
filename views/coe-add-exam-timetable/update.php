<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeAddExamTimetable */

$this->title = 'Update Coe Add Exam Timetable: ' . $model->coe_add_exam_timetable_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Add Exam Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_add_exam_timetable_id, 'url' => ['view', 'id' => $model->coe_add_exam_timetable_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-add-exam-timetable-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
