<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

<?php
	if(isset($internet_copy) && !empty($internet_copy))
	{		
?>


<?php
	require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
  if($file_content_available=="Yes")
  {

  }
  else
  {
    Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
  }
	$data ='<table border=1 width="100%" class="table table-responsive table-striped" align="center" ><tbody align="center">'; 
    $data.='<tr>
    		                 	                
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
	    $curr_value=$markdetails['register_number'];
	    $curr_value_br=$markdetails['register_number'];
    	if($prev_value!=$curr_value)
      	{
		   	$data.='</td></tr><tr>';
		   	
		    $data.='<td>'.$markdetails['register_number'].'</td>';
        $data.='<td>'.date('d-M-y',strtotime($markdetails['dob'])).'</td>';
		    $data.='<td>'.$markdetails['name'].'</td>'.'<td colspan="8">';
		    
	  		$prev_value=$markdetails['register_number'];
	  		$sn++;
    	}
 		if($markdetails['result']=="Pass" || $markdetails['result']=="pass" || $markdetails['result']=="PASS")
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

  		if($markdetails['ESE']==0)
    	{
      		$ese_marks="";
    	}
  		else
    	{
      		$ese_marks=$markdetails['ESE'];
    	}
		if($markdetails['withheld']=="w")
    	{
      		$result="<b><font color='violet'>W</font></b>";
      		$ese_marks="-";
      	}else{
      		$ese_marks=$ese_marks;
      	}
		//$data.= $markdetails['subject_code']."<b>:</b>".$markdetails['CIA']."+".$ese_marks.":".$result."; ";
    if($markdetails['CIA_max']==0 && $markdetails['ESE_max']==0 && $markdetails['CIA_min']==0 && $markdetails['ESE_min']==0)
    {
      $grade_name = 'CO';
    }
    else
    {
      $grade_name = $markdetails['grade_name'];
    }


    $data.= $markdetails['subject_code']."<b>:</b><b>".$markdetails['CIA']."+".$ese_marks."</b>:".$result."; ";
		
    }
    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['internetcopy_print'])){ unset($_SESSION['internetcopy_print']);}
    $_SESSION['internetcopy_print'] = $data;

    if(isset($_SESSION['mark_year']) || isset($_SESSION['mark_month'])){ unset($_SESSION['mark_year']);unset($_SESSION['mark_month']);}
    $_SESSION['mark_year'] = $_POST['year'];
    $_SESSION['mark_month'] = $_POST['month'];

    echo $data;

	}
?>