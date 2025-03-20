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
          
          
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-adc-nptl-coursera','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/adc-nptl-coursera-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
</div>

<?php
  require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
   $semester_name = ConfigUtilities::getSemesterName($_POST['bat_map_val']);
   $degree_name = Yii::$app->db->createCommand("select concat(degree_name,' -  ',programme_name) as degree_name from coe_degree a,coe_programme b,coe_bat_deg_reg c where a.coe_degree_id=c.coe_degree_id and b.coe_programme_id=c.coe_programme_id and c.coe_bat_deg_reg_id='" . $_POST['bat_map_val'] . "'")->queryScalar();
  

  if($file_content_available=="Yes")
  {

  }
  else
  {
    Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
  }
  $month_name= $det_disc_rejoin_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id = '".$_POST["month"]."'")->queryScalar();
  //print_r($month_name);exit;

  $add_duiv ='<div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto;" >'; 
  $data ='<div class="box-body table-responsive"><table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" >'; 
   $data.='<tr>
               
                <td colspan=17 align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                </td>
              
            </tr>';

        
            $data.='<tr><b><td colspan="11" align="center"><h5 align="center"><b>'.strtoupper('End '.$semester_name.'  Adc-Nptel-Coursera-Reports- '.$month_name.' '.$_POST["year"]).'</h5></b> <b><h5 align="center"><b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' - '.$degree_name.'</h5></b></td></tr>';


    $data.='<tr>
               <th><left>SNO</left></th>
                                   
               <th><left>REGISTER NUMBER</left></th>
               <th><left>SEMESTER</left></th>
               <th><left>SUBJECT CODE</left></th>
               <th><left>SUBJECT NAME</left></th>
               <th><left>TOTAL MARKS</left></th>
            </tr>';

    
    
    $prev_value="";
    $prev_value_br="";
    $total_strength = 0;
    $sn=1;

    foreach($internet_copy as $markdetails)
    {
      $curr_value=$markdetails['register_number'];
      $curr_value_br=$markdetails['register_number'];
     
      if($prev_value!=$curr_value)
        {
          $data.='</td></tr><tr>';
          $data.='<td><center>'.$sn.'</td></center>';
          //$data.='<td>'.strtoupper($markdetails['degree_code']." ".$markdetails['programme_name']).'</td>';
          $data.='<td><left>'.$markdetails['register_number'].'</td></left>';
          $data.='<td> <left>'.$markdetails['semester'].'</td></left>';
          $data.='<td><left>'.$markdetails['subject_code'].'</td></left>';
          $data.='<td> <left>'.$markdetails['subject_name'].'</td></left>';
      
          $data.='<td><left>'.$markdetails['total'].'</td></left>';
         
    // $prev_value=$markdetails['register_number'];
         // $data +=$markdetails['count'];
         //$data +=$markdetails['count']                   
          $sn++;
        }
         
  
        
      $ese_marks=$markdetails['ESE']==0 ? "0":$markdetails['ESE'];
      $grade_name = strtoupper($markdetails['grade_name']);
      $cia_disp = $markdetails['CIA']==0?'0':$markdetails['CIA'];
                
      //$data.= $markdetails['subject_code']."<b>:</b><b>".$cia_disp."+".$ese_marks."</b>:".$result.";";
    }
    $total_strength +=$sn-1;

     //$data .= "<tr height='45px'><th colspan='18' >&nbsp; <br /><br /><br /><br /><br /><br /><br /><br /></th></tr>";
    $data .= "<tr><td colspan='4' align='center'> <b>TOTAL COUNT :". $total_strength."
     </b></td></tr>";
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