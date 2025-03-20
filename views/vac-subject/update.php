<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VacSubject */

$this->title = 'Update VAC: ' . $model->coe_vac_id;
$this->params['breadcrumbs'][] = ['label' => 'VAC', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_vac_id, 'url' => ['view', 'id' => $model->coe_vac_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vac-subject-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdate', [
        'model' => $model,
    ]) ?>

</div>
