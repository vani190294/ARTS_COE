<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\MandatorySubcatSubjects */

$this->title = 'Create Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Categories";
$this->params['breadcrumbs'][] = ['label' => 'Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Categories ", 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mandatory-subcat-subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'subjects' => $subjects,
        'mandatorySubjects' => $mandatorySubjects,
    ]) ?>

</div>
