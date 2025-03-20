<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CDCFrontpage */

$this->title = 'Update Cdcfrontpage: ' . $model->cur_fp_id;
$this->params['breadcrumbs'][] = ['label' => 'Cdcfrontpages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cur_fp_id, 'url' => ['view', 'id' => $model->cur_fp_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cdcfrontpage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
