<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\Url;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\SubjectsMapping */

$this->title = $model->coeSubjects->subject_code;
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-view">
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php 
            if(Yii::$app->user->can("/sub-view/update") || Yii::$app->user->can("/sub-view/delete"))
            {
        ?>
                <?=
                Html::a('Update', ['update', 'id' => $model->coe_sub_mapping_id], ['class' => 'btn btn-primary']) 
                ?>
           
                 <?= 
                 Html::a('Delete', ['delete', 'id' => $model->coe_sub_mapping_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) 
                 ?>
             <?php 
            }
           
            ?>
        
    </p>

    <table class="table table-responsive-xl table-responsive table-striped">
    <tr>
        <!--<th><?= $model->getAttributeLabel('coe_subjects_mapping_id') ?></th>
        <td><?= Html::encode($this->title) ?></td>-->
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?></th>
        <td><?= Html::encode($model->coeBatchName->batch_name) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?></th>
        <td><?= Html::encode($model->coeDegreeName->degree_code) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME); ?></th>
        <td><?= Html::encode($model->coeProgrammeName->programme_code) ?></td>
    </tr>
    <tr>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code'; ?></th>
        <td><?= Html::encode($model->coeSubjects->subject_code) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Name'; ?></th>
        <td><?= Html::encode($model->coeSubjects->subject_name) ?></td>
        <th><?= $model->getAttributeLabel('semester') ?></th>
        <td><?= Html::encode($model->semester) ?></td>
        <th><?= $model->getAttributeLabel('CIA_min') ?></th>
        <td><?= Html::encode($model->coeSubjects->CIA_min) ?></td>
    </tr>
   
    <tr>
        <th><?= $model->getAttributeLabel('CIA_max') ?></th>
        <td><?= Html::encode($model->coeSubjects->CIA_max) ?></td>
        <th><?= $model->getAttributeLabel('ESE_min') ?></th>
        <td><?= Html::encode($model->coeSubjects->ESE_min) ?></td>
        <th><?= $model->getAttributeLabel('ESE_max') ?></th>
        <td><?= Html::encode($model->coeSubjects->ESE_max) ?></td>
        <th><?= $model->getAttributeLabel('total_minimum_pass') ?></th>
        <td><?= Html::encode($model->coeSubjects->total_minimum_pass) ?></td>
    </tr>
   
    <tr>
        <th><?= $model->getAttributeLabel('credit_points') ?></th>
        <td><?= Html::encode($model->coeSubjects->credit_points) ?></td>
        <th><?= $model->getAttributeLabel('paper_type_id') ?></th>
        <td><?= Html::encode($model->paperTypes->category_type) ?></td>
        <th><?= $model->getAttributeLabel('subject_type_id') ?></th>
        <td><?= Html::encode($model->subjectTypes->category_type) ?></td>
        <th><?= $model->getAttributeLabel('course_type_id') ?></th>
        <td><?= Html::encode($model->courseTypes->category_type) ?></td>
    </tr>
  </table>
</div>
