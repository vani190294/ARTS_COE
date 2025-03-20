<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\MarkEntryMaster;
use app\models\StuInfo;

?>
<?php
$add_duiv ='<div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x:auto;" >'; 
	$data ='<table border=1 width="90%" style="overflow: scroll;" class="table table-responsive table-striped" align="center" ><tbody align="center"  >';     
    $data.='<tr>
    		       <th><center>SNO</center></th>
                <th><center>BATCH</center></th>  
               <th><center>DEPT</center></th>              	                
               <th><center>Register Number</center></th>
               <th><center>Status</center></th>
               <th><center>DOB</center></th>
               <th><center>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT).' Name</center></th>
               
               <th colspan="8"><center>Marks</center></th>                              
            </tr>';    

    $prev_value="";
    $prev_value_br="";
    $sn=1;
    foreach($internet_copy as $markdetails)
    {
	    $curr_value=$markdetails['register_number'];
	    $curr_value_br=$markdetails['register_number'];
      $stu_withheld = 1;
      $withheld_list = MarkEntryMaster::findOne(['month'=>$markdetails['month'],'year'=>$markdetails['year'],'student_map_id'=>$markdetails['student_map_id'],'withheld'=>'w']);
      $stu_withheld = !empty($withheld_list)?2:1;
    	if($prev_value!=$curr_value)
      	{
		   	  $data.='</td></tr><tr>';
		   	  $data.='<td>'.$sn.'</td>';
            $data.='<td>'.$markdetails['batch_name'].'</td>';
          $data.='<td>'.strtoupper($markdetails['degree_code'].".".$markdetails['programme_name']).'</td>';

		      $data.='<td>'.$markdetails['register_number'].'</td>';
          
           $sudent_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['status_category_type_id']."")->queryScalar();
            $stu_map = StuInfo::findOne(['prev_reg'=>$markdetails['register_number']]);

                         if(!empty($stu_map))
                         {
                         
                         $sudent_type ="Detain/Rejoin";
                        
                         $data.='<td>'.$sudent_type.'</td>';


                         }
                         else
                         {
                        
                        $data.='<td>'.$sudent_type.'</td>';
                         
                        }
          $data.='<td>'.date('d-M-y',strtotime($markdetails['dob'])).'</td>';
		      $data.='<td>'.$markdetails['name'].'</td>'.'<td colspan="8">';
	  		  $prev_value=$markdetails['register_number'];
	  		  $sn++;
    	  }
 		if($markdetails['result']=="Pass" || $markdetails['result']=="pass" || $markdetails['result']=="PASS" )
    	{        
      	$result=$markdetails['withheld']=="W" || $markdetails['withheld']=="w" ?"<b><font color='green'>WH</font></b>": ($markdetails['grade_name']=="WD" || $markdetails['grade_name']=="wd" ? "<b><font color='green'>W</font></b>" : "<b><font color='green'>P</font></b>"); 
    	}
  		else if($markdetails['result']=="Absent" || $markdetails['result']=="Ab" || $markdetails['result']=="AB")
  		{
  		    $result="<b><font color='orange'>A</font></b>";
  		}  		
  		else{
  			$result="<b><font color='red'>R</font></b>";
  		}
      	
      $ese_marks=$markdetails['ESE']==0 ? "":$markdetails['ESE'];
  		
		if($markdetails['withheld']=="w" | $markdetails['withheld']=="W")
    	{
      		$result="<b><font color='violet'>WH</font></b>";
      		$ese_marks="-";
      	}else{
      		$ese_marks=$ese_marks;
      	}
		
    if($markdetails['CIA_max']==0 && $markdetails['ESE_max']==0 && $markdetails['CIA_min']==0 && $markdetails['ESE_min']==0)
    {
      $grade_name = 'COMPLETED';
    }
    else
    {
      if($markdetails['withheld']=="w" || $markdetails['withheld']=="W" || $markdetails['withheld']=="wh" || $markdetails['withheld']=="Wh")
      {
          $grade_name = "RA";
        }else{
          if($markdetails['grade_name']=="WD" || $markdetails['grade_name']=="wd")
          {
              $grade_name = "WD";
              $result="<b><font color='violet'>WD</font></b>";
          }
          else
          {
            $grade_name = $markdetails['grade_name'];
          }
         $grade_name = strtoupper($grade_name);
        }
    }
      $cia_disp = $markdetails['CIA'];
     if($stu_withheld==2)
        {
          $ese_marks = '-';
          $result ='WH';
          $cia_disp='-';
        }            
      $data.= $markdetails['subject_code']."<b>:</b><b>".$cia_disp."+".$ese_marks."</b>:".$result.";";
    }
    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['internetcopy_print'])){ unset($_SESSION['internetcopy_print']);}
    $_SESSION['internetcopy_print'] = $data;

    if(isset($_SESSION['mark_year']) || isset($_SESSION['mark_month'])){ unset($_SESSION['mark_year']);unset($_SESSION['mark_month']);}
    $_SESSION['mark_year'] = $_POST['year'];
    $_SESSION['mark_month'] = $_POST['month'];

    echo $add_duiv.$data.'</div>';


  ?>