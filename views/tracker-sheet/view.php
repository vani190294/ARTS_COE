<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\dialog\Dialog;
echo Dialog::widget();
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\ApplyLeave */
$this->title = "Tracker Sheet";
$this->params['breadcrumbs'][] = ['label' => 'Tracker Sheet', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?> 
<div class="tracker-sheet-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p class="pull-right">
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-success']) ?>
    </p>
<section class="content">

<div class="row">
    <div class="col-lg-12 table-responsive  no-padding" style="margin-bottom:15px;text-align: center; ">

         <div class="col-md-2 padding text-center"></div>
        <div class="col-md-6 padding text-center">
            <table  class="table table-striped" width="100%">
               
                 <tr>
                    <th><b><?= $model->getAttributeLabel('task_tittle') ?></b></th>
                    <td><?= Html::encode($model->task_tittle) ?></td>
                </tr>
                <tr>
                  <th><b><?= $model->getAttributeLabel('task_description') ?></b></th>
                    <td><?= Html::encode($model->task_description) ?></td>
                </tr> 
                
                <tr>
                    <th><b><?= $model->getAttributeLabel('priority') ?></b>
                    <td>
                      
                      <?= Html::encode($model->priority) ?>
                    </td>
                </tr>  
                     
                <tr>
                    <th><b><?= $model->getAttributeLabel('date') ?><b/></th>
                    <td><?= Html::encode(DATE('d-m-Y',strtotime($model->date)))?></td>
                </tr>

                <tr>
                    <th><b><?= $model->getAttributeLabel('task_type') ?></b>
                    <td>
                      <?= Html::encode($model->task_type) ?>
                    </td>
                </tr> 
                <tr>
                    <th><b><?=$model->getAttributeLabel('remark') ?></b></th>
                    <td><?=Html::encode($model->remark) ?></td>
                </tr>  
                <tr>
                   <th><b><?= $model->getAttributeLabel('status') ?></b>
                    <td>
                      <?= Html::encode($model->status) ?>
                    </td>
                </tr>
                <tr>
                    <th><b><?=$model->getAttributeLabel('developed_by') ?></b></th>
                    <td><?=Html::encode($model->developed_by) ?></td>
                </tr>         
               
                
              
            </table>
        </div>
        
    </div>

     </div> <!---End Row Div -->
     </section>
