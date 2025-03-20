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
/* @var $model app\models\ElectiveRegister */

$this->title = 'Elective Course Registration View';
$this->params['breadcrumbs'][] = ['label' => 'Elective Registers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="elective-register-view">
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
         
            <?= Html::a('Update', ['update', 'id' => $model->cur_elect_id], ['class' => 'btn btn-primary pull-right']) ?>
           
       
    <?php }?>

            <?= Html::a('Back', ['index'], ['class' => 'btn btn-primary pull-right']) ?>
           
        </div>
    </div>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12" style="font-size: 16px;">
        <b>Regulation: <?= $regulationyear;?>
        Degree Type: <?= $checksemdata['degree_type'];?> 
        Department: <?= $checksemdata['dept_code'];?></b>
        <br><br>
    </div>
   

    <div class="col-xs-12 col-sm-12 col-lg-12">
       
        <?php  

        if($model->pec_paper!='' && $model->pec_paper!=0)
        {
           for ($i=1; $i <=$model->pec_paper ; $i++) 
           { 
                $id='PEC'.$i;

                $checkelectivesubject = Yii::$app->db->createCommand("SELECT subject_code FROM cur_elective_register_subject WHERE elective_paper='".$id."' AND cur_elect_id=".$model->cur_elect_id)->queryAll();

                $savedata=array();
                foreach ($checkelectivesubject as $evalue) 
                {
                    $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject WHERE subject_code='".$evalue['subject_code']."'")->queryScalar();

                    if($subject_name=='')
                    {
                        $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject WHERE subject_code='".$evalue['subject_code']."'")->queryScalar();
                        
                        if($subject_name=='')
                        {
                            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code='".$evalue['subject_code']."'")->queryScalar();

                            if($subject_name=='')
                            {
                                $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code='".$evalue['subject_code']."'")->queryScalar();
                            }
                        }
                    }   
                    $savedata[]=$evalue['subject_code'].'-'.$subject_name;
                }

                if(count($savedata)>0)
                {
                    $implode=implode(", ", $savedata);
                }

              ?>

            <div class="col-xs-3 col-sm-3 col-lg-3">
                <label class="control-label">PEC <?= $i;?>: </label>
                <?= $implode;?>
            </div>

            <?php 
            }
        }

        if($model->oec_paper!='' && $model->oec_paper!=0)
        {
           for ($i=1; $i <=$model->oec_paper ; $i++) 
           { 
                $id='OEC'.$i;

                $checkelectivesubject = Yii::$app->db->createCommand("SELECT subject_code FROM cur_elective_register_subject WHERE elective_paper='".$id."' AND cur_elect_id=".$model->cur_elect_id)->queryAll();

                //print_r($checkelectivesubject); exit();

                $savedata=array();
                foreach ($checkelectivesubject as $evalue) 
                {
                    $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject WHERE subject_code='".$evalue['subject_code']."'")->queryScalar();

                    if($subject_name=='')
                    {
                        $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject WHERE subject_code='".$evalue['subject_code']."'")->queryScalar();
                        
                        if($subject_name=='')
                        {
                            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$evalue['subject_code']."'")->queryScalar();

                            if($subject_name=='')
                            {
                                $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code_new='".$evalue['subject_code']."'")->queryScalar();
                            }
                        }
                    }   
                    $savedata[]=$evalue['subject_code'].'-'.$subject_name;
                }

                if(count($savedata)>0)
                {
                    $implode=implode(", ", $savedata);
                }

              ?>

            <div class="col-xs-3 col-sm-3 col-lg-3">
                <label class="control-label">OEC <?= $i;?>: </label>
                <?= $implode;?>
            </div>

            <?php 
            }
        }

        if($model->eec_paper!='' && $model->eec_paper!=0)
        {
           for ($i=1; $i <=$model->eec_paper ; $i++) 
           { 
                $id='EEC'.$i;

                $checkelectivesubject = Yii::$app->db->createCommand("SELECT subject_code FROM cur_elective_register_subject WHERE elective_paper='".$id."' AND cur_elect_id=".$model->cur_elect_id)->queryAll();

                $savedata=array();
                foreach ($checkelectivesubject as $evalue) 
                {
                    $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject WHERE subject_code='".$evalue['subject_code']."'")->queryScalar();

                    if($subject_name=='')
                    {
                        $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject WHERE subject_code='".$evalue['subject_code']."'")->queryScalar();
                        
                        if($subject_name=='')
                        {
                            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_elective_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code='".$evalue['subject_code']."'")->queryScalar();

                            if($subject_name=='')
                            {
                                $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject A JOIN cur_electivetodept B ON B.subject_code=A.subject_code WHERE B.subject_code='".$evalue['subject_code']."'")->queryScalar();
                            }
                        }
                    }   
                    $savedata[]=$evalue['subject_code'].'-'.$subject_name;
                }

                if(count($savedata)>0)
                {
                    $implode=implode(", ", $savedata);
                }

              ?>

            <div class="col-xs-3 col-sm-3 col-lg-3">
                <label class="control-label">EEC <?= $i;?>: </label>
                <?= $implode;?>
            </div>

            <?php 
            }
        }

        if($model->mc_paper!='' && $model->mc_paper!=0)
        {
            $checkelectivesubject = Yii::$app->db->createCommand("SELECT subject_code FROM cur_elective_register_subject WHERE elective_paper='MC' AND cur_elect_id=".$model->cur_elect_id)->queryScalar();

            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject WHERE subject_code='".$checkelectivesubject."'")->queryScalar();

                   
              ?>

            <div class="col-xs-3 col-sm-3 col-lg-3">
                <label class="control-label">MC: </label>
                <?= $checkelectivesubject.'-'.$subject_name;?>
            </div>

            <?php 
        }

         if($model->ac_paper!='' && $model->ac_paper!=0)
        {
            $checkelectivesubject = Yii::$app->db->createCommand("SELECT subject_code FROM cur_elective_register_subject WHERE elective_paper='AC' AND cur_elect_id=".$model->cur_elect_id)->queryScalar();

            $subject_name = Yii::$app->db->createCommand("SELECT subject_name FROM cur_curriculum_subject WHERE subject_code='".$checkelectivesubject."'")->queryScalar();

                   
              ?>

            <div class="col-xs-3 col-sm-3 col-lg-3">
                <label class="control-label">AC: </label>
                <?= $checkelectivesubject.'-'.$subject_name;?>
            </div>

            <?php 
        }

        ?>
        
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
