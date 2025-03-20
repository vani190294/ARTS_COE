<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CreditDistribution */

$this->title = 'Create Credit Distribution';
$this->params['breadcrumbs'][] = ['label' => 'Credit Distributions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credit-distribution-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
