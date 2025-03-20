<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoreFacultys */

$this->title = 'Update Core Facultys: ' . $model->cur_cf_id;
$this->params['breadcrumbs'][] = ['label' => 'Core Facultys', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cur_cf_id, 'url' => ['view', 'id' => $model->cur_cf_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="core-facultys-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
