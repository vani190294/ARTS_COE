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
if(isset($degree_reports)) 
{ 
  require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
  /* 
  *   Already Defined Variables from the above included file
  *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
  *   use these variables for application
  *   use $file_content_available="Yes" for Content Status of the Organisation
  */
?>

<div class="col-xs-4 left-padding">
<?php echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('print-courseexamreports','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'btn btn-warning', 'style'=>'color:#fff')); 
      ?>
    <?php echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excel-courseexamreports','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff')); ?>

</div>
</br></br>

<?php
  $supported_extensions = ConfigUtilities::ValidFileExtension(); 
  $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
  $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";
  $previous_subject_code = "";
  $html = "";
  $header = "";
  $body ="";
  $footer = "";
  $new_stu_flag=0;
  $print_stu_data="";
  $totalStudents =0;
  foreach($degree_reports as $rows) 
  { 
      $app_month = $rows['category_type'];
      $year = $rows['year'];

     
if($previous_subject_code!=$rows['programme_name'])
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
        $totalStudents =0;
}   
$header .='
<table class="table" border="1" cellpadding="1" align="center" cellspacing="1">
   <tr>
      <td colspan="7">
        <table width="100%" align="center" border="0">
          <tr>
            <td align="center"> 
              <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/skct_logo.png" class="img-responsive" alt="College Logo">
            </td>
            <td colspan="5" align="center">
              <center><b><font size="6px">'.$org_name.'</font></b></center>
              <center> <font size="3px">'.$org_address.'</font></center>
              <center> Phone : <b>'.$org_phone.'</b></center>
              <center class="tag_line"><b>'.$org_tagline.'</b></center>
            </td>
            <td align="center">  
              <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
            </td>
          </tr>
        </table>
      </td>
  </tr>
  <tr>
    <td colspan="7" ALIGN="center"><b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).' WISE REPORT FOR - 
      '.$app_month." -".$year.'</b> 
    </td>
  </tr>

<tr>
  <td colspan="7"> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).' <b><span style="padding-left:10px">'.$rows['degree_code'].'</b><span style="padding-left:40px">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)).' <b><span style="padding-left:10px">'.$rows['programme_name'].'</span></b>  
  </td> 
  
</tr>
<tr>
  <td>YEAR </td> 
  <td>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Code").'</td>
  <td colspan=2>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Name").' </td>
  <td colspan=3>'.strtoupper("Total ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).'</td>
  
</tr>
';

$footer .='
</table><pagebreak />';


$space='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
  $body .="<tr>
    <td>".$rows['year']."</td>
    <td>".$rows['subject_code']."</td>
    <td colspan=2>".$rows['subject_name']."</td>
    <td colspan=3>".$rows['count']."</td>
 </tr>";
 $totalStudents+=$rows['count'];

} // If not the same Register_number 
else
{
  $body .="<tr>
    <td>".$rows['year']."</td>
    <td>".$rows['subject_code']."</td>
    <td colspan=2>".$rows['subject_name']."</td>
    <td colspan=3>".$rows['count']."</td>
 </tr>";
 $totalStudents+=$rows['count'];
}
$previous_subject_code=$rows['programme_name'];
      } // Foreach Ends Here 

      $body .="<tr><td height=40 colspan=7> &nbsp; </td></tr>
              <tr>
                <td colspan=4> ".strtoupper('Grand Total '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT))."</td>
                <td colspan=3>".$totalStudents."</td>
             </tr>";
      $html = $header .$body.$footer;
      $print_stu_data .=$html;
      if(isset($_SESSION['course_reports'])){ unset($_SESSION['course_reports']);}
      $_SESSION['course_reports'] = $print_stu_data;
      echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-1" >&nbsp;</div><div class="col-lg-9" >'.$print_stu_data.'</div><div class="col-lg-1" >&nbsp;</div></div></div></div></div>'; 

    } // Isset of Print Halls  
?>

