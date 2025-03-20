<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\SubjectsMapping */

$this->title = 'Update : ' . $model->coeSubjects->subject_code;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coeSubjects->subject_code, 'url' => ['view', 'id' => $model->coe_subjects_mapping_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subjects-mapping-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'subjects'=>$subjects,
    ]) ?>

</div>
