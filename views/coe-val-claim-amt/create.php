<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeValClaimAmt */

$this->title = 'Claim Amount List';
$this->params['breadcrumbs'][] = ['label' => 'Coe Val Claim Amts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-val-claim-amt-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
