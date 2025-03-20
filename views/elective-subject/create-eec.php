<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ElectiveSubject */

$this->title = 'Emerging Elective Course';
$this->params['breadcrumbs'][] = ['label' => 'Emerging Elective Subjects', 'url' => ['eec-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-subject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formeec', [
        'model' => $model,
        'electivemodel'=>$electivemodel
    ]) ?>

</div>
