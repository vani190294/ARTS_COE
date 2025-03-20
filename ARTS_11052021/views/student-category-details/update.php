<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\StudentCategoryDetails */

$this->title = 'Update '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Category Details: ' . $model->coe_student_category_details_id;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coe_student_category_details_id, 'url' => ['view', 'id' => $model->coe_student_category_details_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="student-category-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
