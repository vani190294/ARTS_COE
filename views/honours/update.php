<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Honours */

$this->title = 'Update Honours: ' . $model->cur_hon_id;
$this->params['breadcrumbs'][] = ['label' => 'Honours', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cur_hon_id, 'url' => ['view', 'id' => $model->cur_hon_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="honours-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
