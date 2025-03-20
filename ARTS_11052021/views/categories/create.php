<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = 'Create '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'categorytype' => $categorytype,
    ]) ?>

</div>
