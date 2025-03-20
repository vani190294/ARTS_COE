<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\dialog\Dialog;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\widgets\TypeaheadBasic;
use app\models\Guardian;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
$formatter = \Yii::$app->formatter;
echo Dialog::widget();
$this->registerJsFile(
    '@web/js/student_validation.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
if(isset($model->dob))
{
    $this->registerJs("$(document).ready(function() { $('.student_disable').attr('disabled',true)});");
}
/* @var $this yii\web\View */
/* @var $model app\models\Student */
/* @var $form yii\widgets\ActiveForm */
$batch_id = isset($stuMapping->course_batch_mapping_id) && $stuMapping->course_batch_mapping_id!=""?$stuMapping->courseBatchMapping->coeBatch->coe_batch_id:"";
$degree_batch_mapping_id = isset($stuMapping->course_batch_mapping_id) && $stuMapping->course_batch_mapping_id!=""?$stuMapping->course_batch_mapping_id:"";
$section_name = isset($stuMapping->section_name) && $stuMapping->section_name!=""?$stuMapping->section_name:"";
$admission_status = isset($model->admission_status) && $model->admission_status!=""?$model->admission_status:"";
$default_date = date('m/d/Y',strtotime(date("Y-m-d", mktime()) . " - 16 years"));

$dob = isset($model->dob) && $model->dob!="" ? date('m/d/Y',strtotime($model->dob)) :$default_date;
$admission_date = isset($model->admission_date) && $model->admission_date!="" ?$formatter->asDate($model->admission_date):date('m/d/Y');
$admission_category_type_id = isset($stuMapping->admission_category_type_id) && $stuMapping->admission_category_type_id!=""?$stuMapping->admission_category_type_id:"";
?>
<div id="student_update_edit_page" class="configuration-form">
    <div class="box box-primary">
        <div class="box-body">          
            <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
            <div class="student-form">
                <div class="nav-tabs-custom" >
                    <?php 
                        $condition = $model->isNewRecord?true:false;
                        $form = ActiveForm::begin([
                            'enableClientValidation' => true, 
                            'enableAjaxValidation' => $condition,
                            'id' => 'student_form_required_page',
                            'fieldConfig' => [
                                'template' => "{label}{input}{error}",
                            ],
                        ]); ?>
                        <ul id="tabs" class="nav nav-tabs">
                          <li class="active"><a href="#tab_1" data-toggle="tab">Personal</a></li>
                          <li><a href="#tab_4" data-toggle="tab">Academic</a></li>
                          <li><a href="#tab_2" data-toggle="tab">Parent / Guardian</a></li>
                          <li><a href="#tab_3" data-toggle="tab">Address</a></li>
                        </ul>
                    <div class="tab-content">
                    <div id="tab_1" class="tab-pane box box-solid box-success active row">
                         <div class="box-header  with-border">
                            <?php 
                            if($model->isNewRecord) { ?>
                            <strong><?= Html::a("Reset", Url::toRoute(['student/create']), ['onClick'=>"spinner();",'class' => 'pull-right']) ?> </strong>
                          <a href="#" style="margin-right: 1%;" class="pull-right" data-toggle="tab" aria-expanded="true">Admission Number <strong><?php echo $admission_count; ?></strong></s></a>
                          <?php } ?>
                         <h4 class="box-title"><i class="fa fa-user-circle"></i> <?php echo 'Personal Details'; ?>                           
                         </h4>
                      </div>
                    <div  class="col-xs-12">                      
                        <br />
                        <div class="col-xs-12 col-sm-3 col-lg-3">                           
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder'=>'Name of the '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)]) ?>
                        </div>                  
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                                    <?= $form->field($model, 'aadhar_number')->textInput(['maxlength' => true,'placeholder'=>'12 Digits Aadhar Number','autocomplete'=>"none"]) ?>
                            </div>                  
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                                <?= $form->field($model, 'gender')->radioList([  'M' => 'Male', 'F' => 'Female'],['itemOptions' => ['class' =>'flat-red']]); ?>
                        </div>                  
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <?php 
                                    echo '<label for="student-dob" class="required">Date of Birth</label>';
                                    echo DatePicker::widget([
                                        'name' => 'dob',
                                        'value' => $dob,   
                                        'type' => DatePicker::TYPE_INPUT,
                                        'options' => [                                            
                                            'placeholder' => '-- Select Date of Birth ...',
                                            'id'=>'stu_dob',
                                            'required'=>'required',                                            
                                            'onChange' => "isDate(this.id); ", 
                                            'class'=>'form-control',
                                        ],
                                         'pluginOptions' => [
                                            'autoclose'=>true,
                                            'rangeSelect'=> true,
                                        ],
                                    ]);
                                 ?>
                        </div> 
                    </div>
                    <div  class="col-xs-12">                 
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <?= $form->field($model, 'religion')->textInput(['maxlength' => true,'placeholder'=>'Like Hindu, Muslim.. ',]) ?>
                        </div>                  
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <?= $form->field($model, 'nationality')->textInput(['maxlength' => true,'placeholder'=>'Like Indian, US Citizen..',]) ?>
                        </div>                  
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <?= $form->field($model, 'caste')->textInput(['maxlength' => true,'placeholder'=>'Like BC,OC..',]) ?>
                        </div>                  
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <?= $form->field($model, 'sub_caste')->textInput(['maxlength' => true,'placeholder'=>'Full Sub Caste Name',]) ?>
                        </div> 
                    </div> 
                    <div class="col-xs-12">               
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <?= $form->field($model, 'bloodgroup')->textInput(['maxlength' => true,'placeholder' => 'Bloodgroup like A+,AB+..',]) ?>
                            </div>                  
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <?= $form->field($model, 'email_id')->textInput(['maxlength' => true,'placeholder' => 'Valid '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Email Address','class'=>'form-control student_disable',]) ?>
                            </div> 
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                    <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Valid 10 Digits mobile numbers',]) ?>
                            </div>   
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                             <?php echo $form->field($stuMapping, 'admission_category_type_id')->widget(
                                Select2::classname(), [
                                'data' => $model->AdmissionStatus,
                                'options' => [
                                    'placeholder' => '-----Select----',
                                    'class'=>'form-control',
                                    'onChange'=>'',
                                    'value' => $admission_category_type_id,
                                ],
                                ]); 
                            ?>
                        </div>                 
                    </div>  
                    <div class="col-xs-12">
                        <div style="margin-bottom: 20px;" class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="row">
                                <div class="no-padding col-xs-12">
                                    <div style="margin-bottom: 20px;" class="col-xs-12 col-sm-3 col-lg-3">
                                <a href="#tab_4" class='btn btn-block btn-primary' data-toggle="tab" aria-expanded="true">Next</a>
                                    </div>
                                     <?php if(!$model->isNewRecord)
                                    {
                                        ?>
                                        <div style="margin-bottom: 20px;" class="col-xs-12 col-sm-3 col-lg-3">
                                            <?= Html::submitButton( 'Update', ['onClick'=>"spinner();",'class' => 'btn btn-block  btn-success']) ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                        </div>
                        <div id="tab_4" class="tab-pane box box-solid box-success row">
                        <div class="box-header  with-border">
                            <?php 
                            if($model->isNewRecord) { ?>
                            <strong><?= Html::a("Reset", Url::toRoute(['student/create']), ['onClick'=>"spinner();",'class' => 'pull-right']) ?> </strong>
                          <a href="#" style="margin-right: 1%;" class="pull-right" data-toggle="tab" aria-expanded="true">Admission Number <strong><?php echo $admission_count; ?></strong></s></a>
                          <?php } ?> 
                         <h4 class="box-title"><i class="fa fa-id-card"></i> <?php echo 'Academic Details'; ?></h4>
                      </div>
                    <div class="col-xs-12">     
                    <br />      
                        <div class="col-lg-3 col-sm-3">
                            <?php echo $form->field($model, 'stu_batch_id')->widget(
                                Select2::classname(), [
                                'data' => ConfigUtilities::getBatchDetails(),
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_batch_id_selected', 
                                    'value'=> $batch_id,
                                    'class'=>'form-control student_disable',                              
                                ],
                            ]); 
                        ?>
                        </div>     
                        <div class="col-lg-3 col-sm-3">
                            <?php echo $form->field($model, 'stu_programme_id')->widget(
                                Select2::classname(), [
                                    'data'=>ConfigUtilities::getDegreedetails(),                                    
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_programme_selected',
                                    'value'=>$degree_batch_mapping_id,
                                    'class'=>'form-control student_disable',                                   
                                ],
                            ]); 
                        if($degree_batch_mapping_id!="" && $batch_id!="" && $section_name!=""){
                            ?>
                            <input type="hidden" name="stu_programme_id" value="<?php echo $degree_batch_mapping_id; ?>">
                            <input type="hidden" name="stu_batch_id" value="<?php echo $batch_id; ?>">
                            <input type="hidden" name="stu_section_name" value="<?php echo $section_name; ?>">                           
                            <?php
                        }
                        ?>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?php echo $form->field($model, 'stu_section_name')->widget(
                                Select2::classname(), [
                                    'data'=>ConfigUtilities::getSectionnames(),                                    
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id'=>'stu_section_select',
                                    'value'=>$section_name,
                                    'onChange'=>'',
                                    'class'=>'form-control',                                    
                                ],
                            ]); 
                        ?>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <?= $form->field($model, 'register_number')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Unique '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Registration Number','class'=>'form-control student_disable',]) ?>
                        </div>
                    </div>
                    <div class="col-xs-12">   
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                                <?= $form->field($model, 'admission_year')->textInput(['maxlength' => true,'value'=>date("Y"),'placeholder' => 'Year of the admission','class'=>'form-control student_disable',]) ?>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                                <?php 
                                    echo '<label>Admission Date</label>';
                                    echo DatePicker::widget([
                                        'name' => 'admission_date',
                                        'value'=>$admission_date,
                                        'disabled'=>'disabled',
                                        'type' => DatePicker::TYPE_INPUT,
                                        'options' => ['placeholder' => 'Select Admission Date ...',],
                                         'pluginOptions' => [
                                            'autoclose'=>true,
                                        ],
                                    ]);
                                 ?>
                            </div>  
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                             <?php echo $form->field($model, 'admission_status')->widget(
                                Select2::classname(), [
                                'data' => $model->StudentStatus,                                
                                'options' => [
                                    'placeholder' => '-----Select Status----',
                                    'id' => 'stu_status_select',                                    
                                    'value' => $admission_status,
                                    'onChange'=>'addFields(this.id);',
                                    'class'=>'form-control', 
                                ],
                                ]); 
                            ?>
                        </div> 
                        <?php 
                        $add_class = isset($stuMapping->previous_reg_number) && $stuMapping->previous_reg_number!='' ?'':'detain_status';
                        ?>
                        <div class="col-xs-12 col-sm-3 col-lg-3 <?php echo $add_class; ?>">
                             <?php echo $form->field($stuMapping, 'previous_reg_number')->textInput(['class'=>'form-control']); 
                            ?>
                        </div>
                         
                    </div>
                   
                    <div class="col-xs-12">
                        <div style="margin-bottom: 20px;" class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="row">
                                <div class="no-padding col-xs-12">
                                    <div class="col-xs-12 col-sm-3 col-lg-3">
                                        <a href="#tab_1" class='btn btn-block btn-warning' data-toggle="tab" aria-expanded="true">Back</a>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-lg-3">
                                        <a href="#tab_2" class='btn btn-block btn-primary' data-toggle="tab" aria-expanded="true">Next</a>
                                    </div>
                                    <?php if(!$model->isNewRecord)
                                    {
                                        ?>
                                        <div style="margin-bottom: 20px;" class="col-xs-12 col-sm-3 col-lg-3">
                                            <?= Html::submitButton( 'Update', ['onClick'=>"spinner();",'class' => 'btn btn-block  btn-success']) ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
                        <div id="tab_2"  class="tab-pane box box-solid box-success row">
                            <div class="box-header  with-border">
                                <?php 
                            if($model->isNewRecord) { ?>
                          <strong><?= Html::a("Reset", Url::toRoute(['student/create']), ['class' => 'pull-right']) ?> </strong>
                          <a href="#" style="margin-right: 1%;" class="pull-right" data-toggle="tab" aria-expanded="true">Admission Number <strong><?php echo $admission_count; ?></strong></s></a>
                          <?php } ?>
                         <h4 class="box-title"><i class="fa fa-pencil-square"></i> 
                            <?php echo 'Parent / Guardian Information'; ?></h4>
                      </div>
                        <div class="col-xs-12">      
                        <br />          
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_name')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Name of the guardian']) ?>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_relation')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Guardian Relation with '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)]) ?>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_mobile_no')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => '10 Digits Moblile number']) ?>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_address')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Address in Details']) ?>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_income')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Income in numbers']) ?>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_email')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Valid Parent E-Mail Address']) ?>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_occupation')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Occupation in Details']) ?>
                            </div> 
                            <?php 
                            $hide_guardian_add = 'display: block; visibility: visible';
                            $add_id = 'add_guardian_hide_default';
                            if(isset($model->coe_student_id) && $model->coe_student_id!='')
                            {
                                $check_guardian = Guardian::find()->where(['stu_guardian_id'=>$model->coe_student_id])->all();
                                if(count($check_guardian)>1)
                                {
                                    $add_id = 'id_not_required';
                                    $hide_guardian_add = 'display: none; visibility: hidden';
                                    $get_multi_info = 'SELECT * FROM coe_stu_guardian WHERE stu_guardian_id="'.$model->coe_student_id.'" AND guardian_relation!="'.$guardian->guardian_relation.'"';
                                    $find_records = Yii::$app->db->createCommand($get_multi_info)->queryAll();
                                    foreach ($find_records as $key => $value) 
                                    {                                        
                                        $guardian->guardian_name_1=$value['guardian_name'];
                                        $guardian->guardian_relation_1=$value['guardian_relation'];
                                        $guardian->guardian_mobile_no_1=$value['guardian_mobile_no'];
                                    }
                                }
                            }
                        ?>
                            <div style="<?php echo $hide_guardian_add; ?>" class="col-xs-12 col-sm-2 col-lg-2 clearguardian">
                                <br />
                                <a href="#tab_2" id="add_guardian" class='btn btn-block btn-warning' data-toggle="tab" aria-expanded="true">Add Guardian</a>
                            </div>
                            <div style="<?php echo $hide_guardian_add; ?>"  class="col-xs-12 col-sm-1 col-lg-1"> 
                                <br />                             
                                <a href="#tab_2" id="reset_guardian" class='btn btn-block btn-primary' data-toggle="tab" aria-expanded="true">Reset</a>
                            </div>
                        </div>
                        <div id="<?php echo $add_id; ?>" class="col-xs-12">      
                        <br />          
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_name_1')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Name of the guardian']) ?>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_relation_1')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => 'Guardian Relation with '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)]) ?>
                            </div>
                            <div class="col-lg-3 col-sm-3">
                                <?= $form->field($guardian, 'guardian_mobile_no_1')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => '10 Digits Moblile number']) ?>
                            </div>
                        </div>
                        <div class="col-xs-12">
                        <div style="margin-bottom: 20px;" class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="row">
                                <div class="no-padding col-xs-12">
                                    <div class="col-xs-12 col-sm-3 col-lg-3">
                                        <a href="#tab_4" class='btn btn-block btn-warning' data-toggle="tab" aria-expanded="true">Back</a>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-lg-3">
                                        <a href="#tab_3" class='btn btn-block btn-primary' data-toggle="tab" aria-expanded="true">Next</a>
                                    </div>
                                    <?php if(!$model->isNewRecord)
                                    {
                                        ?>
                                        <div style="margin-bottom: 20px;" class="col-xs-12 col-sm-3 col-lg-3">
                                            <?= Html::submitButton( 'Update', ['onClick'=>"spinner();",'class' => 'btn btn-block  btn-success']) ?>                                            
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>  
                        </div>
                    </div>
                    </div>                   
                    <div id="tab_3" class="tab-pane box box-solid box-success row">
                        <div class="box-header  with-border">
                            <?php 
                            if($model->isNewRecord) { ?>
                          <strong><?= Html::a("Reset", Url::toRoute(['student/create']), ['onClick'=>"spinner();",'class' => 'pull-right']) ?> </strong>
                          <a href="#" style="margin-right: 1%;" class="pull-right" data-toggle="tab" aria-expanded="true">Admission Number <strong><?php echo $admission_count; ?></strong></s></a>
                          <?php } ?>
                         <h4 class="box-title"><i class="fa fa-address-book-o"></i> <?php echo 'Address Details'; ?></h4>
                      </div>
                    <div class="col-xs-12">     
                    <br />           
                        <div class="col-lg-3 col-sm-3">
                            <?php 
                            echo $form->field($stuAddress, 'current_country')->widget(TypeaheadBasic::classname(), [
                                'data' => $model->CountryList,
                                'options' => ['placeholder' => 'Search For country name..'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?php 
                                echo $form->field($stuAddress, 'current_state')->widget(TypeaheadBasic::classname(), [
                                'data' => $model->StatesList,
                                'options' => ['placeholder' => 'Search For State name..'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?php 
                                echo $form->field($stuAddress, 'current_city')->widget(TypeaheadBasic::classname(), [
                                'data' => $model->CityList,
                                'options' => ['placeholder' => 'Search For City name..'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?= $form->field($stuAddress, 'current_address')->textInput(['placeholder' => 'Address in Details']) ?>
                        </div>
                    </div>
                    <div class="col-xs-12">  
                        <div class="col-lg-3 col-sm-3">
                            <?= $form->field($stuAddress, 'current_pincode')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => '6 Digits Pincode']) ?>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?php 
                            echo $form->field($stuAddress, 'permanant_country')->widget(TypeaheadBasic::classname(), [
                                'data' => $model->CountryList,
                                'options' => ['placeholder' => 'Search For country name..'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?php 
                                echo $form->field($stuAddress, 'permanant_state')->widget(TypeaheadBasic::classname(), [
                                'data' => $model->StatesList,
                                'options' => ['placeholder' => 'Search For State name..'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?php 
                                echo $form->field($stuAddress, 'permanant_city')->widget(TypeaheadBasic::classname(), [
                                'data' => $model->CityList,
                                'options' => ['placeholder' => 'Search for City name..'],
                                'pluginOptions' => ['highlight'=>true],
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="col-xs-12">                
                        <div class="col-lg-3 col-sm-3">
                            <?= $form->field($stuAddress, 'permanant_address')->textInput(['placeholder' => 'Address in details']) ?>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?= $form->field($stuAddress, 'permanant_pincode')->textInput(['maxlength' => true,'autocomplete'=>"none",'placeholder' => '6 digits pincode']) ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div style="margin-bottom: 20px;" class="col-xs-12 col-sm-3 col-lg-3">
                            <div class="row">
                                <div class="no-padding col-xs-12">
                                    <div class="col-xs-12 col-sm-6 col-lg-6">
                                        <a href="#tab_2" class='btn btn-block btn-warning' data-toggle="tab" aria-expanded="true">Back</a>
                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-lg-6">
                                        <?= Html::submitButton($model->isNewRecord ? 'Finish' : 'Update', ['onClick'=>"spinner();",'id' => 'student_form_required_page_button', 'class' => $model->isNewRecord ? 'btn btn-block btn-success' : 'btn btn-block  btn-success']) ?>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div> <!-- Tab Content Ends  Here -->
            </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
