<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Categorytype;
use yii\db\Query;
use app\models\QpSetting;
use app\models\Batch;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\ElectiveStuSubject */

$this->title = 'View Elective Course Student Registration ';
$this->params['breadcrumbs'][] = ['label' => 'Elective Course Student Registration', 'url' => ['mba-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elective-stu-subject-view">
<br>

<div class="box box-success">
<div class="box-body"> 
    
    <div class="col-xs-12 col-sm-12 col-lg-12" >
        <div class="col-xs-9 col-sm-9 col-lg-9">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-xs-3 col-sm-3 col-lg-3">
         
            <?= Html::a('Back', ['mba-index'], ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    </div>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); 
    ?>

    <div class="col-xs-12 col-sm-12 col-lg-12" style="font-size: 16px;">
        Regulation:<b> <?= $regulationyear;?></b>
        Degree Type:<b> <?= $checkdata['degree_type'];?> </b>
        Department: <b><?= $checkdata['dept_code'];?></b>
        <br><br>
    </div>
   

     <?php  
        if($checksemdata!=0)
        {
           $column=$checksemdata/3;
        ?>
    <div class="col-xs-12 col-sm-12 col-lg-12">

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-2 col-sm-2 col-lg-2">
                <b>Register Number</b>
            </div>

            <?php 
                
                for ($i=1; $i <=$column ; $i++)
                { 
                
                ?>
                    <div class="col-xs-2 col-sm-2 col-lg-2">
                      <b>  Course <?= $i;?></b>
                    </div>
                <?php 
                }
                ?>
                <br><br>
        </div>

       <?php  
        foreach ($reg_num as $value) 
        { 
        
        ?>
         <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-2 col-sm-2 col-lg-2">
                <?= $value['register_number'];?>
            </div>

            <?php   $elective_nominal= Yii::$app->db->createCommand("SELECT subject_code FROM cur_elective_nominal WHERE cur_erss_id='".$cur_erss_id."' AND register_number='".$value['register_number']."'")->queryAll();
                
                foreach($elective_nominal as $envalue)
                { 
                    
                ?>
                    <div class="col-xs-2 col-sm-2 col-lg-2">
                          <?= $envalue['subject_code'];?>
                    </div>
                <?php 
                }
                ?>
        </div>
        <?php 
        }
        ?>
        
    </div>

    
     <?php 
        }
        ?>
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
