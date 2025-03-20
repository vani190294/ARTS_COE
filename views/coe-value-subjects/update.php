<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueSubjects */

$this->title = 'Update Coe Value Subjects: ' . $model->coe_val_sub_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Value Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_val_sub_id, 'url' => ['view', 'id' => $model->coe_val_sub_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coe-value-subjects-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'subjects' => $subjects,
    ]) ?>

</div>
