<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubjectPrefix */

$this->title = 'Update Subject Prefix: ' . $model->prefix_name;
$this->params['breadcrumbs'][] = ['label' => 'Subject Prefixes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->prefix_name];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subject-prefix-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
