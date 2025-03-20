<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeActivityMarks */

$this->title = 'Create Coe Activity Marks';
$this->params['breadcrumbs'][] = ['label' => 'Coe Activity Marks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-activity-marks-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
