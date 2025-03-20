<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PracticalEntry */

$this->title = 'Update Practical Entry: ' . $model->coe_practical_entry_id;
$this->params['breadcrumbs'][] = ['label' => 'Practical Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_practical_entry_id, 'url' => ['view', 'id' => $model->coe_practical_entry_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="practical-entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
