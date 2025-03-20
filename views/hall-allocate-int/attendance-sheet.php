<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
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

        <div class="col-xs-12 col-lg-2 col-sm-2">
             <?php echo $form->field($exam, 'internal_number')->widget(
            Select2::classname(), [
            'data' =>ConfigUtilities::internalNumbers(),
            'options' => [
                'placeholder' => '-----Select Internal Number ----',
            ],
            'options' => [
                'placeholder' => '-----Select----',
                'class'=>'form-control',
                'id' => 'internal_number',
            ],
            ]); 
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
                        <td>
                            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                       </td>    
                        <td colspan=6 align="center">
                           <center><b><font size="4px">'.$org_name.'</font></b></center>
                           <center>'.$org_address.'</center>
                           <center>'.$org_tagline.'</center> 
                        </td>
                        <td align="center">  
                            <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                         </td>                     
                        </tr>
                       
                        <tr>
                          <td colspan=8 align="center"> <h4>
                              INTERNAL EXAMINATIONS - '.$cia.'</h4>
                         </td>                          
                        </tr>
                        <tr>
                          <td colspan=8  align="center"> 
                             <center> DATE OF EXAMINATION : '.date('d/m/Y',strtotime($value['exam_date']))." - ".$value['exam_session'].' </center>
                             <center>FN: (10:30 AM TO 12:00 PM) AN: (02:30 PM TO 04:00 PM)</center>
                         </td>                          
                        </tr>
                        <tr>
                         <td colspan=8 align="center"> <h3><b>
                              EXAMINATION HALL : '.$value['hall_name'].' </b></h3>
                         </td>                          
                        </tr>
                        
                        <tr>
                         <td width="50px" align="left"> S.NO </td>
                         <td width="150px" align="left"> REGISTER NUMBER </td> 
                         <td width="150px" align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' NAME </td> 
                         <td width="100px" align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </td> 
                         <td width="220px" colspan=3 align="left"> ANSWER BOOK NO </td> 
                         <td width="100px" align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).'\'S SIGNATURE </td>  
                        </tr>
                        ';
                        $body .='
                        <tr>
                            <td width="50px" align="left"> '.$value['seat_no'].'</td>
                            <td width="155px" align="left" > '.strtoupper($value['register_number']).'</td>
                            <td width="180px" align="left"> '.strtoupper($value['name']).'</td>
                            <td width="100px" align="left"> '.strtoupper($value['subject_code']).'</td>
                            <td width="220px" align="left" colspan=3> 
                                <table width=100%>
                                    <tr>
                                        <td width="10px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        <td width="15px" style="border: 1px solid #999;">&nbsp;</td>
                                        
                                    </tr>
                                </table>
                            </td>
                            <td width="100px" align="left"> &nbsp; </td>
                        </tr>
                        ';
                        $footer .='
                        <tr>
                            <td height="30px" colspan=8> * Mark AB in RED INK if the candidate is ABSENT
                            </td>
                            
                        </tr>
                        <tr>
                            <td height="30px" colspan=3> PRESENT : </td>
                            <td> &nbsp; </td>
                            <td height="30px" colspan=3> ABSENT : </td>
                            <td> &nbsp; </td>
                        </tr>
                        <tr><td colspan=8 height="80px">&nbsp;</td></tr>
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
                            <td width="50px" align="left"> '.$value['seat_no'].'</td>
                            <td width="155px" align="left" > '.$value['register_number'].'</td>
                            <td width="150px" align="left"> '.$value['name'].'</td>
                            <td width="155px" align="left"> '.$value['subject_code'].'</td>
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
                            <td width="100px" align="left"> &nbsp; </td>
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

                echo '<div class="box box-primary"><div class="box-body"><div class="row" >
                <div class="col-xs-12" ><div class="col-lg-2" style="float:right;"> '.$print_excel." ".$print_pdf.' </div></div>
                <div class="col-lg-12" >'.$print_stu_data.'</div></div></div></div></div>
                </div>';
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('error',"Not Found the Organisation Information" );
            }
        

        }

    ?>




