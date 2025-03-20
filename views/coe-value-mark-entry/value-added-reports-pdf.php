<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\MarkEntryMaster;
use app\models\Subjects;
use yii\db\Query;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

<?php
  if(isset($noticeboard_copy) && !empty($noticeboard_copy))
  {   
?>
<div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
           <?php 
          
          
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-value-added-reports','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/coe-value-mark-entry/value-added-reports-pdf'], [
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
  //$batch_name = Yii::$app->db->createCommand("select batch_name from coe_batch where coe_batch_id='" . $_POST['bat_val'] . "'")->queryScalar();

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

                       

            $data.='<tr><b><td colspan="11" align="center"><h5 align="center"><b>'.strtoupper('End '.$semester_name.'   Value Added Subject Reports- '.$month_name.' '.$_POST["year"]).'</h5></b> <b><h5 align="center"><b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' - '.$degree_name.'</h5></b></td></tr>';


    $data.='<tr>
               <th><center>SNO</centert></th>
               <th><center>BATCH</center></th>
               <th><center>DEPT</center></th>                           
               <th><center>REGISTER NUMBER</center></th>
               <th><center>SUBJECT CODE</center></th>
               <th><center>SUBJECT NAME</center></th>
               <th><center>TOTAL MARKS</center></th>   
               <th ><center>RESULT</center></th>  
               
                                          
            </tr>';    

    $prev_value="";
    $prev_value_br="";
    $sn=1;
    foreach($noticeboard_copy as $notice)
    {
      $curr_value=$notice['register_number'];
      $curr_value_br=$notice['register_number'];
     
      if($prev_value!=$curr_value)
        {
          $data.='</td></tr><tr>';
          $data.='<td><center>'.$sn.'</td></center>';
          $data.='<td>'.$notice['batch_name'].'</td>';
          $data.='<td>'.strtoupper($notice['degree_code']." ".$notice['programme_name']).'</td>';
          $data.='<td><center>'.$notice['register_number'].'</td></center>';
          $data.='<td><center>'.$notice['subject_code'].'</td></center>';
          $data.='<td> <center>'.$notice['subject_name'].'</td></center>';
          $data.='<td><center>'.$notice['total'].'</td></center>';
          $data.='<td><center>'.$notice['result'].'</td></center>';
          //  $data.='<td>'.$markdetails['grade_point'].'</td>';
          // $data.='<td>'.$markdetails['grade_name'].'</td>';

    // $prev_value=$markdetails['register_number'];
          $sn++;
        }
    if($notice['result']=="Pass" || $notice['result']=="pass" || $notice['result']=="PASS" )
      {        
        $result="<b><font color='green'>P</font></b>"; 
      }
      else if($notice['result']=="Absent" || $notice['result']=="Ab" || $notice['result']=="AB")
      {
          $result="<b><font color='orange'>A</font></b>";
      }     
      else{
        $result="<b><font color='red'>R</font></b>";
      }
        
      $ese_marks=$notice['ESE']==0 ? "0":$notice['ESE'];
      $grade_name = strtoupper($notice['grade_name']);
      $cia_disp = $notice['CIA']==0?'0':$notice['CIA'];
                
      //$data.= $markdetails['subject_code']."<b>:</b><b>".$cia_disp."+".$ese_marks."</b>:".$result.";";
    }
    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['noticeboard_print'])){ unset($_SESSION['noticeboard_print']);}
    $_SESSION['noticeboard_print'] = $data;

    if(isset($_SESSION['mark_year']) || isset($_SESSION['mark_month'])){ unset($_SESSION['mark_year']);unset($_SESSION['mark_month']);}
    $_SESSION['mark_year'] = $_POST['year'];
    $_SESSION['mark_month'] = $_POST['month'];


    echo $add_duiv.$data.'</div>';
  
}
  
?>