<?php

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Categorytype */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY_TYPE).': ' . $model->category_type;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY_TYPE), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category_type, 'url' => ['view', 'id' => $model->coe_category_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categorytype-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
