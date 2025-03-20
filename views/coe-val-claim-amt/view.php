<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValClaimAmt */

$this->title = $model->exam_type;
$this->params['breadcrumbs'][] = ['label' => 'Claim Amount List', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-val-claim-amt-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->claim_id], ['class' => 'btn btn-primary']) ?>
        <?php /*Html::a('Delete', ['delete', 'id' => $model->claim_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'exam_type',
            'ug_amt',
            'pg_amt',
            'ta_amt_half_day',
            'ta_amt_full_day',
            'out_session',
        ],
    ]) ?>

</div>
