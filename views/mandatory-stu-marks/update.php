<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MandatoryStuMarks */

$this->title = 'Update Mandatory Stu Marks: ' . $model->coe_mandatory_stu_marks_id;
$this->params['breadcrumbs'][] = ['label' => 'Mandatory Stu Marks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_mandatory_stu_marks_id, 'url' => ['view', 'id' => $model->coe_mandatory_stu_marks_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mandatory-stu-marks-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
