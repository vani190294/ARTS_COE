<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Electivetodept */

$this->title = 'Assign Elective Course To Depts';
$this->params['breadcrumbs'][] = ['label' => 'Elective to Depts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="electivetodept-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
