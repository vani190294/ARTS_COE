<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\MandatorySubjects */

$this->title = 'Create Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT);
$this->params['breadcrumbs'][] = ['label' => 'Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div class="mandatory-subjects-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
