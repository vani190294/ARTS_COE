<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
echo Dialog::widget();
?>

<?php 
if(isset($export_exam_time)) 
{ 
  require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
  /* 
  *   Already Defined Variables from the above included file
  *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
  *   use these variables for application
  *   use $file_content_available="Yes" for Content Status of the Organisation
  */
?>
<br /><br />
<div class="col-xs-4 left-padding">
<?php echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('print-examtimetable','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'btn btn-warning', 'style'=>'color:#fff')); 
      ?>
    <?php echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excel-examtimetable','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff')); ?>

</div>
</br></br>

<?php
  if($file_content_available=="Yes")
  {
  
  $previous_reg_number = "";
  $html = "";
  $header = "";
  $body ="";
  $footer = "";
  $new_stu_flag=0;
  $old_sub_code='';
  $print_stu_data="";
  foreach($export_exam_time as $rows) 
  { 

    if($previous_reg_number!=$rows['programme_code'] && $old_sub_code!=$rows['subject_code'])
    {
    $new_stu_flag=$new_stu_flag + 1;
    if($new_stu_flag > 1) {
            //print_r($new_stu_flag);
            $html = $header .$body.$footer; 
            $print_stu_data .= $html;
            $header = "";
            $body ="";
            $footer = "";
            $new_stu_flag = 1;
    }   
    $header .='
    <table width="100%" class="table" cellpadding="1" align="center" cellspacing="1">
       <tr>
        <td colspan="2" align="center"> 
          <img width=120 src="'.Yii::getAlias("@web").'/images/kcas_logo.jpg" class="img-responsive" alt="College Logo">
        </td>
        <td colspan="6" align="center">
          <center><h1>'.strtoupper($org_name).'</h1></center>
          <center class="tag_line"><h4>( '.strtoupper($org_tagline).' )</h4></center>

          <center class="tag_line"><h3>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)).' TIME TABLE FOR - '.strtoupper($rows['month'])." - ".$rows['exam_year'].'
          <br />
          FN:- 09 : 30 AM to  12 : 30 PM  AN:- 01 : 30 PM to  4 : 30 PM</h3></center>
        </td>          
      </tr>
      
    <tr>
      <td height="40px" colspan="2" align="center"> Regulation </br><b>'.$rows['regulation_year'].'</b> </td>
      <td height="40px" colspan="6" align="center"> BRANCH - <b>'.strtoupper($rows['degree_code']." . ".$rows['programme_name']).'</b></td>
    </tr>
    
    
    ';

    $footer .='
    <tr height="100px"  >
      <td align="left" style="height: 50px;"   height="100px" colspan="8"><br><b>
        Note: Discrepancy ,if any, may be brought to notice of the Controller Of Examinations</b>
        <br /><br /><br /><br /><br />
        
        
      </td>
    </tr>
    <tr height="100px"  >
      <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="8"><br>
        
        <div style="text-align: right; font-size: 17px; " class="pull-right"><b>'.strtoupper("Controller Of Examinations").'</b> </div>
      </td>
    </tr>
    </table><pagebreak></pagebreak>';

      $body .="<tr >
                <td height='40px'  align='center' ><b> SEM  </b></td>
                <td height='40px' align='center'> <b> ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM))." TYPE </b></td>
                <td height='40px' align='center'> <b> DATE </b></td>
                <td height='40px' align='center'> <b> SESSION </b></td>
                <td height='40px' align='center'> <b> ".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." CODE </b></td>
                <td height='40px' align='center' colspan=3> <b>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." NAME </b> </td>
                
              </tr>";
      $body .="<tr>
                <td height='20px' align='center'> ".$rows['semester']." </td>
                <td height='20px' align='center'> Regular / Arrear </td>
                <td height='20px' align='center'>".date("d-m-Y",strtotime($rows['exam_date']))."</td>
                <td height='20px' align='center'>".$rows['exam_session']."</td>
                <td height='20px' align='center'>".$rows['subject_code']."</td>
                <td height='20px' colspan=3>".ucwords(strtolower($rows['subject_name']))."</td>
              </tr>";
      $old_sub_code=$rows['subject_code'];

    } // If not the same Register_number 
    else
    {
      if($old_sub_code!=$rows['subject_code'])
      {
          $body .="<tr>
                <td height='20px' align='center'> ".$rows['semester']."  </td>
                <td height='20px' align='center'> Regular / Arrear </td>
                <td height='20px' align='center'>".date("d-m-Y",strtotime($rows['exam_date']))."</td>
                <td height='20px' align='center'>".$rows['exam_session']."</td>
                <td height='20px' align='center'>".$rows['subject_code']."</td>
                <td height='20px' colspan=3>".ucwords(strtolower($rows['subject_name']))."</td>
              </tr>";
          $old_sub_code=$rows['subject_code'];
      }
      
    }
        $previous_reg_number=$rows['programme_code'];
  } // Foreach Ends Here 
      
      $html = $header .$body.$footer;
      $print_stu_data .=$html;
      if(isset($_SESSION['export_exam_time_data'])){ unset($_SESSION['export_exam_time_data']);}
      $_SESSION['export_exam_time_data'] = $print_stu_data;
      echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-1" >&nbsp;</div><div class="col-lg-9" >'.$print_stu_data.'</div><div class="col-lg-1" >&nbsp;</div></div></div></div></div>'; 
      }// If Institute Information Exists in the file
    } // Isset of Print Halls  
?>

