<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeActivityMarks */

$this->title = 'Update Coe Activity Marks: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Activity Marks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-activity-marks-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
