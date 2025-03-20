<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */

$this->title = $model->category_name;

$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_CATEGORY), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/*echo "<pre>";
echo $model->getCategory();exit;*/
?>
<div class="categories-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_category_id], ['class' => 'btn btn-primary']) ?>
        <?php 
        // Html::a('Delete', ['delete', 'id' => $model->coe_category_id], [
        //     'class' => 'btn btn-danger',
        //     'data' => [
        //         'confirm' => 'Are you sure you want to delete this item?',
        //         'method' => 'post',
        //     ],
        // ]) 
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'coe_category_id',
            'category_name',
            'description',
            
            
        ],
    ]) ?>

</div>
