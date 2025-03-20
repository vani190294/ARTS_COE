<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveSubject */

$this->title = 'Update Professional Elective Course: ' . $model->subject_code;
$this->params['breadcrumbs'][] = ['label' => 'Professional Elective Subjects', 'url' => ['pec-index']];
$this->params['breadcrumbs'][] = ['label' => $model->subject_code];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="elective-subject-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdatepec', [
        'model' => $model,
        'electivemodel'=>$electivemodel,
        'ltpdetails'=>$ltpdetails,
    ]) ?>

</div>
