<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Degree */

$this->title = 'Create '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="degree-create">

  <h1><?= Html::encode($this->title) ?></h1>

  <?= $this->render('_form', [
			      'model' => $model,
			      ]) ?>

</div>