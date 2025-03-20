<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;
use app\models\ExamTimetable;
use yii\db\Query;
$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();
$generator = new \Picqer\Barcode\BarcodeGeneratorSVG();

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
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-revaluationview','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> Print', ['/mark-entry/revaluation-view-pdf'], [
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
                <td colspan=2 align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                </td>
                <td colspan=2 align="center">  
                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
            </tr>';
            $data.='<tr>
                        <th>SNO</th>     
                        <th>REGISTER NUMBER</th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE</th>
                        <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME</th>';

            if(isset($_POST['Revaluation']['is_transparency']) && !empty($_POST['Revaluation']['is_transparency']))
            {
                $data.='<th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)).'</th>';
            }            
            $data.=' <th>PACKET NUMBER</th></tr>';
            
            $old_reg_num='';
            $sn=1;
            foreach($revaluation as $revaluation1){
                $data.='<tr>';
                    if($old_reg_num!=$revaluation1['register_number'])
                    {
                        $old_reg_num = $revaluation1['register_number'];
                        $data.='<td align="left" >'.$sn.'</td>';
                        $data.='<td align="left" >'.$old_reg_num.'</td>';
                        //$data.='<td align="left">AV-'.$ans_pack_dta['vanswer_packet_no']." (".$boono['booklet_sno'].')</td>';
                        $sn++;
                    }
                    else
                    {
                        $data.= '<td align="left" colspan=2 > </td>';
                    }     
                    if(isset($revaluation1['dummy_number']))
                    {

                         $examDate = Yii::$app->db->createCommand("select exam_date from coe_exam_timetable where subject_mapping_id='".$revaluation1['coe_subjects_mapping_id']."' AND exam_month='".$exam_month."' AND exam_year='".$exam_year."'")->queryScalar();

                        $ans_pack_dta = Yii::$app->db->createCommand("select vanswer_packet_no from coe_vanswerpack_regno where subject_code='".$revaluation1['subject_code']."' AND exam_date='".$examDate."' AND exam_month='".$exam_month."' AND exam_year='".$exam_year."' AND stu_reg_no='".$revaluation1['register_number']."'")->queryone();

                        $boono = Yii::$app->db->createCommand("select B.booklet_sno from coe_dummy_number A JOIN coe_val_barcode_verify_details B ON B.dummy_number=A.dummy_number where B.dummy_number = '".$revaluation1['dummy_number']."' AND A.year='".$exam_year."' AND A.month='".$exam_month."'")->queryOne();
            
                        $col_name = isset($revaluation1['dummy_number']) && !empty($revaluation1['dummy_number']) ?$revaluation1['dummy_number']:'NO BAR CODE';
                        $code = str_pad($col_name, 15, '0', STR_PAD_LEFT);

                        $barcode=$generator->getBarcode($code, $generator::TYPE_CODE_128, 1, 30);

                        $data.=
                            '<td align="left" >'.$revaluation1['subject_code'].'</td>
                            <td align="left">'.$revaluation1['subject_name'].'</td>
                           <td align="left">'.$revaluation1['dummy_number'].'<br>'.$barcode.'</td>
                           <td align="left">AV-'.$ans_pack_dta['vanswer_packet_no']." (".$boono['booklet_sno'].')</td>
                        </tr>';
                    }  
                    else{
                        $data.=
                            '<td align="left" colspan="2">'.$revaluation1['subject_code'].'</td>
                            <td align="left" colspan="2">'.$revaluation1['subject_name'].'</td>
                        </tr>';
                    }             
                    
            }

    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['revaluation_print'])){ unset($_SESSION['revaluation_print']);}
    $_SESSION['revaluation_print'] = $data;
    echo $data;


    }
?>