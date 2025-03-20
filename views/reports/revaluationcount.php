<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\Batch;
use app\models\StudentMapping;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\db\Query;
use app\models\SubjectsMapping;
use app\models\MandatorySubjects;
use app\models\HallAllocate;

echo Dialog::widget();
$batch_id = isset($subjects->batch_mapping_id)?$subjects->batchMapping->coeBatch->coe_batch_id:"";


$this->title = "Revaluation Student Count Regular Report";
?>
<h1><?php echo $this->title;     ?></h1>
<style type="text/css">
.left-padding
{
    margin-left: -10px; 
    padding-right: -0px;
}
.righh-padding
{
    padding-right: -0px;
}
</style>

<div class="subjects-form">
<div class="box box-success">
<div class="box-body"> 
   
     <?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div>&nbsp;</div>

    <?php 
    $form = ActiveForm::begin([
                    'id' => 'regularcount-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",

                    ],
            ]); ?>

     <div class="col-xs-12 col-sm-12 col-lg-12">
            

            <div class="col-lg-2 col-sm-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'reval_entry_month',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) 
            ?>
            
        </div>
           <div class="col-sm-2">
                <?php if($Year1==''){$y=date("Y");}else{$y=$Year1;}?>
                       <?= $form->field($model, 'year')->textInput(['value' => "$y"]) ?>

                    </div>


             <div class="col-lg-2 col-sm-2">
               <?php echo $form->field($model1,'stu_batch_id')->widget(
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
                <br>
                <label><input type="checkbox" name="withsp"><b>With Arrear</b></label>
            </div>

            <div class="col-lg-2 col-sm-2" id="yearwise" style="display: none;">
                <br>
                <label><input type="checkbox" name="yearwise"><b>Year Wise</b></label>
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <br />
                <?= Html::submitButton( 'Get', ['class' =>  'btn btn-success']) ?>

                <?= Html::a('Reset', ['revaluationcount'], ['class' => 'btn btn-default']) ?> 
        </div>
    </div>

    <?php ActiveForm::end(); ?>

 
</div>
</div>
</div>

<?php 


if(!empty($content_data))
{

$print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/reports/revaluationcount-pdf'], [
                    'class'=>'btn btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 

$print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/reports/revaluationcount-excel'], [
                    'class'=>'btn btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row" >
            <div class="col-xs-12" >
                <div class="col-lg-12" style="text-align:right !important;"> 
                   
                    <?php echo $print_excel; ?> 
                   
                    <?php echo $print_pdf; ?> 
                    
                </div>
                <div class="col-lg-12"  style="overflow-x:auto;"> 
                    <?php 
                    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));

                if($yearwise==1)
                {
                    $academic='';

                    $ccmonth=$cmonth;
                    $ccyear=$cyear;

                    $wsp='';
                    if($withsp==1)
                    {
                        $wsp=' with Arrear';
                    }
                    else
                    {
                        $wsp=' Regular';
                    }
                     if($cmonth==29)
                    {
                        $year=$cyear-1;
                        $year1=$cyear;
                        $academic='No.of Complaints and Grievances '.$wsp.'<br> Academic Year: '.$year.' - '. $year1;
                    }
                    else
                    {
                        $academic='No.of Complaints and Grievances '.$wsp.'<br> Academic Year: '.$cyear;
                    }
                    
                    $data1='';
                    $data='';
                    $fordisplay='';
                    if($bat_val!='')
                    {
                        $s=1; $html = $head=$body ='';

                        $arr=$arr1=''; 
                        if($withsp==0)
                        {
                            $arr=' AND C.mark_type=27';
                            $arr1=' AND A.mark_type=27';
                        }
                           
                            $contentyear=array();
                            $totstu=0;
                            $recount=0;
                            foreach ($content_data as $key => $value) 
                            {
                               if($value['degree_code']=='MBA' && $cmonth==30 && $batch_name<=2018)
                                {
                                    $cyear=$cyear-1;
                                    $cmonth=32;
                                }
                                else if($value['degree_code']=='MBA' && $cmonth==29 && $batch_name<=2018)
                                {
                                    $cmonth=74;
                                }
                                
                                $stucount = Yii::$app->db->createCommand("SELECT count(DISTINCT D.coe_student_mapping_id) FROM coe_student_mapping as D JOIN coe_mark_entry_master as C ON C.student_map_id=D.coe_student_mapping_id WHERE C.year='" . $cyear . "' AND C.month='" . $cmonth . "' AND course_batch_mapping_id='".$value['coe_bat_deg_reg_id']."'".$arr)->queryScalar();

                                if($stucount==0 && $batch_name<=2018)
                                {
                                    if($value['degree_code']=='MBA' && $cmonth==30)
                                    {
                                        $cmonth=84;
                                        $stucount = Yii::$app->db->createCommand("SELECT count(DISTINCT D.coe_student_mapping_id) FROM coe_student_mapping as D JOIN coe_mark_entry_master as C ON C.student_map_id=D.coe_student_mapping_id WHERE C.year='" . $cyear . "' AND C.month='" . $cmonth . "' AND course_batch_mapping_id='".$value['coe_bat_deg_reg_id']."'".$arr)->queryScalar();
                                        $year=$cyear-1;
                                        $month=84;
                                        $year1=$cyear;
                                        $month1=87;
                                    }
                                    else if($value['degree_code']=='MBA' && $cmonth==29)
                                    {
                                        $cmonth=87;
                                        $stucount = Yii::$app->db->createCommand("SELECT count(DISTINCT D.coe_student_mapping_id) FROM coe_student_mapping as D JOIN coe_mark_entry_master as C ON C.student_map_id=D.coe_student_mapping_id WHERE C.year='" . $cyear . "' AND C.month='" . $cmonth . "' AND course_batch_mapping_id='".$value['coe_bat_deg_reg_id']."'".$arr)->queryScalar();
                                        $year=$cyear-1;
                                        $month=84;
                                        $year1=$cyear;
                                        $month1=87;
                                    }
                                    else
                                    {
                                        $cmonth=30;
                                        $cyear=$cyear-1;
                                        $stucount = Yii::$app->db->createCommand("SELECT count(DISTINCT D.coe_student_mapping_id) FROM coe_student_mapping as D JOIN coe_mark_entry_master as C ON C.student_map_id=D.coe_student_mapping_id WHERE C.year='" . $cyear . "' AND C.month='" . $cmonth . "' AND course_batch_mapping_id='".$value['coe_bat_deg_reg_id']."'".$arr)->queryScalar();
                                    }

                                    
                                }
                                else
                                {
                               
                                    if($value['degree_code']=='MBA' && $cmonth==32)
                                    {
                                        $year=$cyear;
                                        $month=32;
                                        $year1=0;
                                        $month1=0;
                                    }  
                                   else if($value['degree_code']=='MBA' && $cmonth==74)
                                    {
                                        $year=$cyear-1;
                                        $month=32;
                                        $year1=$cyear;
                                        $month1=74;
                                    }  
                                   else if($cmonth==29)
                                    {
                                         $year=$cyear-1;
                                        $month=30;
                                        $year1=$cyear;
                                        $month1=29;
                                    }
                                    else
                                    {
                                        $year=$cyear;
                                        $month=30;
                                        $year1=$cyear+1;
                                        $month1=29;
                                    }

                                    $stucount = Yii::$app->db->createCommand("SELECT count(DISTINCT D.coe_student_mapping_id) FROM coe_student_mapping as D JOIN coe_mark_entry_master as C ON C.student_map_id=D.coe_student_mapping_id WHERE C.year='" . $year . "' AND C.month='" . $month . "' AND course_batch_mapping_id='".$value['coe_bat_deg_reg_id']."'".$arr)->queryScalar();
                                }
                           

                                $semcheck = ConfigUtilities::SemCaluclation($year,$month,$value['coe_bat_deg_reg_id']);   
                                $semcheck1 = ConfigUtilities::SemCaluclation($year1,$month1,$value['coe_bat_deg_reg_id']);

                                if($value['degree_total_semesters']>=$semester)
                                {

                                 $body .='<tr>';
                                $body .='<td>'.$s.'</td>';
                                $body .='<td>'.$value['degree_code'].' '.strtoupper($value['programme_name']).'</td>';
                                $body .='<td>'.$stucount.'</td>';                           

                                $totstu=$totstu+$stucount;
            

                                $qry= "SELECT DISTINCT A.student_map_id FROM coe_revaluation as A 
                                        JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                                         WHERE A.year='" . $year . "' AND A.month='" . $month . "' AND B.batch_mapping_id='".$value['coe_bat_deg_reg_id']."' AND reval_status='YES'".$arr1;
                                //echo "<br>".$qry;
                                $cont1=Yii::$app->db->createCommand($qry)->queryAll(); 

                                $notIn = array_filter(['']);
                                foreach ($cont1 as $value1) 
                                {
                                    
                                    $notIn[$value1['student_map_id']]=$value1['student_map_id'];
                                  
                                }
                                
                                $qry1=  "SELECT DISTINCT A.student_map_id FROM coe_revaluation as A 
                                        JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                                         WHERE A.year='" . $year1 . "' AND A.month='" . $month1 . "' AND B.batch_mapping_id='".$value['coe_bat_deg_reg_id']."' AND reval_status='YES'".$arr1;

                                $cont11=Yii::$app->db->createCommand($qry1)->queryAll(); 

                                foreach ($cont11 as $value11) 
                                {
                                    if(!in_array($value11['student_map_id'], $notIn))
                                    {
                                        $notIn[$value11['student_map_id']]=$value11['student_map_id'];
                                    }
                                  
                                }

                                $notIn = array_filter($notIn);

                                $body .='<td>'.count($notIn).'</td>';

                                //$contentyear[]=array("year"=>$i,"count"=>count($notIn));
                                
                                $recount=$recount+count($notIn);

                                $body .='</tr>';

                                $s++;

                                }
                            }
                           

                            $body .='<tr>';
                            $body .='<td colspan=2 align=right>Total</td>';
                            $body .='<td>'.$totstu.'</td>';
                        

                                $body .='<td>'.$recount.'</td>';
                                $body .='</tr>';

                                $head.='<tr class="table-danger">                                    
                                            <th>S.No.</th> 
                                                        
                                            <th>Class</th>
                                            <th>No of Students</th>
                                             <th>Count</th>
                                        </tr>
                                <tbody>';
                           
                            $html .='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                            <tr>
                             <td align="center" style="border-right:0px; border-bottom:0px" >  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                        </td>
                              <td colspan=2 align="center"> 
                                  <center><b><font size="6px">'.$org_name.'</font></b></center>
                                  <center> <font size="3px">'.$org_address.'</font></center>
                                  <center class="tag_line"><b>'.$org_tagline.'</b></center>
                                  <br> 
                                  <center><b>Batch '.$batch_name.' </b></center> 
                                  <center><b> '.$academic.' </b></center>
                             </td>
                             <td align="center" style="border-left:0px; border-bottom:0px"> 
                                <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                            </td>
                            </tr>';

                            echo $data=$html.$head.$body.'</tbody> </table>'; 

                        

                        if(isset($_SESSION['revaluationcount']))
                        {
                            unset($_SESSION['revaluationcount']);
                        }
                        $_SESSION['revaluationcount'] = $data;

                    }
                    else
                    {
                        $header ='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <tr>
                         <td align="center" style="border-right:0px; border-bottom:0px" >  
                            <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                    </td>
                          <td align="center"> 
                              <center><b><font size="6px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font></center>
                              <center class="tag_line"><b>'.$org_tagline.'</b></center>
                              <br> 
                              <center><b> '.$academic.' </b></center>
                         </td>
                         <td align="center" style="border-left:0px; border-bottom:0px"> 
                            <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                        </td>
                        </tr></tbody> </table>';

                        $n=count($content_data)-1;

                        $grandtotstu=0;
                        $totrecount=0;
                        $totrecountar=0;

                         $arr=$arr1=''; 
                        if($withsp==0)
                        {
                            $arr=' AND C.mark_type=27';
                            $arr1=' AND A.mark_type=27';
                        }
                            $lp=0;
                            foreach ($content_data as $key => $value1) 
                            {
                                $contentyear=array();
                                $totstu=0;                           
                                $s=1; 
                                $html = $head=$body ='';


                                $semester ='';

                                $batname=$value1['year'];
                                $recount=0;

                                $cyear=$ccyear;
                                $cmonth=$ccmonth;

                                foreach ($value1["content"] as $key => $value) 
                                {
                                     

                                    if($s==1)
                                    {
                                       $semester = ConfigUtilities::SemCaluclation($cyear,$cmonth,$value['coe_bat_deg_reg_id']);
                                    }
                                    $batch_name = Yii::$app->db->createCommand("SELECT batch_name FROM coe_batch WHERE coe_batch_id = ".$value['coe_batch_id'])->queryScalar(); 

                                    if($value['degree_code']=='MBA' && $cmonth==30)
                                    {
                                        $cyear=$cyear-1;
                                        $cmonth=32;
                                    }
                                    else if($value['degree_code']=='MBA' && $cmonth==29)
                                    {
                                        $cmonth=74;
                                    }
                                    
                                  
                                    $stucount = Yii::$app->db->createCommand("SELECT count(DISTINCT D.coe_student_mapping_id) FROM coe_student_mapping as D JOIN coe_mark_entry_master as C ON C.student_map_id=D.coe_student_mapping_id WHERE C.year='" . $cyear . "' AND C.month='" . $cmonth . "' AND course_batch_mapping_id='".$value['coe_bat_deg_reg_id']."'".$arr)->queryScalar();

                                    if($value['degree_code']=='MBA' && $stucount==0 && $batch_name<=2018)
                                    {
                                        if($value['degree_code']=='MBA' && $cmonth==32)
                                        {
                                            $cmonth=84;

                                            $month=84;
                                            $year=$cyear;
                                            $year1=0;
                                            $month1=0;
                                        }
                                        else if($value['degree_code']=='MBA' && $cmonth==74)
                                        {
                                            $cmonth=87;

                                            $year=$cyear;
                                            $month=84;
                                            $year1=$cyear+1;
                                            $month1=87;
                                        }
                                   
                                        $stucount = Yii::$app->db->createCommand("SELECT count(DISTINCT D.coe_student_mapping_id) FROM coe_student_mapping as D JOIN coe_mark_entry_master as C ON C.student_map_id=D.coe_student_mapping_id WHERE C.year='" . $cyear . "' AND C.month='" . $cmonth . "' AND course_batch_mapping_id='".$value['coe_bat_deg_reg_id']."'".$arr)->queryScalar();
                                        
                                           
                                    }
                                    else
                                    {
                                   
                                        if($value['degree_code']=='MBA' && $cmonth==32)
                                        {
                                            $year=$cyear;
                                            $month=32;
                                            $year1=0;
                                            $month1=0;
                                        }  
                                       else if($value['degree_code']=='MBA' && $cmonth==74)
                                        {
                                            $year=$cyear-1;
                                            $month=32;
                                            $year1=$cyear;
                                            $month1=74;
                                        }  
                                       else if($cmonth==29)
                                        {
                                             $year=$cyear-1;
                                            $month=30;
                                            $year1=$cyear;
                                            $month1=29;
                                        }
                                        else
                                        {
                                            $year=$cyear;
                                            $month=30;
                                            $year1=$cyear+1;
                                            $month1=29;
                                        }
                                    }
                           
                                    if($stucount>0)
                                    {   
                                        $semcheck = ConfigUtilities::SemCaluclation($year,$month,$value['coe_bat_deg_reg_id']);   
                                        $semcheck1 = ConfigUtilities::SemCaluclation($year1,$month1,$value['coe_bat_deg_reg_id']);


                                        if($value['degree_total_semesters']>=$semester)
                                        {

                                            $body .='<tr>';
                                            $body .='<td>'.$s.'</td>';
                                            $body .='<td>'.$value['degree_code'].' '.strtoupper($value['programme_name']).'</td>';
                                            $body .='<td>'.$stucount.'</td>';                           

                                            $totstu=$totstu+$stucount;
                        
                                            $qry= "SELECT DISTINCT A.student_map_id FROM coe_revaluation as A 
                                                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                                                     WHERE A.year='" . $year . "' AND A.month='" . $month . "' AND B.batch_mapping_id='".$value['coe_bat_deg_reg_id']."' AND reval_status='YES'".$arr1;
                                            //echo "<br>".$qry; //exit;
                                            $cont1=Yii::$app->db->createCommand($qry)->queryAll(); 

                                            $notIn = array_filter(['']);
                                            foreach ($cont1 as $value1) 
                                            {
                                                
                                                $notIn[$value1['student_map_id']]=$value1['student_map_id'];
                                              
                                            }
                                            
                                            $qry1=  "SELECT DISTINCT A.student_map_id FROM coe_revaluation as A 
                                                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                                                     WHERE A.year='" . $year1 . "' AND A.month='" . $month1 . "' AND B.batch_mapping_id='".$value['coe_bat_deg_reg_id']."' AND reval_status='YES'".$arr1;

                                            $cont11=Yii::$app->db->createCommand($qry1)->queryAll(); 

                                            foreach ($cont11 as $value11) 
                                            {
                                                if(!in_array($value11['student_map_id'], $notIn))
                                                {
                                                    $notIn[$value11['student_map_id']]=$value11['student_map_id'];
                                                }
                                              
                                            }

                                            $notIn = array_filter($notIn);

                                            $body .='<td>'.count($notIn).'</td>';

                                            //$contentyear[]=array("year"=>$i,"count"=>count($notIn));
                                            
                                            $recount=$recount+count($notIn);
                                        
                                        
                                            $body .='</tr>';

                                            $s++;
                                        }

                                    }
                                }

                                
                                $grandtotstu=$grandtotstu+$totstu;
                                $totrecount=$totrecount+$recount;
                               
                               
                                

                                $body .='<tr>';
                                $body .='<td colspan=2 align=right>Total</td>';
                                $body .='<td>'.$totstu.'</td>';
                                $body .='<td>'.$recount.'</td>';
                                $body .='</tr>';

                                $head='<tr class="table-danger">                                    
                                            <th>S.No.</th>                                                     
                                            <th>Class</th>
                                            <th>No of Students</th>
                                             <th>Count</th>
                                        </tr>
                                <tbody>';
                              
                               

                                 $html ='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                                <tr>
                                 
                                  <td colspan=4 align="center"> 
                                     
                                      <center><b>Batch '.$batname.' </b></center> 
                                 </td>
                               
                                </tr>';

                                 $data=$html.$head.$body.'</tbody> </table>'; 

                                 
                                if($lp<$n)
                                {
                                    $data1.=$header.$data.'<pagebreak />';
                                }
                                else
                                {
                                   

                                    $data1.=$header.$data;
                                }
                             

                                $fordisplay.=$data;
                                

                                 $lp++;
                            }

                            $body1='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >';
                                        $body1 .='<tr>';
                                    $body1 .='<td colspan=2 align=right>Total Students: </td>';
                                    $body1 .='<td>'.$grandtotstu.'</td>';
                                    $body1 .='<td align=right>Total Revaluation Count: </td>';
                                    $body1 .='<td>'.$totrecount.'</td>';
                                    $body1 .='</tr></tbody> </table>';
                        

                        if(isset($_SESSION['revaluationcount']))
                        {
                            unset($_SESSION['revaluationcount']);
                        }

                         $_SESSION['revaluationcount'] = $data1.$body1;

                         echo $fordisplay.$body1;
                    }

                }
                else
                {

                    $academic='';

                    $ccmonth=$cmonth;
                    $ccyear=$cyear;

                    $wsp='';
                    if($withsp==1)
                    {
                        $wsp=' with Arrear';
                    }
                    else
                    {
                        $wsp=' Regular';
                    }
                    
                    $month_name = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='" . $cmonth . "'")->queryScalar();
                    
                    $academic='No.of Complaints and Grievances '.$wsp.' '.$month_name.' '.$cyear;
                    
                    
                    $data1='';
                    $data='';
                    $fordisplay='';
                    if($bat_val!='')
                    {
                        $s=1; $html = $head=$body ='';

                        $arr=$arr1=''; 
                        if($withsp==0)
                        {
                            $arr=' AND C.mark_type=27';
                            $arr1=' AND A.mark_type=27';
                        }

                            $contentyear=array();
                            $totstu=0;
                            $recount=0;
                            foreach ($content_data as $key => $value) 
                            {
                               
                                $stucount = Yii::$app->db->createCommand("SELECT count(DISTINCT D.coe_student_mapping_id) FROM coe_student_mapping as D JOIN coe_mark_entry_master as C ON C.student_map_id=D.coe_student_mapping_id WHERE C.year='" . $cyear . "' AND C.month='" . $cmonth . "' AND course_batch_mapping_id='".$value['coe_bat_deg_reg_id']."'".$arr)->queryScalar();
                               
                                if($value['degree_total_semesters']>=$semester)
                                {

                                 $body .='<tr>';
                                $body .='<td>'.$s.'</td>';
                                $body .='<td>'.$value['degree_code'].' '.strtoupper($value['programme_name']).'</td>';
                                $body .='<td>'.$stucount.'</td>';                           

                                $totstu=$totstu+$stucount;
            

                                $qry= "SELECT DISTINCT A.student_map_id FROM coe_revaluation as A 
                                        JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                                         WHERE A.year='" . $cyear . "' AND A.month='" . $cmonth . "' AND B.batch_mapping_id='".$value['coe_bat_deg_reg_id']."' AND reval_status='YES'".$arr1;
                                //echo "<br>".$qry;
                                $cont1=Yii::$app->db->createCommand($qry)->queryAll(); 

                                $notIn = array_filter(['']);
                                foreach ($cont1 as $value1) 
                                {
                                    
                                    $notIn[$value1['student_map_id']]=$value1['student_map_id'];
                                  
                                }
                                
                                $qry1=  "SELECT DISTINCT A.student_map_id FROM coe_revaluation as A 
                                        JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                                         WHERE A.year='" . $cyear . "' AND A.month='" . $cmonth . "' AND B.batch_mapping_id='".$value['coe_bat_deg_reg_id']."' AND reval_status='YES'".$arr1;

                                $cont11=Yii::$app->db->createCommand($qry1)->queryAll(); 

                                foreach ($cont11 as $value11) 
                                {
                                    if(!in_array($value11['student_map_id'], $notIn))
                                    {
                                        $notIn[$value11['student_map_id']]=$value11['student_map_id'];
                                    }
                                  
                                }

                                $notIn = array_filter($notIn);

                                $body .='<td>'.count($notIn).'</td>';

                                //$contentyear[]=array("year"=>$i,"count"=>count($notIn));
                                
                                $recount=$recount+count($notIn);

                                $body .='</tr>';

                                $s++;

                                }
                            }
                           

                            $body .='<tr>';
                            $body .='<td colspan=2 align=right>Total</td>';
                            $body .='<td>'.$totstu.'</td>';
                        

                                $body .='<td>'.$recount.'</td>';
                                $body .='</tr>';

                                $head.='<tr class="table-danger">                                    
                                            <th>S.No.</th> 
                                                        
                                            <th>Class</th>
                                            <th>No of Students</th>
                                             <th>Count</th>
                                        </tr>
                                <tbody>';
                           
                            $html .='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                            <tr>
                             <td align="center" style="border-right:0px; border-bottom:0px" >  
                                <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                        </td>
                              <td colspan=2 align="center"> 
                                  <center><b><font size="6px">'.$org_name.'</font></b></center>
                                  <center> <font size="3px">'.$org_address.'</font></center>
                                  <center class="tag_line"><b>'.$org_tagline.'</b></center>
                                  <br> 
                                  <center><b>Batch '.$batch_name.' </b></center> 
                                  <center><b> '.$academic.' </b></center>
                             </td>
                             <td align="center" style="border-left:0px; border-bottom:0px"> 
                                <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                            </td>
                            </tr>';

                            echo $data=$html.$head.$body.'</tbody> </table>'; 

                        

                        if(isset($_SESSION['revaluationcount']))
                        {
                            unset($_SESSION['revaluationcount']);
                        }
                        $_SESSION['revaluationcount'] = $data;

                    }
                    else
                    {
                        $header ='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <tr>
                         <td align="center" style="border-right:0px; border-bottom:0px" >  
                            <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                    </td>
                          <td align="center"> 
                              <center><b><font size="6px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font></center>
                              <center class="tag_line"><b>'.$org_tagline.'</b></center>
                              <br> 
                              <center><b> '.$academic.' </b></center>
                         </td>
                         <td align="center" style="border-left:0px; border-bottom:0px"> 
                            <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                        </td>
                        </tr></tbody> </table>';

                        $n=count($content_data)-1;

                        $grandtotstu=0;
                        $totrecount=0;
                        $totrecountar=0;


                        $arr=$arr1=''; 
                        if($withsp==0)
                        {
                            $arr=' AND C.mark_type=27';
                            $arr1=' AND A.mark_type=27';
                        }

                        
                            $lp=0;
                            foreach ($content_data as $key => $value1) 
                            {
                                $contentyear=array();
                                $totstu=0;                           
                                $s=1; 
                                $html = $head=$body ='';


                                $semester ='';

                                $batname=$value1['year'];
                                $recount=0;

                                $cyear=$ccyear;
                                $cmonth=$ccmonth;

                                foreach ($value1["content"] as $key => $value) 
                                {
                                     
                                    if($s==1)
                                    {
                                       $semester = ConfigUtilities::SemCaluclation($cyear,$cmonth,$value['coe_bat_deg_reg_id']);
                                    }
                                    $batch_name = Yii::$app->db->createCommand("SELECT batch_name FROM coe_batch WHERE coe_batch_id = ".$value['coe_batch_id'])->queryScalar(); 
                                  
                                    $stucount = Yii::$app->db->createCommand("SELECT count(DISTINCT D.coe_student_mapping_id) FROM coe_student_mapping as D JOIN coe_mark_entry_master as C ON C.student_map_id=D.coe_student_mapping_id WHERE C.year='" . $cyear . "' AND C.month='" . $cmonth . "' AND course_batch_mapping_id='".$value['coe_bat_deg_reg_id']."'".$arr)->queryScalar();                                    
                           
                                    if($stucount>0)
                                    {   
                                        
                                        if($value['degree_total_semesters']>=$semester)
                                        {

                                            $body .='<tr>';
                                            $body .='<td>'.$s.'</td>';
                                            $body .='<td>'.$value['degree_code'].' '.strtoupper($value['programme_name']).'</td>';
                                            $body .='<td>'.$stucount.'</td>';                           

                                            $totstu=$totstu+$stucount;
                        
                                            $qry= "SELECT DISTINCT A.student_map_id FROM coe_revaluation as A 
                                                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                                                     WHERE A.year='" . $cyear . "' AND A.month='" . $cmonth . "' AND B.batch_mapping_id='".$value['coe_bat_deg_reg_id']."' AND reval_status='YES'".$arr1;
                                            //echo "<br>".$qry; //exit;
                                            $cont1=Yii::$app->db->createCommand($qry)->queryAll(); 

                                            $notIn = array_filter(['']);
                                            foreach ($cont1 as $value1) 
                                            {
                                                
                                                $notIn[$value1['student_map_id']]=$value1['student_map_id'];
                                              
                                            }
                                            
                                            $qry1=  "SELECT DISTINCT A.student_map_id FROM coe_revaluation as A 
                                                    JOIN coe_subjects_mapping as B ON B.coe_subjects_mapping_id=A.subject_map_id  
                                                     WHERE A.year='" . $cyear . "' AND A.month='" . $cmonth . "' AND B.batch_mapping_id='".$value['coe_bat_deg_reg_id']."' AND reval_status='YES'".$arr1;

                                            $cont11=Yii::$app->db->createCommand($qry1)->queryAll(); 

                                            foreach ($cont11 as $value11) 
                                            {
                                                if(!in_array($value11['student_map_id'], $notIn))
                                                {
                                                    $notIn[$value11['student_map_id']]=$value11['student_map_id'];
                                                }
                                              
                                            }

                                            $notIn = array_filter($notIn);

                                            $body .='<td>'.count($notIn).'</td>';

                                            //$contentyear[]=array("year"=>$i,"count"=>count($notIn));
                                            
                                            $recount=$recount+count($notIn);
                                        
                                        
                                            $body .='</tr>';

                                            $s++;
                                        }

                                    }
                                }

                                
                                $grandtotstu=$grandtotstu+$totstu;
                                $totrecount=$totrecount+$recount;
                               
                               
                                

                                $body .='<tr>';
                                $body .='<td colspan=2 align=right>Total</td>';
                                $body .='<td>'.$totstu.'</td>';
                                $body .='<td>'.$recount.'</td>';
                                $body .='</tr>';

                                $head='<tr class="table-danger">                                    
                                            <th>S.No.</th>                                                     
                                            <th>Class</th>
                                            <th>No of Students</th>
                                             <th>Count</th>
                                        </tr>
                                <tbody>';
                              
                               

                                 $html ='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                                <tr>
                                 
                                  <td colspan=4 align="center"> 
                                     
                                      <center><b>Batch '.$batname.' </b></center> 
                                 </td>
                               
                                </tr>';

                                 $data=$html.$head.$body.'</tbody> </table>'; 

                                 
                                if($lp<$n)
                                {
                                    $data1.=$header.$data.'<pagebreak />';
                                }
                                else
                                {
                                   

                                    $data1.=$header.$data;
                                }
                             

                                $fordisplay.=$data;
                                

                                 $lp++;
                            }

                            $body1='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >';
                                        $body1 .='<tr>';
                                    $body1 .='<td colspan=2 align=right>Total Students: </td>';
                                    $body1 .='<td>'.$grandtotstu.'</td>';
                                    $body1 .='<td align=right>Total Revaluation Count: </td>';
                                    $body1 .='<td>'.$totrecount.'</td>';
                                    $body1 .='</tr></tbody> </table>';
                        

                        if(isset($_SESSION['revaluationcount']))
                        {
                            unset($_SESSION['revaluationcount']);
                        }

                         $_SESSION['revaluationcount'] = $data1.$body1;

                         echo $fordisplay.$body1;
                    }
                }
                ?>


                </div>
            </div>
         </div>
    </div>
</div>

<?php 
}
?>

</div>