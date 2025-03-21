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

                        $header .="<table style='overflow:wrap;font-size:16px;border: 2px solid #999;' class='table table-bordered table-responsive table-hover'>";
                        $header .= '<tr>
                        <td colspan=8>
                            <center><h3>'.strtoupper($org_name).'</h3></center>
                              
                         </td>                          
                        </tr>
                        <tr>
                          <td colspan=8 align="center">                               
                              <center><h4>'.strtoupper($org_address).'<br /> '.strtoupper($org_tagline).'</h4></center>
                         </td>                          
                        </tr>
                        <tr>
                          <td colspan=8 align="center"> <h4>
                              ATTENDANCE SHEET FOR SEMESTER EXAMINATIONS : '.$value['month']." - ".$value['year'].' </h4>
                         </td>                          
                        </tr>
                        <tr>
                          <td colspan=4 align="left"> <h4>
                              DATE OF EXAMINATION : '.date('d/m/Y',strtotime($value['exam_date']))." <br />
                              EXAM SESSION : ".$value['exam_session'].' </h4>
                         </td>   
                         <td colspan=4 align="left"> <h5><b>
                              '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE :  '.$value['subject_code'].' </b></h5>
                              <h5><b>
                              '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME :  '.$value['subject_name'].'</b></h5>
                         </td>                       
                        </tr>
                       
                        <tr>
                         <td align="left"> S.NO </td>
                         <td align="left"> REGISTER NUMBER </td> 
                         <td width="150px" colspan=2 align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' NAME </td> 
                         <td align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </td> 
                         <td width="100px" colspan=3 align="left"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).'\'S SIGNATURE </td>  
                        </tr>
                        ';
                        $body .='
                        <tr>
                            <td height="28px" align="left"> '.$searial_num.'</td>
                            <td height="28px" align="left" > '.strtoupper($value['register_number']).'</td>
                            <td width="150px" colspan=2 height="28px" align="left"> '.strtoupper($value['name']).'</td>
                            <td height="28px" align="left"> '.strtoupper($value['subject_code']).'</td>
                            <td width="100px" height="28px" colspan=3  align="left"> &nbsp; </td>
                        </tr>
                        ';
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
                        <tr><td colspan=8 height="60px">&nbsp;</td></tr>
                        <tr>
                            <td align="left" colspan=4> Internal Examiner With Date </td>
                            <td align="left" colspan=4> Signature of the External Examiner  </td>
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
                            <td height="28px" align="left"> '.strtoupper($value['subject_code']).'</td>
                            <td width="100px" height="28px" colspan=3 align="left"> &nbsp; </td>
                        </tr>
                        ';
                        
                    } // Else Not the same hall name
                    
                    $stu_count_break = $stu_count_break+1;
                } // For each Ends Here 
                $footer = trim($footer,"<pagebreak>");
                $html = $header .$body.$footer;
                $print_stu_data .=$html;
                $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/hall-allocate/attendance-sheet-practical-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/hall-allocate/excel-attendance-practical-sheet'], [
                    'class'=>'pull-right btn btn-block btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                if(isset($_SESSION['attendance_sheet_practical'])){ unset($_SESSION['attendance_sheet_practical']);}
                $_SESSION['attendance_sheet_practical'] = $print_stu_data;

                echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-2" > '.$print_excel." ".$print_pdf.' </div><div class="col-lg-10" >'.$print_stu_data.'</div></div></div></div></div>';
            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('error',"Not Found the Organisation Information" );
            }


        }

    ?>




