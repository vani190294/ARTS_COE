<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CoeValueSubjects */

$this->title = $model->coe_val_sub_id;
$this->params['breadcrumbs'][] = ['label' => 'Coe Value Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coe-value-subjects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_val_sub_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_val_sub_id], [
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
            'coe_val_sub_id',
            'subject_code',
            'subject_name',
            'subject_fee',
            'CIA_min',
            'CIA_max',
            'ESE_min',
            'ESE_max',
            'total_minimum_pass',
            'credit_points',
            'part_no',
            'end_semester_exam_value_mark',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
