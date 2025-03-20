<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AicteNorms */

$this->title = 'Assign Existing Stream to Another Regulation';
$this->params['breadcrumbs'][] = ['label' => 'Curriculum Stream Name', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aicte-norms-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formexisting', [
        'model' => $model,
    ]) ?>

</div>
