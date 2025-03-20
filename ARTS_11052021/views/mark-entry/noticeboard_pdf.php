<?php
use yii\helpers\Html;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Categorytype;
use app\models\Subjects;
use yii\db\Query;

use kartik\dialog\Dialog;
echo Dialog::widget();
?>
<?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

<?php
    if(isset($noticeboard_copy) && !empty($noticeboard_copy))
    {

?>
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-noticeboardcopy','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/noticeboard-copy-pdf'], [
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
    $semester_name = ConfigUtilities::getSemesterName($_POST['bat_map_val']);

    $data_1 ='<div class="box-body table-responsive"><table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" >';
    $data ='<table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" id = "student_import_results">';                 
    $data_1.='<tr>
               
                <td colspan=17 align="center"> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    
                    <center>'.$org_tagline.'</center> 
                </td>
              
            </tr>';
            $data_1.='<tr><td colspan="17" align="center"><h5 align="center">End '.$semester_name.' Examination Notice Board Copy - '.$month.' '.$year.'</h5></td></tr>';
            $data_1.='<tr><td colspan="17" align="center"><h5 align="center">Batch - '.$batch_name.' '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' - '.$degree_name.'</h5></td></tr></table>';

            $sem = ConfigUtilities::semCaluclation($_POST['year'],$_POST['month'],$_POST['bat_map_val']);
            //$total_subs = Yii::$app->db->createCommand("select a.subject_map_id from coe_mark_entry_master a,coe_subjects_mapping b,coe_subjects c where b.coe_subjects_mapping_id=a.subject_map_id and b.subject_id=c.coe_subjects_id and b.batch_mapping_id='".$_POST['bat_map_val']."' and a.year='".$_POST['year']."' and a.month='".$_POST['month']."' and c.semester='".$sem."'group by a.subject_map_id")->queryAll(); 
            $tot_subs_co = count($noticeboard_copy)-1;
            $mark_type = $noticeboard_copy[$tot_subs_co]['mark_type'];
            $total_subs_max_check = Yii::$app->db->createCommand("select count(B.subject_map_id) as max from coe_subjects_mapping as A JOIN coe_mark_entry_master as B ON B.subject_map_id=A.coe_subjects_mapping_id JOIN coe_subjects as C ON C.coe_subjects_id=A.subject_id where batch_mapping_id='".$_POST['bat_map_val']."' and B.year='".$_POST['year']."' and B.month='".$_POST['month']."' and mark_type='".$mark_type."' group by B.student_map_id,A.semester")->queryAll();

            $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($total_subs_max_check));
            foreach($it as $v) {
                    $a[] = $v;
            }
            
            $total_subs_max = max($a); 
            $sem_naaame = $semester_name=="SEMESTER"?"SEM":'TRISEM';

            $data.='<thead><tr>
                        <th>S.No</th>                                            
                        <th>Register Number</th>
                        <th>'.$sem_naaame.'</th>';
                        
            for ($i=0; $i <$total_subs_max ; $i++) 
            { 
                   $data .='<th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).'</th>                      
                        <th>G</th>';
            }
            $data .='<th>GPA</th><th>CGPA</th></tr></thead>';

            $old_reg_num='';
            $cg_reg_num='';
            $sg_reg_num='';
            $reg_num='';
            $prev_reg_num = '';
            $sub_loop = '';
            $curr_reg_num = '';
            $sn=1;
            $first_reg_num = 0;
            $gpa_calc ='';
            $cpga ='';
            $insert_fitrst_data='';
            $data.='<tr>';
            $sub_print = 0;
            foreach($noticeboard_copy as $notice)
            {   
                $total_subs = Yii::$app->db->createCommand("select count(DISTINCT subject_map_id) as count from coe_subjects_mapping as A JOIN coe_mark_entry_master as B ON B.subject_map_id=A.coe_subjects_mapping_id where batch_mapping_id='".$_POST['bat_map_val']."' and B.year='".$_POST['year']."' and B.month='".$_POST['month']."' and student_map_id='".$notice['student_map_id']."'")->queryScalar();    
                $sem_verify = ConfigUtilities::SemCaluclation($_POST['year'],$_POST['month'],$_POST['bat_map_val']);
                if($first_reg_num==0)
                {
                    $cgpa_calc = ConfigUtilities::getCgpaCaluclation($_POST['year'],$_POST['month'],$_POST['bat_map_val'],$notice['student_map_id'],$sem_verify);

                    $insert_fitrst_data .= '<td>'. $cgpa_calc['gpa'] .'</td>'.'<td>'. $cgpa_calc['cgpa'] .'</td> </tr><tr>';

                    $first_reg_num = 5;

                }
                

                if($old_reg_num!=$notice['register_number'])
                {
                    
                    if($old_reg_num!="")
                    {

                        if($first_reg_num == 5)
                        {
                            if($sub_loop!=$total_subs_max)
                            {
                                $data .='<td colspan="'.(($total_subs_max-$sub_loop)*2).'"> &nbsp; </td>';
                            }
                            $data .=$insert_fitrst_data; 
                            $first_reg_num =10;
                            
                        }
                        else
                        {

                            if($sub_loop!=$total_subs_max)
                            {
                                $data .='<td colspan="'.(($total_subs_max-$sub_loop)*2).'"> &nbsp; </td>';
                            }
                            
                            $data .='<td>'. $cgpa_calc['gpa'] .'</td>';
                            $data .='<td>'. $cgpa_calc['cgpa'] .'</td>';
                            $data.= '</tr><tr>';

                        }    
                        $sub_loop = 0;                    
                        $sub_print = 0;
                        
                    }
                    $cgpa_calc = ConfigUtilities::getCgpaCaluclation($_POST['year'],$_POST['month'],$_POST['bat_map_val'],$notice['student_map_id'],$sem_verify);

                    $old_reg_num = $notice['register_number'];
                    $data.='<td align="left" >'.$sn.'</td>';
                    $data.='<td align="left" width="120"  >'.$old_reg_num.'</td>';
                    $sn++;
                    $old_sem = '';
                    
                }
                    
                 if($old_sem!=$notice['semester'])
                {
                    if($old_sem!="")
                    {
                        $data .='</tr><tr><td align="left" >&nbsp;</td><td align="left"  >&nbsp;</td>';
                    }
                    $sub_loop = 0;
                    $data.='<td align="left"  >'.$notice['semester'].'</td>';
                    $old_sem=$notice['semester'];
                }
                


                $grade_name = $notice['CIA_max']==0 && $notice['ESE_max']==0 ? "<b>CO</b>" : $notice['grade_name'];
                $grade_name = $notice['grade_name']== 'WD' || $notice['withheld']== 'wd' ? "<b>W</b>" : $grade_name;
                $grade_name = $notice['withheld']== 'W' || $notice['withheld']== 'w' ? "<b>WH</b>" : $grade_name;
                
                $sub_print = isset($notice['subject_code']) && !empty($notice['subject_code'])? $sub_print+1:$sub_print+0;

                $sub_loop = isset($notice['subject_code']) && !empty($notice['subject_code'])? $sub_loop+1:$sub_loop+1;
                
                $data.='<td align="left"  >'.$notice['subject_code'].'</td><td align="left"  >'.strtoupper($grade_name).'</td>';
                $last_sub_id = $notice['student_map_id'];
            }
            if($sub_loop!=$total_subs_max)
            {
                $data .='<td colspan="'.(($total_subs_max-$sub_loop)*2).'"> &nbsp; </td>';
            }
            $cgpa_calc_2 = ConfigUtilities::getCgpaCaluclation($_POST['year'],$_POST['month'],$_POST['bat_map_val'],$last_sub_id,$sem_verify);

            $data .='<td>'. $cgpa_calc_2['gpa'] .'</td>';
            $data .='<td>'. $cgpa_calc_2['cgpa'] .'</td>';
                

    $data.='</tbody>';        
    $data.='</table>
    <h4> Grade CO: <b>COMPLETED</b></h4></div>';
    
    if(isset($_SESSION['noticeboard_print'])){ unset($_SESSION['noticeboard_print']);}
    $_SESSION['noticeboard_print'] = $data_1.$data;
    echo $data_1.$data;


    }

     
?>


<?php 

$this->registerJsFile(    
    '@web/style/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/style/bower_components/datatables.net/js/jquery.dataTables.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);



$this->registerJs(<<<JS
    $(function () {
    $('#student_import_results').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
       'scrollY': '300',
       "scrollX": true,
       "responsive": "true",
       "pageLength": "200",
       
       
    })
  })
JS
);

?>
