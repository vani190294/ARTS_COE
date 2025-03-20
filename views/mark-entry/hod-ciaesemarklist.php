<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\Categorytype;
use yii\db\Query;
echo Dialog::widget();
use app\models\MarkEntryMaster;
use app\models\ValuationSettings;
use app\models\HallAllocate;

$ValuationSettings = ValuationSettings::findOne(1);
/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "CONSOLIDATE CIA+ESE HOD COPY";
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
          

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model, 'stu_programme_id')->widget(
                    Select2::classname(), [
                       'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>

              <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>$ValuationSettings['current_exam_year'],'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                      'data' => $galley->getMonth(),        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            //'id' => 'exam_month',
                            'class'=>'student_disable',
                            'name'=>'month',
                            'value'=> $ValuationSettings['current_exam_month'] 
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>

        </div>

        
        
        <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry/hod-ciaesemarklist']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>


            </div>
        </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>






<?php
if(isset($ese_list))
{
     require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    /* 
    *   Already Defined Variables from the above included file
    *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
    *   use these variables for application
    *   use $file_content_available="Yes" for Content Status of the Organisation
    */
    if($file_content_available=="Yes")
        {
            
            $previous_subject_code= "";
            $previous_programme_name= "";
            $previous_reg_number = "";
            $html = "";
            $header = "";
            $body ="";
            $footer = "";
            $print_subjects =1;
            $print_register_number="";
            $new_stu_flag=0;
            $print_stu_data="";
            $countVal = (count($subjectsInfo) *3)+3;
            
            //$countStuVal = count($ese_list);
            $stu_print_vals = 0;


            
                foreach ($ese_list as $get_names) {
                    $month = $get_names['month'];
                    $degree_name = $get_names['degree_name'];
                    $prg_name = $get_names['programme_name'];
                    break;
                }

                $_SESSION['hodtemp']=$prg_name;
                $month_name = Categorytype::findOne($month);
               $header .='<div class="box-body table-responsive">
                <table width="100%" class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                  <tr>';
                $header .='<td colspan='.($countVal+2).'  ALIGN="CENTER">
                        <b> '.$org_name.'</b> </td>
                  </tr>
                  <tr>';
                    $header .='<td colspan='.($countVal+2).' ALIGN="CENTER">
                  <b> '.$org_tagline.'</b> 
                    </td>
                  </tr> 
                  <tr>';
                    $header .='<td colspan='.($countVal+2).' ALIGN="CENTER">
                        <b> CONSOLIDATED CIA + ESE MARKS '.strtoupper($month_name->description).' - '.$_POST["mark_year"].'</b> 
                    </td>
                  </tr>
                  <tr>';
                    $header .='<td colspan='.($countVal+2).' ALIGN="CENTER"><b> 
                   '.strtoupper($degree_name).' - '.$prg_name.' </b> 
                
                   </td>
                  </tr> 
                  </table>

                  <table width="100%" class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                    <tr>
                        <td align="center"><b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </b> </td>';
                        $header .='<td colspan='.($countVal-6).' align="center"><b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME</b> </td>
                        <td align="center"><b>Paper Type</b></td>
                         <td align="center"><b>Assessment Type</b></td>
                        <td align="center"><b>CIA MIN</b></td>
                        <td align="center"><b>CIA MAX</b></td>
                        <td align="center"><b>ESE MIN</b></td>
                        <td align="center"><b>ESE MAX</b></td>
                        <td align="center"><b>MIN. PASS</b></td>
                    </tr>';

                  foreach($subjectsInfo_all as $rows) 
                  { 
                      $total_minimum_pass=0;
                      if($rows["ESE_max"]>0 || $rows["CIA_max"]>0)
                      {
                        $total_minimum_pass=$rows["total_minimum_pass"];
                      }
                         $header .='<tr>
                             <td align="center">
                                '.$rows["subject_code"].'</td>
                             <td colspan='.($countVal-6).' align="left">
                                '.$rows["subject_name"].'</td>
                              <td align="center">'.$rows["subject_type"].'</td>
                              <td align="center">'.$rows["assessment_type"].'</td>
                              <td align="center">'.$rows["CIA_min"].'</td>
                              <td align="center">'.$rows["CIA_max"].'</td>
                              <td align="center">'.$rows["ESE_min"].'</td>
                             <td align="center">'.$rows["ESE_max"].'</td>
                             <td align="center">'.$total_minimum_pass.'</td>
                        </tr>';
                       
                    } 
                    $header .='</table>';

                    $header.='<pagebreak>';
                       $header .='
                          <table class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                          <tr>  
                          <td align="center"><b> REGSITER NUMBER</b></td>';
                          foreach($subjectsInfo as $rows) { 
                            $header .='<td align="center" colspan=5><b> '.$rows["subject_code"].'</b></td>';
                          } 
                             $header .='<td align="center" colspan=5><b>Subject Status</b></td>';
                            $header .='</tr><tr><td align="center" rowspan=2></td>';
                          foreach($subjectsInfo as $rows) 
                          { 

                            if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                            {
                                $header .='<td align="center" colspan=5>RESULT</td>';
                            }
                            else
                            {
                              $header .='
                             
                               <td align="center" rowspan=2>CIA</td> 
                               <td align="center" colspan=3>ESE</td> 
                               <td align="center" rowspan=2>TOT</td>';
                            }
                             
                          } 

                           $header .='
                               <td align="center" rowspan=2>Pass</td> 
                               <td align="center" rowspan=2>Fail</td>
                               <td align="center" rowspan=2>Absent</td>
                               <td align="center" rowspan=2>Withdraw</td>
                               <td align="center" rowspan=2>Withheld</td>';
                        $header .='</tr>';

                         $header .='<tr>';
                         foreach($subjectsInfo as $rows) 
                          { 
                              if($rows['type_id']=='143' || $rows['type_id']=='105')
                              {
                                $header .='<td align="center" colspan=5></td>';
                              }
                              else
                              { 
                                 
                                      if($rows['type_id']=='140')
                                      {
                                         $header .='<td align="center">P (25%)</td> 
                                          <td align="center">T (25%)</td>
                                          <td align="center">Tot</td>';
                                      }
                                      else if($rows['type_id']=='141')
                                      {
                                        $header .='<td align="center">P (15%)</td> 
                                          <td align="center">T (35%)</td>
                                          <td align="center">Tot</td>';
                                      }
                                      else if($rows['type_id']=='142')
                                      {
                                        $header .='<td align="center">P (35%)</td> 
                                          <td align="center">T (15%)</td>
                                          <td align="center">Tot</td>';                                        
                                      }   
                                      else if($rows['type_id']=='144')
                                      { 
                                          $header .='<td align="center" colspan=3>P (50%)</td>';
                                      }
                                                             
                                  
                              }
                          
                          } 

                          $header .='</tr>';

                    
                    
                   $temp_sub=$prev_num=""; $Overallpass=0; $Overallfail=0; 
                  

                $query_ese_list = new Query();
                $query_ese_list = "SELECT distinct s.register_number,b.student_map_id,a.status_category_type_id,s.coe_student_id FROM coe_student_mapping a JOIN coe_student s ON a.student_rel_id=s.coe_student_id  JOIN coe_mark_entry as b ON b.student_map_id=a.coe_student_mapping_id JOIN coe_subjects_mapping c ON c.coe_subjects_mapping_id=b.subject_map_id JOIN coe_bat_deg_reg d ON d.coe_bat_deg_reg_id=a.course_batch_mapping_id WHERE a.course_batch_mapping_id='".$batch_mapping_id."' AND year='".$year."' and month='".$month."' AND b.category_type_id=46 ORDER BY s.register_number"; 
                $register_student = Yii::$app->db->createCommand($query_ese_list)->queryAll();

                $rejoinpass_status=array();
                $Overallfail_student=array();
                foreach($register_student as $rowsstudent) 
                { 
                    
                    $header .='</tr>';
                         $header .='<tr>
                         <td align="center">
                            '.$rowsstudent["register_number"].'
                        </td>';
                        
                        $sub_loop=0; $stu_pass=$stu_fail=$stu_abs=$wdcount=0; $whcount=0;
                        foreach($subjectsInfo as $subs) 
                        { 
                            $ese=$cia=$ese_total="";
                            $ese_1="";
                           $esemark=0;
        
                         foreach($ese_list as $stus) 
                          {  
                            
                            if($subs['subject_code']==$stus['subject_code'] && $rowsstudent['register_number']==$stus['register_number'])
                            { 

                                  if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                                  {
                                    $ese_total=$stus['result'];
                                  }
                                  else if($stus['total']==0)
                                  {
                                     $ese_total=0;
                                  }
                                  else
                                  {
                                    $ese_total=round($stus['total']);
                                     $esemark=$stus['grand_total'];
                                  }
                                  if($stus['ese']==0)
                                  {
                                    $ese=0;
                                  }
                                  else
                                  {
                                    $ese=round($stus['ese']);
                                  }

                                if($stus['CIA']==0)
                                {
                                   $cia=0;
                                }
                                else
                                {
                                  $cia=$stus['CIA'];
                                }

                            } 
                            

                          }

                        $absent = Yii::$app->db->createCommand("select count(*) from coe_absent_entry where exam_year ='".$year."' AND exam_month ='".$month."' AND absent_student_reg ='".$rowsstudent['student_map_id']."' AND exam_subject_id ='".$subs['subject_map_id']."'")->queryScalar();

                        $wd = Yii::$app->db->createCommand("select count(*) from coe_mark_entry_master where year ='".$year."' AND month ='".$month."' AND student_map_id ='".$rowsstudent['student_map_id']."' AND subject_map_id ='".$subs['subject_map_id']."' and withdraw='wd'")->queryScalar();

                         $wh = Yii::$app->db->createCommand("select count(*) from coe_mark_entry_master where year ='".$year."' AND month ='".$month."' AND student_map_id ='".$rowsstudent['student_map_id']."' AND subject_map_id ='".$subs['subject_map_id']."' and withheld='w'")->queryScalar();
                        
                        if($wd>0)
                        {
                          $wdcount=$wdcount+1;
                        }

                        if($wh>0)
                        {
                          $whcount=$whcount+1;
                        }
                        
                        if($absent>0)
                        {
                          $stu_abs=$stu_abs+1;
                          
                          $absent_cia_mark = Yii::$app->db->createCommand("select category_type_id_marks as CIA from coe_mark_entry where year ='".$year."' AND month ='".$month."' AND student_map_id ='".$rowsstudent['student_map_id']."' AND subject_map_id ='".$subs['subject_map_id']."'")->queryScalar();

                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              $header .='<td align="center" colspan=5 style="color:red">AB</td>';
                            }
                            else
                            {
                               if($subs['type_id']=='105' || $subs['type_id']=='143' || $subs['type_id']=='144')
                              {
                                $header .='<td align="center" style="color:red">'.$absent_cia_mark.'</td>';
                                $header .='<td align="center" style="color:red" colspan=3>0</td>';
                                $header .='<td align="center" style="color:red">AB</td>';
                              }
                              else
                              {

                                $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$subs['subject_map_id']." AND student_map_id='".$rowsstudent['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();                                                                            
                                $tpesemark=0;
                                $practmark=0;
                                $esemrk=0;
                                if($subs['type_id']=='140')
                                {
                                    $practmark=round($prac_mark*0.25);
                                    $esemrk=round($esemark*0.25);
                                    $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                }
                                else if($subs['type_id']=='141')
                                {
                                  
                                    $practmark=round($prac_mark*0.15);
                                    $esemrk=round($esemark*0.35);
                                    $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                }
                                else if($subs['type_id']=='142')
                                {
                                  
                                   $practmark=round($prac_mark*0.35);
                                    $esemrk=round($esemark*0.15);
                                    $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                }   

                                $header .='<td align="center" style="color:red">'.$absent_cia_mark.'</td>';
                                if($practmark!=0)
                                {
                                    $header .='<td align="center" style="color:red">'.$practmark.'('.$prac_mark.')</td>';
                                }
                                else
                                {
                                  $header .='<td align="center" style="color:red">0</td>';
                                }
                                if($esemrk!=0)
                                {
                                    $header .='<td align="center" style="color:red">'.$esemrk.'('.$esemark.')</td>';
                                }
                                else
                                {
                                  $header .='<td align="center" style="color:red">0</td>';
                                }
                                
                                $header .='<td align="center" style="color:red">'.$tpesemark.'</td>';
                                $header .='<td align="center" style="color:red">AB</td>';
                              }
                             
                            }
                            
                        }
                        else
                        {
                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              if($ese_total=='Fail')
                              {
                                $header .='<td align="center" colspan=5  style="color:red">'.$ese_total.'</td>';
                                $stu_fail=$stu_fail+1;
                              }
                              else
                              {
                                $stu_pass=$stu_pass+1;
                                $header .='<td align="center" colspan=5>'.$ese_total.'</td>';
                              }
                              
                            }
                            else
                            {
                              if($ese_total=='')
                              {
                                
                                $detain_cia_mark = Yii::$app->db->createCommand("select category_type_id_marks as CIA from coe_mark_entry where year ='".$year."' AND month ='".$month."' AND student_map_id ='".$rowsstudent['student_map_id']."' AND subject_map_id ='".$subs['subject_map_id']."'")->queryScalar();

                                $check_nominal = Yii::$app->db->createCommand("SELECT count(coe_nominal_id) from coe_nominal where semester ='".$semester."' AND coe_student_id ='".$rowsstudent['coe_student_id']."' AND coe_subjects_id ='".$subs['subject_id']."'")->queryScalar(); 
                                if($check_nominal>=1)
                                {
                                  if($subs['type_id']=='105' || $subs['type_id']=='143' || $subs['type_id']=='144')
                                  {
                                      if($rowsstudent["register_number"]=='727821TUCS901' && $subs['subject_code']=='21CS401')
                                      {
                                        //$stu_pass=$stu_pass+1;
                                        $header .='<td align="center" style="color:red">-</td>';
                                        $header .='<td align="center" style="color:red" colspan=3>-</td>';
                                        $header .='<td align="center" style="color:red">-</td>';
                                      }
                                      else
                                      {
                                        $stu_fail=$stu_fail+1;
                                         $header .='<td align="center" style="color:red">'.$detain_cia_mark.'</td>';
                                          $header .='<td align="center" style="color:red" colspan=3>0</td>';
                                          $header .='<td align="center" style="color:red">0</td>';
                                      }
                                  }
                                  else
                                  {
                                    $stu_fail=$stu_fail+1;
                                    $header .='<td align="center" style="color:red">'.$detain_cia_mark.'</td>';
                                    $header .='<td align="center" style="color:red">0</td>';
                                    $header .='<td align="center" style="color:red">0</td>';
                                    $header .='<td align="center" style="color:red">0</td>';
                                    $header .='<td align="center" style="color:red">0</td>';
                                  }
                                }
                                else
                                {
                                    $header .='<td align="center" colspan=5>-</td>';
                                }
                              }
                              else
                              {
                                $check_honours = Yii::$app->db->createCommand("SELECT count(register_number) FROM cur_honours_subject_list WHERE semester='".$semester."' AND batch_map_id='".$batch_mapping_id."' AND register_number ='".$rowsstudent['register_number']."' AND subject_code ='".$subs['subject_code']."'")->queryScalar(); 
                                if($check_honours>=1)
                                {
                                    $header .='<td align="center" colspan=5>-</td>';
                                }
                                else
                                {
                                  if($subs['type_id']=='143')
                                  {
                                    if($ese_total<$subs['total_minimum_pass'] || $ese<$subs['ESE_min'])
                                    {
                                        
                                        $stu_fail=$stu_fail+1;
                                        $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                        $header .='<td align="center" style="color:red" colspan=3>'.$ese.'('.$esemark.')</td>';
                                        $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                    }
                                    else
                                    {
                                        $stu_pass=$stu_pass+1;
                                        $header .='<td align="center">'.$cia.'</td>';
                                        $header .='<td align="center" colspan=3>'.$ese.'('.$esemark.')</td>';
                                        $header .='<td align="center">'.$ese_total.'</td>';
                                    }
                                  }
                                  else if($subs['type_id']=='105')
                                  {
                                    if($ese_total<$subs['total_minimum_pass'])
                                    {
                                        
                                        $stu_fail=$stu_fail+1;
                                        $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                        $header .='<td align="center" style="color:red" colspan=3>'.$ese.'</td>';
                                        $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                    }
                                    else
                                    {
                                        $stu_pass=$stu_pass+1;
                                        $header .='<td align="center">'.$cia.'</td>';
                                        $header .='<td align="center" colspan=3>'.$ese.'</td>';
                                        $header .='<td align="center">'.$ese_total.'</td>';
                                    }
                                  }
                                  else
                                  {
                                      $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$subs['subject_map_id']." AND student_map_id='".$rowsstudent['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();                                                                            
                                      $tpesemark=0;
                                      $practmark=0;
                                      $esemrk=0;
                                      if($subs['type_id']=='140')
                                      {
                                          $practmark=round($prac_mark*0.25);
                                          $esemrk=round($esemark*0.25);
                                          $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                      }
                                      else if($subs['type_id']=='141')
                                      {
                                        
                                          $practmark=round($prac_mark*0.15);
                                          $esemrk=round($esemark*0.35);
                                          $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                      }
                                      else if($subs['type_id']=='142')
                                      {
                                        
                                         $practmark=round($prac_mark*0.35);
                                          $esemrk=round($esemark*0.15);
                                          $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                      }   
                                      else if($subs['type_id']=='144')
                                      { 
                                          $tpesemark =$practmark=round($prac_mark*0.50);
                                      } 
                                      else
                                      {
                                          //$practmark=0;
                                          $esemrk=round($esemark*0.50);
                                          $tpesemark =round($esemark*0.50);
                                      } 

                                       $ese_total=$cia+$tpesemark;


                                      if($subs['type_id']=='144')
                                      {
                                        if($ese_total<$subs['total_minimum_pass'] || $tpesemark<$subs['ESE_min'])
                                        {
                                          $stu_fail=$stu_fail+1;
                                          $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                          $header .='<td align="center" style="color:red" colspan=3>'.$practmark.'('.$prac_mark.')</td>';
                                          $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                        }
                                        else
                                        {
                                          $stu_pass=$stu_pass+1;
                                          $header .='<td align="center">'.$cia.'</td>';
                                          $header .='<td align="center" colspan=3>'.$practmark.'('.$prac_mark.')</td>';
                                          $header .='<td align="center">'.$ese_total.'</td>';
                                        }
                                      }                                      
                                      else 
                                      {
                                          if($ese_total<$subs['total_minimum_pass'] || $tpesemark<$subs['ESE_min'] || $prac_mark<45 || $esemark<45)
                                          {
                                            $stu_fail=$stu_fail+1;
                                            $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                            $header .='<td align="center" style="color:red">'.$practmark.'('.$prac_mark.')</td>';
                                            $header .='<td align="center" style="color:red">'.$esemrk.'('.$esemark.')</td>';
                                             $header .='<td align="center" style="color:red">'.$tpesemark.'</td>';
                                            $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                          }
                                          else
                                          {
                                            $stu_pass=$stu_pass+1;
                                            $header .='<td align="center">'.$cia.'</td>';
                                            $header .='<td align="center">'.$practmark.'('.$prac_mark.')</td>';
                                            $header .='<td align="center">'.$esemrk.'('.$esemark.')</td>';
                                            $header .='<td align="center">'.$tpesemark.'</td>';
                                            $header .='<td align="center">'.$ese_total.'</td>';
                                          }
                                        }     
                                    }

                                }

                              }
                             
                            }
                          
                        }

                       $sub_loop++;
                    }
                    

                    if($rowsstudent["status_category_type_id"]==6)
                    {
                      $rejoinpass_status[$rowsstudent["register_number"]]=array('pass'=>$stu_pass,'fail'=>$stu_fail,'absent'=>$stu_abs);
                    }

                    if($wdcount>0)
                    {
                        $stu_pass=$stu_pass+$wdcount;
                    }

                    if($whcount>0)
                    {
                        $stu_pass=$stu_pass+$whcount;
                    }

                    $subcount=0;
                    
                    $subcount=$countOfSubjects;

                    if($stu_fail>0)
                    {
                      $Overallfail=$Overallfail+1;

                      $Overallfail_student[]=$rowsstudent["register_number"];
                    }
                    
                    if($subcount==$stu_pass) 
                    {
                      $Overallpass=$Overallpass+1;
                      $header .='<td align="center">'.$stu_pass.'</td>';
                    }   
                    else
                    {
                      $header .='<td align="center">'.$stu_pass.'</td>';
                    }

                    $header .='<td align="center">'.$stu_fail.'</td>';
                    $header .='<td align="center">'.$stu_abs.'</td>';
                    $header .='<td align="center">'.$wdcount.'</td>';
                    $header .='<td align="center">'.$whcount.'</td>';
                    
                  } 

                  //$implodefail=implode("<br>", $Overallfail_student);

                  //print_r($implodefail); exit();

                  $header .='<tr><td align="center">REGSITERED</td>';
                  foreach($subjectsInfo as $rows) 
                  {
                      $reg_stu_qry = "SELECT count(distinct a.student_map_id) FROM coe_mark_entry a 
                      JOIN coe_subjects_mapping c ON c.coe_subjects_mapping_id=a.subject_map_id WHERE c.batch_mapping_id='".$batch_mapping_id."' AND year='".$year."' and month='".$month."' AND a.subject_map_id='".$rows['subject_map_id']."' AND a.category_type_id=46"; 

                      $register_enroll = Yii::$app->db->createCommand($reg_stu_qry)->queryScalar();

                      if($rows['subject_code']=='21CS401')
                      {
                        $header .='<td align="center" colspan=5>'.(($register_enroll)-1).'</td>';
                      }
                      else
                      {
                        $header .='<td align="center" colspan=5>'.($register_enroll).'</td>';
                      }
                      

                  }
                  $header .='<td align="left" colspan=5><b>Overall Pass: </b>'.$Overallpass.'</td>';
                  $header .='</tr><tr><td align="center">APPEARED</td>';
                  $student_appeared1=0;
                  foreach($subjectsInfo as $rows) 
                  { 

                    $query_absent = new Query();
                      $query_absent->select('count(DISTINCT absent_student_reg)')
                          ->from('coe_student_mapping a')
                          ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                          ->join('JOIN', 'coe_absent_entry b', 'b.absent_student_reg=a.coe_student_mapping_id')
                          ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.exam_subject_id')
                          ->where(['b.exam_subject_id' => $rows['subject_map_id'], 'b.exam_year' => $year, 'b.exam_month' => $month,'student_status'=>'Active']);
                     $student_absent = $query_absent->createCommand()->queryScalar();

                    $query_withheld = new Query();
                    $query_withheld->select('count(DISTINCT student_map_id)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                        ->where(['b.subject_map_id' => $rows['subject_map_id'], 'b.year' => $year, 'b.month' => $month, 'withheld' => 'w']);
                    $student_withheld = $query_withheld->createCommand()->queryScalar();


                    $query_appeared = new Query();
                    $query_appeared->select('count(DISTINCT student_map_id)')
                    ->from('coe_student_mapping a')
                    ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                    ->join('JOIN', 'coe_dummy_number b', 'b.student_map_id=a.coe_student_mapping_id')
                    ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                    ->where(['b.subject_map_id' => $rows['subject_map_id'], 'b.year' => $year, 'b.month' => $month,'student_status'=>'Active'])
                    ->andWhere(['<>','c.paper_type_id','137']);
                    
                    $student_appeared = $query_appeared->createCommand()->queryScalar();

                    if(empty($student_appeared) && $rows['paper_type_id']==121)
                    {
                      $query_appeared = new Query();
                      $query_appeared->select('count(DISTINCT student_map_id)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                        ->join('JOIN', 'coe_practical_entry b', 'b.student_map_id=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                        ->where(['b.subject_map_id' => $rows['subject_map_id'],'b.year' => $year, 'b.month' => $month,'student_status'=>'Active', 'c.paper_type_id' => '121', 'c.type_id' => '144']);
                        //echo $query_appeared->createCommand()->getrawsql();
                       $student_appeared = $query_appeared->createCommand()->queryScalar(); //exit;
                    }
                     
                    if(empty($student_appeared) && ($rows['paper_type_id']==10 || $rows['paper_type_id']==123))
                    {
                      $query_appeared = new Query();
                      $query_appeared->select('count(DISTINCT student_map_id)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                        ->join('JOIN', 'coe_practical_entry b', 'b.student_map_id=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                        ->where(['b.subject_map_id' => $rows['subject_map_id'],'b.year' => $year, 'b.month' => $month,'student_status'=>'Active']);
                        //echo $query_appeared->createCommand()->getrawsql();
                       $student_appeared = $query_appeared->createCommand()->queryScalar(); //exit;
                    }

                    if(empty($student_appeared) && $rows['paper_type_id']==8 && $rows['type_id']==105)
                    {
                      $query_appeared = new Query();
                      $query_appeared->select('count(DISTINCT student_map_id)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                        ->join('JOIN', 'coe_mark_entry_master_temp b', 'b.student_map_id=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                        ->where(['b.subject_map_id' => $rows['subject_map_id'], 'b.year' => $year, 'b.month' => $month, 'student_status'=>'Active', 'c.paper_type_id' => '8', 'c.type_id' => '105']);
                        $student_appeared = $query_appeared->createCommand()->queryScalar();

                        if(empty($student_appeared) && $rows['paper_type_id']==105)
                        {
                          $query_appeared = new Query();
                          $query_appeared->select('count(DISTINCT student_map_id)')
                            ->from('coe_student_mapping a')
                            ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                            ->join('JOIN', 'coe_mark_entry_master_temp b', 'b.student_map_id=a.coe_student_mapping_id')
                            ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                            ->where(['b.subject_map_id' => $rows['subject_map_id'], 'b.year' => $year, 'b.month' => $month, 'student_status'=>'Active', 'c.paper_type_id' => '105']);
                            $student_appeared = $query_appeared->createCommand()->queryScalar();
                        }

                    }

                    if(empty($student_appeared) && $rows['paper_type_id']==106)
                    {
                      $query_appeared = new Query();
                      $query_appeared->select('count(DISTINCT student_map_id)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                        ->join('JOIN', 'coe_mark_entry_master b', 'b.student_map_id=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                        ->where(['b.subject_map_id' => $rows['subject_map_id'], 'b.year' => $year, 'b.month' => $month, 'student_status'=>'Active']);
                        $student_appeared = $query_appeared->createCommand()->queryScalar();
                    }

                      $student_appeared=($student_appeared)-$student_absent;
                      $student_appeared1=$student_appeared;
                      $header .='<td align="center" colspan=5>'.$student_appeared.'</td>';
                  }

                  $header .='<td align="left" colspan=5><b>Overall Pass Percent: </b>'.round(($Overallpass/count($register_student))*100,2).'% </td>';
                  $header .='</tr><tr><td align="center">PASSED/FAILED</td>';
                  
                  $fail_reg='';$previous_subjects_code=''; $missedregsiternumber=array();
                  foreach($subjectsInfo as $rows) 
                  {
                    
                    $subject_fail=$subject_pass=0;
                    if($rows["subject_code"]!=$previous_subjects_code) 
                    {
                          foreach($ese_list as $stus) 
                          {
                              $absent = Yii::$app->db->createCommand("select count(absent_student_reg) from coe_absent_entry where exam_year ='".$year."' AND exam_month ='".$month."' AND absent_student_reg ='".$stus['student_map_id']."' AND exam_subject_id ='".$rows['subject_map_id']."'")->queryScalar();

                            if($absent>0)
                            {

                            }
                            else if($rows['subject_code']==$stus['subject_code']) 
                            {     
                                  if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                                  {
                                      if($stus['result']=='Pass')
                                      {
                                           $subject_pass= $subject_pass+1;
                                      }
                                      else
                                      {
                                          $subject_fail= $subject_fail+1; 
                                      }
                                  }
                                  else if($rows['type_id']=='143')
                                  {
                                    if($stus['total']<$rows['total_minimum_pass'] || $stus['ese']<$rows['ESE_min'])
                                    {
                                        $subject_fail= $subject_fail+1; 
                                    }
                                    else
                                    {
                                        $subject_pass= $subject_pass+1;
                                    }
                                  }
                                  else if($rows['type_id']=='105')
                                  {
                                    if($stus['total']<$rows['total_minimum_pass'])
                                    {
                                        $subject_fail= $subject_fail+1; 
                                    }
                                    else
                                    {
                                        $subject_pass= $subject_pass+1;
                                    }
                                  }
                                  else
                                  {
                                      $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$rows['subject_map_id']." AND student_map_id='".$stus['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();

                                      $esemark=$stus['grand_total'];
                                      $tpesemark=0;
                                      if($rows['type_id']=='140')
                                      {
                                          $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                      }
                                      else if($rows['type_id']=='141')
                                      {
                                           $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                      }
                                      else if($rows['type_id']=='142')
                                      {
                                         $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                      } 
                                      else if($rows['type_id']=='144')
                                      {
                                          $tpesemark =round($prac_mark*0.50);
                                      }   
                                      else
                                      {
                                          $tpesemark =round($esemark*0.50);
                                      } 

                                       $ese_total=$stus['CIA']+$tpesemark;

                                      if($ese_total<$rows['total_minimum_pass'] || $tpesemark<$rows['ESE_min'] || $prac_mark<45 || $esemark<45)
                                      {
                                        $subject_fail= $subject_fail+1;
                                      }
                                      else
                                      {
                                        $subject_pass= $subject_pass+1;
                                      }
                                            
                                  }
                            }
                          }
                    $previous_subjects_code=$rows["subject_code"];

                    }

                    
                    
                    $header .='<td align="center" colspan=5>'.$subject_pass."/".$subject_fail.'</td>';
                                  
                     
                }
                 $header .='<td align="center" rowspan=4 colspan=5 style="vertical-align:bottom;">Signature</td>';
                 $header .='</tr><tr><td align="center">PASS PERCENT </td>';
                  $previous_subjects_code='';
                foreach($subjectsInfo as $rows) 
                {
                    
                    $student_appeared=$subject_fail=$subject_pass=0;
                    if($rows["subject_code"]!=$previous_subjects_code) 
                    {
                          foreach($ese_list as $stus) 
                          {

                             

                            if($rows['subject_code']==$stus['subject_code']) 
                            {
                                $student_appeared= $student_appeared+1;
                                if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                                  {
                                      if($stus['result']=='Pass')
                                      {
                                           $subject_pass= $subject_pass+1;
                                      }
                                      else
                                      {
                                          $subject_fail= $subject_fail+1; 
                                      }
                                  }
                                  else if($rows['type_id']=='143')
                                  {
                                    if($stus['total']<$rows['total_minimum_pass'] || $stus['ese']<$rows['ESE_min'])
                                    {
                                        $subject_fail= $subject_fail+1;                                      
                                    }
                                    else
                                    {
                                        $subject_pass= $subject_pass+1;
                                    }
                                  }
                                  else if($rows['type_id']=='105')
                                  {
                                    if($stus['total']<$rows['total_minimum_pass'])
                                    {
                                        $subject_fail= $subject_fail+1;                                      
                                    }
                                    else
                                    {
                                        $subject_pass= $subject_pass+1;
                                    }
                                  }
                                  else
                                  {
                                      $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$rows['subject_map_id']." AND student_map_id='".$stus['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();

                                      $esemark=$stus['grand_total'];
                                      $tpesemark=0;
                                      if($rows['type_id']=='140')
                                      {
                                          $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                      }
                                      else if($rows['type_id']=='141')
                                      {
                                          $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                      }
                                      else if($rows['type_id']=='142')
                                      {
                                          $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                      }                                         
                                      else if($rows['type_id']=='144')
                                      {
                                           $tpesemark =round($prac_mark*0.50);
                                      }   
                                      else
                                      {
                                          $tpesemark =round($esemark*0.50);
                                      } 

                                       $ese_total=$stus['CIA']+$tpesemark;

                                      if($ese_total<$rows['total_minimum_pass'] || $tpesemark<$rows['ESE_min'] || $prac_mark<45 || $esemark<45)
                                      {
                                        $subject_fail= $subject_fail+1;
                                      }
                                      else
                                      {
                                        $subject_pass= $subject_pass+1;
                                      }
                                            
                                  }
                            }
                          }
                    $previous_subjects_code=$rows["subject_code"];

                    }

                    
                    $header .='<td align="center" colspan=5>'.round(($subject_pass/$student_appeared)*100,2).'</td>';
                                  
                     
                } 

                  $header .='</tr><tr><td align="center">CIA AVERAGE (100)</td>';
                  $previous_subjects_code='';
                  foreach($subjectsInfo as $rows) 
                  { 
                                          
                    $student_appeared=$subject_cia=0;
                    if($rows["subject_code"]!=$previous_subjects_code) 
                    {
                          foreach($ese_list as $stus) 
                          {
                            if($rows['subject_code']==$stus['subject_code']) 
                            {
                               $student_appeared= $student_appeared+1;
                              if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                              {
                                $subject_cia=$subject_cia+$stus['CIA'];
                              }
                              else
                              {
                                $subject_cia=$subject_cia+(($stus['CIA']/$rows['CIA_max'])*100);
                              }
                            }
                          }
                    $previous_subjects_code=$rows["subject_code"];

                    }
                    $header .='<td align="center" colspan=5>'.round(($subject_cia/$student_appeared),2).'</td>';
                                  
                     
                  }
                  $header .='</tr><tr><td align="center">ESE AVERAGE (100)</td>';
                  $previous_subjects_code='';
                  foreach($subjectsInfo as $rows) 
                  { 
                    $student_appeared=$subject_ese=0;

                    if($rows["subject_code"]!=$previous_subjects_code) 
                    {
                          foreach($ese_list as $stus) 
                          {
                             
                            if($rows['subject_code']==$stus['subject_code']) 
                            {
                              $student_appeared= $student_appeared+1;
                                  if($rows['type_id']=='143' || $rows['type_id']=='105')
                                  {
                                      if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                                      {
                                        $subject_ese=$subject_ese;
                                      }
                                      else
                                      {
                                         $subject_ese=$subject_ese+(($stus['ese']/$rows['ESE_max'])*100);
                                      }
                                  }
                                  else
                                  {
                                      $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$rows['subject_map_id']." AND student_map_id='".$stus['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();

                                      $esemark=$stus['grand_total'];
                                      $tpesemark=0;
                                      if($rows['type_id']=='140')
                                      {
                                          $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                      }
                                      else if($rows['type_id']=='141')
                                      {
                                          $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                      }
                                      else if($rows['type_id']=='142')
                                      {
                                          $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                      }                                         
                                      else if($rows['type_id']=='144')
                                      {
                                          $tpesemark =round($prac_mark*0.50);
                                      }   
                                      else
                                      {
                                          $tpesemark =round($esemark*0.50);
                                      } 

                                      if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                                      {
                                        $subject_ese=$subject_ese;
                                      }
                                      else
                                      {
                                        $subject_ese=$subject_ese+(($tpesemark/$rows['ESE_max'])*100);
                                      }
                                            
                                  }                             
                                //$subject_ese=$subject_ese+$stus['ese'];
                            }
                          }
                    $previous_subjects_code=$rows["subject_code"];

                    }
                    $header .='<td align="center" colspan=5>'.round(($subject_ese/$student_appeared),2).'</td>';
                                  
                     
                  }

                  $header .='</tr></table>';

                //rejoin student
              $query_ese_list = new Query();
              $query_ese_list ="SELECT distinct s.register_number,b.student_map_id,a.status_category_type_id FROM coe_student_mapping a JOIN coe_student s ON a.student_rel_id=s.coe_student_id  JOIN coe_mark_entry as b ON b.student_map_id=a.coe_student_mapping_id JOIN coe_subjects_mapping c ON c.coe_subjects_mapping_id=b.subject_map_id JOIN coe_bat_deg_reg d ON d.coe_bat_deg_reg_id=a.course_batch_mapping_id WHERE a.course_batch_mapping_id='".$batch_mapping_id."' AND status_category_type_id=6 AND year='".$year."' and month='".$month."' AND b.category_type_id=46 AND subject_type_id=233 AND additional_course=1 ORDER BY s.register_number"; 
                $register_student1 = Yii::$app->db->createCommand($query_ese_list)->queryAll();

              if(!empty($register_student1))
              {

                    $header.='<pagebreak>';
                    $header .='<table width="100%" class="table" border="1" cellpadding="1" align="center">
                          <tr><td align="center"><b>REJOIN STUDENT COURSES</b></td></tr></table>
                          <table width="100%" class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                          <tr>  
                          <td align="center"><b> REGSITER NUMBER</b></td>';
                          foreach($subjectsInfo_rejoin as $rows) { 
                            $header .='<td align="center" colspan=5><b> '.$rows["subject_code"].'</b></td>';
                          } 
                             $header .='<td align="center" colspan=3><b>Subject Status</b></td>';
                            $header .='</tr><tr><td align="center" rowspan=2></td>';
                          foreach($subjectsInfo_rejoin as $rows) 
                          { 

                            if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                            {
                                $header .='<td align="center" colspan=5>RESULT</td>';
                            }
                            else
                            {
                              $header .='
                             
                               <td align="center" rowspan=2>CIA</td> 
                               <td align="center" colspan=3>ESE</td> 
                               <td align="center" rowspan=2>TOT</td>';
                            }
                             
                          } 

                           $header .='
                               <td align="center" rowspan=2>Pass</td> 
                               <td align="center" rowspan=2>Fail</td>
                               <td align="center" rowspan=2>Absent</td>';
                        $header .='</tr>';

                         $header .='<tr>';
                         foreach($subjectsInfo_rejoin as $rows) 
                          { 
                              if($rows['type_id']=='143' || $rows['type_id']=='105')
                              {
                                $header .='<td align="center" colspan=5></td>';
                              }
                              else
                              { 
                                 
                                      if($rows['type_id']=='140')
                                      {
                                         $header .='<td align="center">P (25%)</td> 
                                          <td align="center">T (25%)</td>
                                          <td align="center">Tot</td>';
                                      }
                                      else if($rows['type_id']=='141')
                                      {
                                        $header .='<td align="center">P (15%)</td> 
                                          <td align="center">T (35%)</td>
                                          <td align="center">Tot</td>';
                                      }
                                      else if($rows['type_id']=='142')
                                      {
                                        $header .='<td align="center">P (35%)</td> 
                                          <td align="center">T (15%)</td>
                                          <td align="center">Tot</td>';                                        
                                      }   
                                      else if($rows['type_id']=='144')
                                      { 
                                          $header .='<td align="center" colspan=3>P (50%)</td>';
                                      }
                                                             
                                  
                              }
                          
                          } 

                          $header .='</tr>';

                  //print_r($rejoinpass_status); exit;

                  foreach($register_student1 as $rowsstudent) 
                  { 
                    
                    $header .='</tr>';
                         $header .='<tr>
                         <td align="center">
                            '.$rowsstudent["register_number"].'
                        </td>';
                        
                        $sub_loop=0; $stu_pass=$stu_fail=$stu_abs=0;
                        foreach($subjectsInfo_rejoin as $subs) 
                        { 
                            $ese=$cia=$ese_total="";
                            $ese_1="";
                           $esemark=0;
        
                         foreach($ese_list as $stus) 
                          {
                           
                            if($subs['subject_code']==$stus['subject_code'] && $rowsstudent['register_number']==$stus['register_number'])
                            { 

                                  if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                                  {
                                    $ese_total=$stus['result'];
                                  }
                                  else if($stus['total']==0)
                                  {
                                     $ese_total=0;
                                  }
                                  else
                                  {
                                    $ese_total=round($stus['total']);
                                     $esemark=$stus['grand_total'];
                                  }
                                  if($stus['ese']==0)
                                  {
                                    $ese=0;
                                  }
                                  else
                                  {
                                    $ese=round($stus['ese']);
                                  }

                                if($stus['CIA']==0)
                                {
                                   $cia=0;
                                }
                                else
                                {
                                  $cia=$stus['CIA'];
                                }

                            } 
                            

                          }

                        $absent = Yii::$app->db->createCommand("select count(*) from coe_absent_entry where exam_year ='".$year."' AND exam_month ='".$month."' AND absent_student_reg ='".$rowsstudent['student_map_id']."' AND exam_subject_id ='".$subs['subject_map_id']."'")->queryScalar();
                                   
                        if($absent>0)
                        {
                          $stu_abs=$stu_abs+1;
                          
                          $absent_cia_mark = Yii::$app->db->createCommand("select category_type_id_marks as CIA from coe_mark_entry where year ='".$year."' AND month ='".$month."' AND student_map_id ='".$rowsstudent['student_map_id']."' AND subject_map_id ='".$subs['subject_map_id']."'")->queryScalar();

                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              $header .='<td align="center" colspan=5 style="color:red">AB</td>';
                            }
                            else
                            {
                               if($subs['type_id']=='105' || $subs['type_id']=='143' || $subs['type_id']=='144')
                              {
                                $header .='<td align="center" style="color:red">'.$absent_cia_mark.'</td>';
                                $header .='<td align="center" style="color:red" colspan=3>0</td>';
                                $header .='<td align="center" style="color:red">AB</td>';
                              }
                              else
                              {

                                $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$subs['subject_map_id']." AND student_map_id='".$rowsstudent['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();                                                                            
                                $tpesemark=0;
                                $practmark=0;
                                $esemrk=0;
                                if($subs['type_id']=='140')
                                {
                                    $practmark=round($prac_mark*0.25);
                                    $esemrk=round($esemark*0.25);
                                    $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                }
                                else if($subs['type_id']=='141')
                                {
                                  
                                    $practmark=round($prac_mark*0.15);
                                    $esemrk=round($esemark*0.35);
                                    $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                }
                                else if($subs['type_id']=='142')
                                {
                                  
                                   $practmark=round($prac_mark*0.35);
                                    $esemrk=round($esemark*0.15);
                                    $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                }   

                                $header .='<td align="center" style="color:red">'.$absent_cia_mark.'</td>';
                                if($practmark!=0)
                                {
                                    $header .='<td align="center" style="color:red">'.$practmark.'('.$prac_mark.')</td>';
                                }
                                else
                                {
                                  $header .='<td align="center" style="color:red">0</td>';
                                }
                                if($esemrk!=0)
                                {
                                    $header .='<td align="center" style="color:red">'.$esemrk.'('.$esemark.')</td>';
                                }
                                else
                                {
                                  $header .='<td align="center" style="color:red">0</td>';
                                }
                                
                                $header .='<td align="center" style="color:red">'.$tpesemark.'</td>';
                                $header .='<td align="center" style="color:red">AB</td>';
                              }
                             
                            }
                            
                        }
                        else
                        {
                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              if($ese_total=='Fail')
                              {
                                $header .='<td align="center" colspan=5  style="color:red">'.$ese_total.'</td>';
                                $stu_fail=$stu_fail+1;
                              }
                              else
                              {
                                $stu_pass=$stu_pass+1;
                                $header .='<td align="center" colspan=5>'.$ese_total.'</td>';
                              }
                              
                            }
                            else
                            {
                              
                                if($subs['type_id']=='143')
                                {
                                  if($ese_total<$subs['total_minimum_pass'] || $ese<$subs['ESE_min'])
                                  {
                                      
                                      $stu_fail=$stu_fail+1;
                                      $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                      $header .='<td align="center" style="color:red" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                  }
                                  else
                                  {
                                      $stu_pass=$stu_pass+1;
                                      $header .='<td align="center">'.$cia.'</td>';
                                      $header .='<td align="center" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center">'.$ese_total.'</td>';
                                  }
                                }
                                else if($subs['type_id']=='105')
                                {
                                  if($ese_total<$subs['total_minimum_pass'])
                                  {
                                      
                                      $stu_fail=$stu_fail+1;
                                      $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                      $header .='<td align="center" style="color:red" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                  }
                                  else
                                  {
                                      $stu_pass=$stu_pass+1;
                                      $header .='<td align="center">'.$cia.'</td>';
                                      $header .='<td align="center" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center">'.$ese_total.'</td>';
                                  }
                                }
                                else
                                {
                                    $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$subs['subject_map_id']." AND student_map_id='".$rowsstudent['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();                                                                            
                                    $tpesemark=0;
                                    $practmark=0;
                                    $esemrk=0;
                                    if($subs['type_id']=='140')
                                    {
                                        $practmark=round($prac_mark*0.25);
                                        $esemrk=round($esemark*0.25);
                                        $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                    }
                                    else if($subs['type_id']=='141')
                                    {
                                      
                                        $practmark=round($prac_mark*0.15);
                                        $esemrk=round($esemark*0.35);
                                        $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                    }
                                    else if($subs['type_id']=='142')
                                    {
                                      
                                       $practmark=round($prac_mark*0.35);
                                        $esemrk=round($esemark*0.15);
                                        $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                    }   
                                    else if($subs['type_id']=='144')
                                    { 
                                        $tpesemark =$practmark=round($prac_mark*0.50);
                                    } 
                                    else
                                    {
                                        //$practmark=0;
                                        $esemrk=round($esemark*0.50);
                                        $tpesemark =round($esemark*0.50);
                                    } 

                                     $ese_total=$cia+$tpesemark;


                                    if($subs['type_id']=='144')
                                    {
                                      if($ese_total<$subs['total_minimum_pass'] || $tpesemark<$subs['ESE_min'])
                                      {
                                        $stu_fail=$stu_fail+1;
                                        $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                        $header .='<td align="center" style="color:red" colspan=3>'.$practmark.'</td>';
                                        $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                      }
                                      else
                                      {
                                        $stu_pass=$stu_pass+1;
                                        $header .='<td align="center">'.$cia.'</td>';
                                        $header .='<td align="center" colspan=3>'.$practmark.'</td>';
                                        $header .='<td align="center">'.$ese_total.'</td>';
                                      }
                                    }                                      
                                    else 
                                    {
                                      if($ese_total<$subs['total_minimum_pass'] || $tpesemark<$subs['ESE_min'] || $prac_mark<45 || $esemark<45)
                                      {
                                        $stu_fail=$stu_fail+1;
                                        $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                        $header .='<td align="center" style="color:red">'.$practmark.'('.$prac_mark.')</td>';
                                        $header .='<td align="center" style="color:red">'.$esemrk.'('.$esemark.')</td>';
                                         $header .='<td align="center" style="color:red">'.$tpesemark.'</td>';
                                        $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                      }
                                      else
                                      {
                                        $stu_pass=$stu_pass+1;
                                        $header .='<td align="center">'.$cia.'</td>';
                                        $header .='<td align="center">'.$practmark.'('.$prac_mark.')</td>';
                                        $header .='<td align="center">'.$esemrk.'('.$esemark.')</td>';
                                        $header .='<td align="center">'.$tpesemark.'</td>';
                                        $header .='<td align="center">'.$ese_total.'</td>';
                                      }
                                    }     
                                }

                            }
                          
                        }

                       $sub_loop++;
                    }

                    
                      $header .='<td align="center">'.$stu_pass.'</td>';
                      $header .='<td align="center">'.$stu_fail.'</td>';
                      $header .='<td align="center">'.$stu_abs.'</td>';
                    
                    $stu_pass = $stu_pass+$rejoinpass_status[$rowsstudent["register_number"]]['pass']; //exit;

                    $subcount=$rejoincountOfSubjects;
                    
                    if($subcount==$stu_pass) 
                    {
                      $Overallpass=$Overallpass+1;
                    }   
                    
                  } 
                 
                  $header .='</table>';
                  $header .=' <table width="100%" class="table" border="1" cellpadding="1" align="right">
                          <tr><td align="right"><b>Overallpass:</b>'.$Overallpass.'</td></tr>
                          <tr><td align="right"><b>Overall Pass Percent: </b>'.round(($Overallpass/count($register_student))*100,2).'% </td></tr>
                          </table>';
              }

               //later entry student
              $query_ese_list = new Query();
              $query_ese_list ="SELECT distinct s.register_number,b.student_map_id,a.status_category_type_id FROM coe_student_mapping a JOIN coe_student s ON a.student_rel_id=s.coe_student_id  JOIN coe_mark_entry as b ON b.student_map_id=a.coe_student_mapping_id JOIN coe_subjects_mapping c ON c.coe_subjects_mapping_id=b.subject_map_id JOIN coe_bat_deg_reg d ON d.coe_bat_deg_reg_id=a.course_batch_mapping_id WHERE a.course_batch_mapping_id='".$batch_mapping_id."' AND status_category_type_id=7 AND year='".$year."' and month='".$month."' AND b.category_type_id=46 AND subject_type_id=233 AND additional_course=1 ORDER BY s.register_number"; 
                $register_student1 = Yii::$app->db->createCommand($query_ese_list)->queryAll();

              if(!empty($register_student1))
              {

                    $header.='<pagebreak>';
                    $header .='<table width="100%" class="table" border="1" cellpadding="1" align="center">
                          <tr><td align="center"><b>LATER ENTRY STUDENT ADDITIONAL COURSES</b></td></tr></table>
                          <table width="100%" class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                          <tr>  
                          <td align="center"><b> REGSITER NUMBER</b></td>';
                          foreach($subjectsInfo_rejoin as $rows) { 
                            $header .='<td align="center" colspan=5><b> '.$rows["subject_code"].'</b></td>';
                          } 
                             $header .='<td align="center" colspan=3><b>Subject Status</b></td>';
                            $header .='</tr><tr><td align="center" rowspan=2></td>';
                          foreach($subjectsInfo_rejoin as $rows) 
                          { 

                            if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                            {
                                $header .='<td align="center" colspan=5>RESULT</td>';
                            }
                            else
                            {
                              $header .='
                             
                               <td align="center" rowspan=2>CIA</td> 
                               <td align="center" colspan=3>ESE</td> 
                               <td align="center" rowspan=2>TOT</td>';
                            }
                             
                          } 

                           $header .='
                               <td align="center" rowspan=2>Pass</td> 
                               <td align="center" rowspan=2>Fail</td>
                               <td align="center" rowspan=2>Absent</td>';
                        $header .='</tr>';

                         $header .='<tr>';
                         foreach($subjectsInfo_rejoin as $rows) 
                          { 
                              if($rows['type_id']=='143' || $rows['type_id']=='105')
                              {
                                $header .='<td align="center" colspan=5></td>';
                              }
                              else
                              { 
                                 
                                      if($rows['type_id']=='140')
                                      {
                                         $header .='<td align="center">P (25%)</td> 
                                          <td align="center">T (25%)</td>
                                          <td align="center">Tot</td>';
                                      }
                                      else if($rows['type_id']=='141')
                                      {
                                        $header .='<td align="center">P (15%)</td> 
                                          <td align="center">T (35%)</td>
                                          <td align="center">Tot</td>';
                                      }
                                      else if($rows['type_id']=='142')
                                      {
                                        $header .='<td align="center">P (35%)</td> 
                                          <td align="center">T (15%)</td>
                                          <td align="center">Tot</td>';                                        
                                      }   
                                      else if($rows['type_id']=='144')
                                      { 
                                          $header .='<td align="center" colspan=3>P (50%)</td>';
                                      }
                                                             
                                  
                              }
                          
                          } 

                          $header .='</tr>';

                  //print_r($rejoinpass_status); exit;

                  foreach($register_student1 as $rowsstudent) 
                  { 
                    
                    $header .='</tr>';
                         $header .='<tr>
                         <td align="center">
                            '.$rowsstudent["register_number"].'
                        </td>';
                        
                        $sub_loop=0; $stu_pass=$stu_fail=$stu_abs=0;
                        foreach($subjectsInfo_rejoin as $subs) 
                        { 
                            $ese=$cia=$ese_total="";
                            $ese_1="";
                           $esemark=0;
        
                         foreach($ese_list as $stus) 
                          {
                           
                            if($subs['subject_code']==$stus['subject_code'] && $rowsstudent['register_number']==$stus['register_number'])
                            { 

                                  if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                                  {
                                    $ese_total=$stus['result'];
                                  }
                                  else if($stus['total']==0)
                                  {
                                     $ese_total=0;
                                  }
                                  else
                                  {
                                    $ese_total=round($stus['total']);
                                     $esemark=$stus['grand_total'];
                                  }
                                  if($stus['ese']==0)
                                  {
                                    $ese=0;
                                  }
                                  else
                                  {
                                    $ese=round($stus['ese']);
                                  }

                                if($stus['CIA']==0)
                                {
                                   $cia=0;
                                }
                                else
                                {
                                  $cia=$stus['CIA'];
                                }

                            } 
                            

                          }

                        $absent = Yii::$app->db->createCommand("select count(*) from coe_absent_entry where exam_year ='".$year."' AND exam_month ='".$month."' AND absent_student_reg ='".$rowsstudent['student_map_id']."' AND exam_subject_id ='".$subs['subject_map_id']."'")->queryScalar();
                                   
                        if($absent>0)
                        {
                          $stu_abs=$stu_abs+1;
                          
                          $absent_cia_mark = Yii::$app->db->createCommand("select category_type_id_marks as CIA from coe_mark_entry where year ='".$year."' AND month ='".$month."' AND student_map_id ='".$rowsstudent['student_map_id']."' AND subject_map_id ='".$subs['subject_map_id']."'")->queryScalar();

                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              $header .='<td align="center" colspan=5 style="color:red">AB</td>';
                            }
                            else
                            {
                               if($subs['type_id']=='105' || $subs['type_id']=='143' || $subs['type_id']=='144')
                              {
                                $header .='<td align="center" style="color:red">'.$absent_cia_mark.'</td>';
                                $header .='<td align="center" style="color:red" colspan=3>0</td>';
                                $header .='<td align="center" style="color:red">AB</td>';
                              }
                              else
                              {

                                $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$subs['subject_map_id']." AND student_map_id='".$rowsstudent['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();                                                                            
                                $tpesemark=0;
                                $practmark=0;
                                $esemrk=0;
                                if($subs['type_id']=='140')
                                {
                                    $practmark=round($prac_mark*0.25);
                                    $esemrk=round($esemark*0.25);
                                    $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                }
                                else if($subs['type_id']=='141')
                                {
                                  
                                    $practmark=round($prac_mark*0.15);
                                    $esemrk=round($esemark*0.35);
                                    $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                }
                                else if($subs['type_id']=='142')
                                {
                                  
                                   $practmark=round($prac_mark*0.35);
                                    $esemrk=round($esemark*0.15);
                                    $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                }   

                                $header .='<td align="center" style="color:red">'.$absent_cia_mark.'</td>';
                                if($practmark!=0)
                                {
                                    $header .='<td align="center" style="color:red">'.$practmark.'('.$prac_mark.')</td>';
                                }
                                else
                                {
                                  $header .='<td align="center" style="color:red">0</td>';
                                }
                                if($esemrk!=0)
                                {
                                    $header .='<td align="center" style="color:red">'.$esemrk.'('.$esemark.')</td>';
                                }
                                else
                                {
                                  $header .='<td align="center" style="color:red">0</td>';
                                }
                                
                                $header .='<td align="center" style="color:red">'.$tpesemark.'</td>';
                                $header .='<td align="center" style="color:red">AB</td>';
                              }
                             
                            }
                            
                        }
                        else
                        {
                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              if($ese_total=='Fail')
                              {
                                $header .='<td align="center" colspan=5  style="color:red">'.$ese_total.'</td>';
                                $stu_fail=$stu_fail+1;
                              }
                              else
                              {
                                $stu_pass=$stu_pass+1;
                                $header .='<td align="center" colspan=5>'.$ese_total.'</td>';
                              }
                              
                            }
                            else
                            {
                              
                                if($subs['type_id']=='143')
                                {
                                  if($ese_total<$subs['total_minimum_pass'] || $ese<$subs['ESE_min'])
                                  {
                                      
                                      $stu_fail=$stu_fail+1;
                                      $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                      $header .='<td align="center" style="color:red" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                  }
                                  else
                                  {
                                      $stu_pass=$stu_pass+1;
                                      $header .='<td align="center">'.$cia.'</td>';
                                      $header .='<td align="center" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center">'.$ese_total.'</td>';
                                  }
                                }
                                else if($subs['type_id']=='105')
                                {
                                  if($ese_total<$subs['total_minimum_pass'])
                                  {
                                      
                                      $stu_fail=$stu_fail+1;
                                      $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                      $header .='<td align="center" style="color:red" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                  }
                                  else
                                  {
                                      $stu_pass=$stu_pass+1;
                                      $header .='<td align="center">'.$cia.'</td>';
                                      $header .='<td align="center" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center">'.$ese_total.'</td>';
                                  }
                                }
                                else
                                {
                                    $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$subs['subject_map_id']." AND student_map_id='".$rowsstudent['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();                                                                            
                                    $tpesemark=0;
                                    $practmark=0;
                                    $esemrk=0;
                                    if($subs['type_id']=='140')
                                    {
                                        $practmark=round($prac_mark*0.25);
                                        $esemrk=round($esemark*0.25);
                                        $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                    }
                                    else if($subs['type_id']=='141')
                                    {
                                      
                                        $practmark=round($prac_mark*0.15);
                                        $esemrk=round($esemark*0.35);
                                        $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                    }
                                    else if($subs['type_id']=='142')
                                    {
                                      
                                       $practmark=round($prac_mark*0.35);
                                        $esemrk=round($esemark*0.15);
                                        $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                    }   
                                    else if($subs['type_id']=='144')
                                    { 
                                        $tpesemark =$practmark=round($prac_mark*0.50);
                                    } 
                                    else
                                    {
                                        //$practmark=0;
                                        $esemrk=round($esemark*0.50);
                                        $tpesemark =round($esemark*0.50);
                                    } 

                                     $ese_total=$cia+$tpesemark;


                                    if($subs['type_id']=='144')
                                    {
                                      if($ese_total<$subs['total_minimum_pass'] || $tpesemark<$subs['ESE_min'])
                                      {
                                        $stu_fail=$stu_fail+1;
                                        $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                        $header .='<td align="center" style="color:red" colspan=3>'.$practmark.'</td>';
                                        $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                      }
                                      else
                                      {
                                        $stu_pass=$stu_pass+1;
                                        $header .='<td align="center">'.$cia.'</td>';
                                        $header .='<td align="center" colspan=3>'.$practmark.'</td>';
                                        $header .='<td align="center">'.$ese_total.'</td>';
                                      }
                                    }                                      
                                    else 
                                    {
                                      if($ese_total<$subs['total_minimum_pass'] || $tpesemark<$subs['ESE_min'] || $prac_mark<45 || $esemark<45)
                                      {
                                        $stu_fail=$stu_fail+1;
                                        $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                        $header .='<td align="center" style="color:red">'.$practmark.'('.$prac_mark.')</td>';
                                        $header .='<td align="center" style="color:red">'.$esemrk.'('.$esemark.')</td>';
                                         $header .='<td align="center" style="color:red">'.$tpesemark.'</td>';
                                        $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                      }
                                      else
                                      {
                                        $stu_pass=$stu_pass+1;
                                        $header .='<td align="center">'.$cia.'</td>';
                                        $header .='<td align="center">'.$practmark.'('.$prac_mark.')</td>';
                                        $header .='<td align="center">'.$esemrk.'('.$esemark.')</td>';
                                        $header .='<td align="center">'.$tpesemark.'</td>';
                                        $header .='<td align="center">'.$ese_total.'</td>';
                                      }
                                    }     
                                }

                            }
                          
                        }

                       $sub_loop++;
                    }

                    
                      $header .='<td align="center">'.$stu_pass.'</td>';
                      $header .='<td align="center">'.$stu_fail.'</td>';
                      $header .='<td align="center">'.$stu_abs.'</td>';
                    
                    $stu_pass = $stu_pass+$rejoinpass_status[$rowsstudent["register_number"]]['pass']; //exit;

                    $subcount=$rejoincountOfSubjects;
                    
                    if($subcount==$stu_pass) 
                    {
                      $Overallpass=$Overallpass+1;
                    }   
                    
                  } 
                 
                  $header .='</table>';
                  $header .=' <table width="100%" class="table" border="1" cellpadding="1" align="right">
                          <tr><td align="right"><b>Overallpass:</b>'.$Overallpass.'</td></tr>
                          <tr><td align="right"><b>Overall Pass Percent: </b>'.round(($Overallpass/count($register_student))*100,2).'% </td></tr>
                          </table>';
              }

              //$header='';
              //honours studnet
              $query_ese_list = new Query();
              $query_ese_list = "SELECT distinct s.register_number,b.student_map_id,a.status_category_type_id,s.coe_student_id FROM coe_student_mapping a JOIN coe_student s ON a.student_rel_id=s.coe_student_id  JOIN coe_mark_entry as b ON b.student_map_id=a.coe_student_mapping_id JOIN coe_subjects_mapping c ON c.coe_subjects_mapping_id=b.subject_map_id JOIN coe_bat_deg_reg d ON d.coe_bat_deg_reg_id=a.course_batch_mapping_id WHERE a.course_batch_mapping_id='".$batch_mapping_id."' AND year='".$year."' and month='".$month."' AND b.category_type_id=46 AND subject_type_id=233 AND s.register_number IN (SELECT register_number FROM cur_honours_subject_list WHERE semester='".$semester."' AND batch_map_id='".$batch_mapping_id."') ORDER BY s.register_number"; 
                $register_student2 = Yii::$app->db->createCommand($query_ese_list)->queryAll();

              if(!empty($register_student2))
              {

                  $header.='<pagebreak>';
                  $header .='<table width="100%" class="table" border="1" cellpadding="1" align="center">
                          <tr><td align="center"><b>HONOURS/MINORS COURSES</b></td></tr></table>
                          <table width="100%" class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                          <tr>  
                          <td align="center"><b> REGSITER NUMBER</b></td>';
                          foreach($subjectinfo_honours as $rows) 
                          { 
                            $header .='<td align="center" colspan=5><b> '.$rows["subject_code"].'</b></td>';
                          } 
                             $header .='<td align="center" colspan=3><b>Subject Status</b></td>';
                            $header .='</tr><tr><td align="center" rowspan=2></td>';
                          foreach($subjectinfo_honours as $rows) 
                          { 

                            if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                            {
                                $header .='<td align="center" colspan=5>RESULT</td>';
                            }
                            else
                            {
                              $header .='
                             
                               <td align="center" rowspan=2>CIA</td> 
                               <td align="center" colspan=3>ESE</td> 
                               <td align="center" rowspan=2>TOT</td>';
                            }
                             
                          } 

                           $header .='
                               <td align="center" rowspan=2>Pass</td> 
                               <td align="center" rowspan=2>Fail</td>
                               <td align="center" rowspan=2>Absent</td>';
                        $header .='</tr>';

                         $header .='<tr>';
                         foreach($subjectinfo_honours as $rows) 
                          { 
                              if($rows['type_id']=='143' || $rows['type_id']=='105')
                              {
                                $header .='<td align="center" colspan=5></td>';
                              }
                              else
                              { 
                                 
                                      if($rows['type_id']=='140')
                                      {
                                         $header .='<td align="center">P (25%)</td> 
                                          <td align="center">T (25%)</td>
                                          <td align="center">Tot</td>';
                                      }
                                      else if($rows['type_id']=='141')
                                      {
                                        $header .='<td align="center">P (15%)</td> 
                                          <td align="center">T (35%)</td>
                                          <td align="center">Tot</td>';
                                      }
                                      else if($rows['type_id']=='142')
                                      {
                                        $header .='<td align="center">P (35%)</td> 
                                          <td align="center">T (15%)</td>
                                          <td align="center">Tot</td>';                                        
                                      }   
                                      else if($rows['type_id']=='144')
                                      { 
                                          $header .='<td align="center" colspan=3>P (50%)</td>';
                                      }
                                                             
                                  
                              }
                          
                          } 

                          $header .='</tr>';

                //print_r($rejoinpass_status); exit;

                foreach($register_student2 as $rowsstudent) 
                { 
                    
                    $header .='</tr>';
                         $header .='<tr>
                         <td align="center">
                            '.$rowsstudent["register_number"].'
                        </td>';
                        
                        $sub_loop=0; $stu_pass=$stu_fail=$stu_abs=0;
                        foreach($subjectinfo_honours as $subs) 
                        { 
                            $ese=$cia=$ese_total="";
                            $ese_1="";
                           $esemark=0;
        
                         foreach($ese_list as $stus) 
                          {
                           
                            if($subs['subject_code']==$stus['subject_code'] && $rowsstudent['register_number']==$stus['register_number'])
                            { 

                                  if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                                  {
                                    $ese_total=$stus['result'];
                                  }
                                  else if($stus['total']==0)
                                  {
                                     $ese_total=0;
                                  }
                                  else
                                  {
                                    $ese_total=round($stus['total']);
                                     $esemark=$stus['grand_total'];
                                  }
                                  if($stus['ese']==0)
                                  {
                                    $ese=0;
                                  }
                                  else
                                  {
                                    $ese=round($stus['ese']);
                                  }

                                if($stus['CIA']==0)
                                {
                                   $cia=0;
                                }
                                else
                                {
                                  $cia=$stus['CIA'];
                                }

                            } 
                            

                          }

                        $absent = Yii::$app->db->createCommand("select count(*) from coe_absent_entry where exam_year ='".$year."' AND exam_month ='".$month."' AND absent_student_reg ='".$rowsstudent['student_map_id']."' AND exam_subject_id ='".$subs['subject_map_id']."'")->queryScalar();
                                   
                        if($absent>0)
                        {
                          $stu_abs=$stu_abs+1;
                          
                          $absent_cia_mark = Yii::$app->db->createCommand("select category_type_id_marks as CIA from coe_mark_entry where year ='".$year."' AND month ='".$month."' AND student_map_id ='".$rowsstudent['student_map_id']."' AND subject_map_id ='".$subs['subject_map_id']."'")->queryScalar();

                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              $header .='<td align="center" colspan=5 style="color:red">AB</td>';
                            }
                            else
                            {
                               if($subs['type_id']=='105' || $subs['type_id']=='143' || $subs['type_id']=='144')
                              {
                                $header .='<td align="center" style="color:red">'.$absent_cia_mark.'</td>';
                                $header .='<td align="center" style="color:red" colspan=3>0</td>';
                                $header .='<td align="center" style="color:red">AB</td>';
                              }
                              else
                              {

                                $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$subs['subject_map_id']." AND student_map_id='".$rowsstudent['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();                                                                            
                                $tpesemark=0;
                                $practmark=0;
                                $esemrk=0;
                                if($subs['type_id']=='140')
                                {
                                    $practmark=round($prac_mark*0.25);
                                    $esemrk=round($esemark*0.25);
                                    $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                }
                                else if($subs['type_id']=='141')
                                {
                                  
                                    $practmark=round($prac_mark*0.15);
                                    $esemrk=round($esemark*0.35);
                                    $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                }
                                else if($subs['type_id']=='142')
                                {
                                  
                                   $practmark=round($prac_mark*0.35);
                                    $esemrk=round($esemark*0.15);
                                    $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                }   

                                $header .='<td align="center" style="color:red">'.$absent_cia_mark.'</td>';
                                if($practmark!=0)
                                {
                                    $header .='<td align="center" style="color:red">'.$practmark.'('.$prac_mark.')</td>';
                                }
                                else
                                {
                                  $header .='<td align="center" style="color:red">0</td>';
                                }
                                if($esemrk!=0)
                                {
                                    $header .='<td align="center" style="color:red">'.$esemrk.'('.$esemark.')</td>';
                                }
                                else
                                {
                                  $header .='<td align="center" style="color:red">0</td>';
                                }
                                
                                $header .='<td align="center" style="color:red">'.$tpesemark.'</td>';
                                $header .='<td align="center" style="color:red">AB</td>';
                              }
                             
                            }
                            
                        }
                        else
                        {
                           $qqey="SELECT count(coe_nominal_id) from coe_nominal A JOIN coe_student B ON B.coe_student_id=A.coe_student_id where A.semester ='".$semester."' AND A.coe_student_id ='".$rowsstudent['coe_student_id']."' AND A.coe_subjects_id ='".$subs['subject_id']."' AND B.register_number IN (SELECT register_number FROM cur_honours_subject_list WHERE semester='".$semester."' AND batch_map_id='".$batch_mapping_id."' AND subject_code='".$subs['subject_code']."')"; 
                          $check_nominal = Yii::$app->db->createCommand($qqey)->queryScalar(); 

                          if($check_nominal>=1)
                          {
                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              if($ese_total=='Fail')
                              {
                                $header .='<td align="center" colspan=5  style="color:red">'.$ese_total.'</td>';
                                $stu_fail=$stu_fail+1;
                              }
                              else
                              {
                                $stu_pass=$stu_pass+1;
                                $header .='<td align="center" colspan=5>'.$ese_total.'</td>';
                              }
                              
                            }
                            else
                            {
                              
                                if($subs['type_id']=='143')
                                {
                                  if($ese_total<$subs['total_minimum_pass'] || $ese<$subs['ESE_min'])
                                  {
                                      
                                      $stu_fail=$stu_fail+1;
                                      $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                      $header .='<td align="center" style="color:red" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                  }
                                  else
                                  {
                                      $stu_pass=$stu_pass+1;
                                      $header .='<td align="center">'.$cia.'</td>';
                                      $header .='<td align="center" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center">'.$ese_total.'</td>';
                                  }
                                }
                                else if($subs['type_id']=='105')
                                {
                                  if($ese_total<$subs['total_minimum_pass'])
                                  {
                                      
                                      $stu_fail=$stu_fail+1;
                                      $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                      $header .='<td align="center" style="color:red" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                  }
                                  else
                                  {
                                      $stu_pass=$stu_pass+1;
                                      $header .='<td align="center">'.$cia.'</td>';
                                      $header .='<td align="center" colspan=3>'.$ese.'</td>';
                                      $header .='<td align="center">'.$ese_total.'</td>';
                                  }
                                }
                                else
                                {
                                    $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$subs['subject_map_id']." AND student_map_id='".$rowsstudent['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();                                                                            
                                    $tpesemark=0;
                                    $practmark=0;
                                    $esemrk=0;
                                    if($subs['type_id']=='140')
                                    {
                                        $practmark=round($prac_mark*0.25);
                                        $esemrk=round($esemark*0.25);
                                        $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                    }
                                    else if($subs['type_id']=='141')
                                    {
                                      
                                        $practmark=round($prac_mark*0.15);
                                        $esemrk=round($esemark*0.35);
                                        $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                    }
                                    else if($subs['type_id']=='142')
                                    {
                                      
                                       $practmark=round($prac_mark*0.35);
                                        $esemrk=round($esemark*0.15);
                                        $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                    }   
                                    else if($subs['type_id']=='144')
                                    { 
                                        $tpesemark =$practmark=round($prac_mark*0.50);
                                    } 
                                    else
                                    {
                                        //$practmark=0;
                                        $esemrk=round($esemark*0.50);
                                        $tpesemark =round($esemark*0.50);
                                    } 

                                     $ese_total=$cia+$tpesemark;


                                    if($subs['type_id']=='144')
                                    {
                                      if($ese_total<$subs['total_minimum_pass'] || $tpesemark<$subs['ESE_min'])
                                      {
                                        $stu_fail=$stu_fail+1;
                                        $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                        $header .='<td align="center" style="color:red" colspan=3>'.$practmark.'</td>';
                                        $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                      }
                                      else
                                      {
                                        $stu_pass=$stu_pass+1;
                                        $header .='<td align="center">'.$cia.'</td>';
                                        $header .='<td align="center" colspan=3>'.$practmark.'</td>';
                                        $header .='<td align="center">'.$ese_total.'</td>';
                                      }
                                    }                                      
                                    else 
                                    {
                                      if($ese_total<$subs['total_minimum_pass'] || $tpesemark<$subs['ESE_min'] || $prac_mark<45 || $esemark<45)
                                      {
                                        $stu_fail=$stu_fail+1;
                                        $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                        $header .='<td align="center" style="color:red">'.$practmark.'('.$prac_mark.')</td>';
                                        $header .='<td align="center" style="color:red">'.$esemrk.'('.$esemark.')</td>';
                                         $header .='<td align="center" style="color:red">'.$tpesemark.'</td>';
                                        $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                                      }
                                      else
                                      {
                                        $stu_pass=$stu_pass+1;
                                        $header .='<td align="center">'.$cia.'</td>';
                                        $header .='<td align="center">'.$practmark.'('.$prac_mark.')</td>';
                                        $header .='<td align="center">'.$esemrk.'('.$esemark.')</td>';
                                        $header .='<td align="center">'.$tpesemark.'</td>';
                                        $header .='<td align="center">'.$ese_total.'</td>';
                                      }
                                    }     
                                }

                            }
                          }
                          else
                          {
                            $header .='<td align="center" colspan=5>-</td>';
                          }
                            
                          
                        }

                       $sub_loop++;
                    }

                    
                      $header .='<td align="center">'.$stu_pass.'</td>';
                      $header .='<td align="center">'.$stu_fail.'</td>';
                      $header .='<td align="center">'.$stu_abs.'</td>';
                    
                    //$stu_pass = $stu_pass+$rejoinpass_status[$rowsstudent["register_number"]]['pass']; //exit;

                    // $subcount=$rejoincountOfSubjects;
                    
                    // if($subcount==$stu_pass) 
                    // {
                    //   $Overallpass=$Overallpass+1;
                    // }   
                    
                  } 
               
                $header .='</table>';
              
              }
                $header .='</div>';

                if(isset($_SESSION['cia_ese_mark_list1'])){ unset($_SESSION['cia_ese_mark_list1']);}
                $_SESSION['cia_ese_mark_list1'] = $header;

                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('cia-ese-mark-list1-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excel-cia-ese-mark-list1','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$header.'</div>
                            </div>
                        </div>
                      </div>'; 
                
               
        }// If no Content found for Institution
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');            
        }
    } 
?>
