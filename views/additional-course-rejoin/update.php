<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AdditionalCourseRejoin */

$this->title = 'Update Additional Course Rejoin: ' . $model->cur_acrj_id;
$this->params['breadcrumbs'][] = ['label' => 'Additional Course Rejoins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cur_acrj_id, 'url' => ['view', 'id' => $model->cur_acrj_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="additional-course-rejoin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
