<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DelQualentSubjects */

$this->title = 'Create Del Qualent Subjects';
$this->params['breadcrumbs'][] = ['label' => 'Del Qualent Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="del-qualent-subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
