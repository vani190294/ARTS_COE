<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Nominal */

$this->title = 'Create '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NOMINAL), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nominal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'batch' => $batch,'programme' => $programme,'coebatdegreg'=>$coebatdegreg,'student'=>$student,'subject'=>$subject,
    ]) ?>

</div>
