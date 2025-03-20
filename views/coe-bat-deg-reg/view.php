<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

/* @var $this yii\web\View */
/* @var $model app\models\CoeBatDegReg */

$this->title = $model->coeDegree->degree_code." ".$model->coeProgramme->programme_code;
$this->params['breadcrumbs'][] = ['label' => 'Bat Deg Regs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->ShowFlashMessages->showFlashes();
?>
<div class="coe-bat-deg-reg-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_bat_deg_reg_id], ['class' => 'btn btn-primary']) ?>
       
    </p>

    <table class="table table-responsive-xl table-responsive table-striped">
    <tr>
       
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH); ?></th>
        <td><?= Html::encode($model->coeBatch->batch_name) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE); ?></th>
        <td><?= Html::encode($model->coeDegree->degree_code) ?></td>
        <th><?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME); ?></th>
        <td><?= Html::encode($model->coeProgramme->programme_code) ?></td>
    </tr>
    <tr>
        <th><?php echo 'Regulation Year'; ?></th>
        <td><?= Html::encode($model->regulation_year) ?></td>
        <th><?php echo "No Of ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION); ?></th>
        <td><?= Html::encode($model->no_of_section) ?></td>
       
    </tr>
    
  </table>
</div>
