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
    $data.='<tr>
                <td colspan=11  align="center"> 
                    <center><b><font size="4px">'.strtoupper($org_name).'</font></b></center>
                    <center>'.strtoupper($org_address).'</center>
                    
                    <center>'.strtoupper($org_tagline).'</center> 
                </td>
               
            </tr>';
            $data.='<tr><td colspan="11" align="center"><h5 align="center">'.strtoupper('End '.$semester_name.' Examination Notice Board Copy - '.$month.' '.$year).'</h5> <h5 align="center">'.strtoupper('Batch - '.$batch_name.' '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' - '.$degree_name).'</h5></td></tr>';
           

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
                        <th>SNO</th>                                            
                        <th>REGISTER NUMBER</th>
                        <th>'.$sem_naaame.'</th>';
            $data .='<th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE</th>
            <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME </th>
            <th>CIA</th>
            <th>ESE</th>
            <th>TOTAL</th>
            <th>RESULT</th>';         
          
            $data .='</tr></thead>';

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
            
            $sub_print = 0;
            
            $result_array = ['ABSENT','FAIL'];
            foreach($noticeboard_copy as $notice)
            {      
                $sem_verify = ConfigUtilities::SemCaluclation($_POST['year'],$_POST['month'],$_POST['bat_map_val']);
                $res_duisplay = $notice['result']=='FAIL' ?'RE-APPEAR':$notice['result'];
                $res_duisplay = $notice['withheld']=='W' || $notice['withheld']=='w' ? 'RE-APPEAR':$res_duisplay;
                $ese_disp = ($notice['withheld']=='W' || $notice['withheld']=='w') ? '-':$notice['ESE'];
                $total_disp = ($notice['withheld']=='W' || $notice['withheld']=='w') ? $notice['CIA']:$notice['total'];
                if($old_reg_num!=$notice['register_number'])
                {   
                    
                    $old_reg_num = $notice['register_number'];                    
                    $change_background = ($notice['result']=='FAIL' || $ese_disp=='-')  ? 'style="background: rgba(179, 0, 0,0.8); color: #FFF;"' : '';
                    $data.='<tr '.$change_background.' >';
                    $data.='<td align="left" >'.$sn.'</td>';
                    $data.='<td align="left" width="120"  >'.$old_reg_num.'</td>';
                    $data.='<td align="left"  >'.$notice['semester'].'</td>';
                    $data.='<td align="left"  >'.$notice['subject_code'].'</td>
                            <td align="left"  >'.$notice['subject_name'].'</td>
                            <td align="left"  >'.$notice['CIA'].'</td>
                            <td align="left"  >'.$ese_disp.'</td>
                            <td align="left"  >'.$total_disp.'</td>
                            <td align="left"  >'.$res_duisplay.'</td>';
                    $sn++;
                    $old_sem = '';
                    
                }
                else
                {
                   
                    $old_reg_num = $notice['register_number'];

                   $change_background = ($notice['result']=='FAIL' || $ese_disp=='-') ? 'style="background: rgba(179, 0, 0,0.8); color: #FFF;"' : '';
                    $data.='<tr '.$change_background.' >';
                    $data.='<td align="left" colspan=2  >&nbsp;</td>
                            <td align="left"  >'.$notice['semester'].'</td>';
                    $data.='<td align="left"  >'.$notice['subject_code'].'</td>
                            <td align="left"  >'.$notice['subject_name'].'</td>
                            <td align="left"  >'.$notice['CIA'].'</td>
                            <td align="left"  >'.$ese_disp.'</td>
                            <td align="left"  >'.$total_disp.'</td>
                            <td align="left"  >'.$res_duisplay.'</td>';
                }
                $last_sub_id = $notice['student_map_id'];
            }
           
                

    $data.='</tbody>';        
    $data.='</table>';
    
    if(isset($_SESSION['noticeboard_print'])){ unset($_SESSION['noticeboard_print']);}
    $_SESSION['noticeboard_print'] = $data;
    echo $data_1.$data.'<h4> Grade CO: <b>COMPLETED</b></h4></div>';

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
