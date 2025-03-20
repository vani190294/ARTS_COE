<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Regulation */
Yii::$app->ShowFlashMessages->showFlashes();
$this->title = 'Update Regulation: ' . $model->regulation_year;
$this->params['breadcrumbs'][] = ['label' => 'Regulations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->regulation_year, 'url' => ['view', 'id' => $model->coe_regulation_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="regulation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
