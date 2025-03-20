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
echo Html::a('<i class="fa fa-file-pdf-o"></i> Export Pdf', ['/exam-timetable/exportexternal-arts'], [
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
          $max = $value['ESE_max'];
          $course_batch_map_id = $value['course_batch_mapping_id'];
}  
$html = "";
$semester_name = ConfigUtilities::getSemesterName($course_batch_map_id);
require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
$table_open ="<table border=1 width='100%' >";
$table_close = "</table>";
$print_data_again =$table_open.'
<tr>
    <td style="border: none !important;" colspan=4> 
       &nbsp;
    </td>
    <td colspan=4 style="border: 1px solid #000; font-weight: bold; width: 300px; padding: 10px;" align="left"> 
        Score Card No:
    </td>
    
</tr>
 <tr>
   <th align=center colspan="8">EXTERNAL SCORE CARD -  '.$mark_month.' / '.$year.' </th>
 </tr>

 <tr>
   <th colspan=3>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)).' NAME </th>
   <th colspan=2>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </th>
   <th colspan=3>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME </th>
   
 </tr>
 
  <tr>
   <td colspan=3>'.$degree_name.'</td>
   <td colspan=2>'.$subject_code.'</td>
   <td colspan=3>'.$subject_name.'</td>
   
 </tr>
 <tr>  
   <th colspan=3>QP CODE  </th>
   <th colspan=2>'.$semester_name.'</th>
   <th colspan=3>MAX MARKS</th>
 </tr>
 <tr>
   
   <td colspan=3>'.$qp_code.'</td>
   <td colspan=2>'.$semester.'</td>
   <td colspan=3>'.$max.'</td>
 </tr>
<tr>
  <td>S.NO </td>
  <td colspan=2 >REGISTER NUMBER</td>
  <td colspan=2 >MARKS OUT OF '.$max.'</td>
  <td colspan=3 >MARKS IN WORDS</td>
</tr>'; 
$i=1;
$html .= $print_data_again;
$bottom_data = "<tr>
     
      <td colspan=2>EXAMINER</td>
      <td colspan=2>REVIEWER </td>
      <td colspan=2>SCRUTINIZER</td>
      <td colspan=2>TABULATOR</td>
      </tr>
      <tr>
        <td colspan=4>Sign</td>
        <td colspan=4>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=4>Date</td>
        <td colspan=4>&nbsp;</td>        
      </tr>
      ";
foreach ($external_score as $value) {
    if(($i%21)==0)
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
        
        $check_pass = 'SELECT * FROM coe_mark_entry_master WHERE student_map_id ="'.$value['coe_student_mapping_id'].'" AND subject_map_id ="'.$value['subject_mapping_id'].'" and result  like "%Pass%" ';
       
         $result_1=Yii::$app->db->createCommand($check_pass)->queryAll();
         if(empty($result_1))
         {
            $html .="<tr>
                  <td  style='line-height: 22px;'  > $i </td>
                  <td  style='line-height: 22px;'  colspan=2 > <b>".strtoupper($value['register_number'])."</b> </td>
                  <td  style='line-height: 22px;'  colspan=2 > &nbsp; </td>
                  <td  style='line-height: 22px;'  colspan=3 > &nbsp; </td>
                </tr>";
             $i++; 
         }
         
      }
      else
      {
        $html .="<tr>
                  <td  style='line-height: 22px;'  > $i </td>
                  <td  style='line-height: 22px;'  colspan=2 > <b>".strtoupper($value['register_number'])."</b> </td>
                  <td  style='line-height: 22px;'  colspan=2 > &nbsp; </td>
                  <td  style='line-height: 22px;'  colspan=3 > &nbsp; </td>
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
  return $this->redirect('external-format');
}
?>


</div>
<div class="col-lg-2 col-sm-2">
    </div>
</div>
</div>