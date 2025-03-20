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
    $reporttype='';
    $colspan=0;
    if($report_type==1)
    {
        $reporttype=' <center> Consolidate Transparency Programme Wise </center>';
        $colspan=2;
    }
    else if($report_type==2)
    {
        $reporttype=' <center> Consolidate Transparency Course Wise </center>';
        $colspan=2;
    }
    $data ='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';                 
    $data.='<tr>
                <td colspan=1> 
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan='.$colspan.' align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                    '.$reporttype.'
                </td>
                <td colspan=1 align="center">  
                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
            </tr>';
           

                    
          
            
            $subject_code='';
            $sn=1;
            $tot_stu=$tot_script=0;
            if($report_type==1)
            {
                 $data.='<tr>
                        <th>SNO</th>     
                        <th>PROGRAMME</th>
                        <th>No Of STUDENT</th>
                        <th>No Of SCRIPT</th>
                        
                       ';

                foreach($revaluation as $revaluation1)
                {

                  

                    $data.='<tr>';
                        
                     $reg_numlist = Yii::$app->db->createCommand(" select  count(DISTINCT student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."' ")->queryScalar();

                      $script = Yii::$app->db->createCommand(" select  count(student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."' ")->queryScalar();

                     $tot_stu= $tot_stu+$reg_numlist;

                      $tot_script= $tot_script+$script;
                     
                        $data.='<td align="left" >'.$sn.'</td>';
                        $data.='<td align="left" >'. $revaluation1['programme_name'].'</td>';
                        $data.='<td align="left" >'.$reg_numlist.'</td>';
                       $data.='<td align="left" >'.$script.'</td>';

                        $data.='</tr>';  
                            
                     $sn++;
                   
     
                }

                 $data.='<tr>';
                 $data.='<td align="right" colspan=2 >Total</td>';
                $data.='<td align="left" >'. $tot_stu.'</td>';
                $data.='<td align="left" >'.$tot_script.'</td>';
               
                $data.='</tr>'; 

            }

            else if($report_type==2)
            {

                   $data.='<tr>
                        <th>SNO</th>     
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>No Of SCRIPT</th>
                       ';

                foreach($revaluation as $revaluation1)
                {


                    $data.='<tr>';                                          

                    /*$reg_numlist = Yii::$app->db->createCommand(" select  count(DISTINCT student_map_id)  from
                     coe_revaluation A WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND A.subject_map_id='".$revaluation1['subject_map_id']."' ")->queryScalar();*/

                      $script = Yii::$app->db->createCommand(" select  count(student_map_id)  from
                     coe_revaluation A  
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND A.subject_map_id='".$revaluation1['subject_map_id']."' ")->queryScalar();

                    // $tot_stu= $tot_stu+$reg_numlist;

                      $tot_script= $tot_script+$script;
                     
                        $data.='<td align="left" >'.$sn.'</td>';
                        $data.='<td align="left" >'. $revaluation1['subject_code'].'</td>';
                        $data.='<td align="left" >'. $revaluation1['subject_name'].'</td>';
                        //$data.='<td align="left" >'.$reg_numlist.'</td>';
                       $data.='<td align="left" >'.$script.'</td>';

                        $data.='</tr>';  
                            
                     $sn++;
                   
     
                }

                 $data.='<tr>';
                 $data.='<td align="right" colspan=2 >Total</td>';
                //$data.='<td align="left" >'. $tot_stu.'</td>';
                $data.='<td align="left" >'.$tot_script.'</td>';
               
                $data.='</tr>'; 
            }

   
    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['transparency_print'])){ unset($_SESSION['transparency_print']);}
    $_SESSION['transparency_print'] = $data;
    echo $data;


    }
?>