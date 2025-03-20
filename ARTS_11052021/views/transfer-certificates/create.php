<?php
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\TransferCertificates */

$this->title = 'Create Transfer Certificates';
$this->params['breadcrumbs'][] = ['label' => 'Transfer Certificates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-certificates-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
