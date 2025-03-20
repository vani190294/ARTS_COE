<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ElectiveFacultys */

$this->title = 'Create Elective Facultys';
$this->params['breadcrumbs'][] = ['label' => 'Elective Facultys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-facultys-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
