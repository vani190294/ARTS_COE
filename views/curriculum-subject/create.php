<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CurriculumSubject */

$this->title = 'Create Professional Core Course';
$this->params['breadcrumbs'][] = ['label' => 'Core Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curriculum-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'servicemodel'=>$servicemodel
    ]) ?>

</div>
