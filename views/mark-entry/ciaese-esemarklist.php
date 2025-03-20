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

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "CONSOLIDATE CIA+ESE (100) MARK AFTER MIGRATE";
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
                    //'data'=>ConfigUtilities::getDegreedetails(),
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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'name'=>'month',
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

                <?= Html::a("Reset", Url::toRoute(['mark-entry/ciaese-esemarklist']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
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
                $month_name = Categorytype::findOne($month);
               $header .='<div class="box-body table-responsive">
                <table class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                  <tr>';
                $header .='<td colspan='.($countVal+1).'  ALIGN="CENTER">
                        <b> '.$org_name.'</b> </td>
                  </tr>
                  <tr>';
                    $header .='<td colspan='.($countVal+1).' ALIGN="CENTER">
                  <b> '.$org_tagline.'</b> 
                    </td>
                  </tr> 
                  <tr>';
                    $header .='<td colspan='.($countVal+1).' ALIGN="CENTER">
                        <b> CONSOLIDATE CIA+ESE (100) MARK AFTER MIGRATE '.$_POST["mark_year"].' - '.$month_name->description.'</b> 
                    </td>
                  </tr>
                  <tr>';
                    $header .='<td colspan='.($countVal+1).' ALIGN="CENTER"><b> 
                   '.$degree_name.' - '.$prg_name.' </b> 
                
                   </td>
                  </tr> <tr>
                        <td align="center"><b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' CODE </b> </td>';
                        $header .='<td colspan='.($countVal-6).' align="center"><b> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)).' NAME</b> </td>
                         <td align="center"><b>Subject Type</b></td>
                        <td align="center"><b>CIA MIN</b></td>
                        <td align="center"><b>CIA MAX</b></td>
                        <td align="center"><b>ESE MIN</b></td>
                        <td align="center"><b>ESE MAX</b></td>
                        <td align="center"><b>MIN. PASS</b></td>
                    </tr>';

                  foreach($subjectsInfo_all as $rows) 
                  { 
                         $header .='<tr>
                             <td align="center">
                                '.$rows["subject_code"].'</td>
                             <td colspan='.($countVal-6).' align="left">
                                '.$rows["subject_name"].'</td>
                              <td align="center">'.$rows["subject_type"].'</td>
                              <td align="center">'.$rows["CIA_min"].'</td>
                              <td align="center">'.$rows["CIA_max"].'</td>
                              <td align="center">'.$rows["ESE_min"].'</td>
                             <td align="center">'.$rows["ESE_max"].'</td>
                             <td align="center">'.$rows["total_minimum_pass"].'</td>
                        </tr>';
                       
                    } 

                       $header .='
                   <tr>  <b> 
                          <td align="center"><b> REGSITER NUMBER</b></td>';
                          foreach($subjectsInfo as $rows) { 
                            $header .='<td align="center" colspan=3><b> '.$rows["subject_code"].'</b></td>';
                          } 
                             $header .='<td align="center" colspan=3><b>Subject Status</b></td>';
                            
                            $header .='</tr><tr><td align="center"></td>';
                           foreach($subjectsInfo as $rows) 
                           { 
                             if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                            {
                                $header .='<td align="center" colspan=3>RESULT</td>';
                            }
                            else
                            {
                              $header .='
                             
                               <td align="center">CIA</td> 
                               <td align="center">ESE</td> 
                               <td align="center">TOTAL</td>';
                            }
                          } 

                        $header .='
                               <td align="center">Pass</td> 
                               <td align="center">Fail</td>
                               <td align="center">Absent</td>';
                        $header .='</tr>';

                    
                    
                    $prev_num=""; $absent=0; $Overallpass=0;

               $query_ese_list = new Query();
                $query_ese_list = "SELECT distinct s.register_number,b.student_map_id,a.status_category_type_id FROM coe_student_mapping a JOIN coe_student s ON a.student_rel_id=s.coe_student_id  JOIN coe_mark_entry as b ON b.student_map_id=a.coe_student_mapping_id JOIN coe_subjects_mapping c ON c.coe_subjects_mapping_id=b.subject_map_id JOIN coe_bat_deg_reg d ON d.coe_bat_deg_reg_id=a.course_batch_mapping_id WHERE a.course_batch_mapping_id='".$batch_mapping_id."' AND year='".$year."' and month='".$month."' AND b.category_type_id=46 ORDER BY s.register_number"; 
                $register_student = Yii::$app->db->createCommand($query_ese_list)->queryAll();

                $rejoinpass_status=array();

                foreach($register_student as $rowsstudent) 
                {  
                       
                         $header .='</tr>';
                         $header .='<tr>
                         <td align="center">
                            '.$rowsstudent["register_number"].'
                        </td>';
                        
                        $stu_pass=$stu_fail=$stu_abs=0;
                        foreach($subjectsInfo as $subs) 
                        { 
                            $ese=$cia=$ese_total="";
                            $ese_1="";
                           
                            
                         foreach($ese_list as $stus) 
                          {
                           
                            if($subs['subject_code']==$stus['subject_code'] && $rowsstudent['register_number']==$stus['register_number'])
                            { 
                                if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                                {
                                  $ese_total=$stus['result'];
                                }
                                else if($stus['ese_mark']==0)
                                {
                                   $ese_total=0;
                                }
                                else
                                {
                                  $ese_total=$stus['ese_mark'];
                                }
                                if($stus['CIA']==0)
                                {
                                   $cia=0;
                                }
                                else
                                {
                                  $cia=$stus['CIA'];
                                }
                                if($stus['ESE']==0)
                                {
                                  $ese=0;
                                }
                                else
                                {
                                  $ese=$stus['ESE'];
                                }

                             } 
                            

                            }

                        $absent = Yii::$app->db->createCommand("select count(*) from coe_absent_entry where exam_year ='".$rowsstudent['year']."' AND exam_month ='".$rowsstudent['month']."' AND absent_student_reg ='".$rowsstudent['student_map_id']."' AND exam_subject_id ='".$subs['subject_map_id']."'")->queryScalar();
                                   
                        if($absent>0)
                        {
                          $stu_abs=$stu_abs+1;
                          if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                          {
                              $header .='<td align="center" colspan=3 style="color:red">AB</td>';
                          }
                          else
                          {
                            $header .='<td align="center" style="color:red">'.$cia.'</td>';
                            $header .='<td align="center" style="color:red">0</td>';
                            $header .='<td align="center" style="color:red">AB</td>';
                          }
                        }
                        else
                        {
                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              if($ese_total=='Fail')
                              {
                                $header .='<td align="center" colspan=3  style="color:red">'.$ese_total.'</td>';
                                $stu_fail=$stu_fail+1;
                              }
                              else
                              {
                                $stu_pass=$stu_pass+1;
                                $header .='<td align="center" colspan=3>'.$ese_total.'</td>';
                              }
                              
                            }
                            else if($ese_total<$subs['total_minimum_pass'] || $ese<$subs['ESE_min'])
                            {
                                $stu_fail=$stu_fail+1;
                                $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                $header .='<td align="center" style="color:red">'.$ese.'</td>';
                                $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                            }
                            else
                            {
                              $stu_pass=$stu_pass+1;
                              $header .='<td align="center">'.$cia.'</td>';
                              $header .='<td align="center">'.$ese.'</td>';
                              $header .='<td align="center">'.$ese_total.'</td>';
                              
                            }
                        }
                        

                       }

                       
                     $header .='<td align="center">'.$stu_pass.'</td>';
                    $header .='<td align="center">'.$stu_fail.'</td>';
                    $header .='<td align="center">'.$stu_abs.'</td>';

                    if($rowsstudent["status_category_type_id"]==6)
                    {
                      $rejoinpass_status[$rowsstudent["register_number"]]=array('pass'=>$stu_pass,'fail'=>$stu_fail,'absent'=>$stu_abs);
                    }
                    
                    if($countOfSubjects==$stu_pass) 
                    {
                      $Overallpass=$Overallpass+1;
                    }  
              }  



                $header .='<tr><td align="center">REGSITERED</td>';
                  foreach($subjectsInfo as $rows) 
                  {
                      $header .='<td align="center" colspan=3>'.count($register_student).'</td>';

                  }
                  $header .='<td align="left" colspan=3><b>Overall Pass: </b>'.$Overallpass.'</td>';
                  $header .='</tr><tr><td align="center">APPEARED</td>';
                  foreach($subjectsInfo as $rows) 
                  { 

                    $query_absent = new Query();
                      $query_absent->select('count(absent_student_reg)')
                          ->from('coe_student_mapping a')
                          ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                          ->join('JOIN', 'coe_absent_entry b', 'b.absent_student_reg=a.coe_student_mapping_id')
                          ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.exam_subject_id')
                          ->where(['b.exam_subject_id' => $rows['subject_map_id'], 'b.exam_year' => $year, 'b.exam_month' => $month,'student_status'=>'Active']);
                     $student_absent = $query_absent->createCommand()->queryScalar();

                    $query_withheld = new Query();
                    $query_withheld->select('count(student_map_id)')
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
                    ->where(['b.subject_map_id' => $rows['subject_map_id'], 'b.year' => $year, 'b.month' => $month,'student_status'=>'Active']);
                    
                    $student_appeared = $query_appeared->createCommand()->queryScalar();
                     
                    if(empty($student_appeared) && $rows['paper_type_id']==10)
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

                    if(empty($student_appeared) && $rows['paper_type_id']==105)
                    {
                      $query_appeared = new Query();
                      $query_appeared->select('count(DISTINCT student_map_id)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                        ->join('JOIN', 'coe_mark_entry_master_temp b', 'b.student_map_id=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                        ->where(['b.subject_map_id' => $rows['subject_map_id'], 'b.year' => $year, 'b.month' => $month, 'student_status'=>'Active']);
                        $student_appeared = $query_appeared->createCommand()->queryScalar();
                    }

                     if(empty($student_appeared) && $rows['paper_type_id']==137)
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

                      $student_appeared=$student_appeared-($student_absent+$student_withheld);

                      $header .='<td align="center" colspan=3>'.$student_appeared.'</td>';
                  }

                  $header .='<td align="left" colspan=3><b>Overall Pass Percent: </b>'.round(($Overallpass/count($register_student))*100,2).'% </td>';
                  $header .='</tr><tr><td align="center">PASSED/FAILED</td>';
                  
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
                                if($stus['ese_mark']>=$rows['total_minimum_pass'] && $stus['ESE']>=$rows['ESE_min'])
                                {
                                   $subject_pass= $subject_pass+1;
                                   
                                }
                                else
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
                            }
                          }
                    $previous_subjects_code=$rows["subject_code"];

                    }
                    $header .='<td align="center" colspan=3>'.$subject_pass."/".$subject_fail.'</td>';
                                  
                     
                }
                 $header .='<td align="center" rowspan=4 colspan=3 style="vertical-align:bottom;">Signature</td>';
                 $header .='</tr><tr><td align="center">PASS PERCENT </td>';
                  
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
                                if($stus['ese_mark']>=$rows['total_minimum_pass'] && $stus['ESE']>=$rows['ESE_min'])
                                {
                                   $subject_pass= $subject_pass+1;
                                   
                                }
                                else 
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
                            }
                          }
                    $previous_subjects_code=$rows["subject_code"];

                    }
                    $header .='<td align="center" colspan=3>'.round(($subject_pass/$student_appeared)*100,2).'</td>';
                                  
                     
                } 

                  $header .='</tr><tr><td align="center">CIA AVERAGE (100)</td>';
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
                    $header .='<td align="center" colspan=3>'.round(($subject_cia/$student_appeared),2).'</td>';
                                  
                     
                  }
                  $header .='</tr><tr><td align="center">ESE AVERAGE (100)</td>';
                  foreach($subjectsInfo as $rows) 
                  { 
                      $student_appeared= $subject_ese=0;
                    if($rows["subject_code"]!=$previous_subjects_code) 
                    {

                          foreach($ese_list as $stus) 
                          {
                            if($rows['subject_code']==$stus['subject_code']) 
                            {
                               $student_appeared= $student_appeared+1;
                              if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                              {
                                $subject_ese=$subject_ese;
                              }
                              else
                              {
                                $subject_ese=$subject_ese+(($stus['ESE']/$rows['ESE_max'])*100);
                              }
                                //$subject_ese=$subject_ese+$stus['ese'];
                            }
                          }
                    $previous_subjects_code=$rows["subject_code"];

                    }
                    $header .='<td align="center" colspan=3>'.round(($subject_ese/$student_appeared),2).'</td>';
                                  
                     
                  }

                

                $header .='</tr></table>';

                $query_ese_list = new Query();
                $query_ese_list = "SELECT distinct s.register_number,b.student_map_id,a.status_category_type_id FROM coe_student_mapping a JOIN coe_student s ON a.student_rel_id=s.coe_student_id  JOIN coe_mark_entry as b ON b.student_map_id=a.coe_student_mapping_id JOIN coe_subjects_mapping c ON c.coe_subjects_mapping_id=b.subject_map_id JOIN coe_bat_deg_reg d ON d.coe_bat_deg_reg_id=a.course_batch_mapping_id WHERE a.course_batch_mapping_id='".$batch_mapping_id."' AND status_category_type_id=6 AND year='".$year."' and month='".$month."' AND b.category_type_id=46 ORDER BY s.register_number"; 
                $register_student1 = Yii::$app->db->createCommand($query_ese_list)->queryAll();

                if(!empty($register_student1))
                {

                  //$header='';
                        $header .='<table width="100%" class="table" border="1" cellpadding="1" align="center">
                          <tr><td align="center"><b>REJOIN STUDENT SUBJECTS</b></td></tr></table>
                          <table width="100%" class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                          <tr>  
                          <td align="center"><b> REGSITER NUMBER</b></td>';
                          foreach($subjectsInfo_rejoin as $rows) { 
                            $header .='<td align="center" colspan=3><b> '.$rows["subject_code"].'</b></td>';
                          } 
                             $header .='<td align="center" colspan=3><b>Subject Status</b></td>';
                            
                            $header .='</tr><tr><td align="center"></td>';
                           foreach($subjectsInfo_rejoin as $rows) 
                           { 
                             if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                            {
                                $header .='<td align="center" colspan=3>RESULT</td>';
                            }
                            else
                            {
                              $header .='
                             
                               <td align="center">CIA</td> 
                               <td align="center">ESE</td> 
                               <td align="center">TOTAL</td>';
                            }
                          } 

                        $header .='
                               <td align="center">Pass</td> 
                               <td align="center">Fail</td>
                               <td align="center">Absent</td>';
                        $header .='</tr>';

                //print_r($ese_list); exit;
                foreach($register_student1 as $rowsstudent) 
                {  
                       
                         $header .='</tr>';
                         $header .='<tr>
                         <td align="center">
                            '.$rowsstudent["register_number"].'
                        </td>';
                        
                        $stu_pass=$stu_fail=$stu_abs=0;
                        foreach($subjectsInfo_rejoin as $subs) 
                        { 
                            $ese=$cia=$ese_total="";
                            $ese_1="";
                           
                            
                         foreach($ese_list as $stus) 
                          {
                           
                            if($subs['subject_code']==$stus['subject_code'] && $rowsstudent['register_number']==$stus['register_number'])
                            { 
                                if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                                {
                                  $ese_total=$stus['result'];
                                }
                                else if($stus['ese_mark']==0)
                                {
                                   $ese_total=0;
                                }
                                else
                                {
                                  $ese_total=$stus['ese_mark'];
                                }
                                if($stus['CIA']==0)
                                {
                                   $cia=0;
                                }
                                else
                                {
                                  $cia=$stus['CIA'];
                                }
                                if($stus['ESE']==0)
                                {
                                  $ese=0;
                                }
                                else
                                {
                                  $ese=$stus['ESE'];
                                }

                             } 
                            

                            }

                        $absent = Yii::$app->db->createCommand("select count(*) from coe_absent_entry where exam_year ='".$year."' AND exam_month ='".$month."' AND absent_student_reg ='".$rowsstudent['student_map_id']."' AND exam_subject_id ='".$subs['subject_map_id']."'")->queryScalar();
                                   
                        if($absent>0)
                        {
                          $stu_abs=$stu_abs+1;
                          if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                          {
                              $header .='<td align="center" colspan=3 style="color:red">AB</td>';
                          }
                          else
                          {
                            $header .='<td align="center" style="color:red">'.$cia.'</td>';
                            $header .='<td align="center" style="color:red">0</td>';
                            $header .='<td align="center" style="color:red">AB</td>';
                          }
                        }
                        else
                        {
                            if($subs['CIA_max']==0 && $subs['ESE_max']==0)
                            {
                              if($ese_total=='Fail')
                              {
                                $header .='<td align="center" colspan=3  style="color:red">'.$ese_total.'</td>';
                                $stu_fail=$stu_fail+1;
                              }
                              else
                              {
                                $stu_pass=$stu_pass+1;
                                $header .='<td align="center" colspan=3>'.$ese_total.'</td>';
                              }
                              
                            }
                            else if($ese_total<$subs['total_minimum_pass'] || $ese<$subs['ESE_min'])
                            {
                                $stu_fail=$stu_fail+1;
                                $header .='<td align="center" style="color:red">'.$cia.'</td>';
                                $header .='<td align="center" style="color:red">'.$ese.'</td>';
                                $header .='<td align="center" style="color:red">'.$ese_total.'</td>';
                            }
                            else
                            {
                              $stu_pass=$stu_pass+1;
                              $header .='<td align="center">'.$cia.'</td>';
                              $header .='<td align="center">'.$ese.'</td>';
                              $header .='<td align="center">'.$ese_total.'</td>';
                              
                            }
                        }
                        

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
