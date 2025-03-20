<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sub */

$this->title = 'Create Sub';
$this->params['breadcrumbs'][] = ['label' => 'Subs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'subjects' => $subjects,'batchmapping' => $batchmapping,
    ]) ?>

</div>
