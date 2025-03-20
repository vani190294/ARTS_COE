<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Electivetodept */

$this->title = 'Assign Service Courses (New Syllabus) to Other Dept';
$this->params['breadcrumbs'][] =['label' => 'Index', 'url' => ['/electivetodept/coresubject-to-dept-new']];
?>
<div class="electivetodept-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <?= $this->render('_formcore_newsyllabus_existing', [
        'model' => $model,'model1' => $model1,
    ]) ?> -->

    <?= $this->render('_formcore_existingsyllabus_newsyallbi', [
        'model' => $model,'model1' => $model1,
    ]) ?>

</div>
