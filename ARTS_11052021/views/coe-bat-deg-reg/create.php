<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\CoeBatDegReg */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-bat-deg-reg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
