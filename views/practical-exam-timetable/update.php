<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PracticalExamTimetable */

$this->title = 'Update Practical Exam Timetable: ' . $model->coe_practical_exam_timetable_id;
$this->params['breadcrumbs'][] = ['label' => 'Practical Exam Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_practical_exam_timetable_id, 'url' => ['view', 'id' => $model->coe_practical_exam_timetable_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="practical-exam-timetable-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'student'=>$student,
        'markEntry'=>$markEntry,
        'MarkEntryMaster'=>$MarkEntryMaster,
    ]) ?>

</div>
