<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */

$this->title = $model->exam_date;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exam-timetable-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_exam_timetable_id], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'coe_exam_timetable_id:datetime',
            //'subject_mapping_id',
            
            'exam_date',
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_SESSION),
                'attribute' => 'exam_session',
                'value' => $model->examSessionRel->category_type,
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Month",
                'attribute' => 'exam_month',
                'value' => $model->examMonthRel->category_type,
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE),
                'attribute' => 'exam_type',
                'value' => $model->examTypeRel->category_type,
            ],
            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TERM),
                'attribute' => 'exam_term',
                'value' => $model->examTermRel->category_type,
            ],
            'qp_code',
            
            
        ],
    ]) ?>

</div>
