<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Scrutiny */

$this->title = 'Update Scrutiny: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Scrutinies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->coe_scrutiny_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scrutiny-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
