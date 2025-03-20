<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DummySequence */

$this->title = 'Create Dummy Sequence';
$this->params['breadcrumbs'][] = ['label' => 'Dummy Sequences', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dummy-sequence-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
