<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeBatDegReg */

$this->title = 'Create Coe Bat Deg Reg';
$this->params['breadcrumbs'][] = ['label' => 'Coe Bat Deg Regs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-bat-deg-reg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
