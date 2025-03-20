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
$semester_name = ConfigUtilities::getSemesterName($_POST['ExamTimetable']['coe_batch_id']);
  
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
   $body .='
    <table width="100%" class="table" cellpadding="2" align="center" cellspacing="2">
       <tr>
         <td align="center"> 
            <img width="100" height="100" src="'.Yii::getAlias("@web").'/images/skct_logo.png" class="img-responsive" alt="College Logo">
         </td>
        <td colspan="'.($countofColSpan-1).'" align="center">
          <center> <h1>'.strtoupper($org_name).'</h1></center>
          <center class="tag_line"> <h4>( '.strtoupper($org_tagline).' )</h4></center>
        <center class="tag_line"> <h4>( '.strtoupper($org_address).' )</h4></center>
        <center class="tagline" > <h1> END '.$semester_name.' REGULAR EXAMINATIONS - '.$month_name.' '.$year.'</h1></center>
         <center class="tagline" ><h1> '.$semester_name.' '.$sem_verify.' '.$degree_type.' '.$rows['degree_code'].'</h1></center>
          <center class="tagline" ><h1> (BATCH - '.$batch_name.')</h1></center>
          <br />
          <center class="tagline"  align="center"><h4> Timings : FN:- 09 : 30 AM to  12 : 30 PM  AN:- 01 : 30 PM to  4 : 30 PM</h4></center><b>
        </td>          
     
      <td align="center">  
        <h4>Regulation </br> '.$rows['regulation_year'].'
        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2"> </h4>
    </td>
       </tr>
    
     
   
     ';
$body .='<tr>
   <th height=40px  align="center" ><b> DATE & DAY </b></th>';
 foreach ($count_1 as $key => $value) {
  # code...
     $body.= '<th height=40px  align="center" ><b>'.$value['programme_name'].'   </b></th>';
               }
$body .='</tr>'
; 



foreach ($query_3 as $key => $date) 
{ 

$session = Categorytype::findOne($date['exam_session']);
$exam .='<tr><td>' .date("d/m/y", strtotime($date['exam_date'])).'<br>'.$session['description'].'</td>';

  foreach ($count_1 as $key => $vals) 
  {
      $query_count = new Query(); 
      $query_count->select(['subject_code','subject_name'])
          ->from('coe_exam_timetable k')  // If students are available then only data will be exported
          ->join('JOIN','coe_subjects_mapping e','e.coe_subjects_mapping_id = k.subject_mapping_id')
          ->join('JOIN','coe_bat_deg_reg d','d.coe_bat_deg_reg_id = e.batch_mapping_id') 
          ->join('JOIN','coe_degree h','h.coe_degree_id = d.coe_degree_id')
          ->join('JOIN','coe_batch i','i.coe_batch_id = d.coe_batch_id')
          ->join('JOIN','coe_programme g','g.coe_programme_id = d.coe_programme_id')
          ->join('JOIN','coe_subjects a','a.coe_subjects_id = e.subject_id')
          ->Where(['k.exam_year'=>$_POST['ExamTimetable']['exam_year'],'k.exam_month'=>$_POST['ExamTimetable']['exam_month'],'i.coe_batch_id'=>$_POST['ExamTimetable']['coe_batch_id'],'h.degree_type'=>$_POST['Degree']['degree_type'],'g.coe_programme_id'=>$vals['coe_programme_id'],'exam_date'=>$date['exam_date'],'d.coe_programme_id'=>$vals['coe_programme_id']]);

        if(isset($_POST['ExamTimetable']['qp_code']) && !empty($_POST['ExamTimetable']['qp_code']))
        {
            $query_count->andWhere(['=','exam_session',$exam_session]);   
        }
      $query_count->orderBy('subject_code,subject_name');
      $getSubInfo = $query_count->createCommand()->queryAll();

      $show_data ='';
      if(count($getSubInfo)>0 && !empty($getSubInfo))
      {
        foreach ($getSubInfo as $key => $bringSubs) 
        {
          $show_data .=$bringSubs['subject_code'].'-'.$bringSubs['subject_name'].'<br />';
        }  
        $show_data = trim($show_data,'<br />');      
      }
      else
      {
        $show_data ='-';
      }
      $exam.='<td>' .$show_data.'</td>';
        
  }
$exam.='</tr>';
  # code...
}
  }
}// Foreach Ends Here

 $footer .='
    <tr height="100px"  >
      <td align="center" style="height: 100px;"   height="100px" colspan="8" ><br><b>
        Note: Discrepancy ,if any, may be brought to notice of the Controller Of Examinations</b>
        <br /><br /><br /><br /><br /> </td>
</tr><tr>
        <td style="height: 50px; text-align: right; margin-right: 5px;"  height="100px" colspan="8" ><br>
        <b>'.strtoupper("Controller Of Examinations").'</b>
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

