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


$this->title = "CONSOLIDATE ARREAR COUNT REPORT";
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
                <?= $form->field($model, 'month')->dropdownList(['29' => 'April/May', '30' => 'Oct/Nov'], ['prompt'=>'Select Month', 'options'=>[$month=> ["Selected"=>'selected']]]) ?>

            </div>
           <div class="col-sm-2">
                <?php if($Year1==''){$y=date("Y");}else{$y=$Year1;}?>
                       <?= $form->field($model, 'year')->textInput(['value' => "$y"]) ?>

                    </div>
            
            <div class="col-lg-4 col-sm-4">
                <br />
                <?= Html::submitButton( 'Get', ['class' =>  'btn btn-success']) ?>

                <?= Html::a('Reset', ['consolidatearrearreport'], ['class' => 'btn btn-default']) ?> 
        </div>
    </div>

    <?php ActiveForm::end(); ?>

<?php $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/reports/consolidatearrearcount-pdf'], [
                    'class'=>'btn btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]);  
$print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/reports/consolidatearrearcount-excel'], [
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
                    $monthname=''; $curyear=$year;
                    if($month==29){ $monthname='OCT/NOV'; $curyear=$year;}
                    if($month==30){ $monthname='APRIL/MAY'; $curyear=$year+1;}

                    $html .='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <tr>
                         
                          <td colspan=13 align="center"> 
                              <center><b><font size="6px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font></center>
                              <center> Phone : <b>'.$org_phone.'</b></center>
                              <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                         </td>
                        
                        </tr> ';
                        $html .= '<tr>
                            <td colspan=13 align="center"><b>CONSOLIDATE ARREAR COUNT REPORT ' . $monthname . ' ' . $curyear .'</b></td>
                        </tr>';

                         $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                        $transfer = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%TRANSFER%'")->queryScalar();

                        $rejoin_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%rejoin%'")->queryScalar();
                         $latearl = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Lateral Entry%'")->queryScalar();

                         $body .=' <tr>
                                <td colspan=13 align="center" style="background-color: #2173bc; color: #fff;">UG</td>
                                </tr>
                                <tr class="table-danger">
                                    
                                                   
                                    <th rowspan=3 width=10%>Batch</th>
                                    <th colspan=4 align="center" width=20%>General/Lateral/Rejoined</th>
                                    <th colspan=4 align="center" width=20%>Detain</th>
                                    <th colspan=4 align="center" width=20%>Total</th>
                                </tr>
                                <tr class="table-danger">
                                    
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                </tr>
                                <tr class="table-danger">
                                  <th align="center">T</th>
                                  <th align="center">P</th>
                                  <th align="center">Total</th>
                                  <th align="center">T</th>
                                  <th align="center">P</th>
                                  <th align="center">Total</th>
                                  <th align="center">T</th>
                                  <th align="center">P</th>
                                  <th align="center">Total</th>
                                </tr>
                                <tbody id="show_dummy_numbers" style="font-weight:bold !important">';

                      $rejoincount=Yii::$app->db->createCommand('SELECT distinct previous_reg_number FROM coe_student_mapping SM  WHERE  previous_reg_number!="" AND status_category_type_id NOT IN('.$det_disc_type.')')->queryAll();

                             $rejoin='';
                             if(!empty($rejoincount))
                             {
                                $notIn = array_filter(['']);
                                
                                    foreach ($rejoincount as $valu) 
                                    {
                                       $getoldmapid=Yii::$app->db->createCommand('SELECT distinct coe_student_mapping_id FROM coe_student_mapping SM JOIN coe_student B ON B.coe_student_id=SM.student_rel_id WHERE status_category_type_id NOT IN('.$det_disc_type.') AND B.register_number="'.$valu['previous_reg_number'].'"')->queryScalar();
                                       if($getoldmapid!=''){
                                        $notIn[$getoldmapid] = $getoldmapid;
                                       }
                                        
                                    }                               

                                $notIn=implode(",",$notIn);
                               
                                $notIn=rtrim($notIn,",");
                                $notIn=ltrim($notIn,",");
                                $rejoin=' AND  A.student_map_id NOT IN ('.$notIn.')';                              
                             }

                              //print_r($rejoin); exit;

                      $tot_stu=$tot_t=$tot_p=$grand_tot=0;

                      $tot_gstu=$tot_gt=$tot_gp=$grand_gtot=0;
                      $tot_dstu=$tot_dt=$tot_dp=$grand_dtot=0;
                      foreach ($content_data as $value1) 
                       {
                          if((date("Y")-$value1['batch_name'])<=8)
                          {
                            $stu_count_gen=Yii::$app->db->createCommand('SELECT count(distinct A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')')->queryScalar();


                            $t_count=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.') AND paper_type_id NOT IN (10,11,12,22,61,68,100,123)')->queryScalar();

                            $p_count=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.') AND paper_type_id IN (10,11,12,22,61,68,100,123)')->queryScalar();
                           

                            $stu_count_detain=Yii::$app->db->createCommand('SELECT count(distinct A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id  
                          JOIN coe_student as S ON S.coe_student_id=B.student_rel_id
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$rejoin)->queryScalar();

                            $t_count_detain=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') AND paper_type_id NOT IN (10,11,12,22,61,68,100,123)')->queryScalar();

                            $p_count_detain=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') AND paper_type_id IN (10,11,12,22,61,68,100,123)')->queryScalar();

                            
                            $body .= '<tr>';
                            $body .='<td>'.$value1['batch_name'].'</td>';
                            $body .='<td align="center">'.$stu_count_gen.'</td>';
                            $body .='<td align="center">'.$t_count.'</td>';
                            $body .='<td align="center">'.$p_count.'</td>';
                            $body .='<td align="center">'.($t_count+$p_count).'</td>';
                            
                            $body .='<td align="center">'.$stu_count_detain.'</td>';
                            $body .='<td align="center">'.$t_count_detain.'</td>';
                            $body .='<td align="center">'.$p_count_detain.'</td>';
                            $body .='<td align="center">'.($t_count_detain+$p_count_detain).'</td>';

                            $body .='<td align="center">'.($stu_count_gen+$stu_count_detain).'</td>';
                            $body .='<td align="center">'.($t_count+$t_count_detain).'</td>';
                            $body .='<td align="center">'.($p_count+$p_count_detain).'</td>';
                            $body .='<td align="center">'.(($t_count+$p_count)+($t_count_detain+$p_count_detain)).'</td>';
                            $body .= '</tr>';
                            
                            $tot_gstu=$tot_gstu+($stu_count_gen);
                            $tot_gt=$tot_gt+($t_count);
                            $tot_gp=$tot_gp+($p_count);
                            $grand_gtot=$grand_gtot+($t_count+$p_count);

                            $tot_dstu=$tot_dstu+($stu_count_detain);
                            $tot_dt=$tot_dt+($t_count_detain);
                            $tot_dp=$tot_dp+($p_count_detain);
                            $grand_dtot=$grand_dtot+($t_count_detain+$p_count_detain);

                            $tot_stu=$tot_stu+($stu_count_gen+$stu_count_detain);
                            $tot_t=$tot_t+($t_count+$t_count_detain);
                            $tot_p=$tot_p+($p_count+$p_count_detain);
                            $grand_tot=$grand_tot+(($t_count+$p_count)+($t_count_detain+$p_count_detain));
                        }
                       }

                       $body.='<tr><td>UG Total</td>
                       <td align="center">'.$tot_gstu.'</td><td align="center">'.$tot_gt.'</td><td align="center">'.$tot_gp.'</td><td align="center">'.$grand_gtot.'</td>
                       <td align="center">'.$tot_dstu.'</td><td align="center">'.$tot_dt.'</td><td align="center">'.$tot_dp.'</td><td align="center">'.$grand_dtot.'</td>
                       <td align="center">'.$tot_stu.'</td><td align="center">'.$tot_t.'</td><td align="center">'.$tot_p.'</td><td align="center">'.$grand_tot.'</td></tr>';

                         $body .='</tbody> </table><br>
                         <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" > <tr>
                                <td colspan=13 align="center" style="background-color: #2173bc; color: #fff;">PG</td>
                                </tr>
                                <tr class="table-danger">                           
                                     <th rowspan=3 width=10%>Batch</th>
                                    <th colspan=4 align="center" width=25%>General/Lateral/Rejoined</th>
                                    <th colspan=4 align="center" width=25%>Detain</th>
                                    <th colspan=4 align="center" width=25%>Total</th>
                                </tr>
                                <tr class="table-danger">
                                    
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                </tr>
                                <tr class="table-danger">
                                  <th align="center">T</th>
                                  <th align="center">P</th>
                                  <th align="center">Total</th>
                                  <th align="center">T</th>
                                  <th align="center">P</th>
                                  <th align="center">Total</th>
                                  <th align="center">T</th>
                                  <th align="center">P</th>
                                  <th align="center">Total</th>
                                </tr>
                                <tbody id="show_dummy_numbers" style="font-weight:bold !important">';

                       $pgtot_stu=$pgtot_t=$pgtot_p=$pggrand_tot=0;

                       $ptot_gstu=$ptot_gt=$ptot_gp=$pgrand_gtot=0;
                      $ptot_dstu=$ptot_dt=$ptot_dp=$pgrand_dtot=0;

                      foreach ($content_data as $value1) 
                       {
                          if((date("Y")-$value1['batch_name'])<=4)
                          {
                            $stu_count_gen=Yii::$app->db->createCommand('SELECT count(distinct A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')')->queryScalar();


                            $t_count=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.') AND paper_type_id NOT IN (10,11,12,22,61,68,100,123)')->queryScalar();

                            $p_count=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.') AND paper_type_id IN (10,11,12,22,61,68,100,123)')->queryScalar();
                                                        

                            $stu_count_detain=Yii::$app->db->createCommand('SELECT count(distinct A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id  
                          JOIN coe_student as S ON S.coe_student_id=B.student_rel_id
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$rejoin)->queryScalar();

                            $t_count_detain=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') AND paper_type_id NOT IN (10,11,12,22,61,68,100,123)')->queryScalar();

                            $p_count_detain=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') AND paper_type_id IN (10,11,12,22,61,68,100,123)')->queryScalar();

                            
                              $body .= '<tr>';
                              $body .='<td>'.$value1['batch_name'].'</td>';
                              $body .='<td align="center">'.$stu_count_gen.'</td>';
                              $body .='<td align="center">'.$t_count.'</td>';
                              $body .='<td align="center">'.$p_count.'</td>';
                              $body .='<td align="center">'.($t_count+$p_count).'</td>';
                              
                              $body .='<td align="center">'.$stu_count_detain.'</td>';
                              $body .='<td align="center">'.$t_count_detain.'</td>';
                              $body .='<td align="center">'.$p_count_detain.'</td>';
                              $body .='<td align="center">'.($t_count_detain+$p_count_detain).'</td>';

                              $body .='<td align="center">'.($stu_count_gen+$stu_count_detain).'</td>';
                              $body .='<td align="center">'.($t_count+$t_count_detain).'</td>';
                              $body .='<td align="center">'.($p_count+$p_count_detain).'</td>';
                              $body .='<td align="center">'.(($t_count+$p_count)+($t_count_detain+$p_count_detain)).'</td>';
                              $body .= '</tr>';

                              $tot_stu=$tot_stu+($stu_count_gen+$stu_count_detain);
                              $tot_t=$tot_t+($t_count+$t_count_detain);
                              $tot_p=$tot_p+($p_count+$p_count_detain);
                              $grand_tot=$grand_tot+(($t_count+$p_count)+($t_count_detain+$p_count_detain));

                               $pgtot_stu=$pgtot_stu+($stu_count_gen+$stu_count_detain);
                               $pgtot_t=$pgtot_t+($t_count+$t_count_detain);
                               $pgtot_p=$pgtot_p+($t_count+$t_count_detain);
                               $pggrand_tot=$pggrand_tot+(($t_count+$p_count)+($t_count_detain+$p_count_detain));

                              $tot_gstu=$tot_gstu+($stu_count_gen);
                              $tot_gt=$tot_gt+($t_count);
                              $tot_gp=$tot_gp+($p_count);
                              $grand_gtot=$grand_gtot+($t_count+$p_count);

                              $tot_dstu=$tot_dstu+($stu_count_detain);
                              $tot_dt=$tot_dt+($t_count_detain);
                              $tot_dp=$tot_dp+($p_count_detain);
                              $grand_dtot=$grand_dtot+($t_count_detain+$p_count_detain);

                              $ptot_gstu=$ptot_gstu+($stu_count_gen);
                              $ptot_gt=$ptot_gt+($t_count);
                              $ptot_gp=$ptot_gp+($p_count);
                              $pgrand_gtot=$pgrand_gtot+($t_count+$p_count);

                              $ptot_dstu=$ptot_dstu+($stu_count_detain);
                              $ptot_dt=$ptot_dt+($t_count_detain);
                              $ptot_dp=$ptot_dp+($p_count_detain);
                              $pgrand_dtot=$pgrand_dtot+($t_count_detain+$p_count_detain);

                            
                        }
                       }

                       $body.='<tr><td>PG Total</td>
                       <td align="center">'.$ptot_gstu.'</td><td align="center">'.$ptot_gt.'</td><td align="center">'.$ptot_gp.'</td><td align="center">'.$pgrand_gtot.'</td>
                       <td align="center">'.$ptot_dstu.'</td><td align="center">'.$ptot_dt.'</td><td align="center">'.$ptot_dp.'</td><td align="center">'.$pgrand_dtot.'</td>
                       <td align="center">'.$pgtot_stu.'</td><td align="center">'.$pgtot_t.'</td><td align="center">'.$pgtot_p.'</td><td align="center">'.$pggrand_tot.'</td></tr>';
                       $body.='<tr><td>Grand Total</td>
                       <td align="center">'.$tot_gstu.'</td><td align="center">'.$tot_gt.'</td><td align="center">'.$tot_gp.'</td><td align="center">'.$grand_gtot.'</td>
                       <td align="center">'.$tot_dstu.'</td><td align="center">'.$tot_dt.'</td><td align="center">'.$tot_dp.'</td><td align="center">'.$grand_dtot.'</td>
                       <td align="center">'.$tot_stu.'</td><td align="center">'.$tot_t.'</td><td align="center">'.$tot_p.'</td><td align="center">'.$grand_tot.'</td></tr></tbody> </table>';

                        echo $html .=$body;

                  
                        if(isset($_SESSION['consolidateregularcount']))
                        {
                            unset($_SESSION['consolidateregularcount']);
                        }
                        $_SESSION['consolidateregularcount'] = $html;
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