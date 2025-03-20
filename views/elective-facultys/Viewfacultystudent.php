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
$this->params['breadcrumbs'][] = ['label' => 'Elective Course Student Registration', 'url' => ['index']];
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
         <?php if($model->approve_status==0) 
            {?>
             <?= Html::a('Update Allocate', ['faculty-student-allocate', 'id' => $cur_ef_id], ['class' => 'btn btn-primary pull-right']) ?>
        <?php }?>
            <?= Html::a('Back', ['fs-index'], ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    </div>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); 

    $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject WHERE subject_code='".$checkelective['subject_code']."'")->queryScalar();

    if($subject_name=='')
    {
        $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject WHERE subject_code='".$checkelective['subject_code']."'")->queryScalar();
        
        if($subject_name=='')
        {
            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$checkelective['subject_code']."'")->queryScalar();

            if($subject_name=='')
            {
                $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$checkelective['subject_code']."'")->queryScalar();
            }
        }
    }

   
    ?>

    <div class="col-xs-12 col-sm-12 col-lg-12" style="font-size: 16px;">
        Regulation:<b> <?= $regulationyear;?></b>
        Degree Type:<b> <?= $checksemdata['degree_type'];?> </b>
        Department: <b><?= $checksemdata['dept_code'];?></b>
        <br>
        Subject Code: <b><?= $checkelective['subject_code'];?></b>
        <br>
        Subject Name: <b><?= $subject_name;?>
        </b>
        <br>
         Faculty Name: <b><?= $faculty_name;?> </b>
        <br><br>
    </div>
   

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <?php $sectionid=1; $checkedcout=0;

        if(!empty($deptdata))
        {
        foreach ($deptdata as $key => $batchvalue) 
        {
            $batch_map_id=$key;
            ?>
            <div class="col-xs-12 col-sm-12 col-lg-12">
                <h1 style="text-align:center;">Department : <?= $deptdata[$batch_map_id]['dept_code'];?></h1>
            <?php
            
            $depdata= $deptdata[$batch_map_id]['reg_num']; 
            //print_r($depdata); exit;    
            foreach ($depdata as $seckey => $value) 
            {

                $regnums=$depdata[$seckey]; 
                $checname='section'.$sectionid;
                $checkstu='sectionstud'.$sectionid;
                ?>
                <div class="col-xs-12 col-sm-12 col-lg-12">
                    <div class="col-xs-12 col-sm-12 col-lg-12" style="text-align: center;">
                        <b>Section <?= $seckey;?></b><br>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-lg-12">
                        
                        <?php 
                            for ($i=0; $i <count($regnums) ; $i++) 
                            { 
                                
                                ?>
                                <div class="col-xs-2 col-sm-2 col-lg-2">
                                   
                                    <?= $regnums[$i]['register_number'];?>
                                </div>
                            <?php
                            } 
                            ?>
                    </div>
                </div>

                <?php $sectionid++;
            }
            ?>
            </div>
            <?php
        }
        }else{
            echo "<div style='color:red; text-align:center;'>No Register Number Allocated</div>";
        }
        ?>
    </div>

    <?php if($model->approve_status==0 && !empty($deptdata)) 
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
