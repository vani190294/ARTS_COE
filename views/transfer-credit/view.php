<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CoeTransferCredit */

$this->title = $model->coe_tc_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Transfer Credits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-transfer-credit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_tc_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_tc_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coe_tc_id',
            'student_map_id',
            'removed_sub_map_id',
            'waiver_reason',
            'total_studied',
            'subject_codes',
            'year',
            'month',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
