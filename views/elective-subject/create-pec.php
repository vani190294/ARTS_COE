<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ElectiveSubject */

$this->title = 'Professional Elective Course';
$this->params['breadcrumbs'][] = ['label' => 'Professional Elective Subjects', 'url' => ['pec-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formpec', [
        'model' => $model,
        'electivemodel'=>$electivemodel
    ]) ?>

</div>
