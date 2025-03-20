<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CDCFrontpage */

$this->title = 'Create CDC Vision Mission';
$this->params['breadcrumbs'][] = ['label' => 'Cdcfrontpages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cdcfrontpage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
