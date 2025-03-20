<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UpdateTracker */

$this->title = 'Create Update Tracker';
$this->params['breadcrumbs'][] = ['label' => 'Update Trackers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="update-tracker-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
