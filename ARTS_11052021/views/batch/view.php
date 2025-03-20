<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Batch */

$this->title = $model->batch_name;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="batch-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_batch_id], ['class' => 'btn btn-primary']) ?>
        <!-- <?= Html::a('Delete', ['delete', 'id' => $model->coe_batch_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           // 'coe_batch_id',
            'batch_name',
            
        ],
    ]) ?>

</div>
