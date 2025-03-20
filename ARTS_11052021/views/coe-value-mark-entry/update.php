<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueMarkEntry */

$this->title = 'Update Coe Value Mark Entry: ' . $model->coe_value_mark_entry_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Value Mark Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_value_mark_entry_id, 'url' => ['view', 'id' => $model->coe_value_mark_entry_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-value-mark-entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
