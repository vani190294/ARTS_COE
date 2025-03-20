<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DelQualentSubjects */

$this->title = $model->coe_del_qualent_subjects_id;
$this->params['breadcrumbs'][] = ['label' => 'Del Qualent Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="del-qualent-subjects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_del_qualent_subjects_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_del_qualent_subjects_id], [
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
            'coe_del_qualent_subjects_id',
            'stu_map_id',
            'sub_map_id',
            'created_at',
            'created_by',
        ],
    ]) ?>

</div>
