<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FrontpClg */

$this->title = 'Update Frontp Clg: ' . $model->cur_fp_id;
$this->params['breadcrumbs'][] = ['label' => 'Frontp Clgs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cur_fp_id, 'url' => ['view', 'id' => $model->cur_fp_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="frontp-clg-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
