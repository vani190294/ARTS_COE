<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $searchModel app\models\ConfigurationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuration-index">
<?php Yii::$app->ShowFlashMessages->showFlashes();?>  
    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_form', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
