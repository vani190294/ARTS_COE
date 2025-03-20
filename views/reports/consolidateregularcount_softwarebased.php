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


echo Dialog::widget();
$batch_id = isset($subjects->batch_mapping_id)?$subjects->batchMapping->coeBatch->coe_batch_id:"";


$this->title = "CONSOLIDATE REGULAR COUNT REPORT";
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
                <?= $form->field($model, 'month')->dropdownList(['29' => 'April/May', '30' => 'Oct/Nav'], ['prompt'=>'Select Month', 'options'=>[$month=> ["Selected"=>'selected']]]) ?>

            </div>
           <div class="col-sm-2">
                <?php if($Year1==''){$y=date("Y");}else{$y=$Year1;}?>
                       <?= $form->field($model, 'year')->textInput(['value' => "$y"]) ?>

                    </div>
            
            <div class="col-lg-4 col-sm-4">
                <br />
                <?= Html::submitButton( 'Get', ['class' =>  'btn btn-success']) ?>

                <?= Html::a('Reset', ['consolidateregularcount'], ['class' => 'btn btn-default']) ?> 
        </div>
    </div>

    <?php ActiveForm::end(); ?>

<?php $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/reports/consolidateregularcount-pdf'], [
                    'class'=>'btn btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]);  
$print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/reports/consolidateregularcount-excel'], [
                    'class'=>'btn btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
?>

 
</div>
</div>
</div>

