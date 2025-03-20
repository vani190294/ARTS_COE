<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntryMaster */

$this->title = 'Update Mark Entry Master: ' . $model->coe_mark_entry_master_id;
$this->params['breadcrumbs'][] = ['label' => 'Mark Entry Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_mark_entry_master_id, 'url' => ['view', 'id' => $model->coe_mark_entry_master_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mark-entry-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
