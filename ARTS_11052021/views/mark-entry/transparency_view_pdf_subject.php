<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

<?php
    if(isset($revaluation) && !empty($revaluation))
    {
?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-transparencyview','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/mark-entry/transparency-view-pdf'], [
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
    $data ='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';                 
    $data.='<tr>
                <td > 
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan=6 align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                </td>
                <td align="center">  
                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
            </tr>';
            $data.='<tr>
                        <th>SNO</th>     
                        <th>REGISTER NUMBER</th>
                        <th>YEAR</th>
                        <th>MONTH</th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE</th>
                        <th colspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME</th>';

            if(isset($_POST['Revaluation']['is_transparency']) && !empty($_POST['Revaluation']['is_transparency']))
            {
                $data.='<th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).'</th>';
            }            
            $data.='</tr>';
            
            $old_reg_num='';
            $sn=1;
            foreach($revaluation as $revaluation1){
                $data.='<tr>';
                    if($old_reg_num!=$revaluation1['register_number'])
                    {
                        $old_reg_num = $revaluation1['register_number'];

                        $data.='<td align="left" >'.$sn.'</td>';
                        $data.='<td align="left" >'.$old_reg_num.'</td>';
                        $data.='<td align="left" >'.$revaluation1['year'].'</td>';
                        $data.='<td align="left" >'.$revaluation1['month'].'</td>';
                        $sn++;
                    }
                    else
                    {
                        $data.= '<td align="left" colspan=2 > </td>';
                    }     
                    if(isset($revaluation1['dummy_number']))
                    {
                        
                        $data.=
                            '<td align="left" >'.$revaluation1['subject_code'].'</td>
                            <td align="left" colspan="2">'.$revaluation1['subject_name'].'</td>
                            <td align="left">'.$revaluation1['dummy_number'].'</td>
                        </tr>';
                    }  
                    else{
                        $data.='<td align="left" >'.$revaluation1['year'].'</td>';
                        $data.='<td align="left" >'.$revaluation1['month'].'</td>';
                        $data.=
                            '<td align="left" colspan="2">'.$revaluation1['subject_code'].'</td>
                            <td align="left" colspan="2">'.$revaluation1['subject_name'].'</td>
                        </tr>';
                    }             
                    
            }

    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['transparency_print'])){ unset($_SESSION['transparency_print']);}
    $_SESSION['transparency_print'] = $data;
    echo $data;


    }
?>