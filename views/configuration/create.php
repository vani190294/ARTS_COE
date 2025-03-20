<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Configuration */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuration-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
