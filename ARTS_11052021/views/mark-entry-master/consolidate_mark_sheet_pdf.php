<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

<?php
	if(isset($name) && !empty($name))
	{		
?>

<div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
  				echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry-master/consolidate-mark-sheet-pdf'], [
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

    if($file_content_available=="Yes")
    {
        $supported_extensions = ConfigUtilities::ValidFileExtension(); 
        $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
        $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";

        $previous_reg_number = "";
        $html = "";
        $header = "";
        $body ="";
        $footer = "";
        foreach ($get_console_list as $value) 
        {
            if($previous_reg_number!=$value['register_number'])
            {
                $files = glob($absolute_dire.$value['register_number'].".*"); // Will find 2.JPG, 2.php, 2.gif
                    // Process through each file in the list
                    // and output its extension

                    if (count($files) > 0)
                    foreach ($files as $file)
                     {
                        $info = pathinfo($file);
                        $extension = ".".$info["extension"];
                     }
                     else
                     {
                        $extension="";
                     }
                $photo_extension = ConfigUtilities::match($supported_extensions,$value['register_number'].$extension); 
                $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg";
            }
        }

	$header ='<table width="100%" id="checkAllFeet" class="table table-responsive table-striped" align="center" >
              <tbody align="center">';                 
    $header.='<tr>
                <td colspan=2> 
                    <img src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan=21 align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                    <center>CONSOLIDATED STATEMENT OF GRADES</center> 
                </td>
                <td  colspan=2 align="center">  
                    <img class="img-responsive" width=120 height=120 src='.$stu_photo.' alt='.$stu_photo.' Photo >
                </td>
            </tr>';
    
    $header.='<tr>'; //Body main tr
    
    $header.='<td colspan="12">'; //first td
     $header.='<table>
    			<tr>
    				<td colspan="4">Name of the Candidate</td>
    				<td colspan="8"></td>
    			</tr>
    			<tr>
    				<td colspan="4">'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' & Branch</td>
    				<td colspan="8"></td>
    			</tr>
    			<tr>
    				<td>Semester</td>
    				<td colspan="2">'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code</td>
    				<td colspan="4">'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Title</td>
    				<td>Credit</td>
    				<td>Grade</td>
    				<td>Grade Points</td>
    				<td colspan="2">Month & Year of Passing</td>
    			</tr>
    			<tr>
    				<td colspan="12" align="center">Total Credits Earned : </td>
    			</tr>';

    $header.='</table></td>'; //first td

    $header.='<td colspan="2"></td>'; //second td
    $header.='<td colspan="12">'; //third td
    $header.='<table>
    			<tr>
    				<td colspan="2">Reg No.</td>
    				<td colspan="4"></td>
    				<td colspan="2">Regulation</td>
    				<td colspan="4"></td>
    			</tr>
    			<tr>
    				<td colspan="2">Date of Birth</td>
    				<td colspan="4"></td>
    				<td colspan="2">Gender</td>
    				<td colspan="4"></td>
    			</tr>
    			<tr>
    				<td colspan="2">Month & Year</td>
    				<td colspan="4"></td>
    				<td colspan="2">Medium</td>
    				<td colspan="4"></td>
    			</tr>
    			<tr>
    				<td>Semester</td>
    				<td colspan="2">'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code</td>
    				<td colspan="4">'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Title</td>
    				<td>Credit</td>
    				<td>Grade</td>
    				<td>Grade Points</td>
    				<td colspan="2">Month & Year of Passing</td>
    			</tr>
    			<tr>
    				<td colspan="12" align="center">Cumulative Grade Point Average(CGPA) : Classification : </td>
    			</tr>';


    $header.='</table></td>'; //third td

    $header.='</tr>'; //main tr

    $header.='</tbody>';        
    $header.='</table>';
    
    	if(isset($_SESSION['consolidatemarksheet_print']))
        { 
            unset($_SESSION['consolidatemarksheet_print']);
        }
        $_SESSION['consolidatemarksheet_print'] = $header;
        echo $header; 
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
    }
}
?>