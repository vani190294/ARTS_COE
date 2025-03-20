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
if(isset($print_halls)) 
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
<?php echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('print-hall-tickets','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'btn btn-warning', 'style'=>'color:#fff')); 
      ?>
    <?php echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excel-hall-ticket','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff')); ?>

</div>
</br></br>

<?php 
  $supported_extensions = ConfigUtilities::ValidFileExtension(); 
  $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
  $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";
  $previous_reg_number = "";
  $html = "";
  $header = "";
  $body ="";
  $footer = "";
  $new_stu_flag=0;
  $print_stu_data="";
  $prev_sub_code = '';
  foreach($print_halls as $rows) 
  { 
      $app_month = $rows['category_type'];
      $semester_id = $rows['semester'];
      $year = $rows['year'];
      $files = glob($absolute_dire.$rows['register_number'].".*"); 
      if (count($files) > 0)
      {
        foreach ($files as $file)
         {
            $info = pathinfo($file);
            $extension = ".".$info["extension"];
         }
      }
      else
      {
         $extension="";
      }
    $photo_extension = ConfigUtilities::match($supported_extensions,$rows['register_number'].$extension); 
    $stu_photo = $photo_extension!="" ? $stu_directory.$rows['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg";
    if($previous_reg_number!=$rows['register_number'])
    {
        $new_stu_flag=$new_stu_flag + 1;
        if($new_stu_flag > 1) 
        {
            $html = $header .$body.$footer; 
            $print_stu_data .= $html;
            $header = "";
            $body ="";
            $footer = "";
            $new_stu_flag = 1;
        }   
        $header .='<table class="table table-responsive table-bordered" align="center" width="100%" >
                 <tr>
                  <td align="center"> 
                    <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/skct_logo.png" class="img-responsive" alt="College Logo">
                  </td>
                  <td colspan="5" align="center">
                     <h4>'.strtoupper($org_name).'</h4>
                     <h5>'.strtoupper($org_address).' </h5>
                     <h6>'.strtoupper($org_tagline).' </h6>
                  </td>
                  <td align="center">  
                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                  </td>
                </tr>
                <tr>
                  <td colspan="7" ALIGN="center"><h5>HALL TICKET FOR END SEMESTER EXAMINATIONS - 
                    '.strtoupper($app_month)." -".$year.' </h5>
                  </td>
                </tr>
              <tr>
              <td colspan="5"> 
                <table class="table table-responsive table-bordered" width="100%">
                  <tr>
                    <td colspan="3" > NAME OF THE CANDIDATE </td>
                    <td colspan="4" > <b>'.strtoupper($rows['name']).'</b> </td>
                  </tr>
                  <tr>
                    <td colspan="3" > REGISTER NUMBER </td>
                    <td colspan="4" > <b>'.strtoupper($rows['register_number']).'</b> </td>
                  </tr>
                  <tr>
                    <td colspan="3" > '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).' & '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)).' </td>
                    <td colspan="4" > <b>'.strtoupper($rows['degree_code']).". ".strtoupper($rows['programme_name']).'</b> </td>
                  </tr>
                </table>
              </td>
              <td align="right" colspan="2"> 
                  <img style="padding: 10px 0 10px 0"  width=120 height=120 src='.$stu_photo.' alt='.$stu_photo.' Photo ><br />
              </td>
            </tr>
            <tr>
              <th width="30px">SEM</th> 
              <th width="100px">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE</th>
              <th width="150px" colspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).'   NAME </th>
              <th width="100px">DATE &amp;  SESSION</th>
              <th width="10px">HALL  NAME</th>
              <th width="30px">SNO</th>  
            </tr>
            <tr>
                <td colspan="7" ALIGN="center">&nbsp;</td>
            </tr>';

            $footer .='<tr>
                        <td colspan="7" ALIGN="center">&nbsp;</td>
                      </tr>
                      <tr height="20px">
                        <td colspan="3">
                        <br>
                          SIGNATURE OF THE CANDIDATE
                        </td>
                        <td colspan="4" align="center"><br />FN: 09:30AM TO 12.30PM<br />AN: 01.30PM TO 04.30PM </td>
                      </tr>
                      <tr>
                        <td colspan="7" align="center">&nbsp;</td>
                      </tr>
                      <tr height="20px">
                        <td colspan="3"> &nbsp; <br></td>
                        <td colspan="4" align="center"><br>Controller Of Examinations</td>
                      </tr></table><pagebreak />';
          $hall_name = isset($rows['hall_name']) && !empty($rows['hall_name'])?$rows['hall_name']:'NO HALL';
          $seat_no = isset($rows['seat_no']) && !empty($rows['seat_no'])?$rows['seat_no']:'NO SEAT';

            $body .="<tr>
              <td>".$rows['semester']."</td>
              <td>".$rows['subject_code']."</td>
              <td colspan='2'>".$rows['subject_name']."</td>
              <td>".date('d-m-Y',strtotime($rows['exam_date']))." ".$rows['exam_session']."</td>
              <td>".$hall_name."</td>
              <td>".$seat_no."</td>
           </tr>";
    } // If not the same Register_number 
    else
    {
      $hall_name = isset($rows['hall_name']) && !empty($rows['hall_name'])?$rows['hall_name']:'NO HALL';
      $seat_no = isset($rows['seat_no']) && !empty($rows['seat_no'])?$rows['seat_no']:'NO SEAT';
      $body .="<tr>
                  <td>".$rows['semester']."</td>
                  <td>".$rows['subject_code']."</td>
                  <td colspan='2'>".$rows['subject_name']."</td>
                  <td>".date('d-m-Y',strtotime($rows['exam_date']))." ".$rows['exam_session']."</td>
                  <td>".$hall_name."</td>
                  <td>".$seat_no."</td>
                  
               </tr>";
    }
$previous_reg_number=$rows['register_number'];      

        

    } // Foreach Ends Here 
    $footer_1 ='<tr>
                    <td colspan="7" ALIGN="center">&nbsp;
                    </td>
                  </tr>
        <tr height="20px">
          <td colspan="3">
          <br>
            SIGNATURE OF THE CANDIDATE
          </td>
          <td colspan="4" align="center"><br>FN: 09:30AM TO 12.30PM<BR>AN: 01.30PM TO 04.30PM </td>
        </tr>
        <tr>
            <td colspan="7" align="center">&nbsp;
            </td>
          </tr>
        <tr height="20px">
          <td colspan="3"> &nbsp; <br></td>
          <td colspan="4" align="center"><br>Controller Of Examinations</td>
        </tr></table>';
      $html = $header .$body.$footer_1;
      $print_stu_data .=$html;
      if(isset($_SESSION['hall_ticket_print'])){ unset($_SESSION['hall_ticket_print']);}
      $_SESSION['hall_ticket_print'] = $print_stu_data;
      echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-1" >&nbsp;</div><div class="col-lg-9" >'.$print_stu_data.'</div><div class="col-lg-1" >&nbsp;</div></div></div></div></div>'; 
	
}
?>

