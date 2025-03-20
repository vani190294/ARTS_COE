<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SubjectsMapping */

$this->title = 'Create Subjects Mapping';
$this->params['breadcrumbs'][] = ['label' => 'Subjects Mappings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subjects-mapping-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
