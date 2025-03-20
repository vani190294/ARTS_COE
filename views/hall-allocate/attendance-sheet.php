<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categories;
use app\models\Categorytype;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
echo Dialog::widget();
$this->title = 'Attendance Sheet';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  


<div class="row" id="hide_batch_section">
    <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-2 col-lg-2">
                <?php echo $form->field($model,'seat_arrangement')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-xs-12 col-sm-2 col-lg-2">
                <?php echo $form->field($model, 'subject_code')->widget(
                    Select2::classname(), [
                    'data'=>ConfigUtilities::getDegreedetails(),

                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-lg-2">
                <?php echo $form->field($model, 'student_count')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getSectionnames(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id'=>'stu_section_select',
                            'name'=>'sec',
                            'class'=>'form-control',                                    
                        ],
                                                             
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION)); 
                ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($exam,'subject_code')->widget(
                Select2::classname(), [
                    'data' => [1,2,3,4,5,6,7,8],
                    'options' => [
                        'placeholder' => '-----Select SEMESTER ----',
                        'id' => 'semester',
                        'name' => 'semester',
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

    </div>
<div class="row">
        <div class="col-xs-12 col-sm-12 col-lg-12">  

        <div class="col-lg-2 col-sm-2">  <br />
        <?php $exam->coe_batch_id = 'yes'; ?> 
                <?= $form->field($exam, 'coe_batch_id')->checkbox(array(
                    'label'=>'',
                    'labelOptions'=>array('style'=>'padding:5px;'),                    
                    ))
                    ->label('Practical'); ?>
                
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y')]) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $model->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',                               
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($exam, 'exam_date')->widget(
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
            <?= $form->field($exam, 'exam_session')->widget(
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
            <?php echo $form->field($exam,'subject_code')->widget(
                Select2::classname(), [
                    'data' => $exam->ExamType,
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE).' ----',
                        'id' => 'exam_type',
                        'name' => 'exam_type',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM_TYPE)); 
            ?>
            </div>
        
        

</div>

</div>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= Html::submitButton($model->isNewRecord ? 'Show' : 'Update', ['onClick'=>"spinner();",'class' => $model->isNewRecord ? 'btn btn-group-lg btn-group btn-success' : 'btn  btn-group-lg btn-group btn-primary']) ?>
            <?= Html::a("Reset", Url::toRoute(['hall-allocate/attendance-sheet']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

<?php 

        if(isset($get_data) && !empty($get_data))
        {
            if($print_for==='Practical')
            {
                require('attendance-sheet-practical.php'); 
            }
            else
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
                foreach ($get_data as $value) 
                {
                    
                    $exam_type_for = Categorytype::findOne($_POST['exam_type']);
                    if($prev_hall_name!=$value['hall_name'])
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

                        $header .="<table style='overflow:wrap;' class='table table-bordered table-responsive table-hover'>";
                        $header .= '<tr>
                        <td colspan=10>
                            <center><h3>'.strtoupper($org_name).'</h3></center>
                              
                         </td>                          
                        </tr>
                        <tr>
                          <td colspan=10 align="center"> 
                              
                              <center><h5>'.strtoupper($org_address).'<br /> '.strtoupper($org_tagline).'</h5></center>
                         </td>                          
                        </tr>
                        
                        <tr>
                           <td colspan=10 align="center"> <h5>
                              ATTENDANCE SHEET FOR END SEMESTER  '.strtoupper($exam_type_for->description).' EXAMINATIONS : '.strtoupper($value['month'])." - ".$value['year'].' </h5>
                         </td>                      
                        </tr>
                        <tr>
                          <td colspan=10 align="center"> <h5>
                              DATE OF EXAMINATION & SESSION : '.date('d/m/Y',strtotime($value['exam_date']))." - ".$value['exam_session'].' </h5>
                         </td>                          
                        </tr>
                        <tr>
                         <td colspan=10 align="center"> <h4><b>
                               HALL NO : '.$value['hall_name'].' </b></h4>
                         </td>                          
                        </tr>
                        
                        <tr>
                         <td width="50px" align="left"> S.NO </td>
                         <td width="120px" align="left"> REGISTER NUMBER </td> 
                         <td width="120px" align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' NAME </td> 
                         <td width="90px" align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </td> 
                         <td width="100px" align="left"> SEMESTER </td>
                         <td width="190px" colspan=3 align="left"> ANSWER BOOK NO </td> 
                         <td width="90px" align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).'\'S SIGNATURE </td>  
                        </tr>
                        ';
                        $body .='
                        <tr>
                             <td width="50px" align="left"> '.$value['seat_no'].'</td>
                            <td width="105px" align="left" > '.$value['register_number'].'</td>
                            <td width="100px" align="left"> '.$value['name'].'</td>
                            <td width="100px" align="left"> '.$value['subject_code'].'</td>
                            <td width="65px" align="center"> '.strtoupper($value['semester']).'</td>
                            <td width="200px" align="left" colspan=3> 
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
                            <td width="80px" align="left"> &nbsp; </td>
                        </tr>
                        ';
                        $footer .='
                        <tr>
                            <td height="20px" colspan=10> * Mark AB in RED INK if the candidate is ABSENT
                            </td>
                            
                        </tr>
                        <tr>
                            <td height="20px" colspan=4> PRESENT : </td>
                            <td> &nbsp; </td>
                            <td height="20px" colspan=3> ABSENT : </td>
                            <td> &nbsp; </td>
                        </tr>
                        <tr><td colspan=10 height="80px">&nbsp;</td></tr>
                        <tr>
                            <td align="left" colspan=5> Name & Signature of Hall Superintendent With Date </td>
                            
                            <td align="left" colspan=5> Signature of the Chief Superintendent / Controller of Examinations with Date.  </td>
                            
                        </tr>
                        
                        </table>
                        <pagebreak />';

                    }
                    else
                    {
                    $body .='
                        <tr>
                             <td width="50px" align="left"> '.$value['seat_no'].'</td>
                            <td width="105px" align="left" > '.$value['register_number'].'</td>
                            <td width="100px" align="left"> '.$value['name'].'</td>
                            <td width="100px" align="left"> '.$value['subject_code'].'</td>
                            <td width="65px" align="center"> '.strtoupper($value['semester']).'</td>
                            <td width="200px" align="left" colspan=3> 
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
                            <td width="80px" align="left"> &nbsp; </td>
                        </tr>
                        ';
                        
                    } // Else Not the same hall name
                    $prev_hall_name=$value['hall_name'];
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

        }

    ?>




