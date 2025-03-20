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
    <div class="col-lg-10 col-sm-10">
        &nbsp;
    </div>
        <div style="align-content: center; text-align: center;" class="form-group col-lg-2 col-sm-2">
<div class="btn-group" role="group" aria-label="Actions to be Perform">
    <?= Html::submitButton('UPDATE', ['value'=>'UPDATE','name'=>"view_reval_btn_UPDATE" ,'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
</div>
</div>
</div>   
<?php
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $data ='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';                 
   
            $data.='<tr>
                        <th>SNO</th>     
                        <th>REGISTER NUMBER</th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE</th>
                        <th colspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME</th>
                        <th>UPDATE</th>     ';

            
            $data.='</tr>';
            
            $old_reg_num='';
            $sn=1;
            foreach($revaluation as $revaluation1){
                $data.='<tr>';
                    if($old_reg_num!=$revaluation1['register_number'])
                    {
                        $old_reg_num = $revaluation1['register_number'];
                        $data.='<td align="left" > '.$sn.'</td>';
                        $data.='<input type="hidden" name="student_map_id'.$sn.' " value='.$revaluation1['student_map_id'].' >';
                        $data.='<input type="hidden" name="subject_map_id'.$sn.' " value='.$revaluation1['subject_map_id'].' >';
                        $data.='<input type="hidden" name="year'.$sn.' " value='.$revaluation1['year'].' >'.'<input type="hidden" name="month'.$sn.' " value='.$revaluation1['month'].' >'.'<input type="hidden" name="mark_type'.$sn.' " value='.$revaluation1['mark_type'].' >';
                        $data.='<td align="left" >'.$old_reg_num.'</td>';
                        
                    }
                    else
                    {
                        $data.='<td align="left" > '.$sn.'</td>';
                        $data.= '<td align="left" > &nbsp; </td>';
                    }     
                    
                        $data.='<input type="hidden" name="student_map_id'.$sn.' " value='.$revaluation1['student_map_id'].' >';
                        $data.='<input type="hidden" name="subject_map_id'.$sn.' " value='.$revaluation1['subject_map_id'].' >';
                        $data.='<input type="hidden" name="year'.$sn.' " value='.$revaluation1['year'].' >'.'<input type="hidden" name="month'.$sn.' " value='.$revaluation1['month'].' >'.'<input type="hidden" name="mark_type'.$sn.' " value='.$revaluation1['mark_type'].' >';

                        $data.=
                            '<td align="left" >'.$revaluation1['subject_code'].'</td>
                            <td align="left"  colspan="2" >'.$revaluation1['subject_name'].'</td>
                            <td align="left" ><input type="checkbox" checked name="checkbox'.$sn.' " value=YES ></td>
                        </tr>';
                    
                    $sn++;            
                    
            }

    $data.='</tbody>';        
    $data.='</table>';
    
    $_SESSION['transparency_print'] = $data;
    echo $data;

    ?>
<div class="col-xs-12 col-sm-12 col-lg-12">
    <div class="col-lg-10 col-sm-10">
        &nbsp;
    </div>
        <div style="align-content: center; text-align: center;" class="form-group col-lg-2 col-sm-2">
<div  class="btn-group" role="group" aria-label="Actions to be Perform">
    <?= Html::submitButton('UPDATE', ['value'=>'UPDATE','name'=>"view_reval_btn_UPDATE" ,'class' => 'btn  btn-group-lg btn-group btn-primary']) ?>
</div>
</div>
</div> 
<?php
}

?>