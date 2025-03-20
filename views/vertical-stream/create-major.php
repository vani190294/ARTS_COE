<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VerticalStream */

$this->title = 'Create Vertical name Major';
$this->params['breadcrumbs'][] = ['label' => 'Vertical Name Major', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vertical-stream-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formmajor', [
        'model' => $model,
    ]) ?>

</div>
