<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */

$this->title = 'Create '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Timetables';
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exam-timetable-create">

   <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
