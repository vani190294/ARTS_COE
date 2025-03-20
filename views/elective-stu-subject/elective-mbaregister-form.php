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
                <input type="hidden" name="reg_num[]" value="<?= $value['register_number'];?>">
                <?= $value['register_number'];?>
            </div>

            <?php  
                for ($i=1; $i <=$column ; $i++)
                { 
                    $subject_code=$value['register_number']."[]";
                    $id=$value['register_number'].$i;
                ?>
                    <div class="col-xs-2 col-sm-2 col-lg-2">
                          <?= $form->field($model1, 'subject_code')->widget(
                                    Select2::classname(), [  
                                        'data' => $subjectlist,                      
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'options' => [
                                            'placeholder' => '-----Select----',
                                            'id' => $id, 
                                            'name' => $subject_code, 
                                        ],
                                       'pluginOptions' => [
                                           'allowClear' => true,
                                        ],
                                    ])->label(false); ?>
                    </div>
                <?php 
                }
                ?>
        </div>
        <?php 
        }
        ?>
        
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-9 col-sm-9 col-lg-9"></div>
            <div class="col-xs-3 col-sm-3 col-lg-3">
                <div class="form-group">
                    <br>
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success pull-right','name'=>'saveelect','data-confirm' => 'Are you sure you want to Continue?','onclick'=>'spinner();']); ?>
                </div>
            </div>
    </div>
    
     <?php 
        }
        ?>
    <?php ActiveForm::end(); ?>
</div>
</div>


</div>

</div>
