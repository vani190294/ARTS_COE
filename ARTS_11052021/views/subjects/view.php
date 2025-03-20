<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\Subjects */

$this->title = $model->subject_code;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subjects-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_subjects_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_subjects_id], [
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
            //'coe_subjects_id',
            'subject_code',
            'subject_name',
            'coeSubjectsMapping.semester',
            'coeSubjectsMapping.paper_no',
            'CIA_min',
            'CIA_max',
            'ESE_min',
            'ESE_max',
            'total_minimum_pass',
            'credit_points',
            'end_semester_exam_value_mark',
            'subject_fee',
           
        ],
    ]) ?>

</div>
