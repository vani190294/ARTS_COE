<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeAddHallAllocate */

$this->title = 'Create Coe Add Hall Allocate';
$this->params['breadcrumbs'][] = ['label' => 'Coe Add Hall Allocates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-add-hall-allocate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'hallmaster' =>$hallmaster,'categorytype' =>$categorytype,'exam'=>$exam
    ]) ?>

</div>
