<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ServiceCount */

$this->title = 'Update Service Request Course';
$this->params['breadcrumbs'][] = ['label' => 'Service Counts', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="service-count-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
