<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FrontpClg */

$this->title = 'Create Frontp Clg';
$this->params['breadcrumbs'][] = ['label' => 'Frontp Clgs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frontp-clg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
