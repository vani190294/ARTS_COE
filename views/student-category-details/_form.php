<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\dialog\Dialog;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\widgets\TypeaheadBasic;
use app\models\StudentCategoryDetails;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
$formatter = \Yii::$app->formatter;
echo Dialog::widget();
$admission_status = isset($model->stu_status_id) && $model->stu_status_id!=""?$model->stu_status_id:"";
$student_map_id = isset($model->student_map_id) && $model->student_map_id!=""?$model->student_map_id:"";
if(isset($model->student_map_id))
{
    $this->registerJs("$(document).ready(function() { $('.student_disable').attr('disabled',true)});");
}
/* @var $this yii\web\View */
/* @var $model app\models\StudentCategoryDetails */
/* @var $form yii\widgets\ActiveForm */
?>
<div id="student_update_edit_page" class="student-category-details-form">
    <?php 
        $form = ActiveForm::begin(); 
    ?>
    <div class="box box-primary box box-solid">
        <div class="box-body"> 
            <div class="box-group" id="accordion">
                <div class="panel  box box-info">
                  <div class="box-header  with-border" role="tab" >
                    <div class="row">
                        <div class="col-md-10">
                             <h4 class="padding box-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                <?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Information" ?>
                              </a>                              
                            </h4>
                        </div>
                    </div>
                  </div>
            <div id="collapseOne" class="panel-collapse collapse in">
            <div class="box-body">
            <div  class="row">
                <div  class="col-xs-12">
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                        <?php echo $form->field($model, 'student_map_id')->widget(
                                Select2::classname(), [
                                'data' => ConfigUtilities::TransferStudents($student_map_id),                                
                                'options' => [
                                    'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' ----',
                                    'id' => 'student_map_id',  
                                    'value'=>$student_map_id,
                                    'class'=>'form-control student_disable',
                                ],
                                ]); 
                            ?>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                        <?= $form->field($model, 'old_clg_reg_no')->textInput(['maxlength' => true,'class'=>'form-control student_disable']) ?>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-lg-3">
                        <?php echo $form->field($model, 'stu_status_id')->widget(
                                Select2::classname(), [
                                'data' => $model->StudentStatus,                                
                                'options' => [
                                    'placeholder' => '-----Select Status----',
                                    'id' => 'stu_status_id',                                    
                                    'value' => $admission_status,
                                    'class'=>'form-control student_disable',
                                ],
                                ]); 
                            ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        </div> <!-- Student Information ends Her  -->
        <div class="panel  box box-warning">
        <div class="box-header with-border">
        <div class="row">
            <div class="col-md-12">
                 <h4 style="width: 100% !important;" class="padding box-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    <?php echo ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Information" ?>
                  </a>
                  
                    <?= Html::Button('Existing '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), ['id'=>"add_sub_row",'style'=>$model->isNewRecord?'display: block':'display: none','class' => 'btn btn-primary pull-right']) ?>
                     
                </h4>
            </div>
        </div>
      </div>
        <div id="collapseTwo" class="panel-collapse collapse in">
            <div id='create_div_element'>
            <?php 

                if($student_map_id!="")
                {
                    $check_data = StudentCategoryDetails::find()->where(['student_map_id'=>$student_map_id,'coe_student_category_details_id'=>$model->coe_student_category_details_id])->one();
                }
                if(!empty($check_data) && count($check_data)>0)
                {
                       
            ?> 
            
            <div id="add_sub_row_div" class="box-body">
                
                <h3 style="color: #57ba1d; font-weight: bold;" class="add_count_sub">
                    <?php $name_change = $model->isNewRecord?"NEW ":"UPDATE "; 
                    echo $name_change.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); ?></h3><br />
                
            <div  class="row seven-cols">
                <div class="col-md-1">
                    <?= $form->field($model, 'subject_code[]')->textInput(['value'=>$check_data['subject_code']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'subject_name[]')->textInput(['value'=>$check_data['subject_name']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'semester[]')->textInput(['value'=>$check_data['semester']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'credit_point[]')->textInput(['value'=>$check_data['credit_point']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'CIA[]')->textInput(['value'=>$check_data['CIA']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'ESE[]')->textInput(['value'=>$check_data['ESE']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'total[]')->textInput(['value'=>$check_data['total']]) ?>
                </div>
            </div>      
            <div  class="row seven-cols">
                <div class="col-md-1">
                    <?= $form->field($model, 'result[]')->textInput(['value'=>$check_data['result']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'grade_point[]')->textInput(['value'=>$check_data['grade_point']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'grade_name[]')->textInput(['value'=>$check_data['grade_name']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'gpa[]')->textInput(['value'=>$check_data['gpa']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'year[]')->textInput(['value'=>$check_data['year']]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'month[]')->textInput(['value'=>$check_data['month']]) ?>
                    
                   
                </div>
                <div class="col-md-1">
                   <?= $form->field($model, 'year_of_passing[]')->textInput(['value'=>$check_data['year_of_passing']]) ?>
                </div>
            </div>
        </div><!-- 2nd Box Info Closed Here -->
    <?php
          
        } // if not empty if check data is closed here 
        else
        {
            ?>
            <div id="add_sub_row_div" class="box-body">
                <h3 style="color: #57ba1d; font-weight: bold;" class="add_count_sub"><?php echo "NEW ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); ?></h3><br />
            <div  class="row seven-cols">
                <div class="col-md-1">
                    <?= $form->field($model, 'subject_code[]')->textInput(['maxlength' => true,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'subject_name[]')->textInput(['maxlength' => true,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'semester[]')->textInput(['onkeypress'=>'numbersOnly(event)','maxlength'=>1,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'credit_point[]')->textInput(['onkeypress'=>'numbersOnly(event)','maxlength'=>3,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'CIA[]')->textInput(['onkeypress'=>'numbersOnly(event)','maxlength'=>3,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'ESE[]')->textInput(['onkeypress'=>'numbersOnly(event)','maxlength'=>3,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'total[]')->textInput(['onkeypress'=>'numbersOnly(event)','maxlength'=>3,'autocomplete'=>"off"]) ?>
                </div>
            </div>      
            <div  class="row seven-cols">
                <div class="col-md-1">
                    <?= $form->field($model, 'result[]')->textInput(['maxlength' => true,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'grade_point[]')->textInput(['onkeypress'=>'numbersOnly(event)','maxlength'=>6,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'grade_name[]')->textInput(['maxlength' => true,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'gpa[]')->textInput(['onkeypress'=>'numbersOnly(event)','maxlength'=>6,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'year[]')->textInput(['onkeypress'=>'numbersOnly(event)','maxlength'=>4,'autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                   <?= $form->field($model, 'month[]')->textInput(['autocomplete'=>"off"]) ?>
                </div>
                <div class="col-md-1">
                   <?= $form->field($model, 'year_of_passing[]')->textInput(['maxlength' => true,'autocomplete'=>"off"]) ?>
                </div>
            </div>
        </div><!-- 2nd Box Info Closed Here -->
            <?php
        }
                    
    ?>
    </div><!-- create element div closed here -->
    </div><!-- 2nd Panel closed here -->
        <div  class="row">
        <div  class="col-xs-12">
            <div class="col-xs-12 col-sm-3 col-lg-3"> <br />
                <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                    <?= Html::submitButton($model->isNewRecord ? 'Finish' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    <?= Html::a("Reset", Url::toRoute(['student-category-details/create']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>
            </div>
        </div>
    </div>  
    <?php ActiveForm::end(); ?>
</div><!-- box-group According Closed Her -->
</div><!-- box-body Closed Here -->
</div><!-- box box-primary Closed Here -->
</div> <!-- student_update_edit_page Closed Here -->