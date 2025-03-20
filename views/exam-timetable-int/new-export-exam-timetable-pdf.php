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
$exam_type="";
if($month_name=='Oct/Nov'){$month_name='Oct/Nov';}else{$month_name=$month_name;}
  
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

  $end_batchname='';
  if($_POST['ExamTimetableInt']['degree_type']=='B.E')
  {
    $end_batchname=$batch_name+4;
  }
  else
  {
      $end_batchname=$batch_name+2;
  }

  $_SESSION['intexambatch_name'] = '(BATCH: '.$batch_name.' - '.$end_batchname.')';
  //echo $countofColSpan; exit;
     $body .='
    <table width="100%" class="table" cellpadding="2" align="center" cellspacing="2" border="1">
    <tr>
      

       <td align="left" colspan=2 style="border-right:0px;">  
        <h6><b>REGULATIONS '.$rows['regulation_year'].'</b></h6> 
      </td>

      <td align="right" style="border-left:0px;">  
        <h6><b>BATCH: '.$batch_name.' - '.$end_batchname.'</b></h6> 
      </td>

       </tr>
       <tr>
         <td align="center" style="border-right:0px"> 
            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" class="img-responsive" alt="College Logo">
         </td>
        <td align="center" >
          <h5><b>'.strtoupper($org_name).'</b></h5>
          <h6>('.strtoupper($org_tagline).') <br>'.strtoupper($org_address).'</h6>
           <h6><b> OFFICE OF THE CONTROLLER OF EXAMINATIONS</b></h6>
          <h5><b>INTERNAL EXAMINATIONS TIMETABLE- '.strtoupper($month_name).' '.$year.' </b></h5>
          <h6><b> INTERNAL EXAM: '.$cia.'</b></h6>
        </td>          
     
      <td align="center">  
        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
      </td>
       </tr>
    </table>
     
   
     ';
$body .=' <table width="100%" border="1" class="table" cellpadding="2" align="center" cellspacing="2" style="font-size: 9px; padding:3px; margin-bottom:0px !important;" >
<tr>
   <td height=40px  align="center" ><b> DATE & SESSION </b></td>';
 foreach ($count_1 as $key => $value) {
  # code...
     $body.= '<td height=40px  align="center" ><b>'.$value['degree_code'].' '.$value['programme_name'].'   </b></td>';
               }
$body .='</tr>'
; 



foreach ($query_3 as $key => $date) 
{ 

  $session = Categorytype::findOne($date['exam_session']);
  $time_slot = Categorytype::findOne($date['time_slot']);
  $exam .='<tr><td align="center"><b>' .date("d/m/Y", strtotime($date['exam_date'])).'<br>'.$session['description'].'<br>'.$time_slot['description'].'</b></td>';

  foreach ($count_1 as $key => $vals) 
  {
    $exam_session = $date['exam_session'];
      $query_count = new Query(); 
      $query_count->select(['subject_code','subject_name'])
          ->from('coe_exam_timetable_int k')  // If students are available then only data will be exported
          ->join('JOIN','coe_subjects_mapping e','e.coe_subjects_mapping_id = k.subject_mapping_id')
          ->join('JOIN','coe_bat_deg_reg d','d.coe_bat_deg_reg_id = e.batch_mapping_id') 
          ->join('JOIN','coe_degree h','h.coe_degree_id = d.coe_degree_id')
          ->join('JOIN','coe_batch i','i.coe_batch_id = d.coe_batch_id')
          ->join('JOIN','coe_programme g','g.coe_programme_id = d.coe_programme_id')
          ->join('JOIN','coe_subjects a','a.coe_subjects_id = e.subject_id')
          ->Where(['k.exam_year'=>$_POST['ExamTimetableInt']['exam_year'],'k.exam_month'=>$_POST['ExamTimetableInt']['exam_month'],'g.coe_programme_id'=>$vals['coe_programme_id'],'exam_date'=>$date['exam_date'],'d.coe_programme_id'=>$vals['coe_programme_id'],'exam_session'=>$exam_session,'k.time_slot'=>$date['time_slot'],'k.internal_number'=>$_POST['ExamTimetableInt']['internal_number']]);

      if(isset($_POST['ExamTimetableInt']['coe_batch_id']) && !empty($_POST['ExamTimetableInt']['coe_batch_id']))
      {
           $query_count->andWhere(['d.coe_batch_id'=>$_POST['ExamTimetableInt']['coe_batch_id'],]);
      }   

      if(isset($_POST['ExamTimetableInt']['degree_type']) && !empty($_POST['ExamTimetableInt']['degree_type']))
      {
           if($_POST['ExamTimetableInt']['degree_type']=='B.E')
            {
                $query_count->andWhere(['h.degree_type'=>'UG']);
            }
            else
            {
               $query_count->andWhere(['h.degree_code'=>$_POST['ExamTimetableInt']['degree_type']]);
            }
      }
      $query_count->orderBy('subject_code,subject_name');
      $getSubInfo = $query_count->createCommand()->queryAll();
    //print_r($getSubInfo);exit;
      $show_data ='';
      if(count($getSubInfo)>0 && !empty($getSubInfo))
      {
        foreach ($getSubInfo as $key => $bringSubs) 
        {
          $show_data .='<b>'.$bringSubs['subject_code'].'</b>-'.strtoupper($bringSubs['subject_name']).'<br />';
        }  
        //$show_data = trim($show_data,'<br />');      
      }
      else
      {
        $show_data ='-';
      }
      $exam.='<td align="center" >' .$show_data.'</td>';
        
  }
$exam.='</tr>';
  # code...
}
  }
}// Foreach Ends Here

 $footer .=' </table>
  <table width="100%" class="table" cellpadding="2" align="center" cellspacing="2" border="0">
    <tr>
      <td align="center" colspan="8" style="font-size: 10px;">
        Note: Discrepancy if any, may be brought to notice of the Controller of Examinations
        <br /><br /></td>
      </tr><tr>
      <td style="text-align: left; margin-right: 5px; font-size: 12px;" colspan="4" ><br>
        <b>'.strtoupper("Controller of Examinations").'</b>
      </td>
        <td style="text-align: right; margin-right: 5px; font-size: 12px;"   colspan="4" ><br>
        <b>'.strtoupper("Principal").'</b>
      </td>
    </tr>
   
    </table>';
     $html = $body.$exam.$footer;
    $print_stu_data .=$html;
  
      if(isset($_SESSION['new_export_exam_time_data'])){ unset($_SESSION['new_export_exam_time_data']);}
      $_SESSION['new_export_exam_time_data'] = $print_stu_data;
      echo '<div class="box box-primary"><div class="box-body"><div class="row" ><div class="col-xs-12" ><div class="col-lg-1" >&nbsp;</div><div class="col-lg-9" >'.$print_stu_data.'</div><div class="col-lg-1" >&nbsp;</div></div></div></div></div>'; 
         }// If Institute Information Exists in the file
    } // Isset of Print Halls  
?>

