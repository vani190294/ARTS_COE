<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Student */

$this->title = "Add ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT);
$this->params['breadcrumbs'][] = ['label' => "Add ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'admission_count' => $admission_count,
        'guardian'=>$guardian,
        'stuAddress'=>$stuAddress,
        'stuMapping' => $stuMapping,
    ]) ?>

</div>
