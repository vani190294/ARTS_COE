<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BarCodeQuestMarks */

$this->title = 'Update Bar Code Quest Marks: ' . $model->coe_bar_code_quest_marks_id;
$this->params['breadcrumbs'][] = ['label' => 'Bar Code Quest Marks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_bar_code_quest_marks_id, 'url' => ['view', 'id' => $model->coe_bar_code_quest_marks_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bar-code-quest-marks-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
