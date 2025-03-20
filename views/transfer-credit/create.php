<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeTransferCredit */

$this->title = 'Transfer Credit';
$this->params['breadcrumbs'][] = ['label' => 'Transfer Credits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-transfer-credit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'student' =>$student,
    ]) ?>

</div>
