<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\ExamTimetable;
use app\models\Categorytype;
use app\models\MarkEntry;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'UPDATE REVALUATION MARKS';
$year= isset($_POST['MarkEntryMaster']['year'])?$_POST['MarkEntryMaster']['year']:date('Y');
$month= isset($_POST['month'])?$_POST['month']:'';
$this->registerJs("$(document).ready(function() { $(window).keydown(function(event){ if(event.keyCode == 13) { event.preventDefault(); return false; } }); });");
?>
<h1>UPDATE REVALUATION MARKS</h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
           
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['required'=>'required','name'=>'year','value'=>$year,'id'=>'mark_year']) ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'data' => $model::getMonth(),
                        'options' => [                            
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',                            
                            'required'=>'required',
                            'name'=>'month',
                            'value'=>$month
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'grade_name')->textInput(['name'=>'register_number','id'=>'register_number'])->label('Register Number') ?>

            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['name'=>'dummy_number','id'=>'dummy_number'])->label(strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY))) ?>

            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($student, 'register_number')->textInput(['id'=>'subject_code','name'=>'subject_code'])->label(strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." CODE") ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['name'=>'semester_val','id'=>'semester'])->label('SEMESTER') ?>

            </div>
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform"> <br />
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success','onClick' =>'requireFields();' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/reval-marks-update']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        
       
    <?php ActiveForm::end(); ?>
    </div> 
    <?php 
    if(isset($chec_written) && !empty($chec_written))
    {
        $header = '<div style="margin-top: 25px;" >&nbsp;</div>';
        $header .= '<table class="table table-responsive table-hover" width="100%"  border="1" style="padding-top: 20px !important;line-height:3em; padding: 10px;"   >
        <thead  class="thead-dark">
                    <tr class="table-active">
                        <th scope="col">REGISTER NUMBER</th>
                        <th scope="col">NAME</th>
                        <th scope="col">' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " CODE") . '</th>
                        <th scope="col">' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT) . " NAME") . '</th>
                        
                        <th scope="col">PREV MARKS</th>
                        <th scope="col">NEW MARKS</th>          
                        <th scope="col">&nbsp;</th>              
                    </tr> 
                <tbody></thead>';
                
                
                    $sub_info = Yii::$app->db->createCommand('SELECT subject_code,subject_name,ESE_max FROM coe_subjects as A JOIN coe_subjects_mapping as B ON B.subject_id=A.coe_subjects_id WHERE B.coe_subjects_mapping_id="'.$chec_written["subject_map_id"].'"')->queryOne();

                    $stu_info = Yii::$app->db->createCommand('SELECT register_number,name FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id WHERE B.coe_student_mapping_id="'.$chec_written["student_map_id"].'"')->queryOne();
                    $category_type_id = Categorytype::find()->where(['description' => "Revaluation"])->orWhere(['category_type' => "Revaluation"])->one();
                    
                    $stu_marks = Yii::$app->db->createCommand('SELECT * FROM coe_mark_entry WHERE student_map_id="'.$chec_written["student_map_id"].'" and subject_map_id="'.$chec_written["subject_map_id"].'" and year="'.$chec_written['year'].'" and month="'.$chec_written['month'].'" and mark_type="'.$chec_written['mark_type'].'" and category_type_id="'.$category_type_id['coe_category_type_id'].'"')->queryOne();

                    $header .= '<tr>
                                <td><input type="hidden" name="stuMapId" value='.$chec_written["student_map_id"].' />' . $stu_info["register_number"] . '</td>
                                <td><input type="hidden" name="subMapId" value='.$chec_written["subject_map_id"].' />' . $stu_info["name"] . '</td>
                                <td><input type="hidden" name="year" value='.$chec_written["year"].' /> <input type="hidden" name="sub_max_val" id="sub_max_val" value='.$sub_info["ESE_max"].' />' . $sub_info["subject_code"] . ' </td>
                                <td><input type="hidden" name="month" value='.$chec_written["month"].' />' . $sub_info["subject_name"] . '</td>
                                <td><input type="hidden" name="mark_type" value='.$chec_written["mark_type"].' /> <input type="hidden" name="cia_marks" value='.$chec_written["CIA"].' />' . $stu_marks["category_type_id_marks"] . '</td>
                                <td><input  type="text" name="update_ese"  onkeypress="numbersOnly(event); allowEntr(event,this.id); " id="update_ese" autocomplete="off" onblur="check_sub_max_number(this.id, this.value, $(\'#sub_max_val\').val() );" /></td>
                                <td><input type="submit" name="submit_ese" class="btn btn-block btn-danger" value="UPDATE" /></td>
                             </tr>';
            echo $header .='</tbody></table>';
         
    }

    ?>
           
</div>
</div>
</div>
