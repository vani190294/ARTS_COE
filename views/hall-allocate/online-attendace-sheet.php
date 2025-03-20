<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Student;
use yii\web\JsExpression;

echo Dialog::widget();
$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(), 'register_number', 'register_number');
$register_numbers = empty($student->register_number) ? $numbers : Student::findOne($student->register_number);
$from_reg_no = isset($_POST['from_reg']) ? $_POST['from_reg'] : '';
$to_reg_no = isset($_POST['to_reg']) ? $_POST['to_reg'] : '';
$this->title = 'Online Attendance Sheet';
?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>

<div class="hall-allocate-form">
<div class="box box-success">
<div class="box-body"> 

    <?php $form = ActiveForm::begin(); ?>
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>  


<div class="row">
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
               <?php
                        echo $form->field($student, 'register_number_from')->widget(Select2::classname(), [
                            'initValueText' => $register_numbers, // set the initial display text
                            'options' => ['placeholder' => 'Search for a register_number ...', 'name' => 'from_reg', 'value' => $from_reg_no],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:"All"}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                        ]);
                        
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
             <?php
                        echo $form->field($student, 'register_number_to')->widget(Select2::classname(), [
                            'initValueText' => $register_numbers, // set the initial display text
                            'options' => ['placeholder' => 'Search for a register_number ...', 'name' => 'to_reg', 'id' => 'to_reg', 'value' => $to_reg_no],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:"All"}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                        ]);
                        ?>
            </div>
            
        </div>

    </div>
<div class="row">
        <div class="col-xs-12 col-sm-12 col-lg-12">  

        
        

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
                $stu_count_break = 1;

                $i=0;
                $sn_no = 1;$body='';
                foreach ($get_data as $value) 
                {
                    
                    if($stu_count_break%26==0 || $stu_count_break==1)
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
                        <td colspan=9>
                            <center><h3>'.strtoupper($org_name).'</h3></center>
                              
                         </td>                          
                        </tr>
                        <tr>
                          <td colspan=9 align="center"> 
                              
                              <center><h4>'.strtoupper($org_address).'<br /> '.strtoupper($org_tagline).'</h4></center>
                         </td>                          
                        </tr>
                        
                        <tr>
                          <td colspan=9 align="center"> <h4>
                              ATTENDANCE SHEET FOR SEMESTER EXAMINATIONS : '.strtoupper($value['month'])." - ".$value['year'].' </h4>
                         </td>                          
                        </tr>
                        <tr>
                          <td colspan=9 align="center"> <h4>
                              DATE OF EXAMINATION : '.date('d/m/Y',strtotime($value['exam_date']))." - ".$value['exam_session'].' </h4>
                         </td>                          
                        </tr>
                        <tr>
                         <td colspan=9 align="left"> <h3><b>
                              STAFF NAME :  </b></h3>
                         </td>                          
                        </tr>
                        
                        <tr>
                         <td width="25px" align="left"> S.NO </td>
                         <td width="120px" align="center"> REGISTER NUMBER </td> 
                         <td width="120px" align="center"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' NAME </td> 
                         <td width="70px" align="center"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </td> 
                         <td width="72px" align="center"> Script Uploaded Yes/No</td> 
                           
                            <td width="72px" align="center"> Present/Absent </td>
                            <td width="87px" align="center"> Remarks </td>  
                        </tr>
                        ';
                        $body .='
                        <tr>
                            <td width="20px" align="center"> '.$sn_no.'</td>
                            <td width="125px" align="center" > '.strtoupper($value['register_number']).'</td>
                            <td width="150px" align="left"> '.strtoupper($value['name']).'</td>
                            <td width="70px" align="center"> '.strtoupper($value['subject_code']).'</td>
                            
                            <td width="70px" align="left"> &nbsp; </td>
                            <td width="70px" align="left"> &nbsp; </td>
                            <td width="70px" align="left"> &nbsp; </td>
                           
                        </tr>
                        ';
                        $sn_no++;
                        $footer .='
                        <tr>
                            <td height="10px" colspan=9> * Mark AB in RED INK if the candidate is ABSENT
                            </td>
                            
                        </tr>
                        <tr>
                            <td height="10px" colspan=3> PRESENT : </td>
                            <td> &nbsp; </td>
                            <td height="10px" colspan=3> ABSENT : </td>
                            <td> &nbsp; </td>
                        </tr>
                        <tr><td colspan=9 height="50px">&nbsp;</td></tr>
                        <tr>
                            <td align="left" colspan=4> Name & Signature of Hall Superintendent With Date </td>
                            
                            <td align="left" colspan=4> Signature of the Chief Superintendent / Controller of Examinations with Date.  </td>
                            
                        </tr>
                        
                        </table>
                        <pagebreak />';

                    }
                    else
                    {
                        $body .='
                        <tr>
                            <td width="50px" align="center"> '.$sn_no.'</td>
                            <td width="155px" align="center" > '.$value['register_number'].'</td>
                            <td width="150px" align="left"> '.$value['name'].'</td>
                            <td width="155px" align="center"> '.$value['subject_code'].'</td>
                            
                             <td width="70px" align="left"> &nbsp; </td>
                            <td width="70px" align="left"> &nbsp; </td>
                           <td width="70px" align="left"> &nbsp; </td>

                        </tr>
                        ';
                        $sn_no++;
                        
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

        }

    ?>




