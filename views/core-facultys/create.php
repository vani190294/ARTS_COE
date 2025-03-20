<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoreFacultys */

$this->title = 'Create Core Facultys';
$this->params['breadcrumbs'][] = ['label' => 'Core Facultys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="core-facultys-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
