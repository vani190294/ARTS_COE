<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CurSyllabus */

$this->title = 'Create Curriculum Syllabus';
$this->params['breadcrumbs'][] = ['label' => 'Cur Syllabi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cur-syllabus-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'curmodel'=>$curmodel
    ]) ?>

</div>
