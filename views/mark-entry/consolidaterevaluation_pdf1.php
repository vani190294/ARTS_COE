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

    $month_name = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='" . $exam_month . "'")->queryScalar();

    $reporttype='';
    $colspan=0;
    if($report_type==1)
    {
        $reporttype=' <center> Consolidate Revaluation Programme Wise '.strtoupper($month_name) . '-' . $exam_year.'</center>';

        if($withsp==1)
        {
          $colspan=8;
        }
        else
        {
          $colspan=4;
        }
    }
    else if($report_type==2)
    {
        $reporttype=' <center> Consolidate Revaluation Course Wise '.strtoupper($month_name) . '-' . $exam_year.'</center>';
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
            $tot_stuu=$tot_scriptu=0;
            $tot_stuar=$tot_scriptar=0;

            $tot_stuch=$tot_scriptch=0;

            $tot_stuchar=$tot_scriptchar=0;

            if($report_type==1)
            {
              if($withsp==1)
              {
                $data.='<tr>
                        <th>SNO</th>     
                        <th>PROGRAMME</th>
                        <th>No Of STUDENT (Regular)</th>
                        <th>No Of SCRIPT (Regular)</th>
                        <th>No Of STUDENT Changes</th>
                        <th>No Of SCRIPT Changes</th>
                        <th>No Of STUDENT (Arrear)</th>
                        <th>No Of SCRIPT (Arrear)</th>
                        <th>No Of STUDENT Changes</th>
                        <th>No Of SCRIPT Changes</th>
                       ';

                foreach($revaluation as $revaluation1)
                {


                    $data.='<tr>';

                     $reg_numlistu = Yii::$app->db->createCommand(" select  count(DISTINCT student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' ")->queryScalar();

                      $scriptu = Yii::$app->db->createCommand(" select  count(student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."' and A.reval_status='YES' ")->queryScalar();
                        
                     $reg_numlist = Yii::$app->db->createCommand(" select  count(DISTINCT student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' AND A.mark_type=27 ")->queryScalar();

                      $script = Yii::$app->db->createCommand(" select  count(student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."' and A.reval_status='YES' AND A.mark_type=27 ")->queryScalar();

                       $reg_numlistar = Yii::$app->db->createCommand(" select  count(DISTINCT student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' AND A.mark_type=28 ")->queryScalar();

                      $scriptar = Yii::$app->db->createCommand(" select  count(student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."' and A.reval_status='YES' AND A.mark_type=28 ")->queryScalar();

                    $stu_changes = Yii::$app->db->createCommand(" select  count(DISTINCT A.student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     JOIN coe_mark_entry_master_reval as D ON D.subject_map_id=A.subject_map_id AND D.student_map_id=A.student_map_id AND A.year=D.year AND A.month=D.month
                     JOIN coe_mark_entry_master as E ON E.subject_map_id=A.subject_map_id AND E.student_map_id=A.student_map_id AND A.year=E.year AND A.month=E.month
                     WHERE D.grade_name!=E.grade_name AND A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' AND A.mark_type=27 ")->queryScalar();

                       $script_changes = Yii::$app->db->createCommand(" select  count(A.student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     JOIN coe_mark_entry_master_reval as D ON D.subject_map_id=A.subject_map_id AND D.student_map_id=A.student_map_id AND A.year=D.year AND A.month=D.month
                     JOIN coe_mark_entry_master as E ON E.subject_map_id=A.subject_map_id AND E.student_map_id=A.student_map_id AND A.year=E.year AND A.month=E.month
                     WHERE D.grade_name!=E.grade_name AND A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' AND A.mark_type=27 ")->queryScalar();

                     $stu_changesar = Yii::$app->db->createCommand(" select  count(DISTINCT A.student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     JOIN coe_mark_entry_master_reval as D ON D.subject_map_id=A.subject_map_id AND D.student_map_id=A.student_map_id AND A.year=D.year AND A.month=D.month
                     JOIN coe_mark_entry_master as E ON E.subject_map_id=A.subject_map_id AND E.student_map_id=A.student_map_id AND A.year=E.year AND A.month=E.month
                     WHERE D.grade_name!=E.grade_name AND A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' AND A.mark_type=28 ")->queryScalar();

                       $script_changesar = Yii::$app->db->createCommand(" select  count(A.student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     JOIN coe_mark_entry_master_reval as D ON D.subject_map_id=A.subject_map_id AND D.student_map_id=A.student_map_id AND A.year=D.year AND A.month=D.month
                     JOIN coe_mark_entry_master as E ON E.subject_map_id=A.subject_map_id AND E.student_map_id=A.student_map_id AND A.year=E.year AND A.month=E.month
                     WHERE D.grade_name!=E.grade_name AND A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' AND A.mark_type=28 ")->queryScalar();

                     $tot_stu= $tot_stu+$reg_numlist;

                      $tot_script= $tot_script+$script;

                     
                       $tot_stuar= $tot_stuar+$reg_numlistar;

                      $tot_scriptar= $tot_scriptar+$scriptar;

                        $tot_stuu= $tot_stuu+$reg_numlistu;

                      $tot_scriptu= $tot_scriptu+$scriptu;

                      $tot_stuch= $tot_stuch+$stu_changes;

                      $tot_scriptch= $tot_scriptch+$script_changes;

                      $tot_stuchar= $tot_stuchar+$stu_changesar;

                      $tot_scriptchar= $tot_scriptchar+$script_changesar;

                     
                        $data.='<td align="left" >'.$sn.'</td>';
                        $data.='<td align="left" >'. $revaluation1['programme_name'].'</td>';
                        $data.='<td align="left" >'.$reg_numlist.'</td>';
                       $data.='<td align="left" >'.$script.'</td>';
                       $data.='<td align="left" >'.$stu_changes.'</td>';
                       $data.='<td align="left" >'.$script_changes.'</td>';
                        $data.='<td align="left" >'.$reg_numlistar.'</td>';
                       $data.='<td align="left" >'.$scriptar.'</td>';
                       $data.='<td align="left" >'.$stu_changesar.'</td>';
                       $data.='<td align="left" >'.$script_changesar.'</td>';
                        $data.='</tr>';  
                            
                     $sn++;
                   
     
                }

                 $data.='<tr>';
                 $data.='<td align="right" colspan=2 >Total</td>';
                $data.='<td align="left" >'. $tot_stu.'</td>';
                $data.='<td align="left" >'.$tot_script.'</td>';
                $data.='<td align="left" colspan=1 >'. $tot_stuch.'</td>';
                $data.='<td align="left" colspan=1 >'. $tot_scriptch.'</td>';
               $data.='<td align="left" >'. $tot_stuar.'</td>';
                $data.='<td align="left" >'.$tot_scriptar.'</td>';
                $data.='<td align="left" colspan=1 >'. $tot_stuchar.'</td>';
                $data.='<td align="left" colspan=1 >'. $tot_scriptchar.'</td>';
                $data.='</tr>'; 

                 $data.='<tr>';
                 $data.='<td align="right" colspan=2 >Total Student</td>';
                $data.='<td align="left" colspan=2>'. $tot_stuu.'</td>';
                $data.='<td align="right" colspan=2 >Total script</td>';
                $data.='<td align="left"  colspan=2>'.$tot_scriptu.'</td>';
                $data.='</tr>'; 

              }
              else
              {

                $data.='<tr>
                        <th>SNO</th>     
                        <th>PROGRAMME</th>
                        <th>No Of STUDENT</th>
                        <th>No Of SCRIPT</th>
                        <th>No Of STUDENT Changes</th>
                        <th>No Of SCRIPT Changes</th>
                       ';

                foreach($revaluation as $revaluation1)
                {

                  

                    $data.='<tr>';

                     $reg_numlistu = Yii::$app->db->createCommand(" select  count(DISTINCT student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' ")->queryScalar();

                      $scriptu = Yii::$app->db->createCommand(" select  count(student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."' and A.reval_status='YES' ")->queryScalar();
                                               

                      $stu_changes = Yii::$app->db->createCommand(" select  count(DISTINCT A.student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     JOIN coe_mark_entry_master_reval as D ON D.subject_map_id=A.subject_map_id AND D.student_map_id=A.student_map_id AND A.year=D.year AND A.month=D.month
                     JOIN coe_mark_entry_master as E ON E.subject_map_id=A.subject_map_id AND E.student_map_id=A.student_map_id AND A.year=E.year AND A.month=E.month
                     WHERE D.grade_name!=E.grade_name AND A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' ")->queryScalar();

                       $script_changes = Yii::$app->db->createCommand(" select  count(A.student_map_id)  from
                     coe_revaluation A  
                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                    JOIN coe_bat_deg_reg as G ON G.coe_bat_deg_reg_id=B.batch_mapping_id 
                     JOIN coe_programme as H ON H.coe_programme_id=G.coe_programme_id
                     JOIN coe_mark_entry_master_reval as D ON D.subject_map_id=A.subject_map_id AND D.student_map_id=A.student_map_id AND A.year=D.year AND A.month=D.month
                     JOIN coe_mark_entry_master as E ON E.subject_map_id=A.subject_map_id AND E.student_map_id=A.student_map_id AND A.year=E.year AND A.month=E.month
                     WHERE D.grade_name!=E.grade_name AND A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND H.programme_code='".$revaluation1['programme_code']."'  and A.reval_status='YES' ")->queryScalar();

                        $tot_stuu= $tot_stuu+$reg_numlistu;

                      $tot_scriptu= $tot_scriptu+$scriptu;

                      // $tot_stuu= $tot_stuu+$reg_numlistu;

                     // $tot_scriptu= $tot_scriptu+$scriptu;


                      $tot_stuch= $tot_stuch+$stu_changes;

                      $tot_scriptch= $tot_scriptch+$script_changes;

                     
                        $data.='<td align="left" >'.$sn.'</td>';
                        $data.='<td align="left" >'. $revaluation1['programme_name'].'</td>';
                        $data.='<td align="left" >'.$reg_numlistu.'</td>';
                       $data.='<td align="left" >'.$scriptu.'</td>';
                       $data.='<td align="left" >'.$stu_changes.'</td>';
                       $data.='<td align="left" >'.$script_changes.'</td>';
                        $data.='</tr>';  
                            
                     $sn++;
                   
     
                }

               

                 $data.='<tr>';
                 $data.='<td align="right" colspan=2 >Total </td>';
                $data.='<td align="left" colspan=1 >'. $tot_stuu.'</td>';
                $data.='<td align="left"  colspan=1>'.$tot_scriptu.'</td>';
                $data.='<td align="left" colspan=1 >'. $tot_stuch.'</td>';
                $data.='<td align="left" colspan=1 >'. $tot_scriptch.'</td>';
                $data.='</tr>'; 
              }

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
                     WHERE A.year='" .  $exam_year . "' and A.month='" . $exam_month . "' AND A.subject_map_id='".$revaluation1['subject_map_id']."' and A.reval_status='YES' ")->queryScalar();

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
                 $data.='<td align="right" colspan=3 >Total</td>';
                //$data.='<td align="left" >'. $tot_stu.'</td>';
                $data.='<td align="left" >'.$tot_script.'</td>';
               
                $data.='</tr>'; 
            }

   
    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['revaluation_print'])){ unset($_SESSION['revaluation_print']);}
    $_SESSION['revaluation_print'] = $data;
    echo $data;


    }
?>