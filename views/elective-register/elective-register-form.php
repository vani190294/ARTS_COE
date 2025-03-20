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

$this->title = 'Elective Course Register Form';
$this->params['breadcrumbs'][] = ['label' => 'Elective Registers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
.select2-results {
    height: 100px !important;
    overflow: auto;
}    
.select2-container
{
    width: 100% !important;
}
</style>
<div class="elective-register-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="elective-register-form">

   <div class="box box-success">
<div class="box-body"> 
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <div>&nbsp;</div>
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12" style="display:none">
            <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [
                        'data' => $model->getMonth(),
                    ]) ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">

        <?php  //print_r($electcount); exit();
        if(count($electcount)!=0)
        {
            for ($i=0; $i <count($electcount) ; $i++) 
            { 
                $n = $electcount[$i]/3;
                $whole = floor($n);      // 1
                $fraction = $n - $whole; // .25

                if($fraction>0)
                {
                    $n = $electcount[$i]/4;
                }

                if($electcount[$i]==0)
                {
                   if($electdata[$i]=='MC')
                    { ?>
                    <div class="col-xs-12 col-sm-12 col-lg-12">
                        <div class="col-xs-3 col-sm-3 col-lg-3">
                            <div class="form-group">
                               <label class="control-label">MC</label>
                               <select class="f1 form-control" name="mc_course">
                                <option value="">Select</option>
                                <?php
                                foreach ($mcdata as $value) 
                                {   
                                    ?>
                                    <option value="<?= $value['subject_code'];?>"><?= $value['subject_code'];?> - <?= $value['subject_name'];?></option>
                                    <?php 
                                }

                                ?>
                               </select>
                            </div>
                        </div>
                    </div>
                    <?php }
                    if($electdata[$i]=='AC')
                    {?>
                        <div class="col-xs-12 col-sm-12 col-lg-12">
                        <div class="col-xs-3 col-sm-3 col-lg-3">
                            <div class="form-group">
                               <label class="control-label">Audit Course</label>
                               <select class="f1 form-control" name="ac_course">
                                <option value="">Select</option>
                                <?php
                                foreach ($acdata as $value) 
                                {   
                                    ?>
                                    <option value="<?= $value['subject_code'];?>"><?= $value['subject_code'];?> - <?= $value['subject_name'];?></option>
                                    <?php 
                                }

                                ?>
                               </select>
                            </div>
                        </div>
                    </div>

                   <?php }
                }
                else
                {?>
                  <div class="col-xs-12 col-sm-12 col-lg-12">
                  <?php

                    $loopdata='';
                    if($electdata[$i]=='PEC')
                    {
                        $loopdata=$pecdata;
                    }
                    else if($electdata[$i]=='OEC')
                    {
                        $loopdata=$oecdata;
                    }
                    else if($electdata[$i]=='EEC')
                    {
                        $loopdata=$eecdata;
                    }
                    for ($loop=1; $loop <=$n ; $loop++) 
                    { 
                        $id=$electdata[$i].$loop;
                        $name=$id."[]";?>
                        <div class="col-xs-4 col-sm-4 col-lg-4">
                            <div class="form-group">
                               <label class="control-label"><?= $electdata[$i];?> <?= $loop;?></label>
                               <select class="fs1 form-control select2-hidden-accessible" id="<?= $id;?>" name="<?= $name;?>" multiple>
                               
                                 <?php
                                foreach ($loopdata as $value) 
                                {   
                                    ?>
                                    <option value="<?= $value['subject_code'];?>"><?= $value['subject_code'];?> - <?= $value['subject_name'];?></option>
                                    <?php 
                                }

                                ?>
                               </select>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                    <?php
                }
                
            }
        }
        else if(count($mcdata)!=0)
        {?>

            <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                   <label class="control-label">MC</label>
                   <select class="f1 form-control" name="mc_course">
                    <option value="">Select</option>
                    <?php
                    foreach ($mcdata as $value) 
                    {   
                        ?>
                        <option value="<?= $value['subject_code'];?>"><?= $value['subject_code'];?> - <?= $value['subject_name'];?></option>
                        <?php 
                    }

                    ?>
                   </select>
                </div>
            </div>

       <?php  }
        ?>

        
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-9 col-sm-9 col-lg-9"></div>
            <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success pull-right','name'=>'saveelect']); ?>
                </div>
            </div>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
</div>


</div>

</div>
