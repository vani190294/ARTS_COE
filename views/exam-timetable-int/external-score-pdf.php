<?php 
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\Html;
use app\models\ExamTimetable;
use app\models\SubjectsMapping;
?>
<div class="row">
<div class="col-12">
    <div class="col-lg-2 col-sm-2">
    </div>
    <div class="col-lg-8 col-sm-8">
      <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php
if(isset($external_score) && !empty($external_score)){
echo Html::a('<i class="fa fa-file-pdf-o"></i> Export Pdf', ['/exam-timetable/exportexternal'], [
                'class'=>'pull-right btn btn-success', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
            ]); 
$exam_type_g = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Arrear%'")->queryScalar();

foreach ($external_score as $key => $value) 
{
          $course_name =  strtoupper($value['degree_name']);
          $batch_name = strtoupper($value['batch_name']);
          $year = strtoupper($value['year']);
          $mark_month = strtoupper($value['month']);
          $subject_code = strtoupper($value['subject_code']);
          $semester = strtoupper($value['semester']);
          $degree_name = strtoupper($value['degree_name']);
          $subject_name = strtoupper($value['subject_name']);
          $qp_code = strtoupper($value['qp_code']);
          $max = $value['ESE_max']+$value['CIA_max'] ;
          $course_batch_map_id = $value['course_batch_mapping_id'];
}  
$html = "";
$semester_name = ConfigUtilities::getSemesterName($course_batch_map_id);
require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
$table_open ="<table border=1 width='100%' >";
$table_close = "</table>";
$print_data_again =$table_open.'
<tr>
    <td> 
        <img class="img-responsive"  height="100" width="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
    </td>
    <td colspan=8 align="center"> 
        <center><b><font size="4px">'.$org_name.'</font></b></center>
        <center>'.$org_address.'</center>
        
        <center>'.$org_tagline.'</center> 
    </td>
    <td align="right">  
        <img class="img-responsive"  height="100" width="100" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
    </td>
</tr>
 <tr>
   <th align=center colspan="10">EXTERNAL SCORE CARD : '.$course_name.' / '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).' : '.$batch_name.'</th>
 </tr>
 <tr>
   <th align=center  colspan="8"> YEAR '.$year.' /  MONTH '.$mark_month.'</th>
   <th>DATE</th>
   <th></th>
 </tr>
 <tr>
   <th colspan=3>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).'</th>
   <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </th>
   <th colspan=3>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME </th>
   <th>QP CODE  </th>
   <th>'.$semester_name.'</th>
   <th>MAX MARKS</th>
 </tr>
  <tr>
   <td colspan=3>'.$degree_name.'</td>
   <td>'.$subject_code.'</td>
   <td colspan=3>'.$subject_name.'</td>
   <td>'.$qp_code.'</td>
   <td>'.$semester.'</td>
   <td>'.$max.'</td>
 </tr>
<tr>
  <th>S.NO </th>
  <th colspan=3 >REGISTER NUMBER</th>
  <th colspan=2>MARKS OUT OF '.$max.'</th>
  <th colspan=4>MARKS IN WORDS</th>
</tr>'; 
$i=1;
$html .= $print_data_again;
$bottom_data = "<tr>
     
      <td colspan=5>EXAMINER</td>
      <td colspan=5>CHIEF EXAMINER </td>
      </tr>
      <tr>
      <td height='30' colspan=5>&nbsp;</td>
      <td height='30' colspan=5>&nbsp;</td>
      </tr>
      ";

foreach ($external_score as $value) 
{
    if(($i%31)==0)
    {
      /*echo $table_close;
      echo "<pagebreak />";
      echo $print_data_again;      */
      

      $i=1;
      $html .=$bottom_data.$table_close."<pagebreak />".$print_data_again;
    }   
    
    $check_ab = 'SELECT * FROM coe_absent_entry WHERE absent_student_reg ="'.$value['coe_student_mapping_id'].'" AND exam_type="'.$value['exam_type'].'" and absent_term="'.$value['exam_term'].'" and exam_subject_id="'.$value['subject_mapping_id'].'" and exam_year="'.$year.'" and exam_month="'.$value['exam_month'].'"';
    $result=Yii::$app->db->createCommand($check_ab)->queryAll();
    
    if(!empty($result))
    {
        
    }
    else
    {

      if($exam_type_g==$value['exam_type'])
      {
        
        $check_pass = 'SELECT * FROM coe_mark_entry_master WHERE student_map_id ="'.$value['coe_student_mapping_id'].'" AND subject_map_id ="'.$value['subject_mapping_id'].'" and result ="Pass" ';
       
         $result_1=Yii::$app->db->createCommand($check_pass)->queryAll();
         if(empty($result_1))
         {
            $html .="<tr>
                  <td  style='line-height: 22px;'  > $i </td>
                  <td  style='line-height: 22px;'  colspan=3 > <b>".strtoupper($value['register_number'])."</b> </td>
                  <td  style='line-height: 22px;'  colspan=2 > &nbsp; </td>
                  <td  style='line-height: 22px;'  colspan=4 > &nbsp; </td>
                </tr>";
             $i++; 
         }
         
      }
      else
      {
        $html .="<tr>
                  <td  style='line-height: 22px;'  > $i </td>
                  <td  style='line-height: 22px;'  colspan=3 > <b>".strtoupper($value['register_number'])."</b> </td>
                  <td  style='line-height: 22px;'  colspan=2 > &nbsp; </td>
                  <td  style='line-height: 22px;'  colspan=4 > &nbsp; </td>
                </tr>";
        $i++; 
      }
    }
}
  echo $html.$bottom_data."</table>"; 
  if(isset($_SESSION['external_score_data']))
  {
      unset($_SESSION['external_score_data']);
  }
  $_SESSION['external_score_data']=$html.$bottom_data."</table>";
}
else
{
  Yii::$app->ShowFlashMessages->setMsg('Error',"No data Found");
  return $this->redirect('external');
}
?>


</div>
<div class="col-lg-2 col-sm-2">
    </div>
</div>
</div>