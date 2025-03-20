<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CoeValueSubjects */

$this->title = 'Create  Value  Added Subjects';
$this->params['breadcrumbs'][] = ['label' => 'Coe Value Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-value-subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 'model' => $model,'subjects' => $subjects,'batchmapping' => $batchmapping,
    ]) ?>

</div>
