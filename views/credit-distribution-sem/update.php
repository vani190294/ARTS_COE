<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CreditDistribution */

$this->title = 'Update Credit Distribution ';
$this->params['breadcrumbs'][] = ['label' => 'Credit Distributions', 'url' => ['index']];
?>
<div class="credit-distribution-update">

    <?= $this->render('credit_form_update', [
        'streamdata' => $streamdata,
            'deptdata'=>$deptdata
    ]) ?>

</div>
