<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Batch */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).': ' . $model->batch_name;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->batch_name, 'url' => ['view', 'id' => $model->coe_batch_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="batch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'regulation' => $regulation,
    ]) ?>

</div>
