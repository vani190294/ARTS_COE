<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HallAllocate */

$this->title = 'Hall Arrangement Internal Exam';
$this->params['breadcrumbs'][] = ['label' => 'Hall Allocates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hall-allocate-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,'hallmaster' => $hallmaster,'categorytype' => $categorytype,'exam' => $exam,
    ]) ?>

</div>
