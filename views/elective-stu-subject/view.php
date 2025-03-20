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
             <?= Html::a('Update', ['update', 'id' => $model->cur_erss_id], ['class' => 'btn btn-primary pull-right']) ?>
        <?php }?>
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-primary pull-right']) ?>
            <?= Html::a('Create', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
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

    $elective_nominal= Yii::$app->db->createCommand("SELECT register_number FROM cur_elective_nominal WHERE cur_erss_id='".$cur_erss_id."'")->queryAll();
    $savedata=array();
    foreach ($elective_nominal as $evalue) 
    {
         $name = Yii::$app->db->createCommand("SELECT name FROM coe_student WHERE register_number='".$evalue['register_number']."'")->queryScalar();

        $savedata[]=$evalue['register_number']." - ".$name;
    }
    $implode='';
    if(count($savedata)>0)
    {
        $implode=implode("<br>", $savedata);
    }
    ?>

    <div class="col-xs-12 col-sm-12 col-lg-12" style="font-size: 16px;">
        Regulation:<b> <?= $regulationyear;?></b>
        Degree Type:<b> <?= $checksemdata['degree_type'];?> </b>
        Department: <b><?= $checksemdata['dept_code'];?></b>
        <br>
        Subject Code: <b><?= $checkelective['subject_code'];?></b>
        <br>
        Subject Name: <b><?= $subject_name;?></b>
        <br>
         Semester: <b><?= $checkelective['semester'];?></b>
        <br>
        
        <br>
        No. of Elective Opted Students Count: <b><?= count($savedata);?></b>
        <br>
        <br>
    </div>
   

    <div class="col-xs-12 col-sm-12 col-lg-12">
       <label class="control-label">Register Number: </label><br>
        <?= $implode;?>
        
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
