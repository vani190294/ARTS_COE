<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveStuSubject */

$this->title = 'Update Elective Course Student Registration ';
$this->params['breadcrumbs'][] = ['label' => 'Elective Course Student Registration', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->cur_erss_id, 'url' => ['view', 'id' => $model->cur_erss_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="elective-stu-subject-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdate', [
        'model' => $model,
        'student'=> $student,
        'cur_erss_id'=>$cur_erss_id
    ]) ?>

</div>
