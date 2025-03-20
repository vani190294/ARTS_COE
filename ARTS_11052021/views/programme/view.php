<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Programme */

$this->title = $model->programme_code;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programme-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_programme_id], ['class' => 'btn btn-primary']) ?>
         <?php //Html::a('Delete', ['delete', 'id' => $model->coe_programme_id], [
        //     'class' => 'btn btn-danger',
        //     'data' => [
        //         'confirm' => 'Are you sure you want to delete this item?',
        //         'method' => 'post',
        //     ],
        // ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'coe_programme_id',
            'programme_code',
            'programme_name',
            
        ],
    ]) ?>

</div>
