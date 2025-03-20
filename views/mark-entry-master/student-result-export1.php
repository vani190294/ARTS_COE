<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\db\Query;
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
use app\models\MarkEntryMaster;
use app\models\StuInfo;
error_reporting(0);
echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title=ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Results Copy";

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
                            'id' => 'exam_month', 
                            'name' => 'month',                           
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="form-group col-lg-3 col-sm-3"><br />
            <input type="Submit" id="student_res_export" onclick="spinner();"  class="btn btn-success" value="Submit"> <!--onclick="getstudentresults();"-->
        </div>       
    </div>

    <?php ActiveForm::end(); ?>


    <?php print_r($internet_copy); exit; if(!empty($internet_copy)){ ?>

    <div id="display_results_stu">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-3 col-sm-10 col-lg-10">
            </div>    
                <div class="col-xs-3 col-sm-2 col-lg-2">
                    <?php 
                       /* echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry-master/student-result-export-pdf'], [
                        'class'=>'pull-right btn btn-primary', 
                        'target'=>'_blank', 
                        'data-toggle'=>'tooltip', 
                        'title'=>'Will open the generated PDF file in a new window'
                        ]);*/

                  echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-export-student-result','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));

                 
                    ?>
                </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-lg-12">
            <?php  $data ='<table border=1 width="100%" class="table table-responsive table-striped" align="center" ><tbody align="center">'; 
    
                    $data.='<tr>
                                         <th><center>Batch</center></th>
                                         <th><center>Programme</center></th>                       
                               <th><center>RegisterNo</center></th>
                               <th><center>Status</center></th>
                               <th><center>Semester</center></th>
                               <th><center>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).'Code</center></th>
                               <th><center>CIA Marks</center></th>
                               <th><center>ESE Marks</center></th>
                               <th><center>Total</center></th>
                               <th><center>Result</center></th>
                               <th><center>Credit Points</center></th>
                               <th><center>Grade</center></th>
                               <th><center>Grade Point</center></th>
                                <th><center>Subject Type</center></th>
                                   <th><center>Exam Type</center></th>
                                                        
                            </tr>';    

                    $prev_value="";
                    $prev_value_br="";
                    $sn=1;
                    foreach($internet_copy as $markdetails)
                    {
                        $semester_detain=$markdetails['semester_detain'];

                        $regular_sem = ConfigUtilities::semCaluclation($_POST['year'], $_POST['month'], $markdetails['batch_mapping_id']);

                        if($markdetails['status_category_type_id']!=4)
                        {

                            $curr_value=$markdetails['register_number'];
                            $curr_value_br=$markdetails['register_number'];
                             $subject_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['paper_type_id']."")->queryScalar();
                             $exam_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['mark_type']."")->queryScalar();
                              $sudent_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['status_category_type_id']."")->queryScalar();
                             $rejoin="Detain/Rejoin";

                            $stu_withheld = 1;
                            $withheld_list = MarkEntryMaster::findOne(['month'=>$_POST['month'],'year'=>$_POST['year'],'student_map_id'=>$markdetails['student_map_id'],'withheld'=>'w']);
                            $stu_withheld = !empty($withheld_list)?2:1;

                            $data.='<tr>';

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
                            
                            $data.='<td>'.$sudent_type.'</td>';
                             
                             }
                            $data.='<td>'.$markdetails['semester'].'</td>';
                            $data.='<td>'.$markdetails['subject_code'].'</td>';
                            /*$data.='<td>'.$markdetails['CIA'].'</td>';
                            $data.='<td>'.$markdetails['ESE'].'</td>';
                            $data.='<td>'.$markdetails['total'].'</td>';
                            $data.='<td>'.$markdetails['result'].'</td>';*/
                            

                            if($markdetails['ESE_max']==0 && $markdetails['CIA_max']==0 && $markdetails['ESE_min']==0 && $markdetails['CIA_min']==0)
                            {

                                if($markdetails['result']=="Pass")
                               {
                                $data.='<td colspan=3>COMPLETED</td>';
                                $data.='<td>'.strtoupper($markdetails['result']).'</td>';
                                $data.='<td colspan=3>COMPLETED</td>';
                                $data.='<td>'.$subject_type.'</td>';
                                 $data.='<td>'.$exam_type.'</td>';
                             }

                             else
                             {
                                 
                                $data.='<td colspan=3> NOT COMPLETED</td>';
                                $data.='<td>'.strtoupper($markdetails['result']).'</td>';
                                $data.='<td colspan=3> NOT COMPLETED</td>';
                                $data.='<td>'.$subject_type.'</td>';
                                 $data.='<td>'.$exam_type.'</td>';                             



                             }
                                
                            }
                            else
                            {
                                if($stu_withheld==2)
                                {
                                    $ese_disp='-';
                                    $res_dip='WITHHELD';
                                    $grade_name='WH';
                                    $grade_point = '0';
                                    $total_disp=$markdetails['CIA'];
                                }
                                else
                                {
                                    $ese_disp=$markdetails['ESE'];
                                    $res_dip=strtoupper($markdetails['result']);
                                    $grade_name = $markdetails['result']=='Absent' || $markdetails['result']=='ABSENT' || $markdetails['result']=='absent' ? 'AB' : strtoupper($markdetails['grade_name']);
                                    $grade_point = $markdetails['grade_point'];
                                    $total_disp=$markdetails['total'];
                                    $paper_type_id=$subject_type;
                                    
                                }
                                $data.='<td>'.$markdetails['CIA'].'</td>';
                                $data.='<td>'.$ese_disp.'</td>';
                                $data.='<td>'.$total_disp.'</td>';
                                $data.='<td>'.$res_dip.'</td>';
                                $data.='<td>'.$markdetails['credit_points'].'</td>';
                                $data.='<td>'.$grade_name.'</td>';
                                $data.='<td>'.$grade_point.'</td>';
                                $data.='<td>'.$paper_type_id.'</td>';
                                 $data.='<td>'.$exam_type.'</td>';

                            }

                            
                            $data.='</tr>';  
                        }
                        else if($markdetails['status_category_type_id']==4 && $regular_sem<$semester_detain && $markdetails['mark_type']==27)
                        {

                            $curr_value=$markdetails['register_number'];
                            $curr_value_br=$markdetails['register_number'];
                             $subject_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['paper_type_id']."")->queryScalar();
                             $exam_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['mark_type']."")->queryScalar();
                              $sudent_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['status_category_type_id']."")->queryScalar();
                             $rejoin="Detain/Rejoin";

                            $stu_withheld = 1;
                            $withheld_list = MarkEntryMaster::findOne(['month'=>$_POST['month'],'year'=>$_POST['year'],'student_map_id'=>$markdetails['student_map_id'],'withheld'=>'w']);
                            $stu_withheld = !empty($withheld_list)?2:1;

                            $data.='<tr>';

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
                            
                            $data.='<td>'.$sudent_type.'</td>';
                             
                             }
                            $data.='<td>'.$markdetails['semester'].'</td>';
                            $data.='<td>'.$markdetails['subject_code'].'</td>';
                            /*$data.='<td>'.$markdetails['CIA'].'</td>';
                            $data.='<td>'.$markdetails['ESE'].'</td>';
                            $data.='<td>'.$markdetails['total'].'</td>';
                            $data.='<td>'.$markdetails['result'].'</td>';*/
                            

                            if($markdetails['ESE_max']==0 && $markdetails['CIA_max']==0 && $markdetails['ESE_min']==0 && $markdetails['CIA_min']==0)
                            {

                                if($markdetails['result']=="Pass")
                               {
                                $data.='<td colspan=3>COMPLETED</td>';
                                $data.='<td>'.strtoupper($markdetails['result']).'</td>';
                                $data.='<td colspan=3>COMPLETED</td>';
                                $data.='<td>'.$subject_type.'</td>';
                                 $data.='<td>'.$exam_type.'</td>';
                             }

                             else
                             {
                                 
                                $data.='<td colspan=3> NOT COMPLETED</td>';
                                $data.='<td>'.strtoupper($markdetails['result']).'</td>';
                                $data.='<td colspan=3> NOT COMPLETED</td>';
                                $data.='<td>'.$subject_type.'</td>';
                                 $data.='<td>'.$exam_type.'</td>';                             



                             }
                                
                            }
                            else
                            {
                                if($stu_withheld==2)
                                {
                                    $ese_disp='-';
                                    $res_dip='WITHHELD';
                                    $grade_name='WH';
                                    $grade_point = '0';
                                    $total_disp=$markdetails['CIA'];
                                }
                                else
                                {
                                    $ese_disp=$markdetails['ESE'];
                                    $res_dip=strtoupper($markdetails['result']);
                                    $grade_name = $markdetails['result']=='Absent' || $markdetails['result']=='ABSENT' || $markdetails['result']=='absent' ? 'AB' : strtoupper($markdetails['grade_name']);
                                    $grade_point = $markdetails['grade_point'];
                                    $total_disp=$markdetails['total'];
                                    $paper_type_id=$subject_type;
                                }
                                $data.='<td>'.$markdetails['CIA'].'</td>';
                                $data.='<td>'.$ese_disp.'</td>';
                                $data.='<td>'.$total_disp.'</td>';
                                $data.='<td>'.$res_dip.'</td>';
                                $data.='<td>'.$markdetails['credit_points'].'</td>';
                                $data.='<td>'.$grade_name.'</td>';
                                $data.='<td>'.$grade_point.'</td>';
                                $data.='<td>'.$paper_type_id.'</td>';
                                 $data.='<td>'.$exam_type.'</td>';

                            }

                            
                            $data.='</tr>';  
                        }
                        else if($markdetails['status_category_type_id']==4 && $regular_sem==$semester_detain && $markdetails['mark_type']!=27)
                        {

                            $curr_value=$markdetails['register_number'];
                            $curr_value_br=$markdetails['register_number'];
                             $subject_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['paper_type_id']."")->queryScalar();
                             $exam_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['mark_type']."")->queryScalar();
                              $sudent_type = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id =".$markdetails['status_category_type_id']."")->queryScalar();
                             $rejoin="Detain/Rejoin";

                            $stu_withheld = 1;
                            $withheld_list = MarkEntryMaster::findOne(['month'=>$_POST['month'],'year'=>$_POST['year'],'student_map_id'=>$markdetails['student_map_id'],'withheld'=>'w']);
                            $stu_withheld = !empty($withheld_list)?2:1;

                            $data.='<tr>';

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
                            
                            $data.='<td>'.$sudent_type.'</td>';
                             
                             }
                            $data.='<td>'.$markdetails['semester'].'</td>';
                            $data.='<td>'.$markdetails['subject_code'].'</td>';
                            /*$data.='<td>'.$markdetails['CIA'].'</td>';
                            $data.='<td>'.$markdetails['ESE'].'</td>';
                            $data.='<td>'.$markdetails['total'].'</td>';
                            $data.='<td>'.$markdetails['result'].'</td>';*/
                            

                            if($markdetails['ESE_max']==0 && $markdetails['CIA_max']==0 && $markdetails['ESE_min']==0 && $markdetails['CIA_min']==0)
                            {

                                if($markdetails['result']=="Pass")
                               {
                                $data.='<td colspan=3>COMPLETED</td>';
                                $data.='<td>'.strtoupper($markdetails['result']).'</td>';
                                $data.='<td colspan=3>COMPLETED</td>';
                                $data.='<td>'.$subject_type.'</td>';
                                 $data.='<td>'.$exam_type.'</td>';
                             }

                             else
                             {
                                 
                                $data.='<td colspan=3> NOT COMPLETED</td>';
                                $data.='<td>'.strtoupper($markdetails['result']).'</td>';
                                $data.='<td colspan=3> NOT COMPLETED</td>';
                                $data.='<td>'.$subject_type.'</td>';
                                 $data.='<td>'.$exam_type.'</td>';                             



                             }
                                
                            }
                            else
                            {
                                if($stu_withheld==2)
                                {
                                    $ese_disp='-';
                                    $res_dip='WITHHELD';
                                    $grade_name='WH';
                                    $grade_point = '0';
                                    $total_disp=$markdetails['CIA'];
                                }
                                else
                                {
                                    $ese_disp=$markdetails['ESE'];
                                    $res_dip=strtoupper($markdetails['result']);
                                    $grade_name = $markdetails['result']=='Absent' || $markdetails['result']=='ABSENT' || $markdetails['result']=='absent' ? 'AB' : strtoupper($markdetails['grade_name']);
                                    $grade_point = $markdetails['grade_point'];
                                    $total_disp=$markdetails['total'];
                                    $paper_type_id=$subject_type;
                                    
                                }
                                $data.='<td>'.$markdetails['CIA'].'</td>';
                                $data.='<td>'.$ese_disp.'</td>';
                                $data.='<td>'.$total_disp.'</td>';
                                $data.='<td>'.$res_dip.'</td>';
                                $data.='<td>'.$markdetails['credit_points'].'</td>';
                                $data.='<td>'.$grade_name.'</td>';
                                $data.='<td>'.$grade_point.'</td>';
                                $data.='<td>'.$paper_type_id.'</td>';
                                 $data.='<td>'.$exam_type.'</td>';

                            }

                            
                            $data.='</tr>';  
                        }

                        $prev_value=$markdetails['register_number'];
                        $sn++;                    
                        
                    }
                    $data.='</tbody>';        
                    $data.='</table>';
                    if(isset($_SESSION['student_res_export'])){ unset($_SESSION['student_res_export']);}
                    $_SESSION['student_res_export'] = $data;

                    echo $data; ?>
        </div>

    </div>

<?php }?>

    </div>
	</div>
	</div>


