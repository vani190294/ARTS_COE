<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveSubject */

$this->title = $model->subject_code;
$this->params['breadcrumbs'][] = ['label' => 'Elective Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-subject-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_elective_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Create New Elective Subject', ['create'], ['class' => 'pull-right btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'batch.batch_name',
            'regulation.regulation_year',
            'dept.dept_code',
            'degree_type',
            'electivetype.category_type',
            'subject_code',
            'subject_name:ntext',
            'ltp.LTP',
            'subjecttype.category_type',
            'subjectctype.category_type',
            'external_mark',
            'internal_mark',
            'remarks',
        ],
    ]) ?>

</div>
