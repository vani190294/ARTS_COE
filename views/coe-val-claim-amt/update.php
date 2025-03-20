<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValClaimAmt */

$this->title = 'Claim Amount List' . $model->exam_type;
$this->params['breadcrumbs'][] = ['label' => 'Claim Amount List', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->exam_type, 'url' => ['view', 'id' => $model->claim_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-val-claim-amt-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
