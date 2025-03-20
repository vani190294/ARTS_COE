<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AicteNorms */

$this->title = 'Update Curriculum Stream Name: ' . $model->stream_name;
$this->params['breadcrumbs'][] = ['label' => 'Curriculum Stream Name', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->stream_name];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="aicte-norms-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
