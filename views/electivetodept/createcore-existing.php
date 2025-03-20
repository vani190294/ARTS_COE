<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\Electivetodept */

$this->title = 'Service Courses to Other Dept (From Common/Exisiting Syllabus)';
$this->params['breadcrumbs'][] =['label' => 'Index', 'url' => ['/electivetodept/coresubject-to-dept-existing']];
?>
<div class="electivetodept-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formcore_existingsyllabus', [
        'model' => $model,'model1' => $model1,
    ]) ?>

</div>
