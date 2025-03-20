<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EqualentSubjects */

$this->title = 'Update Equalent Subjects: ' . $model->coe_equalent_subjects_id;
$this->params['breadcrumbs'][] = ['label' => 'Equalent Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_equalent_subjects_id, 'url' => ['view', 'id' => $model->coe_equalent_subjects_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="equalent-subjects-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
