<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\CurriculumSubject */

$this->title = $model->subject_code;
$this->params['breadcrumbs'][] = ['label' => 'Curriculum Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curriculum-subject-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_cur_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Create New Curriculum Subject', ['create'], ['class' => 'pull-right btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'batch.batch_name',
            'regulation.regulation_year',
            'dept.dept_code',
            'degree_type',
            'semester',
            'subject_code',
            'subject_name:ntext',
            [
                'attribute' => 'coe_ltp_id',
                'value' => 'ltp.LTP',

            ],
            [
                'attribute' => 'subject_type_id',
                'value' => 'subjecttype.category_type',

            ],
            [
                'attribute' => 'subject_category_type_id',
                'value' => 'subjectctype.category_type',

            ],
            'external_mark',
            'internal_mark',
            [
                'attribute' => 'coe_ltp_id',
                'value' => 'ltp.credit_point',

            ],
            [
                'attribute' => 'coe_ltp_id',
                'value' => 'ltp.contact_hrsperweek',

            ],
        ],
    ]) ?>

</div>
