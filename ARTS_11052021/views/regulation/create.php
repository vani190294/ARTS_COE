<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Regulation */
Yii::$app->ShowFlashMessages->showFlashes();
$this->title = 'New Regulation';
$this->params['breadcrumbs'][] = ['label' => 'Regulations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regulation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
