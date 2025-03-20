<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Configuration */

$this->title = $model->config_desc;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_NAME), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuration-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_config_id], ['class' => 'btn btn-primary']) ?>
        
    </p>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'coe_config_id',
            //'config_name',
            'config_value',
            'config_desc',
            
        ],
    ]) ?>

</div>
