<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY).' : ' . $model->category_name;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category_name, 'url' => ['view', 'id' => $model->coe_category_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categories-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,'categorytype' => $categorytype,
    ]) ?>

</div>
