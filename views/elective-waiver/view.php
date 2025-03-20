<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\ElectiveWaiver */

$this->title = $model->student->register_number;
$this->params['breadcrumbs'][] = ['label' => 'Elective Waivers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-waiver-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'label' =>ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT),
                'attribute' => 'student_map_id',
                'value' => $model->student->register_number,
                    
            ],
            [
                'label' =>"WAIVER ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)),
                'attribute' => 'removed_sub_map_id',
                'value' => $model->subjects->subject_code,
                    
            ],
            [
                'label' =>strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' COMPLETED',
                'attribute' => 'subject_codes',
                'value' => $model->subject_codes,
                    
            ],            
            [
                'label' =>'WAIVER REASON',
                'attribute' => 'waiver_reason',
                'value' => $model->waiver_reason,
                    
            ],
            [
                'label' =>'YEAR',
                'attribute' => 'year',
                'value' => $model->year,
                    
            ],
            [
                'label' =>'MONTH',
                'attribute' => 'month',
                'value' => $model->month0->description,
                    
            ],
            [
                'label' =>'TOTAL WAIVER',
                'attribute' => 'total_studied',
                'value' => $model->total_studied,
                    
            ],
        ],
    ]) ?>

</div>
