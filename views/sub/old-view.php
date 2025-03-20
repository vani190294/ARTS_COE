<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sub */

$this->title = $model->coe_sub_mapping_id;
$this->params['breadcrumbs'][] = ['label' => 'Subs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_sub_mapping_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_sub_mapping_id], [
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
            'coe_sub_mapping_id',
            'batch_mapping_id',
            'val_subject_id',
            'semester',
            'paper_type_id',
            'subject_type_id',
            'course_type_id',
            'migration_status',
            'paper_no',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
