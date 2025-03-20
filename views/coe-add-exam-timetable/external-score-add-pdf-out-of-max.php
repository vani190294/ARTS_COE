<?php 
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\Html;
use app\models\ExamTimetable;
use app\models\SubjectsMapping;
use app\models\Categorytype;
?>
<div class="row">
<div class="col-12">
    <div class="col-lg-2 col-sm-2">
    </div>
    <div class="col-lg-8 col-sm-8">
      <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php
if(isset($external_score) && !empty($external_score)){
echo Html::a('<i class="fa fa-file-pdf-o"></i> Export Pdf', ['/coe-add-exam-timetable/exportexternal-arts'], [
                'class'=>'pull-right btn btn-success', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
            ]); 
$exam_type_g = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Arrear%'")->queryScalar();
//print_r($external_score);exit;

foreach ($external_score as $key => $value) 
{

          $course_name =  strtoupper($value['degree_name']);
          $batch_name = strtoupper($value['batch_name']);
          $year = strtoupper($value['exam_year']);
          
          $mark_month = strtoupper($value['exam_month']);
          $subject_code = strtoupper($value['subject_code']);
         
          $degree_name = strtoupper($value['degree_name']);
          $subject_name = strtoupper($value['subject_name']);
          $qp_code = strtoupper($value['qp_code']);
       
          $course_batch_map_id = $value['course_batch_mapping_id'];
          $sem_verify = ConfigUtilities::SemCaluclation($year,$mark_month,$course_batch_map_id);
           $semester = strtoupper($sem_verify);
}  
$html = "";
$semester_name = ConfigUtilities::getSemesterName($course_batch_map_id);
  $getmonthName = Categorytype::findOne($mark_month);
require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
$table_open ="<table border=1 width='100%' >";
$table_close = "</table>";
$print_data_again =$table_open.'

<tr>
    <td style="border: none !important;" colspan=4> 
       &nbsp;
    </td>
    <td colspan=4 style="border: 1px solid #000; font-weight: bold; width: 300px; padding: 10px;" align="left"> 
        CE 15(01)
    </td>
    
</tr>


<tr>
    <td style="border: none !important;" colspan=4> 
       &nbsp;
    </td>
    <td colspan=4 style="border: 1px solid #000; font-weight: bold; width: 300px; padding: 10px;" align="left"> 
        Score Card No:
    </td>
    
</tr>
<tr>
    <td colspan=8 align="center"> 
                          <center><b><font size="10px">' . $org_name . '</font></b></center>
                        
                          
                          <center class="tag_line"><b>' . $org_tagline . '</b></center> 
                     </td>
    
</tr>
 <tr>
   <th align=center colspan="8">End Semester Examinations -  '.strtoupper($getmonthName['category_type']).' - '.$year.' </th>
 </tr>
 <tr>
   <th align=center colspan="8" > Score Card</th>
 </tr>

 <tr>
   <th colspan=3>Programme  </th>
   <th colspan=2>Course </th>
   <th colspan=3>Course Code  </th>
   
 </tr>

 
 
  <tr>
   <td colspan=3>ACC</td>
   <td colspan=2>'.$subject_name.'</td>
   <td colspan=3>'.$subject_code.'</td>
   
 </tr>

 <tr>
   <td colspan=4 align=left style= "font-weight: bold;"  >Date:  </td>
   <td colspan=4 align=left style= "font-weight: bold;">Max Marks: </td>
   
   
 </tr>

 
 
<tr>
  <td>S.NO </td>
  <td colspan=2 >REGISTER NUMBER</td>
  <td colspan=2 >MARKS OUT OF </td>
  <td colspan=3> REMARKS</td>
</tr>'; 
$i=1;
$html .= $print_data_again;
$bottom_data = "<tr>
     
      <td colspan=8 align=left>EXAMINER NAME</td>
     
      </tr>
      <tr>
        <td colspan=4 align=left>SIGN</td>
        <td colspan=4>&nbsp;</td>
      </tr>
      <tr>
        <td colspan=4 align=left>NAME</td>
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
    
    $check_ab = 'SELECT * FROM coe_add_abanswerpack_regno WHERE stu_reg_no ="'.$value['coe_student_mapping_id'].'" AND exam_type=27  and subject_mapping_id="'.$value['subject_mapping_id'].'" and exam_year="'.$year.'" and exam_month="'.$value['exam_month'].'"';
    $result=Yii::$app->db->createCommand($check_ab)->queryAll();
    
    if(!empty($result))
    {
        
         $html .="<tr>
                  <td  style='line-height: 22px;'  > $i </td>
                  <td  style= 'color: #F00' line-height: 22px;'  ' colspan=2 > <b>".strtoupper($value['stu_reg_no'])."</b> </td>
                  <td  style= 'color: #F00' 'line-height: 22px;'  colspan=2 > <b> AB </b></td>
                  <td  style='color: #F00' 'line-height: 22px;'  colspan=3 > <b> Absent </b> </td>
                </tr>";
             $i++; 
             
    }
    else
    {

      if($exam_type_g==27)
      {
        
        $check_pass = 'SELECT * FROM coe_value_mark_entry WHERE student_map_id ="'.$value['coe_student_mapping_id'].'" AND subject_map_id ="'.$value['subject_mapping_id'].'" and result  like "%Pass%" ';
       
         $result_1=Yii::$app->db->createCommand($check_pass)->queryAll();
         if(empty($result_1))
         {
            $html .="<tr>
                  <td  style='line-height: 22px;'  > $i </td>
                  <td  style='line-height: 22px;'  colspan=2 > <b>".strtoupper($value['stu_reg_no'])."</b> </td>
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
                  <td  style='line-height: 22px;'  colspan=2 > <b>".strtoupper($value['stu_reg_no'])."</b> </td>
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