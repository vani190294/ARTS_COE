<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeTrackerSheet */

$this->title = 'Create Tracker Sheet';
$this->params['breadcrumbs'][] = ['label' => 'Tracker Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-tracker-sheet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
