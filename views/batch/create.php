<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Batch */

$this->title = 'Create '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="batch-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'regulation' => $regulation,
        'degree' => $degree,
        'programme' => $programme,

    ]) ?>

</div>
