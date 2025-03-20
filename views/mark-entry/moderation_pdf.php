<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>
<?php


if(isset($view_moderation) && !empty($view_moderation))
{
	?>
	<div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
             echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-noticeboardcopy','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/mark-entry/print-application-pdf'], [
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
	$data ='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';                 
    $data.='<tr>
                <td colspan=2> 
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan=9 align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                </td>
                <td  colspan=2 align="center">  
                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
            </tr>';
    foreach($view_moderation as $view_mod){
        $month_name = $view_mod['category_type'];
    }
    $data.='<tr><td colspan="13" align="center"><h3>Moderation Report For  '.$_POST['view_mod_mark_year']." - ".$month_name.'</h3></td></tr>';
    $sn=1;

    $data.='<tr>
                <th>S.No</th>
                <th colspan=2>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' </th>
                <th>Register Number</th>
                <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code</th>
                <th>Sem</th>
                <th>CIA</th>
                
                <th>ESE</th>
                <th>Total</th>
                <th>Grade</th>
                <th>Result</th>
            </tr>'; //<th>Grade</th> No Grade Caluclation so removed it
    foreach($view_moderation as $view_mod){
    	$data.='<tr>
                    <td align="left">'.$sn.'</td>
                    <td  colspan=2 align="left">'.$view_mod['degree_name'].'</td>
                   <td align="left">'.$view_mod['register_number'].'</td>
                    <td align="left">'.$view_mod['subject_code'].'</td>
                    <td align="left">'.$view_mod['semester'].'</td>
                    <td align="left">'.$view_mod['CIA'].'</td>
                   
                    <td align="left">'.$view_mod['newESE'].'</td>
                    <td align="left">'.$view_mod['total'].'</td>
                    <td align="left">'.$view_mod['grade_name'].'</td>
                    <td align="left">'.$view_mod['result'].'</td>
                </tr>';
    	$sn++;
    }
    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['moderation_print'])){ unset($_SESSION['moderation_print']);}
    $_SESSION['moderation_print'] = $data;
    echo $data;
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
    }
}