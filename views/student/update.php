<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

use app\models\StudentMapping;
use app\models\StuAddress;
use app\models\Guardian;

/* @var $this yii\web\View */
/* @var $model app\models\Student */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->coe_student_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="student-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'guardian' => $guardian,
        'stuAddress' => $stuAddress,
        'stuMapping' => $stuMapping,
    ]) ?>

</div>
