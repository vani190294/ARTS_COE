<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\Scrutiny */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Scrutinies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>
<div class="scrutiny-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coe_scrutiny_id], ['class' => 'btn btn-primary']) ?>
       
    </p>
    
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-body">
                <div class="box-group" id="accordion">
                    <div class="panel  box box-info">
                        <div class="box-header  with-border" role="tab">
                            <div class="row">
                                <div class="col-md-10">
                                    <h4 class="padding box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                            Personal details
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse in">
                            <div class="box-body">
                                <table class="table table-responsive-xl table-responsive table-striped">
                                    <tr>
                                        <th><?= $model->getAttributeLabel('name') ?></th>
                                        <td><?= Html::encode($model->name) ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= $model->getAttributeLabel('department') ?></th>
                                        <td> <abbr title="<?= Html::encode($model->departmentName->dept_code) ?>">
                                            <?= Html::encode($model->departmentName->dept_code) ?></abbr> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?= $model->getAttributeLabel('designation') ?></th>
                                        <td><?= Html::encode($model->designationName->category_type) ?></td>
                                        </tr>
                                    </tr>
                                    <tr>
                                        <th><?= $model->getAttributeLabel('phone_no') ?></th>
                                        <td><?= Html::encode($model->phone_no) ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= $model->getAttributeLabel('email') ?></th>
                                        <td><?= Html::encode($model->email) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-body">
                <div class="box-group" id="acc">
                    <div class="panel  box box-warning">
                        <div class="box-header  with-border" role="tab">
                            <div class="row">
                                <div class="col-md-10">
                                    <h4 class="padding box-title">
                                        <a class="text-warning" data-toggle="collapse" data-parent="#ac" href="#collTwo">
                                            Bank Details
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div id="collTwo" class="panel-collapse collapse in">
                            <div class="box-body">
                                <table class="table table-responsive-xl table-responsive table-striped">
                                    <tr>
                                        <th><?= $model->getAttributeLabel('Account number') ?></th>
                                        <td><?= Html::encode($model->bank_accno) ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= $model->getAttributeLabel('IFSC Number') ?></th>
                                        <td><?= Html::encode($model->bank_ifsc) ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= $model->getAttributeLabel('Bank Name') ?></th>
                                        <td><?= Html::encode($model->bank_name) ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= $model->getAttributeLabel('Branch Name') ?></th>
                                        <td><?= Html::encode($model->bank_branch) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>