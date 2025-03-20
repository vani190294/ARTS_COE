<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Timetable: ' .date('d-m-Y', strtotime($model->exam_date));
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->exam_date, 'url' => ['view', 'id' => $model->coe_exam_timetable_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="exam-timetable-update">
<h1><?= Html::encode($this->title) ?></h1> 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
