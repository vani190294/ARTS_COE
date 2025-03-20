<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeAddExamTimetable */

$this->title = 'Create Coe Add Exam Timetable';
$this->params['breadcrumbs'][] = ['label' => 'Coe Add Exam Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-add-exam-timetable-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
