<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EqualentSubjects */

$this->title = 'Create Equalent Subjects';
$this->params['breadcrumbs'][] = ['label' => 'Equalent Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equalent-subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
