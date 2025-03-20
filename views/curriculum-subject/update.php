<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CurriculumSubject */

$this->title = 'Update Professional Core Course: ' . $model->subject_code;
$this->params['breadcrumbs'][] = ['label' => 'Core Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->subject_code, 'url' => ['view', 'id' => $model->coe_cur_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="curriculum-subject-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdate', [
        'model' => $model,
        'ltpdetails'=>$ltpdetails,
        'servicemodel'=>$servicemodel
    ]) ?>

</div>
