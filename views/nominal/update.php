<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Nominal */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL).': ' . $model->coe_nominal_id;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_nominal_id, 'url' => ['view', 'id' => $model->coe_nominal_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nominal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
