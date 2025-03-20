<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ValuationFaculty */

$this->title = 'Create Valuation Faculty';
$this->params['breadcrumbs'][] = ['label' => 'Valuation Faculties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="valuation-faculty-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formcreate', [
        'model' => $model,
    ]) ?>

</div>
