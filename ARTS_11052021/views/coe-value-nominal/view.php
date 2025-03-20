<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueNominal */

$this->title = $model->coe_nominal_val_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Value Nominals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-value-nominal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_nominal_val_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_nominal_val_id], [
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
            'coe_nominal_val_id',
            'course_batch_mapping_id',
            'coe_student_id',
            'coe_subjects_id',
            'section_name',
            'semester',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
