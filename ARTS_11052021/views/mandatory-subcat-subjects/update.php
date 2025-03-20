<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\MandatorySubcatSubjects */

$this->title = 'Update Mandatory Subcategory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' : ' . $model->sub_cat_code;
$this->params['breadcrumbs'][] = ['label' => 'Mandatory Subcat Subjects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sub_cat_code, 'url' => ['view', 'id' => $model->coe_mandatory_subcat_subjects_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mandatory-subcat-subjects-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'subjects' => $subjects,
        'mandatorySubjects' => $mandatorySubjects,
    ]) ?>

</div>
