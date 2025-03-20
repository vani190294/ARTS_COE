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

use app\models\ValuationSettings;

$ValuationSettings = ValuationSettings::findOne(1);

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Temp Internet Copy II";

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
            <?= $form->field($model, 'year')->textInput(['name'=>'year','value'=>$ValuationSettings['current_exam_year'],'id' => 'exam_year',]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => $galley->getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month_ic', 
                            'name' => 'month',   
                            'value'=> $ValuationSettings['current_exam_month']                        
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
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

         <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'mark_type')->radioList(ExamTimetable::getExamType()); ?>
           
            </div>

        <div class="form-group col-lg-2 col-sm-2"><br />
            <input type="Submit" id="student_res_export" name="student_res_export" class="btn btn-success" value="Submit">
        </div>       
    </div>

    <?php ActiveForm::end(); 

    if(isset($internet_copy) && !empty($internet_copy))
    {?>

        <div id="display_results_stu">
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
            $data ='';
            
                $data ='<table border=1 width="100%" class="table table-responsive table-striped" align="center" ><tbody align="center">'; 
                $data.='<tr><td colspan="21" align="center"><b>Internet Copy II Temp Report <span color="red">(Regular Grade Based on Absolute Grade)</span></b><td></tr>';
                $data.='</table>'; 

                  $data.='<table border=1 width="100%" class="table table-responsive table-striped" align="center" ><tbody align="center">'; 
    
                    $data.='<tr>
                                <th><center>Sno</center></th>
                                <th><center>Batch</center></th>
                                <th><center>Programme</center></th>
                                <th><center>Exam Type</center></th>                       
                               <th><center>RegisterNo</center></th>
                               <th><center>Status</center></th>
                               <th><center>Semester</center></th>
                               <th><center>Course Code</center></th>
                                <th><center>Credit Points</center></th>
                               <th><center>CIA</center></th>
                               <th><center>ESE</center></th>
                               <th><center>Total</center></th>
                               <th><center>Result</center></th>
                               <th><center>Grade Name</center></th>
                               <th><center>Grade Point</center></th>
                                <th><center>Subject Type</center></th>                                    
                                  <th><center>Practical 100</center></th> 
                                  <th><center>ESE 100</center></th>
                                 <th><center>Boolet No.</center></th>  
                                 <th><center>AV No.</center></th> 
                                                    
                            </tr>';    
            
                    $prev_value="";
                    $prev_value_br="";
                    $sn=1;
                    foreach($internet_copy as $markdetails)
                    {
                        $curr_value=$markdetails['register_number'];
                        $curr_value_br=$markdetails['register_number'];
                         
                        $rejoin="Detain/Rejoin";

                        $absent_count = Yii::$app->db->createCommand("select count(coe_absent_entry_id) from coe_absent_entry where absent_student_reg =".$markdetails['student_map_id']." AND exam_subject_id =".$markdetails['subject_map_id']." AND exam_year=".$_POST['year']." AND exam_month=".$_POST['month'])->queryScalar();

                        $data.='<tr>';
                        $data.='<td>'.$sn.'</td>';
                        $data.='<td>'.$markdetails['batch_name'].'</td>';
                        $data.='<td>'.$markdetails['programme_code'].'</td>';
                        $data.='<td>'.$markdetails['mark_type'].'</td>';  
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
                        $data.='<td>'.$markdetails['credit_points'].'</td>';

                        if($absent_count>0)
                        {
                            $data.='<td>'.$markdetails['CIA'].'</td>';
                            $data.='<td>0</td>';
                            $data.='<td>'.$markdetails['CIA'].'</td>';
                            $data.='<td>Absent</td>';
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
                                
                                $ese_disp=$markdetails['ESE'];
                                $res_dip=strtoupper($markdetails['result']);
                                $temp=$markdetails['grade_name'];
                                $grade_name = $markdetails['result']=='Absent' || $markdetails['result']=='ABSENT' || $markdetails['result']=='absent' ? 'AB' : strtoupper($temp);
                                $grade_point = $markdetails['grade_point'];
                                $total_disp=$markdetails['total'];
                                $paper_type_id=$markdetails['paper_type'];
                                $mark_type=$markdetails['mark_type'];
                                
                                $data.='<td>'.$markdetails['CIA'].'</td>';
                                $data.='<td>'.$ese_disp.'</td>';
                                $data.='<td>'.$total_disp.'</td>';
                                $data.='<td>'.$res_dip.'</td>';
                                
                                $data.='<td>'.$grade_name.'</td>';
                                $data.='<td>'.$grade_point.'</td>';
                                $data.='<td>'.$paper_type_id.'</td>';
                                //$data.='<td>'.$ese100['category_type_id_marks'].'</td>';
                                $get_dummyno = Yii::$app->db->createCommand("SELECT dummy_number FROM coe_dummy_number 
                                WHERE student_map_id=".$markdetails['student_map_id']." AND subject_map_id=".$markdetails['subject_map_id']." AND year=".$_POST['year']." AND month=".$_POST['month'])->queryone();

                                if(!empty($get_dummyno))
                                {
                                    $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 FROM coe_practical_entry WHERE subject_map_id=".$markdetails['subject_map_id']." AND student_map_id='".$markdetails['student_map_id']."'  AND year='".$_POST['year']."'  AND month='".$_POST['month']."' AND mark_type='".$markdetails['exam_type']."'")->queryScalar();

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
                                        $data.='<td></td><td></td><td></td><td></td>';
                                    }
                                }
                                else
                                {
                                    $data.='<td></td><td></td><td></td><td></td>';
                                }

                                //$data.='<td></td><td></td><td></td>';

                            }
                        }
                        
                        //echo  $data.='</tr>';  exit;
                        $prev_value=$markdetails['register_number'];
                        $sn++;                    
                        
                    }
                    $data.='</tbody>';        
                    $data.='</table>';
                    if(isset($_SESSION['student_res_exporttemp'])){ unset($_SESSION['student_res_exporttemp']);}
                    $_SESSION['student_res_exporttemp'] = $data;
                    echo $data;
            

            ?>

        </div>

    <?php }?>

    </div>
	</div>
	</div>


