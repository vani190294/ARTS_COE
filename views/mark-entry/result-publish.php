<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\MarkEntryMaster;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;

$this->title="Result Publish";
/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

/*$batch= isset($_POST['bat_val'])?$_POST['bat_val']:'';
$programme= isset($_POST['bat_map_val'])?$_POST['bat_map_val']:'';
$sec= isset($_POST['sec'])?$_POST['sec']:'';
$sem= isset($_POST['exam_semester'])?$_POST['exam_semester']:'';*/
//$year= isset($model->year)?$model->year:date('Y');
//$month= isset($model->month)?$model->month:'';
?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 

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
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y')]) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                            //'value'=>$batch,
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
                    'data'=>ConfigUtilities::getDegreedetails(),

                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                            //'value'=> $programme,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id'=>'exam_month',
                            //'value'=>$month,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div> 
            <div class="col-lg-2 col-sm-2">
                <?php 
                echo $form->field($model, 'section')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getSectionnames(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id'=>'stu_section_select',
                            //'value'=>$sec,
                            'name'=>'sec',
                            'class'=>'form-control',                                    
                        ],
                                                             
                    ]); 
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'semester')->textInput(['id'=>'mark_semester','name'=>'exam_semester']) ?>

                
            </div>
            
            
             
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="form-group col-lg-3 col-sm-3"><br />
                    <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['id'=>'res_pub','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    <?= Html::a("Reset", Url::toRoute(['mark-entry/result-publish']), ['class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
    </div>   

    <?php ActiveForm::end(); ?>
    <?php 
    
    if(isset($send_result) && !empty($send_result))
    {
       require(Yii::getAlias('@webroot/includes/use_institute_info.php'));


       if($file_content_available=="Yes")
        {
            $exam_month_send = $model->month;
            $exam_year_send = $model->year;
            $previous_subject_code= "";
            $previous_subjects_code= "";
            $previous_programme_name= "";
            $previous_reg_number = "";
            $total_pass_count_calc = 0;
            $total_appeared_count_calc = '';
            $html = "";
            $header = "";
            $body ="";
            $footer = "";
            $print_subjects =1;
            $print_register_number="";
            $new_stu_flag=0;
            $print_stu_data="";
            $countVal = count($subjectsInfo);
            $target = array('F', 'A');
            $fail_grade = ['U','W','AB','WD','WH','wh'];
            $final_result = "PASS";
            $stu_count_result = array_filter(array(''=>''));
            //$countStuVal = count($cia_list);
            $stu_print_vals = 0;
            
                foreach ($send_result as $get_names) {
                    $month = $get_names['month'];
                    $degree_name = $get_names['degree_name'];
                    $prg_code = $get_names['programme_code'];
                    $batch_name = $get_names['batch_name'];
                     $semester = $get_names['semester'];
                    break;
                }
                $header .='<div class="box-body table-responsive">
                    <table border=1 align="center" cellspacing="1" cellspacing="1" width="100%">
                    <tr>';
                              
               /*$header .='<table class="table" border="1" cellpadding="1" align="center" cellspacing="1">
                  <tr>';*/
                $header .='<td colspan='.($countVal+3).'  ALIGN="CENTER">
                        '.$org_name.'</td>
                  </tr>
                  <tr>';
                    $header .='<td colspan='.($countVal+3).' ALIGN="CENTER">
                  '.$org_tagline.'
                    </td>
                  </tr> 
                  <tr>';
                    $header .='<td colspan='.($countVal+3).' ALIGN="CENTER">
                        <b>MARK STATEMENT REGISTER FOR '.$batch_name.'  ( '.strtoupper($prg_code).' ) '.strtoupper($degree_name).'</b>
                    </td>
                  </tr>
                  <tr>';
                    $header .='<td colspan='.($countVal+3).' ALIGN="CENTER">
                   <b> SEMESTER '.ConfigUtilities::getSemesterRoman($semester).' EXAMINATION HELD IN '.$model->year.'  '.strtoupper($month).' </b>
                
                   </td>
                  </tr>
                    ';
                       $header .='<tr>   
                          <td align="center"> REGISTER <br /> NUMBER </td>';
                          $colspan=$countVal;
                          foreach($subjectsInfo as $rows) 
                          {
                              if($rows["subject_code"]!=$previous_subject_code) 
                              {
                                    $header .='
                                    <td align="center"> 
                                        <table autosize=1 style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;" width=100%>
                                            <tr>
                                                <th align="center" colspan=5>'.$rows["subject_code"].'</th>
                                            </tr>
                                            <tr>
                                                <th align="center" colspan=5>'.wordwrap($rows["subject_name"],15,"<br />").'</th>
                                            </tr>
                                            <tr>
                                                <th align="center">'.$rows["CIA_max"].'</th>
                                                <th align="center">'.$rows["ESE_max"].'</th>
                                                <th align="center">'.$rows["total"].'</th>
                                                <th align="center"> R </th>
                                                <th align="center"> G </th>
                                            </tr>
                                        </table>
                                    </td>
                                    ';
                                    $previous_subject_code=$rows["subject_code"];
                              }

                          } 
                        $header .='<td align="center"> RESULT</td><td style=" text-align: center; "  align="center" > NO OF <br /> &nbsp;&nbsp;ARREARS </td></tr>';

                    
                    $prev_num="";
                    
                    foreach($send_result as $rowsstudent) 
                    { 
                        $stu_withheld = 1;
                        $withheld_list = MarkEntryMaster::findOne(['month'=>$exam_month_send,'year'=>$exam_year_send,'student_map_id'=>$rowsstudent['student_map_id'],'withheld'=>'w']);
                        $stu_withheld = !empty($withheld_list)?2:1;
                         
                         if($prev_num!=$rowsstudent['register_number']) 
                         { 
                            $count_of_arrears = 0;
                            $prev_num=$rowsstudent['register_number'];                   
                             $stu_result = [];
                             $stu_result = array_filter($stu_result);
                             $header .='<tr>
                                         <td align="center">
                                            '.$rowsstudent["register_number"].'
                                        </td>';
                        foreach($subjectsInfo as $checkSubj) 
                        { 
                            $result ='';
                            $cia = '';
                            $total ='';
                            $ese = '';
                            $grade_name ='';
                            
                            foreach ($send_result as $print_td) 
                            {                                
                                if($rowsstudent['register_number']==$print_td['register_number']
                                    && $print_td['subject_code']==$checkSubj['subject_code'] 
                                )
                                    {
                                        if($checkSubj['ESE_max']==0 && $checkSubj['CIA_max']==0)
                                        {
                                            $result = ($print_td["result"]=="Pass" || $print_td["result"]=="PASS" || $print_td["result"]=="pass") ?'P':'F';
                                            $cia = '-';
                                            $total = '-';
                                            $ese = '-';
                                            $grade_name = "CO";
                                            $stu_result[$result] = $result;
                                            $count_of_arrears = $result!="P"?$count_of_arrears+1:$count_of_arrears;
                                        }                                        
                                        else
                                        {
                                            $result = ($print_td["result"]=="Pass" || $print_td["result"]=="pass" || $print_td["result"]=="PASS") ?'P':($print_td["result"]=="Absent"?'A':'F');

                                            $result = ($print_td['grade_name']== 'WD' || $print_td['grade_name']== 'wd') ? "<b>W</b>" : $result;

                                             $result = ($print_td['withheld']== 'W' || $print_td['withheld']== 'w') ? "<b>WH</b>" : $result;

                                             $grade_name_pri = ($print_td['grade_name']== 'WD' || $print_td['grade_name']== 'wd') ? "<b>W</b>" : $print_td['grade_name'];

                                             $grade_name_pri = ($print_td['withheld']== 'W' || $print_td['withheld']== 'w') ? "<b>WH</b>" : $grade_name_pri;

                                             $grade_name_pri = ($print_td['grade_name']== 'WH' || $print_td['grade_name']== 'wh') ? "<b>WH</b>" : $grade_name_pri;

                                            $cia = $print_td["CIA"];
                                            $total = $print_td["total"];
                                            $ese = $print_td["ESE"];
                                            $grade_name = strtoupper($grade_name_pri);
                                            $stu_result[$result] = $result;
                                            
                                            if(in_array($grade_name,$fail_grade))
                                            {
                                                $count_of_arrears =$count_of_arrears+1;
                                            }
                                            elseif($stu_withheld==2) 
                                            {
                                                $count_of_arrears =$count_of_arrears+1;
                                            }                        
                                            else
                                            {
                                                $count_of_arrears =  $count_of_arrears;
                                            }
                                        }
                                    }
                                    
                            }
                                if($grade_name=='' && $total=='' && $ese=='' && $result=='')
                                {
                                    $header .='<td>&nbsp;</td>';
                                }
                                else
                                {
                                    if($stu_withheld==2)
                                    {
                                        $header .='
                                        <td align="center"> 
                                            <table  width=100%>
                                                <tr>';                                            
                                        $add_css1="border: 1px solid #000; width: 22px; height: 22px; background: #666; color: #FFF; ";
                                        $header .='<td  colspan=5  align="center" style="'.$add_css1.'"> WITHHELD </td>';  
                                        $header .='</tr>
                                            </table>
                                        </td>';
                                    }
                                    else if($checkSubj['ESE_max']==0 && $checkSubj['CIA_max']==0)
                                    {
                                        $print_comp = $result=='P'?'COMPLETED':'NOT COMPLETED';
                                        $header .='
                                        <td align="center"> 
                                            <table  width=100%>
                                                <tr>';                                            
                                        $add_css1= $result!='P'? "border: 1px solid #333; width: 22px; height: 22px; background: #333; color: #FFF; ":"color: #000;";
                                        $header .='<td  colspan=5  align="center" style="'.$add_css1.'"> '.$print_comp.' </td>';  
                                        $header .='</tr>
                                            </table>
                                        </td>';
                                    } 
                                    else
                                    {
                                        $header .='
                                        <td align="center"> 
                                            <table  width=100%>
                                                <tr>';
                                        $header .='<td align="center">'.$cia.'</td>';
                                        $header .='<td align="center">'.$ese.'</td>';
                                        $header .='<td align="center">'.$total.'</td>';
                                        $header .='<td align="center" >'.$result.'</td>';
                                        $add_css1=($grade_name=="RA" || $grade_name=="AB" || $grade_name=="W" || $grade_name=="w" || $grade_name=="wh" || $grade_name=="WH") ?"border: 1px solid #333; width: 22px; height: 22px; background: #666; color: #FFF; ":"color: #000;";
                                        $header .='<td align="center" style="'.$add_css1.'">'.strtoupper($grade_name).'</td>';  
                                        $header .='</tr>
                                            </table>
                                        </td>
                                        ';
                                    }
                                    
                                }
                                
                            }
                            $total_pass_count_calc = $count_of_arrears==0?$total_pass_count_calc+1:$total_pass_count_calc+0;
                            
                            $final_result=$count_of_arrears==0?"PASS":"REAPPEAR";
                            $add_css_2 = $final_result=="REAPPEAR"?'background: #ccc; color: #000; font-weign: bold; padding: 5px 0; ':'color: #000;';
                            $final_result = $count_of_arrears==count($subjectsInfo) && ($final_result!='REAPPEAR' || $final_result!='PASS') ?"ABSENT":$final_result;
                            $header .='<td align="center" style="'.$add_css_2.'" >'.$final_result.'</td><td align="center">'.$count_of_arrears.'</td></tr>'; 
                        }


                      }   // CIA LIST CLOSES HERE

                $header .='<tr> <td align="center">REGISTERED</td>';

                foreach($subjectsInfo as $rows) 
                {
                  if($rows["subject_code"]!=$previous_subjects_code) 
                  {
                        foreach ($send_result as $key => $values) 
                        {
                            $stu_1_withheld = 1;
                            $withheld_list_1 = MarkEntryMaster::findOne(['month'=>$exam_month_send,'year'=>$exam_year_send,'student_map_id'=>$values['student_map_id'],'withheld'=>'w']);
                            $stu_1_withheld = !empty($withheld_list_1)?2:1;

                            if($rows["subject_code"]==$values['subject_code'])
                            {
                                $sub_vals[] = $values['subject_code'];                                
                            }
                        }
                    $previous_subjects_code=$rows["subject_code"];
                  }
              }
             
              $appeared = array_count_values($sub_vals); 
              $stu_count_array= array();
            
              $m=0;
              foreach($subjectsInfo as $rowss) 
              {   
                    foreach ($appeared as $key => $count) 
                    {
                        if($rowss["subject_code"]==$key)
                        {
                            $header .='<td align="center">'.$count.'</td>';
                            array_push($stu_count_array, $count);
                        }
                    }
                    $m++;

              }
              $total_registed_count = max($stu_count_array);
             
                $header .='<td colspan=2 align="center">'.$total_registed_count.'</td></tr><tr> <td align="center">APPEARED</td>';
                $print_onetime=1;
                $sub_vals=array_filter(['']);
                $previous_subjects_code = '';
                foreach($subjectsInfo as $rows) 
                {
                  
                  if($rows["subject_code"]!=$previous_subjects_code) 
                  {
                        foreach ($send_result as $key => $values) 
                        {
                            $stu_1_withheld = 1;
                            $withheld_list_1 = MarkEntryMaster::findOne(['month'=>$exam_month_send,'year'=>$exam_year_send,'student_map_id'=>$values['student_map_id'],'withheld'=>'w']);
                            $stu_1_withheld = !empty($withheld_list_1)?2:1;
                           
                            if($rows["subject_code"]==$values['subject_code'])
                            {
                                $sub_vals[] = ($values['result']!='Absent')?$values['subject_code']:0;
                                if($values["result"]=="Pass" || $values["result"]=="pass" || $values["result"]=="PASS")
                                {
                                    if($stu_1_withheld==2)
                                    {
                                        $stu_count_result[] =0;
                                    }
                                    else 
                                    {
                                        $stu_count_result[] =$values['subject_code'];
                                    }

                                }
                                else
                                {
                                    $stu_count_result[] =0;
                                }
                                $cia_total[] = [ $values['subject_code']=>$values['CIA'],'max'=>$values['CIA_max']];
                                if($stu_1_withheld==2)
                                {
                                    $ese_total[] = [ $values['subject_code']=>0,'max'=>$values['ESE_max']];
                                }
                                else
                                {                                   
                                    $ese_total[] = [ $values['subject_code']=>$values['ESE'],'max'=>$values['ESE_max']];
                                }
                                
                            }
                        }
                    $previous_subjects_code=$rows["subject_code"];
                  }
              }
              $stu_count_result=array_filter($stu_count_result, function($x) { return !empty($x); });
              $pass_percentage = array_count_values($stu_count_result); 
              $appeared = array_count_values($sub_vals); 
              $stu_count_array_disp= array();
              $cia_total_subs_max_average = array_filter(['']);
              $ese_total_subs_max_average = array_filter(['']);
              $pass_count_array=array();
              $m=0;
             
              foreach($subjectsInfo as $rowss) 
              {   
                    foreach ($appeared as $key => $count) 
                    {
                        if($rowss["subject_code"]==$key)
                        {
                            $header .='<td align="center">'.$count.'</td>';
                            array_push($stu_count_array_disp, $count);
                        }
                    }
                    $cia_grand_total = $ese_grand_total = $cia_max_total = $ese_max_total  = 0;
                    for ($l=0; $l <count($cia_total) ; $l++) 
                    { 

                        if(array_key_exists($rowss['subject_code'],$cia_total[$l]))
                        {
                            $cia_grand_total += $cia_total[$l][$rowss['subject_code']];
                            $cia_max_total +=$cia_total[$l]['max'];
                        }

                        if(array_key_exists($rowss['subject_code'],$ese_total[$l]))
                        {
                            $ese_grand_total += $ese_total[$l][$rowss['subject_code']];
                            $ese_max_total += $ese_total[$l]['max'];
                        }
                       
                    }
                    $cia_total_subs_max_average[$m] = $cia_max_total;
                    $ese_total_subs_max_average[$m] = $ese_max_total;
                    $cia_total_subs_average[$m] = $cia_grand_total; 
                    $ese_total_subs_average[$m] = $ese_grand_total;
                    $m++;

              }
              $total_appeared_count = max($stu_count_array_disp);
              $header .='<td colspan=2 align="center">'.$total_appeared_count.'</td></tr><tr> <td align="center">PASSED</td>';             
              //$header .='<td colspan=2>&nbsp;</td></tr><tr> <td align="center">PASSED</td>';             
              foreach($subjectsInfo as $rowss) 
              {                    
                    $printed_status = 0;
                    foreach ($pass_percentage as $pass_key => $pass_count) 
                    {
                        if($rowss["subject_code"]==$pass_key)
                        {
                            $header .='<td align="center">'.$pass_count.'</td>';
                            array_push($pass_count_array, $pass_count);
                            $printed_status = 1;
                        }
                    }
                    if($printed_status!=1)
                    {                        
                        $header .='<td align="center">0</td>';
                        array_push($pass_count_array, 0);
                    }

              }
              $total_pass_count = 0;
              
              for($total_count_of=0;$total_count_of<count($pass_count_array);$total_count_of++)
              {
                $total_pass_count = $total_pass_count+$pass_count_array[$total_count_of];
              }
             
              $header .='<td colspan=2 align="center">'.$total_pass_count_calc.'</td></tr> ';
              //$header .='<td colspan=2>&nbsp;</td></tr> ';
              $header .='<tr><td align="center">PASS PERCENT</td>';
              $total_pass = 0;
              $total_appeared = 0;
              for($i=0;$i<count($pass_count_array);$i++){
                if(isset($stu_count_array[$i]))
                {
                    $pass_percent = ($pass_count_array[$i]/$stu_count_array[$i])*100;
                    $header .='<td align="center">'.round($pass_percent,2).'</td>';

                    $total_pass = $total_pass+$pass_count_array[$i];
                    $total_appeared = $total_appeared+$stu_count_array[$i];
                }
                else
                {
                    $header .='<td align="center">NO DATA</td>';
                }
                
              }

              $overall_perce = round((($total_pass_count_calc/$total_registed_count)*100),2);
            $overall_perce = strlen($overall_perce)==1?"0".$overall_perce.".00":$overall_perce;
           $overall_perce = (strlen($overall_perce)==2 || strlen($overall_perce)==3)?$overall_perce.".00":$overall_perce;
            $overall_perce = strlen($overall_perce)==4?$overall_perce."0":$overall_perce;

              $header .= '<td colspan=2 align="center">'.$overall_perce.'</td></tr>';
              //$header .= '<td colspan=2>&nbsp;</td></tr>';
              
              $header.='<tr><td align="center">CIA AVERAGE</td>';
              
              for($i=0;$i<count($cia_total_subs_average);$i++){
                if(isset($cia_total_subs_max_average[$i]))
                {
                    if($cia_total_subs_max_average[$i]==0)
                    {
                         $header.='<td align="center">--</td>';
                    }
                    else
                    {
                        $cia_overall_per = round((($cia_total_subs_average[$i]/$cia_total_subs_max_average[$i])),2);
                        $cia_overall_per = strlen($cia_overall_per)==1?"0".$cia_overall_per.".00":$cia_overall_per;
                       $cia_overall_per = (strlen($cia_overall_per)==2 || strlen($cia_overall_per)==3)?$cia_overall_per.".00":$cia_overall_per;
                        $cia_overall_per = strlen($cia_overall_per)==4?$cia_overall_per."0":$cia_overall_per;

                         $header.='<td align="center">'.$cia_overall_per.'</td>';
                    }
                   
                }
                else
                {
                    $header.='<td align="center">NO DATA</td>'; 
                }
                
              }
              $header .= '<td  align="center" colspan=2> -- </td></tr>';

              $header.='<tr><td align="center">ESE AVERAGE</td>';
              
              for($i=0;$i<count($ese_total_subs_average);$i++){
                if(isset($ese_total_subs_max_average[$i]))
                {
                    if($ese_total_subs_max_average[$i]==0)
                    {
                        $header.='<td align="center">--</td>';
                    }
                    else
                    {
                        $gpa_result_send = round((($ese_total_subs_average[$i]/$ese_total_subs_max_average[$i])),2);
                        $gpa_result_send = strlen($gpa_result_send)==1?"0".$gpa_result_send.".00":$gpa_result_send;
                        $gpa_result_send = (strlen($gpa_result_send)==2 || strlen($gpa_result_send)==3) ?$gpa_result_send.".00":$gpa_result_send;
                        $gpa_result_send = strlen($gpa_result_send)==4?$gpa_result_send."0":$gpa_result_send;

                        $header.='<td align="center">'.$gpa_result_send.'</td>';
                    }
                    
                }
                else
                {
                    $header.='<td align="center">NO DATA</td>';
                }
                
              }
              $header .= '<td  align="center" colspan=2> -- </td></tr>';

                $header .='</table></div>';
                if(isset($_SESSION['result_publish'])){ unset($_SESSION['result_publish']);}
                $_SESSION['result_publish'] = $header;

                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('result-publish-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excel-result-publish','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$header.'</div>
                            </div>
                        </div>
                      </div>'; 
               
        }// If no Content found for Institution
        
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found');
    }    ?>
</div>
</div>
</div>

