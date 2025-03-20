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
                <td colspan=2 > 
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                </td>
                <td colspan=2 align="center">  
                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
            </tr>';
            $old_reg_num='';
            $reg_nums_print = '';
            $sn=1;
            foreach($revaluation as $revaluation1){
                $data.='<tr>';
                    if($old_reg_num!=$revaluation1['subject_code'])
                    {
                        if($old_reg_num!='' && $old_reg_num!=$revaluation1['subject_code'])
                        {
                            $data.="<tr><td colspan=5 align='left' >".$reg_nums_print."</td></tr><tr><td height='45px'>&nbsp;</td></tr>";
                            
                        }
                        $reg_nums_print='';
                        $old_reg_num = $revaluation1['subject_code'];

                        $data .='<tr height="45px"><th  colspan=5  style="border: 3px solid #000; color: #000;" align="center" height=35 ><h4> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE  = '.$revaluation1['subject_code'].'  '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME =  '.$revaluation1['subject_name'].' </h4></th> </tr>';


                        if(isset($revaluation1['dummy_number']))
                        {
                            $reg_nums_print.=$revaluation1['register_number'].' - '.$revaluation1['dummy_number'].', ' ;
                        }
                        else
                        {
                            $reg_nums_print.=$revaluation1['register_number'].' - , ';
                        }
                        
                        $sn++;
                    }
                    else
                    {
                        if(isset($revaluation1['dummy_number']))
                        {
                            $reg_nums_print.=$revaluation1['register_number'].' - '.$revaluation1['dummy_number'].', ' ;
                        }
                        else
                        {
                            $reg_nums_print.=$revaluation1['register_number'].' - , ';
                        }
                    }     
                          
                    
            }
            $data.="<tr><td colspan=5 align='left' >".$reg_nums_print."</td></tr><tr><td height='45px'>&nbsp;</td></tr>";

    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['transparency_print'])){ unset($_SESSION['transparency_print']);}
    $_SESSION['transparency_print'] = $data;
    echo $data;


    }
?>