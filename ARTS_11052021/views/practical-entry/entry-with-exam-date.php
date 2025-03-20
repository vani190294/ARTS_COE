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

echo Dialog::widget();

$this->title = 'PRACTICAL MARK ENTRY WITH EXAM DATE';
/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(), 'register_number', 'register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number);
$from_reg_no = isset($_POST['from_reg']) ? $_POST['from_reg'] : '';
$to_reg_no = isset($_POST['to_reg']) ? $_POST['to_reg'] : '';
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
           
            <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($hallAll, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($hallAll, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $hallAll->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',
                            'onchange'=>'getPracticalExamDa ($("#exam_year").val(),this.value);'                       
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($student, 'exam_date')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Date ----', 
                            'id' => 'exam_date',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($student, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Session ----', 
                            'id' => 'exam_session',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]); ?>
        </div>
        <div class="col-lg-2 col-sm-2">
                <?= $form->field($markEntry, 'mark_type')->textInput(['name'=>'examiner_name','id'=>'examiner_name','required'=>'required'])->label('Internal Examiner Name'); ?>                
        </div> 
        <div class="col-lg-2 col-sm-2">
            <?= $form->field($markEntry, 'mark_type')->textInput(['name'=>'chief_exam_name','id'=>'chief_exam_name','required'=>'required'])->label('External Examiner Name'); ?>                
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($student, 'exam_session')->widget(
                    Select2::classname(), [                        
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'data'=>['A'=>'A','B'=>'B','C'=>'C','D'=>'D'],
                        'options' => [
                            'placeholder' => '-----Select Section ----', 
                            'id' => 'exam_section',     
                            'name'=>'exam_section',                      
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                           'onchange' => 'getExamPracticalStuList($("#exam_year").val(), $("#exam_month").val(),$("#exam_date").val(),$("#exam_session").val(), this.value);',
                        ],
                    ])->label('Section'); ?>
        </div>
        <br />
        <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
            <?= Html::a("Reset", Url::toRoute(['practical-entry/entry-with-exam-date']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
        </div>
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
                <?= Html::submitButton('Updated & Save', ['id'=>'update_comp','class' => 'btn btn-success' ,'formtarget'=>"_blank"]) ?>
            </div>
        </div>
        
        
    <?php ActiveForm::end(); ?>
</div>


</div>
</div>

