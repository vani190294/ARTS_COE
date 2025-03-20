<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CoeBatDegReg */

$this->title = $model->coe_bat_deg_reg;
$this->params['breadcrumbs'][] = ['label' => 'Coe Bat Deg Regs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-bat-deg-reg-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_bat_deg_reg_id], ['class' => 'btn btn-primary']) ?>
       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coe_bat_deg_reg_id',
            'coe_degree_id',
            'coe_programme_id',
            'coe_batch_id',
            'no_of_section',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
