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
use app\models\MarkEntry;
use app\models\HallAllocate;
use app\models\StuInfo;
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Internet Copy II Before Migrate";

?>

<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
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

       

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['name'=>'year','value'=>date('Y')]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $galley->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            //'id' => 'exam_month', 
                            'name' => 'month',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <div class="col-md-2">
                   <?= $form->field($model, 'degree_type')->dropDownList($model->getDegreeType(), ['name'=>'degree_type','prompt' => Yii::t('app', '--- Select Degree Type ---')]) ?>
        </div>

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

            <div class="form-group col-lg-2 col-sm-2"><br />
                <label><input type="checkbox" name="checkbox" class="btn btn-success">With AV</label>
            </div> 
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
             <div class="form-group col-lg-2 col-sm-2"><br />
                <label><input type="checkbox" name="witharr" class="btn btn-success">Arrear Only</label>
            </div> 

            <div class="form-group col-lg-2 col-sm-2"><br />
                <input type="submit" id="student_res_export" onclick="spinner();" class="btn btn-success" value="Submit">
            </div>       
        </div>

    <?php ActiveForm::end();
        
        if(isset($internet_copy) && !empty($internet_copy))
            { //echo print_r($internet_copy); exit(); ?>

    <div id="display_results_stu1">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-3 col-sm-10 col-lg-10">
            </div>    
                <div class="col-xs-3 col-sm-2 col-lg-2">
                    <?php 
                       

                  echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-export-student-resulttemp','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));

                 
                    ?>
                </div>
        </div>

        <?php 
                  $data ='<table border=1 width="100%" class="table table-responsive table-striped" align="center" ><tbody align="center">'; 
                if($checkbox=='on')
                {
                     $data.='<tr>
                                 <th><center>Sno</center></th>
                                 <th><center>Batch</center></th>
                                 <th><center>Programme</center></th>                       
                               <th><center>RegisterNo</center></th>
                               <th><center>Status</center></th>
                               
                               <th><center>Semester</center></th>
                               <th><center>Cource Code</center></th>
                               <th><center>Mark Type</center></th>
                               <th><center>CIA Marks</center></th>
                               <th><center>ESE Marks</center></th>
                               <th><center>Total</center></th>
                               <th><center>Result</center></th>
                               <th><center>Credit Points</center></th>
                               <th><center>Grade Range</center></th>
                               <th><center>Grade Point</center></th>
                                <th><center>Subject Type</center></th>  
                                 <th><center>Practical 100</center></th> 
                                 <th><center>ESE 100</center></th>                                 
                               <th><center>Boolet No.</center></th>        
                                                         
                                <th><center>AV No.</center></th> 
                            </tr><tbody>';  
                }
                else
                {
                    $data.='<tr>
                                 <th><center>Sno</center></th>
                                 <th><center>Batch</center></th>
                                 <th><center>Programme</center></th>                       
                               <th><center>RegisterNo</center></th>
                               <th><center>Status</center></th>                               
                               <th><center>Semester</center></th>
                               <th><center>Cource Code</center></th>
                               <th><center>Mark Type</center></th>
                               <th><center>CIA Marks</center></th>
                               <th><center>ESE Marks</center></th>
                               <th><center>Total</center></th>
                               <th><center>Result</center></th>
                               <th><center>Credit Points</center></th>
                               <th><center>Grade Range</center></th>
                               <th><center>Grade Point</center></th>
                                <th><center>Subject Type</center></th>                                    
                               
                            </tr><tbody>';    
                    
                     }    

                    $prev_value="";
                    $prev_value_br="";
                    $sn=1;
                    $sss=0;
                    foreach($internet_copy as $markdetails)
                    {
                        $curr_value=$markdetails['register_number'];
                        $curr_value_br=$markdetails['register_number'];
                        
                         $rejoin="Detain/Rejoin";

                        $stu_withheld = 1;
                        //$withheld_list = MarkEntryMaster::findOne(['month'=>$_POST['month'],'year'=>$_POST['year'],'student_map_id'=>$markdetails['student_map_id'],'withheld'=>'w']);
                        $stu_withheld = '';//!empty($withheld_list)?2:1;
                        

                        //$absent_count = Yii::$app->db->createCommand("select count(coe_absent_entry_id) from coe_absent_entry where absent_student_reg =".$markdetails['student_map_id']." AND exam_subject_id =".$markdetails['subject_map_id']." AND exam_year=".$_POST['year']." AND exam_month=".$_POST['month'])->queryScalar();

                        $data.='<tr>';
                        $data.='<td>'.$sn.'</td>';
                        $data.='<td>'.$markdetails['batch_name'].'</td>';
                        $data.='<td>'.$markdetails['programme_code'].'</td>';
                                                    
                                                    

                        $data.='<td>'.$markdetails['register_number'].'</td>';
                        
                        $stu_map = StuInfo::findOne(['prev_reg'=>$markdetails['register_number']]);
                         
                         if(!empty($stu_map))
                         {
                         
                         $sudent_type ="Detain/Rejoin";
                        
                         $data.='<td>'.$sudent_type.'</td>';


                         }
                         else
                         {
                        
                        $data.='<td>'.$markdetails['sudent_type'].'</td>';
                         
                         }
                        $data.='<td>'.$markdetails['semester'].'</td>';
                        $data.='<td>'.$markdetails['subject_code'].'</td>';

                        $mark_typeid=0;
                         if($markdetails['mark_type']=='Regular')
                        {
                            $data.='<td>Regular</td>';
                            $mark_typeid=27;
                        }
                        else
                        {
                            $data.='<td>Arrear</td>';
                            $mark_typeid=28;
                        }

                        $absent_count = Yii::$app->db->createCommand("select count(coe_absent_entry_id) from coe_absent_entry where absent_student_reg =".$markdetails['student_map_id']." AND exam_subject_id =".$markdetails['subject_map_id']." AND exam_year=".$markdetails['year']." AND exam_month=".$markdetails['month'])->queryScalar();

                        if($absent_count>0)
                        {
                            $data.='<td>'.$markdetails['CIA'].'</td>';
                            $data.='<td>0</td>';
                            $data.='<td>'.$markdetails['CIA'].'</td>';
                            $data.='<td>Absent</td>';
                            $data.='<td>'.$markdetails['credit_points'].'</td>';
                            $data.='<td>0</td>';
                            $data.='<td>0</td>';
                            $data.='<td>'.$markdetails['paper_type'].'</td>';
                        }
                        else
                        {
                            
                            if($markdetails['ESE_max']==0 && $markdetails['CIA_max']==0 && $markdetails['ESE_min']==0 && $markdetails['CIA_min']==0)
                            {

                                if($markdetails['result']=="Pass")
                               {
                                $data.='<td colspan=3>COMPLETED</td>';
                                $data.='<td>'.strtoupper($markdetails['result']).'</td>';
                                $data.='<td colspan=3>COMPLETED</td>';
                                $data.='<td>'.$markdetails['paper_type'].'</td>';
                                  // $data.='<td></td><td></td><td></td>';
                             }

                             else
                             {
                                 
                                $data.='<td colspan=3> NOT COMPLETED</td>';
                                $data.='<td>'.strtoupper($markdetails['result']).'</td>';
                                $data.='<td colspan=3> NOT COMPLETED</td>';
                                $data.='<td>'.$markdetails['paper_type'].'</td>';                          
                                  //$data.='<td></td><td></td><td></td>';


                             }
                                
                            }
                            else
                            {
                                if($markdetails['mark_type']=='Regular')
                                {
                                    $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$markdetails['subject_map_id']." AND student_map_id='".$markdetails['student_map_id']."'  AND year='".$markdetails['year']."'  AND month='".$markdetails['month']."' ")->queryScalar();                                                                            
                                    $tpesemark=0;
                                    $practmark=0;
                                    $ese_disp=0;
                                    $total=0;
                                    $ese100=$markdetails['grand_total'];
                                    if($markdetails['type_id']=='140') //type1
                                    {
                                        
                                        $ese_disp = ceil(($ese100*0.25) + ($prac_mark*0.25));
                                        $total=$markdetails['CIA']+$ese_disp;
                                    }
                                    else if($markdetails['type_id']=='141') //type2
                                    {
                                      
                                        $ese_disp = ceil(($ese100*0.35) + ($prac_mark*0.15));
                                        $total=$markdetails['CIA']+$ese_disp;
                                    }
                                    else if($markdetails['type_id']=='142') //type3
                                    {
                                      
                                        $ese_disp = ceil(($ese100*0.15) + ($prac_mark*0.35));

                                       $total=$markdetails['CIA']+$ese_disp;
                                    } 
                                    else
                                    {
                                         $ese_disp=$markdetails['ESE'];
                                        $res_dip=strtoupper($markdetails['result']);

                                        $total=$markdetails['CIA']+$ese_disp;
                                    } 


                                   
                                     if($total>=90 || 100 <= $total)
                                    {

                                    $temp='91-100';
                                    $grade_point = '10';
                                    $grade_name='O';
                                    $res_dip="Pass";
                                    }
                                    else if($total>=81 || 90 <= $total)
                                    {
                                       $temp='81-90';
                                       $grade_point = '9';
                                       $grade_name='A+';
                                       $res_dip="Pass";
                                    }
                                    else if($total>=71 || 80 <= $total)
                                    {

                                     $temp='71-80';
                                     $grade_point = '8';
                                     $res_dip="Pass";
                                     $grade_name='A';
                                    }
                                    else if($total>=61 || 70 <= $total) 
                                    {
                                        $temp='61-70';
                                        $grade_point = '7';
                                        $grade_name='B+';
                                        $res_dip="Pass";
                                    }
                                    else if($total>=56 || 60 <= $total) 
                                     {
                                        $temp='56-60';
                                        $grade_point = '6';
                                        $grade_name='WH';
                                        $grade_name='B';
                                        $res_dip="Pass";
                                    }
                                    else if($total>=50 || 55 <= $total) 
                                     {
                                        $temp='50-55';
                                        $grade_point = '5';
                                        $grade_name='C';
                                        $res_dip="Pass";
                                    }
                                    else
                                    {
                                        $res_dip="Fail";
                                        $temp='0-49';
                                        $grade_point = '0';
                                        $grade_name='U';
                                    }


                                }
                                else
                                {
                                       //echo $markdetails['type_id']; exit();
                                        $ese_disp=$markdetails['ESE'];
                                        $res_dip=$markdetails['result'];
                                        $grade_point = $markdetails['grade_point'];
                                        $grade_name=$markdetails['grade_name'];
                                        $total=$markdetails['total'];
                                   
                                }
                                     

                                    //$grade_point = $markdetails['grade_point'];
                                    $total_disp=$total;
                                    $paper_type_id=$markdetails['paper_type'];
                                    $mark_type=$markdetails['mark_type'];
                                
                                $data.='<td>'.$markdetails['CIA'].'</td>';
                                $data.='<td>'.$ese_disp.'</td>';
                                $data.='<td>'.$total_disp.'</td>';
                                $data.='<td>'.$res_dip.'</td>';
                                $data.='<td>'.$markdetails['credit_points'].'</td>';
                                $data.='<td>'.$grade_name.'</td>';
                                $data.='<td>'.$grade_point.'</td>';
                                $data.='<td>'.$paper_type_id.'</td>';
                                
                                if($checkbox=='on')
                                {
                                    $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 FROM coe_practical_entry WHERE subject_map_id=".$markdetails['subject_map_id']." AND student_map_id='".$markdetails['student_map_id']."'  AND year='".$markdetails['year']."'  AND month='".$markdetails['month']."' AND mark_type='".$mark_typeid."'")->queryScalar();

                                    $get_dummyno = Yii::$app->db->createCommand("SELECT dummy_number FROM coe_dummy_number WHERE student_map_id=".$markdetails['student_map_id']." AND subject_map_id=".$markdetails['subject_map_id']." AND year=".$markdetails['year']." AND month=".$markdetails['month'])->queryone();

                                    if(!empty($get_dummyno['dummy_number']))
                                    {
                                        $get_avdetails = Yii::$app->db->createCommand("SELECT B.grand_total, B.booklet_sno,D.vanswer_packet_no FROM coe_val_barcode_verify_details B 
                                        JOIN coe_val_barcode_verify C ON C.val_barecode_id=B.val_barecode_id 
                                        JOIN coe_vanswer_packet D ON D.val_faculty_all_id=C.val_faculty_all_id
                                        WHERE B.dummy_number=".$get_dummyno['dummy_number'])->queryone();
                                        if(!empty($get_avdetails))
                                        {
                                            $data.='<td>'.$prac_mark.'</td>';
                                            $data.='<td>'.$get_avdetails['grand_total'].'</td>';
                                            $data.='<td>'.$get_avdetails['booklet_sno'].'</td>';
                                            $data.='<td>AV-'.$get_avdetails['vanswer_packet_no'].'</td>';
                                        }
                                        else
                                        {
                                            $data.='<td colspan=3></td>';
                                        }
                                    }
                                    else
                                    {
                                        $data.='<td colspan=3></td>';
                                    }
                                }
                            }
                        }
                        $data.='</tr>';  
                        //$prev_value=$markdetails['register_number'];
                        $sn++;                    
                        
                    }

                    //echo $sss; exit;

                    $data.='</tbody>';        
                    $data.='</table>';
                    if(isset($_SESSION['student_res_exporttemp'])){ unset($_SESSION['student_res_exporttemp']);}
                    $_SESSION['student_res_exporttemp'] = $data;
                    
                    echo "<div style='text-align:center; color:#F00;'>Click Excel to Download</div>";
            } 

        ?>

    </div>

    </div>
	</div>
	</div>


