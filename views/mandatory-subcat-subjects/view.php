<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\Url;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\MandatorySubcatSubjects */

$this->title = $model->sub_cat_code;
$this->params['breadcrumbs'][] = ['label' => 'Mandatory Subcategory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mandatory-subcat-subjects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_mandatory_subcat_subjects_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_mandatory_subcat_subjects_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <table class="table table-responsive-xl table-responsive table-striped">
    <tr>       
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?></th>
        <td><?= Html::encode($model->batch->batch_name) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?></th>
        <td><?= Html::encode($model->coeDegree->degree_code) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME); ?></th>
        <td><?= Html::encode($model->coeProgramme->programme_code) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code'; ?></th>
        <td><?= Html::encode($model->manSubject->subject_code) ?></td>
    </tr>
    <tr>
        
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name'; ?></th>
        <td><?= Html::encode($model->manSubject->subject_name) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).'  Sub Category Code'; ?></th>
        <td><?= Html::encode($model->sub_cat_code) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Sub Category Name'; ?></th>
        <td><?= Html::encode($model->sub_cat_name) ?></td>
        <th><?= $model->getAttributeLabel('CIA_min') ?></th>
        <td><?= Html::encode($model->manSubject->CIA_min) ?></td>
    </tr>
   
    <tr>
        <th><?= $model->getAttributeLabel('CIA_max') ?></th>
        <td><?= Html::encode($model->manSubject->CIA_max) ?></td>
        <th><?= $model->getAttributeLabel('ESE_min') ?></th>
        <td><?= Html::encode($model->manSubject->ESE_min) ?></td>
        <th><?= $model->getAttributeLabel('ESE_max') ?></th>
        <td><?= Html::encode($model->manSubject->ESE_max) ?></td>
        <th><?= $model->getAttributeLabel('total_minimum_pass') ?></th>
        <td><?= Html::encode($model->manSubject->total_minimum_pass) ?></td>
    </tr>
   
    <tr>
        <th><?= $model->getAttributeLabel('credit_points') ?></th>
        <td><?= Html::encode($model->credit_points) ?></td>
        <th><?= $model->getAttributeLabel('paper_type_id') ?></th>
        <td><?= Html::encode($model->paperType->category_type) ?></td>
        <th><?= $model->getAttributeLabel('subject_type_id') ?></th>
        <td><?= Html::encode($model->subjectType->category_type) ?></td>
        <th><?= $model->getAttributeLabel('course_type_id') ?></th>
        <td><?= Html::encode($model->courseType->category_type) ?></td>
    </tr>
  </table>

</div>
