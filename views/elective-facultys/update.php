<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveFacultys */

$this->title = 'Update Elective Faculty ';
$this->params['breadcrumbs'][] = ['label' => 'Elective Facultys', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->cur_ersf_id, 'url' => ['view', 'id' => $model->cur_ersf_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="elective-facultys-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdate', [
        'model' => $model,
    ]) ?>

</div>
