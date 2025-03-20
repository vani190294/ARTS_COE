<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Electivetodept */

$this->title = 'Service Courses to Other Dept';
$this->params['breadcrumbs'][] =['label' => $this->title, 'url' => ['/electivetodept/coresubject-to-dept']];
?>
<div class="electivetodept-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formcore', [
        'model' => $model,'model1' => $model1,
    ]) ?>

</div>
