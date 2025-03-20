<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Honours */

$this->title = 'Create Honours';
$this->params['breadcrumbs'][] = ['label' => 'Honours', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="honours-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
