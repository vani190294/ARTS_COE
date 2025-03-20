<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\StudentCategoryDetails */

$this->title = $model->studentDetails->register_number;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Category Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-category-details-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_student_category_details_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_student_category_details_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <table class="table table-responsive-xl table-responsive table-striped">
    <tr>
        
        <th><?php echo "Register Number"; ?></th>
        <td><?= Html::encode($model->studentDetails->register_number) ?></td>
        <th><?php echo "Old College Register Number"; ?></th>
        <td><?= Html::encode($model->old_clg_reg_no) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code'; ?></th>
        <td><?= Html::encode($model->subject_code) ?></td>

    </tr>
    <tr>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code'; ?></th>
        <td><?= Html::encode($model->subject_code) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name'; ?></th>
        <td><?= Html::encode($model->subject_name) ?></td>
        <th><?= $model->getAttributeLabel('credit_point') ?></th>
        <td><?= Html::encode($model->credit_point) ?></td>
    </tr>
   
    <tr>
        <th><?= $model->getAttributeLabel('CIA') ?></th>
        <td><?= Html::encode($model->CIA) ?></td>
        <th><?= $model->getAttributeLabel('ESE') ?></th>
        <td><?= Html::encode($model->ESE) ?></td>
        <th><?= $model->getAttributeLabel('total') ?></th>
        <td><?= Html::encode($model->total) ?></td>
        
    </tr>
   
    <tr>
        <th><?= $model->getAttributeLabel('grade_point') ?></th>
        <td><?= Html::encode($model->grade_point) ?></td>
        <th><?= $model->getAttributeLabel('grade_name') ?></th>
        <td><?= Html::encode($model->grade_name) ?></td>
        <th><?= $model->getAttributeLabel('year') ?></th>
        <td><?= Html::encode($model->year) ?></td>
        
    </tr>
    <tr>
        <th><?= $model->getAttributeLabel('month') ?></th>
        <td><?= Html::encode($model->month) ?></td>
        <th><?= $model->getAttributeLabel('result') ?></th>
        <td><?= Html::encode($model->result) ?></td>
       
    </tr>
  </table>


</div>
