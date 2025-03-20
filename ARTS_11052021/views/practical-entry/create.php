<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PracticalEntry */

$this->title = 'Practical Mark Entry';
$this->params['breadcrumbs'][] = ['label' => 'Practical Mark Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="practical-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'markEntry'=>$markEntry,
        'student'=>$student,
        'MarkEntryMaster'=>$MarkEntryMaster,
    ]) ?>

</div>
