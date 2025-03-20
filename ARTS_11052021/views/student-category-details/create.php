<?php

use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\StudentCategoryDetails */

$this->title = 'Add';
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-category-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
