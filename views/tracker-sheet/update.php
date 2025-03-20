<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeTrackerSheet */

$this->title = 'Update  Tracker Sheet';
$this->params['breadcrumbs'][] = ['label' => 'Tracker Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-tracker-sheet-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
