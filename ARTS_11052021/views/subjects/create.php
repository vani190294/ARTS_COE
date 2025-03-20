<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Subjects */

$this->title = 'Create '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'subjects' => $subjects,'batchmapping' => $batchmapping,
        //'batch' => $batch,
    ]) ?>

</div>
