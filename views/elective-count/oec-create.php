<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\Models\ElectiveCount */

$this->title = 'Create OEC Count';
$this->params['breadcrumbs'][] = ['label' => 'Elective Counts', 'url' => ['oec-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-count-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formoec', [
        'model' => $model,
    ]) ?>

</div>
