<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveSubject */

$this->title = 'Update Emerging Elective Course: ' . $model->subject_code;
$this->params['breadcrumbs'][] = ['label' => 'Emerging Elective Subjects', 'url' => ['eec-index']];
$this->params['breadcrumbs'][] = ['label' => $model->subject_code];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="elective-subject-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdateeec', [
        'model' => $model,
        'electivemodel'=>$electivemodel,
        'ltpdetails'=>$ltpdetails,
    ]) ?>

</div>
