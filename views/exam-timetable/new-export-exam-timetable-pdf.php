<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\models\Categorytype;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\grid\GridView;
use yii\db\Query;
echo Dialog::widget();
?>

<?php 
if(isset($new_export_exam_time)) 
{ 
  require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
 $semester_array = ['1'=>'I','2'=>'II','3'=>'III','4'=>'IV','5'=>'V','6'=>'VI','7'=>'VII','8'=>'VIII','9'=>'IX','10'=>'X'];
  /* 
  *   Already Defined Variables from the above included file
  *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
  *   use these variables for application
  *   use $file_content_available="Yes" for Content Status of the Organisation
  */
?>
<br /><br />
<div class="col-xs-4 left-padding">
<?php echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('new-print-examtimetable','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'btn btn-warning', 'style'=>'color:#fff')); 
      ?>
    <?php echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('new-excel-examtimetable','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff')); ?>

</div>
</br></br>
<?php
if($file_content_available=="Yes")
  {
$programme_name="";
$new_stu_flag="";
$exam_type=""; //echo $sem_verify."==".$get_sem; exit;
if($sem_verify==$get_sem){ $exam_type="REGULAR";}else{$exam_type="ARREAR";}
if($month_name=='Oct/Nov'){$month_name='Nov/Dec';}else{$month_name=$month_name;}
  $degreeNames = implode( '/',array_column($degree_code, 'degree_code'));
  foreach($new_export_exam_time as $rows) 

  { 
if($programme_name!=$rows['programme_name'])
{
$new_stu_flag=$new_stu_flag + 1;
if($new_stu_flag > 1) {
        //print_r($new_stu_flag);
        $html = $header .$body.$footer; 
        $print_stu_data .= $html;
        $header = "";
        $body ="";
        $footer = "";
        $new_stu_flag = 1;
}   
$countofColSpan = count($count_1);
  $previous_reg_number = "";
  $html = "";
  $header = "";
  $body ="";
  $footer = "";
  $new_stu_flag=0;
  $old_sub_code='';
  $print_stu_data="";
  $exam="";


 $footer='
  <table width="100%" class="table footertabel" align="center"border="0">
    <tr>
      <td align="center" colspan="8" style="font-size: 12px;"><br>
       <b>Note: Discrepancy if any, may be brought to notice of the Controller of Examinations<b>
        <br /><br /></td>
</tr><tr>
      <td style="text-align: left;font-size: 12px;" colspan="4" ><br><br>
        <b>'.strtoupper("Controller Of Examinations").'</b>
      </td>
        <td style="text-align: right; margin-right: 5px;font-size: 12px;"   colspan="4" ><br><br>
        <b>'.strtoupper("Principal").'</b>
      </td>
    </tr>
   
    </table>';

   $body .='
    <table width="100%" class="table" cellpadding="2" align="center" cellspacing="2" border="0">
    <tr>
         
      <td colspan="'.($countofColSpan+1).'" align="right">  
        <h6><b>REGULATIONS '.$rows['regulation_year'].'</b></h6> 
      </td>
       </tr>
       <tr>
         <td align="center" style="border-right:0px"> 
            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" class="img-responsive" alt="College Logo">
         </td>
        <td colspan="'.($countofColSpan-1).'" align="center" >
          <h5><b>'.strtoupper($org_name).'</b></h5>
          <h6>(AN AUTONOMOUS INSTITUTION, '.strtoupper($org_tagline).', '.strtoupper($org_address).' )</h6>
           <h6><b> OFFICE OF THE CONTROLLER OF EXAMINATIONS</b></h6>
          <h5><b>END SEMESTER '.$exam_type.' EXAMINATIONS TIMETABLE- '.strtoupper($month_name).' '.$year.' <br>'.$semester_array[$sem_verify].' SEMESTER'.'  '.$degree_type.' '.$degreeNames.' (BATCH - '.$batch_name.')</b></h5>
          <h6><b> Timings : FN:- 09 : 45 AM to  12 : 45 PM  AN:- 01 : 15 PM to  4 : 15 PM</b></h6>
        </td>          
     
      <td align="center">  
        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
      </td>
       </tr>
    </table>
     
   
     ';
$exam .=' <table width="100%" border="1" class="table">
<tr>
   <td height=40px  align="center" ><b> DATE & SESSION </b></td>';
   $coure_name="";
 foreach ($count_1 as $key => $value) {
  if($value['programme_name']=='COURSE WORK'){$coure_name='PHD ('.$value['programme_name'].')'; }else{$coure_name=$value['programme_name'];}
     $exam.= '<td height=100px  align="center" ><b>'.strtoupper($coure_name).'   </b></td>';
               }
$exam .='</tr>'; 

$ii=1;
$loop=0;
foreach ($query_3 as $key => $date) 
{ 



  $session = Categorytype::findOne($date['exam_session']);
  $day = date('l', strtotime($date['exam_date']));
  $exam .='<tr><td align="center"><b>' .date("d/m/Y", strtotime($date['exam_date'])).'<br>'.$session['category_type'].'<br>'.$day.'</b></td>';

  foreach ($count_1 as $key => $vals) 
  {
    $exam_session = isset($_POST['ExamTimetable']['qp_code']) && !empty($_POST['ExamTimetable']['qp_code'])?$_POST['ExamTimetable']['qp_code']:$date['exam_session'];
      $query_count = new Query(); 
      $query_count->select(['subject_code','subject_name'])
          ->from('coe_exam_timetable k')  // If students are available then only data will be exported
          ->join('JOIN','coe_subjects_mapping e','e.coe_subjects_mapping_id = k.subject_mapping_id')
          ->join('JOIN','coe_bat_deg_reg d','d.coe_bat_deg_reg_id = e.batch_mapping_id') 
          ->join('JOIN','coe_degree h','h.coe_degree_id = d.coe_degree_id')
          ->join('JOIN','coe_batch i','i.coe_batch_id = d.coe_batch_id')
          ->join('JOIN','coe_programme g','g.coe_programme_id = d.coe_programme_id')
          ->join('JOIN','coe_subjects a','a.coe_subjects_id = e.subject_id')
          ->Where(['k.exam_year'=>$_POST['ExamTimetable']['exam_year'],'k.exam_month'=>$_POST['ExamTimetable']['exam_month'],'i.coe_batch_id'=>$_POST['ExamTimetable']['coe_batch_id'],'h.degree_type'=>$_POST['Degree']['degree_type'],'g.coe_programme_id'=>$vals['coe_programme_id'],'exam_date'=>$date['exam_date'],'d.coe_programme_id'=>$vals['coe_programme_id'],'exam_session'=>$exam_session,'e.semester'=>$sem_verify]);
      $query_count->orderBy('subject_code,subject_name');
      $getSubInfo = $query_count->createCommand()->queryAll();
    //print_r($getSubInfo);exit;
      $show_data ='';
      if(count($getSubInfo)>0 && !empty($getSubInfo))
      {
        foreach ($getSubInfo as $key => $bringSubs) 
        {
          $show_data.=" <b>".$bringSubs['subject_code']." - ".strtoupper($bringSubs['subject_name'])."</b>\r\n";
        }  
        //$show_data = trim($show_data,'<br />');      
      }
      else
      {
        $show_data ='-';
      }
      $exam.='<td height=100px align="center" ><p>' .nl2br($show_data).'</p></td>';
        
  }

$exam.='</tr>';
  // if($ii==2 && $loop==0)
  // {
  //   $exam .='</table>'.$footer.'<pagebreak>';
  //   $exam .=' <table width="100%" border="1" class="table">';
  //   //$ii=0;
  //    $ii=1;
  //    $loop++;
  // }
  // else if($ii==4 && $loop!=0)
  // {
  //   $exam .='</table>'.$footer.'<pagebreak>';
  //   $exam .=' <table width="100%" border="1" class="table">';
  //   //$ii=0;
  //    $ii=1;
  //    $loop++;
  // }
  // else
  // {
  //   $ii++;

  // }
}
  }
}// Foreach Ends Here

     $html = $body.$exam.' </table>'.$footer;
    $print_stu_data .=$html;
    $_SESSION['exam_footer'] ='';
     $_SESSION['exam_footer'] = $footer;
  
      if(isset($_SESSION['new_export_exam_time_data'])){ unset($_SESSION['new_export_exam_time_data']);}
      $_SESSION['new_export_exam_time_data'] = $print_stu_data;
      echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-1" >&nbsp;</div><div class="col-lg-9" >'.$print_stu_data.'</div><div class="col-lg-1" >&nbsp;</div></div></div></div></div>'; 
         }// If Institute Information Exists in the file
    } // Isset of Print Halls  
?>

