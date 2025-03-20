<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Configuration */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME).' : '. $model->config_desc;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->config_value, 'url' => ['view', 'id' => $model->coe_config_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="configuration-update">
	<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
