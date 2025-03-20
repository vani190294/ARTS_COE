<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeValueNominal */

$this->title = 'Create Coe Value Nominal';
$this->params['breadcrumbs'][] = ['label' => 'Coe Value Nominals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-value-nominal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
