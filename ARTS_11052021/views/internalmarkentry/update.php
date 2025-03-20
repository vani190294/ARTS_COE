<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InternalMarkentry */

$this->title = 'Update Internal Markentry: ' . $model->mark_entry_id;
$this->params['breadcrumbs'][] = ['label' => 'Internal Markentries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mark_entry_id, 'url' => ['view', 'id' => $model->mark_entry_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="internal-markentry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
