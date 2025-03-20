<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
/* @var $this yii\web\View */
/* @var $model app\models\Regulation */
Yii::$app->ShowFlashMessages->showFlashes();
$this->title = $model->regulation_year;
$this->params['breadcrumbs'][] = ['label' => 'Regulations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regulation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_regulation_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coe_regulation_id], [
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
        <td><?= Html::encode($model->coeBatch->batch_name) ?></td>
        <th><?php echo "Regulation"; ?></th>
        <td><?= Html::encode($model->regulation_year) ?></td>
        
    </tr>
    <tr>
       
        <th><?php echo 'Grade Name'; ?></th>
        <td><?= Html::encode($model->grade_name) ?></td>
        <th><?php echo "Grade From"; ?></th>
        <td><?= Html::encode($model->grade_point_from) ?></td>
       
       
    </tr>
    <tr>
       
       
        <th><?php echo "Grade To"; ?></th>
        <td><?= Html::encode($model->grade_point_to) ?></td>
       
    </tr>
    
  </table>


</div>
