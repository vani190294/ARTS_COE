<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ElectiveStuSubject */

$this->title = 'Elective Course Student Registration';
$this->params['breadcrumbs'][] = ['label' => 'Elective Course Student Registration', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-stu-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'student'=> $student
    ]) ?>

</div>
