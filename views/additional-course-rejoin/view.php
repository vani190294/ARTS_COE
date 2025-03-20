<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AdditionalCourseRejoin */

$this->title = $model->cur_acrj_id;
$this->params['breadcrumbs'][] = ['label' => 'Additional Course Rejoins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="additional-course-rejoin-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->cur_acrj_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->cur_acrj_id], [
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
            'cur_acrj_id',
            'batch_map_id',
            'degree_type',
            'coe_regulation_id',
            'coe_dept_id',
            'register_number',
            'subject_code',
            'semester',
            'approve_status',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
