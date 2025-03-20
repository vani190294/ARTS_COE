<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sub */

$this->title = 'Update Sub: ' . $model->coe_sub_mapping_id;
$this->params['breadcrumbs'][] = ['label' => 'Subs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_sub_mapping_id, 'url' => ['view', 'id' => $model->coe_sub_mapping_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sub-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
