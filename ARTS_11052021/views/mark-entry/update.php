<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */

$this->title = 'Update Mark Entry: ' . $model->coe_mark_entry_id;
$this->params['breadcrumbs'][] = ['label' => 'Mark Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_mark_entry_id, 'url' => ['view', 'id' => $model->coe_mark_entry_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mark-entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
