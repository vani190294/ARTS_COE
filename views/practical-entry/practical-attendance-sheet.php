<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\Categorytype;

echo Dialog::widget();

//$date_prac = isset($_POST['date_prac']) && !empty($_POST['date_prac'])?DATE('d-m-Y',strtotime($_POST['date_prac']) ):'';
//print_r($date_prac);exit;
  
 $year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');

 $batch_id = isset($_POST['bat_val']) ? $_POST['bat_val'] : '';
 $batch_map_id = isset($_POST['bat_map_val']) ? $_POST['bat_map_val'] : '';
 //$section_name = isset($model->section_name) && $model->section_name!=""?$model->section_name:"";
 //$section = isset($_POST['MarkEntry']['section'] != 'All' )? $_POST['MarkEntry']['section'] : '';
$mark_type = isset($_POST['mark_type']) ? $_POST['mark_type'] : '';
$semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$month = isset($_POST['month']) ? $_POST['month'] : '';
$section_name = isset($_POST['section']) ? $_POST['section'] : '';


 $month = Yii::$app->request->post('month');
 $month_1 = Yii::$app->db->createCommand("select description from coe_category_type where coe_category_type_id='" . $month . "'")->queryScalar();
//$month="OCT/NOV";
 //print_r($month_1);exit;

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Practical Attendance Sheet";
$this->params['breadcrumbs'][] = ['label' => 'Practical Proforma', 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                            'value' => $batch_id,

                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'stu_programme_id')->widget(
                    Select2::classname(), [
                    'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                            'value' => $batch_map_id,

                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>

    
                    <div class="col-lg-2 col-sm-2">
                            <?php echo $form->field($model, 'section')->widget(
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


             
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                           // 'class'=>'student_disable',
                            'name'=>'month',
                            'value'=>$month_1,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'mark_type')->widget(
                    Select2::classname(), [
                        'data' => ExamTimetable::getExamType(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Type ----',
                            'id' => 'exam_type',
                            'class'=>'student_disable',
                            'name'=>'mark_type',
                            'value'=>$mark_type,

                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>

             
          
         <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($exam,'subject_code')->widget(
                Select2::classname(), [
                    'data' => [1,2,3,4,5,6,7,8,9,10],
                    'options' => [
                        'placeholder' => '-----Select SEMESTER ----',
                        'id' => 'semester',
                        'name' => 'semester',
                        'value' => $semester,

                        'onChange'=>'getSubCodesPrac(this.value, $("#stu_programme_selected").val() )',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label('SEMESTER'); 
            ?>
            </div>
            <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($exam,'subject_code')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' ----',
                        'id' => 'subject_map_id',
                        'name' => 'subject_map_id',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)); 
            ?>
            </div>
          
        
           
           
        </div>

        
        
        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Show Me', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['practical-entry/practical-attendance-sheet']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>


<?php 

        if(isset($practicalattendance) && !empty($practicalattendance))
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
                $get_page_count = count($practicalattendance);
                $i=0;
                $searial_num = 1;
                $stu_count_break = 1;
                foreach ($practicalattendance as $value) 
                {
                    foreach($countOfSubjects  as $va)
                    {
                    if($va['count']<='45')
                    {
                        $total=$va['count'];

                    }
                    else
                    {

                           $total=round(($va['count']/2),0);

                    }
                    //print_r($total);exit;
                    
                    
                   // if($stu_count_break%32==1 || $stu_count_break==1)
                  if($stu_count_break%$total==1 || $stu_count_break==1)
                    {
                        $new_stu_flag=$new_stu_flag + 1;
                        if($new_stu_flag >1) 
                        {
                                //print_r($new_stu_flag);
                                $html = $header .$body.$footer; 
                                $print_stu_data .= $html;
                                $header = "";
                                $body ="";
                                $footer = "";
                                $new_stu_flag = 1;
                        }
                        $header .='<table width="100%" > <tr class ="alternative_border">
                                <td align="right"><b>
                                    CE 15(01)</b><br />
                                </td>
                                
                                </tr>
                                 </table>';
    
                        $header .="<table style='overflow:wrap;' class='table table-bordered table-responsive table-hover'>";
                        $header .= '<tr>
                        <td colspan=8>
                            <center><h4><b>'.strtoupper($org_name).'<br> COIMBATORE- 641008</b></br></h4></center>
                              
                         </td>                          
                        </tr>
                      
                        
                        <tr>
                          <td colspan=8 align="center"> <h5><b>
                              END SEMESTER PRACTICAL/PROJECT VIVA VOCE EXAMINATION - ATTENDANCE : '.strtoupper($month_1)." - ".$year.'  </b></h5>
                         </td>                          
                        </tr>
                        <tr>
                          <td colspan=4 align="left"> <b><h5>
                             DATE OF EXAMINATION & SESSION: <b> </h5>
                         </td>
                          <td colspan=4 align="left"> <h5><b>
                              COURSE CODE :  '.$value['subject_code'].' </b></h5>
                              <h5><b>
                             COURSE TITLE :  '.$value['subject_name'].'</b></h5>
                         </td>                          
                        </tr>
                        
                        
                        <tr>
                         <td width="20px" align="left"><b> S.NO </b></td>
                         <td width="120px" align="left"><b> REGISTER NUMBER</b> </td> 
                         <td width="120px" align="left"><b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' NAME</b> </td> 
                         
                         <td width="190px" colspan=3 align="CENTER"><b> ANSWER BOOK NO </b></td> 
                         <td width="90px" align="CENTER" colspan=3><b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).'\'S SIGNATURE </b></td>  
                        </tr>
                        ';
                          $searial_num= 1;
                        $body .='
                        <tr>
                            <td width="20px" align="left"> '.$searial_num.'</td>
                            <td width="125px" align="left" > '.strtoupper($value['register_number']).'</td>
                            <td width="150px" align="left"> '.strtoupper($value['name']).'</td>
                            
                            <td width="190px" align="left" colspan=3> 
                                <table width=70%>
                                    <tr>
                                        <td width="5px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="10px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="10px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="10px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="10px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="10px" style="border: 1px solid #999;">&nbsp;</td>
                                        
                                    </tr>
                                </table>
                            </td>
                            <td width="90px" align="left" colspan=3> &nbsp; </td>
                        </tr>
                        ';
                        $footer .='
                        <tr>
                            <td height="10px" colspan=8> * Mark AB in RED INK if the candidate is ABSENT
                            </td>
                            
                        </tr>
                        <tr>
                            <td height="10px" colspan=3> PRESENT : </td>
                            <td> &nbsp; </td>
                            <td height="10px" colspan=3> ABSENT : </td>
                            <td> &nbsp; </td>
                        </tr>
                        <tr><td colspan=8 height="90px">&nbsp;</td></tr>
                        <tr>
                            <td align="left" colspan=3> Name & Signature of Internal Examiner With Date </td>
                            
                            <td align="left" colspan=5> Name & Signature of External Examiner With Date  </td>
                            
                        </tr>
                       
                        
                        </table>
                        <pagebreak />';

                    }
                    else
                    {   
                        $searial_num++;
                        $body .='
                        <tr>
                            <td width="50px" align="left"> '.$searial_num.'</td>
                            <td width="155px" align="left" > '.$value['register_number'].'</td>
                            <td width="150px" align="left"> '.$value['name'].'</td>
                         
                            <td width="220px" align="left" colspan=3> 
                                <table width=100%>
                                    <tr>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>

                                        
                                    </tr>
                                </table>
                            </td>
                            <td width="100px" align="left" colspan=3> &nbsp; </td>
                        </tr>
                        ';
                      
                        }   
                    } // Else Not the same hall name
                    $stu_count_break = $stu_count_break+1;
                } // For each Ends Here 
                $footer = trim($footer,"<pagebreak>");
                $html = $header .$body.$footer;
                $print_stu_data .=$html;
                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/hall-allocate/attendance-sheet-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/hall-allocate/excel-attendance-sheet'], [
                    'class'=>'pull-right btn btn-block btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                if(isset($_SESSION['attendance_sheet'])){ unset($_SESSION['attendance_sheet']);}
                $_SESSION['attendance_sheet'] = $print_stu_data;

                echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > '.$print_excel." ".$print_pdf.' </div><div class="col-lg-10" >'.$print_stu_data.'</div></div></div></div></div>';
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('error',"Not Found the Organisation Information" );
            }
        }

        

    ?>


