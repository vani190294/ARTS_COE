<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BarCodeQuestMarks */

$this->title = 'Create Bar Code Quest Marks';
$this->params['breadcrumbs'][] = ['label' => 'Bar Code Quest Marks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bar-code-quest-marks-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
