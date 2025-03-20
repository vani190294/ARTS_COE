<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VerticalStream */

$this->title = 'Update PSO: ' . $model->pso_title;
$this->params['breadcrumbs'][] = ['label' => 'PSO', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->cur_vs_id, 'url' => ['view', 'id' => $model->cur_vs_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vertical-stream-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdate', [
        'model' => $model,
    ]) ?>

</div>
