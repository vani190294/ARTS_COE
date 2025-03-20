<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveRegister */

$this->title = 'Update Elective Course Registration';
$this->params['breadcrumbs'][] = ['label' => 'Elective Course Registration', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->cur_elect_id, 'url' => ['view', 'id' => $model->cur_elect_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="elective-register-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