<?php 
if(!empty($content_data))
{

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
                    $html = $body ='';
                    $month = Yii::$app->db->createCommand("select description from coe_category_type where coe_category_type_id='" . $month . "'")->queryScalar();

                    $html .='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <tr>
                         
                          <td colspan=16 align="center"> 
                              <center><b><font size="6px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font></center>
                              <center> Phone : <b>'.$org_phone.'</b></center>
                              <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                         </td>
                        
                        </tr> ';
                        $html .= '<tr>
                            <td colspan=10 align="center"><b>Examinations: ' . $month . ' ' . $Year1 .'
                            </b></td>
                        </tr>';
                        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                        $transfer = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%TRANSFER%'")->queryScalar();

                        $rejoin_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%rejoin%'")->queryScalar();
                         $latearl = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Lateral Entry%'")->queryScalar();
                         $i=0; 
                         $total_ug_stu=0; $total_pg_stu=0; 
                         $total_ug_subject=0; $total_pg_subject=0;
                         $total_ug_script = $total_pg_script=0;
                         $total_ug_pract = $total_pg_pract=0;
                         $total_ug_pract_count = $total_pg_pract_count=0;
                         $total_ug_rejoin = $total_pg_rejoin=0;
                         $total_ug_grand_script = $total_pg_grand_script =0;
                         
                        $total_ugsw_based_subject=0;
                        $total_ugsw_based_script=0;
                        foreach ($content_data as $key => $value1) 
                       {


                       if(!empty($value1["content"]))
                       {
                            $yrs=$value1["year"];
                            $body .=' <tr>
                            <td colspan=16 align="center" style="background-color: #2173bc; color: #fff;">Batch: '.$value1["year"].' Semester: '.$semester.'</td>
                        </tr>
                        <tr class="table-danger">
                            
                            <th>S.No.</th>                
                            <th>Class</th>
                            <th>No of Students</th>
                            <th>No of Theory Subjects</th>
                            <th>No of Theory Scripts</th>
                            <th>No of Practical and Other Courses</th>
                            <th>No of Practical and other Scripts</th>
                            <th>No of Software Based Courses</th>
                            <th>No of Software Based Scripts</th>
                           <th>No of additional scripts for rejoin students</th>
                            <th>Total No of Scripts</th>
                        </tr>   
                       
                        ';

                        $sn_no = 1;

                        $detain_total = $ug_count = $pg_count = $others_count_total = $transfer_total = $rejoin_total =$latearl_total= $disc_total=$first_year_count = $GRAND_TOTAL = $total_strength = $rejoin_add_total = 0;

                         $detain_total_sem = $transfer_total_sem = $rejoin_total_sem = $disc_total_sem=0;

                        $detain_total_phd = $ug_count_phd = $pg_count_phd = $others_count_total_phd = $transfer_total_phd = $rejoin_total_phd = $ACTIVE_count_php = $disc_total_phd =$latearl_total_phd=$first_year_count_phd  = $GRAND_TOTAL_phd = $total_strength_phd = $printed = $phd_printed  = 0;

                        $pg_general_count=0;
                        $rejoin_add_dip =0;

                        $subjects_count=0;
                        $total_subject_count=0;
                        $tot_no_of_sub=0;

                        $other_subjects_count=0;
                        $total_other_subject_count=0;
                        $tot_no_of_other=0;

                        $total_no_of_script=0;

                        $total_grand_rejoin=0;

                        $mat_subject_count =0;
                        $total_sw_based_subject=0;
                        $total_sw_based_script=0;
                        foreach ($value1["content"] as $key => $value) 
                        {
                            
                            $detain_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type])->all();
                            $detain_total +=count($detain_count); 
                            $detain_disp = count($detain_count)==0?'0':count($detain_count);

                            $disc_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type])->all();
                            $disc_total +=count($disc_count); 
                            $disc_disp = count($disc_count)==0?'0':count($disc_count);


                            //$rejoin_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$rejoin_type])->all();
                            $rejoin_query = new Query();
                            $rejoin_query->select(['distinct (student_rel_id)'])
                            ->from('coe_student_mapping a')
                            ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $rejoin_type])
                               ->andWhere(['<=', 'semester_detain', $semester]);
                            //echo "<br>".$rejoin_query->createCommand()->getRawSql();
                            $rejoin_result = $rejoin_query->createCommand()->queryAll();
                            $rejoin_count=count($rejoin_result); 
                            $rejoin_total +=count($rejoin_result); 
                            $rejoin_dip = ($rejoin_count)==0?'0':($rejoin_count);


                            $transfer_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$transfer])->all();
                            $transfer_total +=count($transfer_count); 
                            $transfer_count_dip = count($transfer_count)==0?'0':count($transfer_count);


                            $lateral_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$latearl])->all();
                            $latearl_total +=count($lateral_count); 
                            $lateral_count_dip = count($lateral_count)==0?'0':count($lateral_count);
                            

                            $others_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['NOT IN','status_category_type_id',[$transfer,$rejoin_type,$det_disc_type,$det_cat_type,$latearl]])->all();

                            $disc_count1 = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type]) ->andWhere(['>', 'semester_detain', $semester])->all();
                            $det_count1 = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['>', 'semester_detain', $semester])->all();

                            $ott=count($disc_count1)+count($det_count1);

                            $others_count_total +=(count($others_count)==0 || count($others_count)=='' )?0:count($others_count); 
                            $other_disp = count($others_count)==0?'0':count($others_count);
                            
                            $other_disp = $other_disp + $ott;

                            $ACTIVE_count = $other_disp+$transfer_count_dip+$rejoin_dip+$lateral_count_dip; 
                            $active_final_disp = count($ACTIVE_count)==0?'0':$ACTIVE_count;

                            $body_phd= '';  

                            $man_count=0;
                            $man_query = new Query();
                            $man_query->select('D.subject_code')
                                ->from('coe_bat_deg_reg A')
                                ->join('JOIN', 'coe_programme B', 'A.coe_programme_id=B.coe_programme_id')
                                ->join('JOIN', 'coe_degree C', 'A.coe_degree_id=C.coe_degree_id')
                                ->join('JOIN', 'coe_mandatory_subcat_subjects E', 'A.coe_bat_deg_reg_id=E.batch_map_id')
                                ->join('JOIN', 'coe_mandatory_subjects D', 'D.coe_mandatory_subjects_id=E.man_subject_id')
                                ->join('JOIN', 'coe_category_type G', 'G.coe_category_type_id=E.paper_type_id')
                                ->join('JOIN', 'coe_category_type H', 'H.coe_category_type_id=E.subject_type_id')
                                ->join('JOIN', 'coe_category_type I', 'I.coe_category_type_id=E.course_type_id')
                                ->join('JOIN', 'coe_batch F', 'A.coe_batch_id=F.coe_batch_id')
                                ->where([ 'D.batch_mapping_id' => $value['course_batch_mapping_id'], 'D.semester' => $semester,'E.is_additional'=>'NO'])
                                ->andWhere(['NOT LIKE','D.subject_code','AC'])->groupBy('subject_code')->orderBy('paper_no');
                            $man_subjects = $man_query->createCommand()->queryAll();
                            $man_count = count($man_subjects)==0?'0':count($man_subjects);
                           
                            $query = new Query();
                            $query->select('E.paper_type_id,E.subject_type_id')
                                ->from('coe_bat_deg_reg A')
                                ->join('JOIN', 'coe_programme B', 'A.coe_programme_id=B.coe_programme_id')
                                ->join('JOIN', 'coe_degree C', 'A.coe_degree_id=C.coe_degree_id')
                                ->join('JOIN', 'coe_subjects_mapping E', 'A.coe_bat_deg_reg_id=E.batch_mapping_id')
                                ->join('JOIN', 'coe_subjects D', 'E.subject_id=D.coe_subjects_id')
                                ->join('JOIN', 'coe_category_type G', 'G.coe_category_type_id=E.paper_type_id')
                                ->join('JOIN', 'coe_category_type H', 'H.coe_category_type_id=E.subject_type_id')
                                ->join('JOIN', 'coe_category_type I', 'I.coe_category_type_id=E.course_type_id')
                                ->join('JOIN', 'coe_batch F', 'A.coe_batch_id=F.coe_batch_id')
                                ->where(['E.batch_mapping_id' => $value['course_batch_mapping_id'], 'E.semester' => $semester])->groupBy('subject_code')->orderBy('paper_no');
                            $subject = $query->createCommand()->queryAll();
                            $common_subject=0; $sw_based_subject=0; $prac_mand_subject=0; $elective_subject=0;
                            foreach ($subject as $subject1) 
                            {
                                if($subject1['subject_type_id']=='13')
                                {
                                    if($subject1['paper_type_id']=='8' || $subject1['paper_type_id']=='9' || $subject1['paper_type_id']=='121')
                                    {
                                        $common_subject=$common_subject+1;
                                    }
                                    else if($subject1['paper_type_id']=='136')
                                    {
                                        $sw_based_subject=$sw_based_subject+1;
                                    }
                                    else
                                    {
                                        $prac_mand_subject=$prac_mand_subject+1;
                                       
                                    }
                                }
                                 if($subject1['subject_type_id']=='15' && $subject1['paper_type_id']=='106' || $subject1['paper_type_id']=='137')
                                {
                                    $prac_mand_subject=$prac_mand_subject+1;
                                }
                            }
                         

                            $stu_id = Yii::$app->db->createCommand("select student_rel_id from coe_student_mapping where course_batch_mapping_id='".$value['course_batch_mapping_id']."' AND status_category_type_id ='3' ")->queryone();

                            $common_elective_query = new Query();
                            $common_elective_query->select(['distinct (coe_subjects_id)'])
                            ->from('coe_nominal a')
                            ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                            ->andWhere(['a.coe_student_id'=>$stu_id['student_rel_id']])
                            ->andWhere(['a.semester'=>$semester]);
                           // echo "<br>".$common_elective_query->createCommand()->getRawSql();
                             if($value['degree_type']=='UG')
                            {
                              // echo "<br>".$value['programme_name']."<br>".$common_elective_query->createCommand()->getRawSql();
                            }
                            $common_elective_result = $common_elective_query->createCommand()->queryAll();
                            $common_elective_count=count($common_elective_result); 
                            
                            $common_elective_count = ($common_elective_count)==0?'0':($common_elective_count);

                            $subject_count = $common_subject+$common_elective_count;


                            $rejoin_add_dip=0;
                            if($rejoin_dip!=0)
                            {
                               
                                $rett=0; $cec=0;
                                foreach ($rejoin_result as $key => $list) 
                                { 
                                   $cd = Yii::$app->db->createCommand("select count(coe_subjects_id) as count from coe_nominal where course_batch_mapping_id = '".$value['course_batch_mapping_id']."' AND semester='".$semester."' AND coe_student_id='".$list['student_rel_id']."'")->queryScalar();

                                  $cec+=$cd-$common_elective_count;      
                                }
                                
                                if($cec>0)
                                {
                                   $rett=$cec;
                                }
                               
                                $rejoin_add_total +=$rett; 
                                $rejoin_add_dip = ($rett)==0?'0':($rett);
                                
                            }

                            $other_subjects_count = $prac_mand_subject+$man_count;
                           
                            if($value['degree_type']=='PG' && $printed==0 &&$value['degree_code']!=='Ph.D' )
                            {
                                $others_count_total_disp = $others_count_total-((count($others_count)==0 || count($others_count)=='' )?0:count($others_count));
                                $body .='<tr style="background-color: #dedbdb;"><td colspan=2 algin="right"><strong>TOTAL STRENGTH UG</strong></td>';
                                $body .='<td>'.$GRAND_TOTAL.'</td>';
                                $body .='<td>'.$total_subject_count.'</td>';
                                $body .='<td>'.$tot_no_of_sub.'</td>';
                                 $body .='<td>'.$total_other_subject_count.'</td>';
                                 $body .='<td>'.$tot_no_of_other.'</td>';
                                 $body .='<td>'.$total_sw_based_subject.'</td>';
                                $body .='<td>'.$total_sw_based_script.'</td>';
                                  $body .='<td>'.$total_grand_rejoin.'</td>';
                                  $body .='<td>'.$total_no_of_script.'</td>';
                                $body .='</tr>';

                                $total_ug_stu+=$GRAND_TOTAL;
                                $total_ug_subject+=$total_subject_count;
                                $total_ug_script+=$tot_no_of_sub;
                                $total_ug_pract+=$total_other_subject_count;
                                $total_ug_pract_count+=$tot_no_of_other;
                                $total_ug_rejoin+=$total_grand_rejoin;

                                $total_ug_grand_script += $total_no_of_script;
                                $total_ugsw_based_subject+=$total_sw_based_subject;
                                $total_ugsw_based_script+=$total_sw_based_script;

                                
                                $detain_total = $ug_count = $pg_count = $others_count_total = $transfer_total =$latearl_total= $rejoin_total = $disc_total = $GRAND_TOTAL = $total_strength = 0;
                                $others_count_total +=((count($others_count)==0 || count($others_count)=='' )?0:count($others_count));
                                $printed = 1; $pg_general_count=0;   
                                $total_subject_count =0;
                                $tot_no_of_sub=0;

                                $total_other_subject_count =0;
                                $tot_no_of_other=0;
                                $total_no_of_script=0;
                                $total_grand_rejoin=0;
                                $rejoin_add_total=0;
                               
                            }
                            
                           
                            if($value['degree_type']=='UG')
                            {
                                $body .='<tr>';
                                $body .='<td>'.$sn_no.'</td>';
                                $body .='<td>'.strtoupper($value['programme_name']).'</td>';
                                $body .='<td>'.$active_final_disp.'</td>';
                                $body .='<td>'.$subject_count.'</td>';
                                $body .='<td>'.$tt=($subject_count*$active_final_disp).'</td>';
                               $body .='<td>'.$other_subjects_count.'</td>';
                               $body .='<td>'.$ott=($other_subjects_count*$active_final_disp).'</td>';
                               $body .='<td>'.$sw_based_subject.'</td>';
                               $body .='<td>'.$swb=($sw_based_subject*$active_final_disp).'</td>';
                               $body .='<td>'.$rtt=$rejoin_add_dip.'</td>';
                               $body .='<td>'.$stt=($ott+$tt+$rtt+$swb).'</td>';
                                $body .='</tr>';
                                $sn_no++;

                                $total_other_subject_count+=$other_subjects_count;
                                 $tot_no_of_sub+=$tt;
                                  $tot_no_of_other+=$ott;
                                 $GRAND_TOTAL +=$active_final_disp;
                                 $total_subject_count +=$subject_count;

                                 $total_no_of_script += $stt;
                                  $total_grand_rejoin+=$rtt;

                                   $total_sw_based_subject=$total_sw_based_subject+$sw_based_subject;
                                  
                                  $total_sw_based_script=$total_sw_based_script+$swb;
                                 
                            }


                            if(($value['degree_type']=='PG') && ($value['degree_code']!='Ph.D') && ($value['semester']<=$semester && $value['degree_total_semesters']>=$semester  && ($semester<5) ))
                            {
                                

                                $body .='<tr>';
                                $body .='<td>'.$sn_no.'</td>';
                                $body .='<td>'.strtoupper($value['programme_name']).'</td>';
                                $body .='<td>'.$active_final_disp.'</td>';
                                $body .='<td>'.$subject_count.'</td>';
                                $body .='<td>'.$tt=($subject_count*$active_final_disp).'</td>';
                                $tot_no_of_sub+=$tt;
                                $body .='<td>'.$other_subjects_count.'</td>';
                                $body .='<td>'.$ott=($other_subjects_count*$active_final_disp).'</td>';
                                 $tot_no_of_other+=$ott;
                                  $body .='<td>0</td>';
                                $body .='<td>0</td>';
                                   $body .='<td>'.$rtt=$rejoin_add_dip.'</td>';
                                  $body .='<td>'.$stt=($ott+$tt+$rtt).'</td>';
                                $body .='</tr>';
                                $sn_no++;

                                 $GRAND_TOTAL +=$active_final_disp;
                                $pg_general_count+=$other_disp;
                                $total_other_subject_count+=$other_subjects_count;
                                $total_subject_count +=$subject_count;
                                 $total_no_of_script += $stt;

                                 $total_grand_rejoin+=$rtt;
                                 
                            }
                        }
                          


                        if($GRAND_TOTAL>'0')
                       {
                            $total_pg_stu+=$GRAND_TOTAL;
                                $total_pg_subject+=$total_subject_count;
                                $total_pg_script+=$tot_no_of_sub;
                                $total_pg_pract+=$total_other_subject_count;
                                $total_pg_pract_count+=$tot_no_of_other;
                                $total_pg_rejoin+=$total_grand_rejoin;
                                $total_pg_grand_script += $total_no_of_script;

                            $body .='<tr style="background-color: #dedbdb;"><td colspan=2 algin="center" ><strong>TOTAL STRENGTH PG</strong> </td>';
                            $body .='<td>'.$GRAND_TOTAL.'</td>';
                            $body .='<td>'.$total_subject_count.'</td>';
                            $body .='<td>'.$tot_no_of_sub.'</td>';
                            $body .='<td>'.$total_other_subject_count.'</td>';
                            $body .='<td>'.$tot_no_of_other.'</td>';
                            $body .='<td>0</td>';
                            $body .='<td>0</td>';
                            $body .='<td>'.$total_grand_rejoin.'</td>';
                            $body .='<td>'.$total_no_of_script.'</td>';
                            $body .='</tr>';
                      }
                        
                    }
                    $semester=$semester+2;
                      $i++;
                  }

                  if($total_ug_stu!=0)
                  {
                    $body .='<tr style="background-color: #a19b9b;"><td colspan=2 algin="center" ><strong>TOTAL UG</strong> </td>';
                            $body .='<td>'.$total_ug_stu.'</td>';
                            $body .='<td>'.$total_ug_subject.'</td>';
                            $body .='<td>'.$total_ug_script.'</td>';
                            $body .='<td>'.$total_ug_pract.'</td>';
                            $body .='<td>'.$total_ug_pract_count.'</td>';
                            $body .='<td>'.$total_ugsw_based_subject.'</td>';
                            $body .='<td>'.$total_ugsw_based_script.'</td>';
                            $body .='<td>'.$total_ug_rejoin.'</td>';
                            $body .='<td>'.$total_ug_grand_script.'</td>';
                            $body .='</tr>';
                        $body .='<tr style="background-color: #a19b9b;"><td colspan=2 algin="center" ><strong>TOTAL PG</strong> </td>';
                            $body .='<td>'.$total_pg_stu.'</td>';
                            $body .='<td>'.$total_pg_subject.'</td>';
                            $body .='<td>'.$total_pg_script.'</td>';
                            $body .='<td>'.$total_pg_pract.'</td>';
                            $body .='<td>'.$total_pg_pract_count.'</td>';
                            $body .='<td>0</td>';
                            $body .='<td>0</td>';
                             $body .='<td>'.$total_pg_rejoin.'</td>';
                            $body .='<td>'.$total_pg_grand_script.'</td>';
                            $body .='</tr>';
                        $body .='<tr style="background-color: #a19b9b;"><td colspan=2 algin="center" ><strong>TOTAL UG & PG</strong> </td>';
                            $body .='<td>'.($total_ug_stu+$total_pg_stu).'</td>';
                            $body .='<td>'.($total_ug_subject+$total_pg_subject).'</td>';
                            $body .='<td>'.($total_ug_script+$total_pg_script).'</td>';
                            $body .='<td>'.($total_ug_pract+$total_pg_pract).'</td>';
                            $body .='<td>'.($total_ug_pract_count+$total_pg_pract_count).'</td>';
                            $body .='<td>'.$total_ugsw_based_subject.'</td>';
                            $body .='<td>'.$total_ugsw_based_script.'</td>';
                            $body .='<td>'.($total_ug_rejoin+$total_pg_rejoin).'</td>';
                            $body .='<td>'.($total_ug_grand_script+$total_pg_grand_script).'</td>';
                            $body .='</tr>';

                        echo $html .='<tbody id="show_dummy_numbers" style="font-weight:bold !important"> '.$body.'</tbody> </table>'; 

                  
                        if(isset($_SESSION['consolidateregularcount']))
                        {
                            unset($_SESSION['consolidateregularcount']);
                        }
                        $_SESSION['consolidateregularcount'] = $html;
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