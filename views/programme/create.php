<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Programme */

$this->title = 'Create '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME);
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programme-create">
<?php Yii::$app->ShowFlashMessages->showFlashes();?>  
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
