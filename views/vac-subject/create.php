<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VacSubject */

$this->title = 'Create Vac Subject';
$this->params['breadcrumbs'][] = ['label' => 'Vac Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vac-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
