<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\TransferCertificates */

$this->title = $model->register_number;
$this->params['breadcrumbs'][] = ['label' => 'Transfer Certificates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-certificates-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_transfer_certificates_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_transfer_certificates_id], [
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
            'register_number',
            'name',
            'parent_name',
            'dob',
            'nationality',
            'religion',
            'community',
            'caste',
            [
                'label' =>$model->getAttributeLabel('admission_date'),
                'attribute' => 'admission_date',
                'value' => DATE('d-m-Y',strtotime($model->admission_date)),
            ],
            'class_studying',
            'reason',
            'is_qualified',
            'conduct_char',
            [
                'label' =>$model->getAttributeLabel('date_of_tc'),
                'attribute' => 'date_of_tc',
                'value' => DATE('d-m-Y',strtotime($model->date_of_tc)),
            ],
            [
                'label' =>$model->getAttributeLabel('date_of_app_tc'),
                'attribute' => 'date_of_app_tc',
                'value' => DATE('d-m-Y',strtotime($model->date_of_app_tc)),
            ],
            [
                'label' =>$model->getAttributeLabel('date_of_left'),
                'attribute' => 'date_of_left',
                'value' => DATE('d-m-Y',strtotime($model->date_of_left)),
            ],
            'serial_no',
        ],
    ]) ?>

</div>
