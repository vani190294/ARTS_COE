<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\ExamTimetable;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\HallAllocate;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Update Old Grade Points";
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>
<input type="hidden" value="All" name="section" id='stu_section_select' class='form-control student_disable' />
<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
          
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'year')->textInput(['value'=>$year,'id'=>'mark_year']) ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry,'month')->widget(
                    Select2::classname(), [
                        'data'=>HallAllocate::getMonth(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
         
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'semester')->textInput(['name'=>'register_number','id'=>'reg_num'])->label('Register Number'); ?>
                
            </div> 
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'semester')->textInput(['name'=>'subject_code','id'=>'sub_code'])->label(strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." CODE"); ?>
                
            </div>     <br />       

                <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                    <?= Html::Button('Submit', ['id'=>'get_det','onClick'=>'getStuSubGradeDetail($("#mark_year").val(), $("#exam_month").val(),$("#reg_num").val(),$("#sub_code").val() )','class' => 'btn btn-success' ]) ?>
                    <?= Html::a("Reset", Url::toRoute(['categorytype/update-old-grade']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>

        </div>
        <br />
        <div id='hide_dum_sub_data' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-1"> &nbsp; </div>
                <div class="col-xs-10">
                    <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <thead class="thead-inverse">
                            <tr class="table-danger">
                                
                                <th>SNO</th>
                                <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE"); ?></th>
                                
                                <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME"); ?></th>
                                <th><?php echo strtoupper("Minimum"); ?></th>
                                <th><?php echo strtoupper("Maximum"); ?></th>
                                <th><?php echo strtoupper("Min Pass"); ?></th>
                            </tr>               
                        </thead> 
                        <tbody id="show_dummy_entry">     

                        </tbody>
                    </table> 
                </div>
            <div class="col-xs-1"> &nbsp; </div>
            </div>
        </div> <!-- Row Closed --><br />
        <div class="row">
            <div id='disp_show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">
                <div id='show_details_subs' class="col-xs-12 col-sm-12 col-lg-12">

                </div>
                <?= Html::submitButton('Updated & Save', ['id'=>'update_comp','class' => 'btn btn-success' ]) ?>
            </div>
        </div>
        
        
    <?php ActiveForm::end(); ?>
</div>


</div>
</div>

