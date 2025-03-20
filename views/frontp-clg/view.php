<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FrontpClg */

$this->title = 'View Front Page Content';
$this->params['breadcrumbs'][] = ['label' => 'Index', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frontp-clg-view">


<br>

<div class="box box-success">
<div class="box-body"> 
      <div class="col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-xs-9 col-sm-9 col-lg-9">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-3 col-sm-3 col-lg-3">
       

            <?= Html::a('Back', ['index'], ['class' => 'btn btn-primary pull-right']) ?>
           
        </div>
    </div>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12" style="font-size: 16px;">
        <b>Regulation: <?= $regulationyear;?>
        Degree Type: <?= $vision['degree_type'];?></b>
        <br><br>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <h3>Vision</h3>
        <p>
            <?= $vision['vision'];?>
        </p>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <h3>Mission</h3>
        <ul>
            <?php 
            foreach ($mission as $key => $value) 
            { ?>
                <li><?= $value['mission'];?></li>

            <?php } ?>
        </ul>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <h3>Program Outcomes</h3>
        <ol>
            <?php 
            foreach ($po_list as $key => $value) 
            { ?>
                <li><b><?= $value['po_title'];?>:</b> <?= $value['po'];?></li>

            <?php } ?>
        </ol>
    </div>

 <?php if($model->approve_status==0) 
    {?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-9 col-sm-9 col-lg-9"></div>
            <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton('Approve', ['class' => 'btn btn-success pull-right','name'=>'saveelect']); ?>
                </div>
            </div>
    </div>

    <?php } ?>
    
    <?php ActiveForm::end(); ?>
</div>
</div>

   
</div>
