<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Degree */

$this->title = $model->degree_code;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="degree-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_degree_id], ['class' => 'btn btn-primary']) ?>
       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'coe_degree_id',
            'degree_code',
            'degree_name',
            'degree_type',
            'degree_total_years',
            'degree_total_semesters',
           
        ],
    ]) ?>

</div>
