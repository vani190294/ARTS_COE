<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntryMaster */

$this->params['breadcrumbs'][] = ['label' => 'UPDATE EXTERNAL MARKS', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-entry-master-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'student' =>$student,
    ]) ?>

</div>
