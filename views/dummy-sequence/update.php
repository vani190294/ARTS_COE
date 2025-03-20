<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\DummySequence */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Sequence: ' . $model->subjectDet->subject_code;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY).' Sequences', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->subjectDet->subject_code];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dummy-sequence-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
