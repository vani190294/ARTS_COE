<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Classifications */

$this->title = 'Create Classifications';
$this->params['breadcrumbs'][] = ['label' => 'Classifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classifications-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
