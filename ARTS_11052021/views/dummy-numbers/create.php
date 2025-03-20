<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\DummyNumbers */

$this->title = ' Generate '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dummy-numbers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'examModel'=>$examModel,
        
    ]) ?>

</div>
