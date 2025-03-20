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
use kartik\date\DatePicker;
use kartik\time\TimePicker;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Attendance Sheet for Practical';
$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(), 'register_number', 'register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number);
$from_reg_no = isset($_POST['from_reg']) ? $_POST['from_reg'] : '';
$to_reg_no = isset($_POST['to_reg']) ? $_POST['to_reg'] : '';
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
$exam_date=isset($model->exam_date)?date('d-m-Y',strtotime($model->exam_date)):"";
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">

<?php if(empty($get_data))
        {?>
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'batch_mapping_id')->widget(
                    Select2::classname(), [
                    'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry, 'section')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getSectionnames(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id'=>'stu_section_select',
                            'class'=>'form-control student_disable',                                    
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
            </div> 
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'exam_year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'exam_month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'mark_type')->radioList(ExamTimetable::getExamType()); ?>
           
        </div>
            
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'semester')->textInput(['onblur'=>'getPracticalSubsOnly(this.value);',]) ?>
            </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php 
                echo '<label>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date </label>';
                echo DatePicker::widget([
                    'name' => 'exam_date',
                    'id'=>'prac_exam_date',  
                    'value' => $exam_date,   
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => [                        
                        'placeholder' => '-- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Date ...',
                        'autocomplete' => 'OFF',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                    ],
                                       
                ]);
            ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">

            <?php echo $form->field($model, 'exam_session')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getPracExamSessions(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'class'=>'form-control student_disable',  
                            'id'=>'exam_session',  
                           'onchange'=>'getPracticalSubsOnly1(this.value);',                                  
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
        </div>
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'unique_prac_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'unique_prac_id',                                   
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Practical Batch'); 
                ?>
            </div>
            <div class="col-lg-4 col-sm-4">
                    
                    <div class="btn-group col-lg-6 col-sm-6" role="group" aria-label="Actions to be Perform">
                    <br />
                    <?= Html::submitButton('Print', ['class' => 'btn btn-success' ,'formtarget'=>"_blank"]) ?>
                
                    <?= Html::a("Reset", Url::toRoute(['practical-exam-timetable-new/attendance-sheet-practical']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>                     
            </div>

            </div>
    
        
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

<?php }

        if(isset($get_data) && !empty($get_data))
        {

            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            /* 
            *   Already Defined Variables from the above included file
            *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
            *   use these variables for application
            *   use $file_content_available="Yes" for Content Status of the Organisation
            */
           
            if($file_content_available=="Yes")
            {
                $html = "";
                $header = "";
                $body ="";
                $footer = "";
                $prev_hall_name = "";
                $new_stu_flag=0;
                $print_stu_data ='';
                $get_page_count = count($get_data);
                $i=0;
                $searial_num = 0;
                $stu_count_break = 1;
                foreach ($get_data as $value) 
                {
                    $searial_num++;
                    if($stu_count_break%31==0 || $stu_count_break==1)
                    {
                        $new_stu_flag=$new_stu_flag + 1;
                        if($new_stu_flag > 1) 
                        {
                                //print_r($new_stu_flag);
                                $html = $header .$body.$footer; 
                                $print_stu_data .= $html;
                                $header = "";
                                $body ="";
                                $footer = "";
                                $new_stu_flag = 1;
                        }

                        $header .="<table width='100%' style='overflow:wrap;font-size:16px;border: 2px solid #999;' class='table table-bordered table-responsive table-hover'>";
                        $header.='<tr>
                                <td align="center">
                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=6 align="center"><h5> 
                                      <center><b><font size="5px">' . $org_name . '</font></b></center>
                                      <center> <font size="3px">' . $org_address . '</font></center>
                                      <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                                      </h5>
                                      <h5> PRACTICAL EXAMINATIONS ATTENDANCE SHEET - '.strtoupper($value['month']).' - '.$value['year'].' </h5>
                                 </td>
                                  <td align="center">  
                                    <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                  </td>
                             </tr>';
                        $header .= '
                        <tr>
                          <td colspan=2 align="left"> <h5>
                              DATE OF EXAMINATION : '.date('d/m/Y',strtotime($value['exam_date']))." <br />
                              EXAM SESSION : ".$value['exam_session'].' </h5>
                         </td>   
                         <td colspan=6 align="left"> <h5><b>
                              '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE :  '.$value['subject_code'].' </b></h5>
                              <h5><b>
                              '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME :  '.$value['subject_name'].'</b></h5>
                         </td>                       
                        </tr>
                       
                        <tr>
                         <td align="left"> S.NO </td>
                         <td align="left"> REGISTER NUMBER </td> 
                         <td width="150px" colspan=2 align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' NAME </td> 
                        
                         <td width="100px" colspan=4 align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).'\'S SIGNATURE </td>  
                        </tr>
                        ';
                        $body .='
                        <tr>
                            <td height="28px" align="left"> '.$searial_num.'</td>
                            <td height="28px" align="left" > '.strtoupper($value['register_number']).'</td>
                            <td width="150px" colspan=2 height="28px" align="left"> '.strtoupper($value['name']).'</td>
                            <td width="100px" height="28px" colspan=4  align="left"> &nbsp; </td>
                        </tr>
                        ';

                        $clg_code=$value["college_code"];

                        if(empty($clg_code)){$clg_code='SKCT';}

                        $ext_faculty2=Yii::$app->db->createCommand("SELECT concat(faculty_name,' - ',COALESCE(`college_code`,'SKCT')) FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['external_examiner2']."'")->queryScalar();
                                  $ext_faculty='';
                                  if(!empty($ext_faculty2))
                                  {
                                    $ext_faculty=$value["external_examiner_name"].' - '.$clg_code.' / '.$ext_faculty2;
                                  }
                                  else
                                  {
                                     $ext_faculty=$value["external_examiner_name"].' - '.$clg_code;
                                  }

                        $int_faculty2=Yii::$app->db->createCommand("SELECT faculty_name FROM coe_valuation_faculty WHERE coe_val_faculty_id='".$value['internal_examiner2']."'")->queryScalar();
                                  $int_faculty='';
                                  if(!empty($int_faculty2))
                                  {
                                    $int_faculty=$value["internal_examiner_name"].' / '.$int_faculty2;
                                  }
                                  else
                                  {
                                     $int_faculty=$value["internal_examiner_name"];
                                  }


                        $footer .='
                        <tr>
                            <td height="30px" colspan=8 style="text-transform: uppercase;" > * Mark AB in <b>RED INK</b> if the candidate is ABSENT
                            </td>
                        </tr>
                        <tr>
                            <td height="30px" colspan=3> PRESENT : </td>
                            <td> &nbsp; </td>
                            <td height="30px" colspan=3> ABSENT : </td>
                            <td> &nbsp; </td>
                        </tr>
                        
                        <tr>
                            <td align="left" colspan=2><br><br>'.$int_faculty.'<br>Internal Examiner </td>
                            <td align="right" colspan=6><br><br> '.$ext_faculty.' <br>External Examiner  </td>
                        </tr>
                        </table>
                        <pagebreak />';

                    }
                    else
                    {
                        $body .='
                        <tr>
                            <td height="28px" align="left"> '.$searial_num.'</td>
                            <td height="28px" align="left" > '.strtoupper($value['register_number']).'</td>
                            <td width="150px" colspan=2 height="28px" align="left"> '.strtoupper($value['name']).'</td>                           
                            <td width="100px" height="28px" colspan=4 align="left"> &nbsp; </td>
                        </tr>
                        ';
                        
                    } // Else Not the same hall name
                    
                    $stu_count_break = $stu_count_break+1;
                } // For each Ends Here 
                $footer = trim($footer,"<pagebreak>");
                $html = $header .$body.$footer;
                $print_stu_data .=$html;


                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/practical-exam-timetable-new/attendance-sheet-practical-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/practical-exam-timetable-new/excel-attendance-practical-sheet'], [
                    'class'=>'pull-right btn btn-block btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                if(isset($_SESSION['Exam_attendance_sheet_practical'])){ unset($_SESSION['Exam_attendance_sheet_practical']);}
                $_SESSION['Exam_attendance_sheet_practical'] = $print_stu_data;

                echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > '.$print_excel." ".$print_pdf.' </div><div class="col-lg-10" >'.$print_stu_data.'</div></div></div></div></div>';
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('error',"Not Found the Organisation Information" );
            }


        }

    ?>




