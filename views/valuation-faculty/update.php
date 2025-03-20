<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ValuationFaculty */

$this->title = 'Update Valuation Faculty: ' . $model->faculty_name;
$this->params['breadcrumbs'][] = ['label' => 'Valuation Faculties', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->faculty_name, 'url' => ['view', 'id' => $model->coe_val_faculty_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="valuation-faculty-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
