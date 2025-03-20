<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MandatoryStuMarks */


$this->params['breadcrumbs'][] = ['label' => 'Mandatory Stu Marks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mandatory-stu-marks-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'sub_model' =>$sub_model,
        'mandatorySubjects' =>$mandatorySubjects,
    ]) ?>

</div>
