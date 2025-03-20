<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UpdateTracker */

$this->title = 'Update Update Tracker: ' . $model->coe_update_tracker_id;
$this->params['breadcrumbs'][] = ['label' => 'Update Trackers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_update_tracker_id, 'url' => ['view', 'id' => $model->coe_update_tracker_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="update-tracker-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
