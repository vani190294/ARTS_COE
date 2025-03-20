<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Servicesubjecttodept */

$this->title = 'Assign Service Subject to Dept';
$this->params['breadcrumbs'][] = ['label' => 'Servicesubjecttodepts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicesubjecttodept-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
