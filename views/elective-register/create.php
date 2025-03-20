<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ElectiveRegister */

$this->title = 'Create Elective Course Registration';
$this->params['breadcrumbs'][] = ['label' => 'Elective Course Registration', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-register-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
