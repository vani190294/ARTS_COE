<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\MandatorySubjects */

$this->title = 'Update Mandatory ' .ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT);;
$this->params['breadcrumbs'][] = ['label' => 'Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->subject_code, 'url' => ['view', 'id' => $model->coe_mandatory_subjects_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div class="mandatory-subjects-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
