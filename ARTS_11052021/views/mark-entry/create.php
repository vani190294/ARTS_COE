<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */

$this->title = 'Internal Mark Entry';
$this->params['breadcrumbs'][] = ['label' => 'Mark Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
