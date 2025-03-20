<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveSubject */

$this->title = 'Update Minor Degree Course: ' . $model->subject_code;
$this->params['breadcrumbs'][] = ['label' => 'Minor Degree Courses', 'url' => ['minordeg-index']];
$this->params['breadcrumbs'][] = ['label' => $model->subject_code];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="elective-subject-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdateminordeg', [
        'model' => $model,
        'electivemodel'=>$electivemodel,
        'ltpdetails'=>$ltpdetails,
    ]) ?>

</div>
