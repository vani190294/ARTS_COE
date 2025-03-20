<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\MarkEntryMaster;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

<?php
	if(isset($internet_copy) && !empty($internet_copy))
	{		
?>
<div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
  				
          echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-internet-copy-additional-credits-pdf','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));

         
            ?>
        </div>
</div>

<?php
	require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
  if($file_content_available=="Yes")
  {

  }
  else
  {
    Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
  }

  $add_duiv ='<div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto;" >'; 
  $data ='<table border=1 width="90%" style="overflow: scroll;" class="table table-responsive table-striped" align="center" ><tbody align="center"  >';     
    $data.='<tr>
               <th><center>SNO</center></th>
               <th><center>DEPT</center></th>                               
               <th><center>Register Number</center></th>
               <th><center>DOB</center></th>
               <th><center>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Name</center></th>
               
               <th colspan="8"><center>Marks</center></th>                              
            </tr>';    

    $prev_value="";
    $prev_value_br="";
    $sn=1;
    foreach($internet_copy as $markdetails)
    {
      //print_r($markdetails);exit;
      $curr_value=$markdetails['register_number'];
      $curr_value_br=$markdetails['register_number'];
     
      if($prev_value!=$curr_value)
        {
          $data.='</td></tr><tr>';
          $data.='<td>'.$sn.'</td>';
          $data.='<td>'.strtoupper($markdetails['degree_code']." ".$markdetails['programme_name']).'</td>';
          $data.='<td>'.$markdetails['register_number'].'</td>';
          $data.='<td>'.date('d-M-y',strtotime($markdetails['dob'])).'</td>';
          $data.='<td>'.$markdetails['name'].'</td>'.'<td colspan="8">';
          $prev_value=$markdetails['register_number'];
          $sn++;
        }
    if($markdetails['result']=="Pass" || $markdetails['result']=="pass" || $markdetails['result']=="PASS" )
      {        
        $result="<b><font color='green'>P</font></b>"; 
      }
      else if($markdetails['result']=="Absent" || $markdetails['result']=="Ab" || $markdetails['result']=="AB")
      {
          $result="<b><font color='orange'>A</font></b>";
      }     
      else{
        $result="<b><font color='red'>R</font></b>";
      }
        
      $ese_marks=$markdetails['total']==0 ? "0":$markdetails['total'];
      $grade_name = strtoupper($markdetails['grade_name']);
      $cia_disp = $markdetails['CIA']==0?'0':$markdetails['CIA'];
                
      $data.= $markdetails['subject_code']."<b>:</b><b>".$cia_disp."+".$ese_marks."</b>:".$result.";";
    }
    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['internetcopy_additional_print'])){ unset($_SESSION['internetcopy_additional_print']);}
    $_SESSION['internetcopy_additional_print'] = $data;

    if(isset($_SESSION['mark_year']) || isset($_SESSION['mark_month'])){ unset($_SESSION['mark_year']);unset($_SESSION['mark_month']);}
    $_SESSION['mark_year'] = $_POST['year'];
    $_SESSION['mark_month'] = $_POST['month'];

    echo $add_duiv.$data.'</div>';
  
}


  
?>