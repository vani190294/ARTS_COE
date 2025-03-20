<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VerticalStream */

$this->title = 'Create PSO';
$this->params['breadcrumbs'][] = ['label' => 'Vertical Streams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vertical-stream-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
