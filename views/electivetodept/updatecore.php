<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Electivetodept */

$this->title = 'Update Service Courses to Other Dept';
$this->params['breadcrumbs'][] =['label' => 'Service Courses to Other Dept', 'url' => ['/electivetodept/coresubject-to-dept']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="electivetodept-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formcoreupdate', [
        'model' => $model,'model1' => $model1,'codatalist'=>$codatalist
    ]) ?>

</div>
