<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PracticalExamTimetable */

$this->title = 'Create Student Per Batch Count';
$this->params['breadcrumbs'][] = ['label' => 'Practical Exam Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practical-exam-timetable-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formstuperbatch', [
        'model' => $model,
        'student'=>$student,
        'markEntry'=>$markEntry,
        'MarkEntryMaster'=>$MarkEntryMaster,
    ]) ?>

</div>
