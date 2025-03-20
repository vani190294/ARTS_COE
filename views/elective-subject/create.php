<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ElectiveSubject */

$this->title = 'Create Open Elective Course';
$this->params['breadcrumbs'][] = ['label' => 'Elective Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'electivemodel'=>$electivemodel
    ]) ?>

</div>
