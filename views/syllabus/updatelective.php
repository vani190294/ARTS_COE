<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CurSyllabus */

$this->title = 'Update Elective Syllabus';
$this->params['breadcrumbs'][] = ['label' => 'Syllabi', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->cur_syllabus_id, 'url' => ['view', 'id' => $model->cur_syllabus_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cur-syllabus-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formupdatelective', [
        'model' => $model,
    ]) ?>

</div>
