<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Degree */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).': ' . $model->degree_name;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->degree_name, 'url' => ['view', 'id' => $model->coe_degree_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="degree-update">

  <!-- <h1><?= Html::encode($this->title) ?></h1> -->

  <?= $this->render('_form', [
			      'model' => $model,
			      ]) ?>

</div>