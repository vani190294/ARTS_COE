<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ElectiveWaiver */

$this->title = 'Elective Waiver';
$this->params['breadcrumbs'][] = ['label' => 'Elective Waivers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-waiver-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'student' =>$student,
    ]) ?>

</div>
