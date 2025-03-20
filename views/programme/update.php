<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Programme */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).': ' . $model->programme_code;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->programme_code, 'url' => ['view', 'id' => $model->coe_programme_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="programme-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
