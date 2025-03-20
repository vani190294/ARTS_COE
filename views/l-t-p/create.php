<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LTP */

$this->title = 'Create Ltp';
$this->params['breadcrumbs'][] = ['label' => 'Ltps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ltp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
