<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\MandatorySubjects */

$this->title = $model->subject_code;
$this->params['breadcrumbs'][] = ['label' => 'Mandatory '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div class="mandatory-subjects-view">

    <h1><?php echo $this->title; ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_mandatory_subjects_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_mandatory_subjects_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <table class="table table-responsive-xl table-responsive table-striped">
   
    <tr>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' NAME'; ?></th>
        <td><?= Html::encode($model->manBatch->batch_name) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' CODE'; ?></th>
        <td><?= Html::encode($model->subject_code) ?></td>
   
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' NAME'; ?></th>
      
        <td><?= Html::encode($model->CIA_min) ?></td>
    </tr>
   
    <tr>
        <th><?= $model->getAttributeLabel('CIA_max') ?></th>
        <td><?= Html::encode($model->CIA_max) ?></td>
    
        <th><?= $model->getAttributeLabel('ESE_min') ?></th>
        <td><?= Html::encode($model->ESE_min) ?></td>
    </tr>
    <tr>
        <th><?= $model->getAttributeLabel('ESE_max') ?></th>
        <td><?= Html::encode($model->ESE_max) ?></td>
   
        <th><?= $model->getAttributeLabel('total_minimum_pass') ?></th>
        <td><?= Html::encode($model->total_minimum_pass) ?></td>
    </tr>
   
  </table>
    
</div>
