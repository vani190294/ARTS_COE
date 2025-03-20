<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AdditionalCourseRejoin */

$this->title = 'Create Additional Course Rejoin Registration';
$this->params['breadcrumbs'][] = ['label' => 'Additional Course Rejoin', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="additional-course-rejoin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
