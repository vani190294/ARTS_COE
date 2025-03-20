<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HallMaster */

$this->title = 'Create Hall Master';
$this->params['breadcrumbs'][] = ['label' => 'Hall Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hall-master-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,'categorytype' => $categorytype,
    ]) ?>

</div>
