<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EqualentSubjects */

$this->title = $model->coe_equalent_subjects_id;
$this->params['breadcrumbs'][] = ['label' => 'Equalent Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equalent-subjects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_equalent_subjects_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_equalent_subjects_id], [
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
            'coe_equalent_subjects_id',
            'prev_stu_map_id',
            'prev_sub_map_id',
            'new_stu_map_id',
            'new_sub_map_id',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
