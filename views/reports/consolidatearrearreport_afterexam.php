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
$curyear=$year; $curmon=$month;
if($month==29){ $curmon=30; $monthname='OCT/NOV'; $curyear=$year;}
if($month==30){ $curmon=29; $monthname='APRIL/MAY'; $curyear=$year+1;}


$this->title = "CONSOLIDATE ARREAR COUNT REPORT AFTER EXAM";
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
                <?= $form->field($model, 'month')->dropdownList(['29' => 'April/May', '30' => 'Oct/Nov'], ['prompt'=>'Select Month', 'options'=>[$curmon=> ["Selected"=>'selected']]]) ?>

            </div>
           <div class="col-sm-2">
                <?php if($curyear==''){$y=date("Y");}else{$y=$curyear;}?>
                       <?= $form->field($model, 'year')->textInput(['value' => "$y"]) ?>

                    </div>

              <!-- <div class="col-lg-2 col-sm-2">
               <?php echo $form->field($model1,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('Batch'); 
                ?>
            </div> -->
            
            <div class="col-lg-4 col-sm-4">
                <br />
                <?= Html::submitButton( 'Get', ['class' =>  'btn btn-success']) ?>

                <?= Html::a('Reset', ['consolidatearrearreport'], ['class' => 'btn btn-default']) ?> 
        </div>
    </div>

    <?php ActiveForm::end(); ?>

<?php 

$print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/reports/consolidatearrearcount-pdf'], [
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
                    $monthname=''; 
                    $curyear=$year; $curmon=$month;
                  if($month==29){ $curmon=30; $monthname='OCT/NOV'; $curyear=$year;}
                  if($month==30){ $curmon=29; $monthname='APRIL/MAY'; $curyear=$year+1;}

                  //echo $curyear; exit();
                    $html .='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <tr>
                         
                          <td colspan=18 align="center"> 
                              <center><b><font size="6px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font></center>
                              <center> Phone : <b>'.$org_phone.'</b></center>
                              <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                         </td>
                        
                        </tr> ';
                        $html .= '<tr>
                            <td colspan=18 align="center"><b>CONSOLIDATE ARREAR COUNT REPORT AFTER EXAM' . $monthname . ' ' . $curyear .'</b></td>
                        </tr>';

                         $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                        $transfer = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%TRANSFER%'")->queryScalar();

                        $rejoin_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%rejoin%'")->queryScalar();
                         $latearl = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Lateral Entry%'")->queryScalar();

                         $body .=' <tr>
                                <td colspan=18 align="center" style="background-color: #2173bc; color: #fff;">UG</td>
                                </tr>
                                <tr class="table-danger">
                                    
                                                   
                                    <th rowspan=3 width=10%>Batch</th>
                                    <th colspan=4 align="center" width=30%>General/Lateral/Rejoined</th>
                                    <th colspan=4 align="center" width=25%>Detain</th>
                                    <th colspan=4 align="center" width=25%>Total</th>
                                    <th colspan=5 align="center" width=25%>Absent</th>
                                </tr>
                                <tr class="table-danger">
                                    
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                    <th rowspan=2 align="center">T</th>
                                    <th rowspan=2 align="center">TPT</th>
                                    <th rowspan=2 align="center">TPP</th>
                                    <th rowspan=2 align="center">P</th>
                                    <th rowspan=2 align="center">Total</th>
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
                            

                      $tot_stu=$tot_t=$tot_p=$grand_tot=0;

                      $tot_gstu=$tot_gt=$tot_gp=$grand_gtot=0;
                      $tot_dstu=$tot_dt=$tot_dp=$grand_dtot=0;

                      $tot_tabs=$tot_tptabs=$tot_tppabs=$tot_pabs=$grand_tot_abs=0;

                      $overalltotal=0;
                      foreach ($content_data as $value1) 
                      {
                          
                          if($curmon==30 && ($curyear-$value1['batch_name'])>=7)
                          {
                            //echo (date("Y")-$value1['batch_name']); exit;
                          }
                          else if(($curyear-$value1['batch_name'])<=7)
                          {
                            $bat_map_val=Yii::$app->db->createCommand('SELECT coe_bat_deg_reg_id FROM coe_bat_deg_reg WHERE coe_batch_id="'.$value1['coe_batch_id'].'"')->queryScalar();
                            
                            $sem_verify = ConfigUtilities::SemCaluclation($curyear,$curmon,$bat_map_val);

                            $tpaper_type_id=''; $ppaper_type_id=''; $tsubject_type_id=''; $tpsubject_type_id='';


                            if($value1['batch_name']>=2021)
                            {
                                $tpaper_type_id=' AND paper_type_id IN (8,9,121,136,38)';

                                $thpaper_type_id=' AND paper_type_id IN (8)';
                                $thppaper_type_id=' AND paper_type_id IN (9,121,136,38)';

                                $ppaper_type_id=' AND paper_type_id IN (10,11,12,22,61,68,100,123,137,105)';

                                $tsubject_type_id=' AND type_id IN (143)'; 
                                $tpsubject_type_id=' AND type_id IN (140,141,142)';
                            }
                            else
                            {
                                $tpaper_type_id=' AND paper_type_id IN (8,9,121,136,38)';

                                $ppaper_type_id=' AND paper_type_id IN (10,11,12,22,61,68,100,123,137,105)';
                            }
                        
                            $cur_rejoin=Yii::$app->db->createCommand('SELECT coe_student_mapping_id,SM.previous_reg_number,S.register_number FROM coe_student_mapping SM JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=SM.course_batch_mapping_id JOIN coe_student S ON S.coe_student_id=SM.student_rel_id WHERE SM.status_category_type_id NOT IN('.$det_disc_type.') AND F.coe_batch_id="'.$value1['coe_batch_id'].'" AND SM.status_category_type_id =6 AND semester_detain>='.$sem_verify)->queryAll();

                            $regrejoin=''; $rejoin_stu_count=$t_count_rejoin=$p_count_rejoin=$th_count_rejoin=$tp_count_rejoin=0;
                            

                             $stu_count_gen=Yii::$app->db->createCommand('SELECT count(distinct A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')')->queryScalar();

                              $thry='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$tpaper_type_id; //exit;
                        
                                $t_count=Yii::$app->db->createCommand($thry)->queryScalar();

                               $pqry= 'SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$ppaper_type_id; //exit;

                                $p_count=Yii::$app->db->createCommand($pqry)->queryScalar();

                             $tpgry='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where C.semester>=3 AND degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where  B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$thpaper_type_id.$tsubject_type_id; //exit;

                                $th_count=Yii::$app->db->createCommand($tpgry)->queryScalar();

                              $tpgry_sem12='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where C.semester<=2 AND degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where  B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$thpaper_type_id.$tsubject_type_id; //exit;

                                $th_count_sem12=Yii::$app->db->createCommand($tpgry_sem12)->queryScalar();

                                $th_count=$th_count+$th_count_sem12; //exit;

                              $tpgry1='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where C.semester>=3 AND degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$thppaper_type_id.$tpsubject_type_id; //exit;
                               
                                $tp_count=Yii::$app->db->createCommand($tpgry1)->queryScalar();

                                $tpgry_sem12='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where C.semester<=2 AND degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$thppaper_type_id.$tpsubject_type_id; //exit;
                               
                                $tp_count_sem12=Yii::$app->db->createCommand($tpgry_sem12)->queryScalar();

                                $tp_count=$tp_count+$tp_count_sem12;

                                $thpgry1='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$thppaper_type_id;
                           
                                $thp_count=Yii::$app->db->createCommand($thpgry1)->queryScalar();

                                $dqry='SELECT count(distinct A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id  
                              JOIN coe_student as S ON S.coe_student_id=B.student_rel_id
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$rejoin; //exit;

                                $stu_count_detain=Yii::$app->db->createCommand($dqry)->queryScalar();

                               $tdqry= 'SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$tpaper_type_id.$rejoin;

                                $t_count_detain=Yii::$app->db->createCommand($tdqry)->queryScalar();                           

                               $thdqry='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where C.semester>=3 and degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$thpaper_type_id.$tsubject_type_id; //exit();

                                $th_count_detain=Yii::$app->db->createCommand($thdqry)->queryScalar();

                             $thdqry12='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where C.semester<=2 AND degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$thpaper_type_id.$tsubject_type_id; //exit();

                                $th_count_detain= $th_count_detain + (Yii::$app->db->createCommand($thdqry12)->queryScalar());

                                $tpqery='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where C.semester>=3 AND degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$thppaper_type_id.$tpsubject_type_id;

                              $tp_count_detain=Yii::$app->db->createCommand($tpqery)->queryScalar();

                             $tpqery12='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where C.semester<=2 AND degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$thppaper_type_id.$tpsubject_type_id;

                                $tp_count_detain= $tp_count_detain + (Yii::$app->db->createCommand($tpqery12)->queryScalar());


                               $th_pqery='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$thppaper_type_id;

                                $th_p_count_detain=Yii::$app->db->createCommand($th_pqery)->queryScalar();


                                $p_count_detain=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$ppaper_type_id)->queryScalar();

                                $tppqery='SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where degree_type="UG" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                              where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$tpaper_type_id.$tpsubject_type_id;

                                $tpp_count_detain=Yii::$app->db->createCommand($tppqery)->queryScalar();


                              $q_th_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="UG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.') AND A.exam_date is NULL '.$tpaper_type_id;

                              $th_abs=Yii::$app->db->createCommand($q_th_abs)->queryScalar();
                             
                              $q_tpt_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="UG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.') AND A.exam_date is NULL '.$thppaper_type_id;
                           
                             $tpt_abs=Yii::$app->db->createCommand($q_tpt_abs)->queryScalar();

                             $q_tpp_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="UG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.') AND A.exam_date is NOT NULL '.$thppaper_type_id;                           
                           
                             $tpp_abs=Yii::$app->db->createCommand($q_tpp_abs)->queryScalar();

                             $q_p_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="UG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$ppaper_type_id;                             
                           
                             $p_abs=Yii::$app->db->createCommand($q_p_abs)->queryScalar();

                             $qry_tot_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="UG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.') ';
                           
                             $tot_abs=Yii::$app->db->createCommand($qry_tot_abs)->queryScalar();

                            $stu_count_gen=$stu_count_gen;
                            $t_count=$t_count;
                            $p_count=$p_count;

                            if($value1['batch_name']>=2021)
                            {
                              $p_count=$p_count+($tp_count);
                                $body .= '<tr>';
                              $body .='<td>'.$value1['batch_name'].'</td>';
                              $body .='<td align="center">'.$stu_count_gen.'</td>';
                              $body .='<td align="center">'.$t_count.' (T:'.($th_count).' TP:'.($thp_count).')</td>';
                              $body .='<td align="center">'.($p_count).' (P:'.($p_count-($tp_count)).' TPP:'.($tp_count).')</td>';
                              $body .='<td align="center">'.($t_count+$p_count).'</td>';
                              
                              $body .='<td align="center">'.$stu_count_detain.'</td>';
                              $body .='<td align="center">'.$t_count_detain.' (T:'.$th_count_detain.' TP:'.$th_p_count_detain.')</td>';
                              $p_count_detain=$p_count_detain+$tpp_count_detain;
                              $body .='<td align="center">'.$p_count_detain.' (P:'.($p_count_detain-$tpp_count_detain).' TPP:'.$tpp_count_detain.')</td>';
                              $body .='<td align="center">'.($t_count_detain+$p_count_detain).'</td>';

                              $body .='<td align="center">'.($stu_count_gen+$stu_count_detain).'</td>';
                              $body .='<td align="center">'.($t_count+$t_count_detain).'</td>';
                              $body .='<td align="center">'.($p_count+$p_count_detain).'</td>';
                              //$body .='<td align="center">'.(($t_count+$p_count)+($t_count_detain+$p_count_detain)).'</td>';
                              $ot=($t_count+($p_count-($tp_count))+$t_count_detain+($p_count_detain-$tp_count_detain));

                              $overalltotal=$overalltotal+$ot;
                              $body .='<td align="center">'.$ot.'</td>';
                              $body .='<td align="center">'.$th_abs.'</td>';
                              $body .='<td align="center">'.$tpt_abs.'</td>';
                              $body .='<td align="center">'.$tpt_abs.'</td>';
                              $body .='<td align="center">'.$p_abs.'</td>';
                              $body .='<td align="center">'.($tot_abs).'</td>';
                              $body .= '</tr>';

                              $tot_tabs=$tot_tabs+$th_abs;
                              $tot_tptabs=$tot_tptabs+$tpt_abs;
                              $tot_tppabs=$tot_tppabs+$tpt_abs;
                              $tot_pabs=$tot_pabs+$p_abs;
                              $grand_tot_abs=$grand_tot_abs+$tot_abs;
                            }
                            else
                            {
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
                              $ot=(($t_count+$p_count)+($t_count_detain+$p_count_detain));
                              $overalltotal=$overalltotal+$ot;
                              $body .='<td align="center">'.$ot.'</td>';
                              $body .='<td align="center">'.$th_abs.'</td>';
                              $body .='<td align="center">'.$tpt_abs.'</td>';
                              $body .='<td align="center">'.$tpt_abs.'</td>';
                              $body .='<td align="center">'.$p_abs.'</td>';
                              $body .='<td align="center">'.($tot_abs).'</td>';
                              $body .= '</tr>';

                              $tot_tabs=$tot_tabs+$th_abs;
                              $tot_tptabs=$tot_tptabs+$tpt_abs;
                              $tot_tppabs=$tot_tppabs+$tpt_abs;
                              $tot_pabs=$tot_pabs+$p_abs;
                              $grand_tot_abs=$grand_tot_abs+$tot_abs;
                            }
                            
                            
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
                       <td align="center">'.$tot_stu.'</td><td align="center">'.$tot_t.'</td><td align="center">'.$tot_p.'</td><td align="center">'.$overalltotal.'</td>

                       <td align="center">'.$tot_tabs.'</td><td align="center">'.$tot_tptabs.'</td><td align="center">'.$tot_tppabs.'</td><td align="center">'.$tot_pabs.'</td><td align="center">'.$grand_tot_abs.'</td></tr>';

                         $body .='</tbody> </table><br>
                         
                         <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" > <tr>
                                <td colspan=18 align="center" style="background-color: #2173bc; color: #fff;">PG</td>
                                </tr>
                                <tr class="table-danger">
                                    
                                                   
                                    <th rowspan=3 width=10%>Batch</th>
                                    <th colspan=4 align="center" width=30%>General/Lateral/Rejoined</th>
                                    <th colspan=4 align="center" width=25%>Detain</th>
                                    <th colspan=4 align="center" width=25%>Total</th>
                                    <th colspan=5 align="center" width=25%>Absent</th>
                                </tr>
                                <tr class="table-danger">
                                    
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                    <th rowspan=2 align="center">No. of Std.</th>                
                                    <th colspan=3 align="center">No. of Script</th>
                                                                        <th rowspan=2 align="center">T</th>
                                    <th rowspan=2 align="center">TPT</th>
                                    <th rowspan=2 align="center">TPP</th>
                                    <th rowspan=2 align="center">P</th>
                                    <th rowspan=2 align="center">Total</th>
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
                      $poveralltotal=0;
                      
                      $ptot_tabs=$ptot_tptabs=$ptot_tppabs=$ptot_pabs=$pgrand_tot_abs=0;

                    foreach ($content_data as $value1) 
                    {

                          if($curmon==30 && ($curyear-$value1['batch_name'])>=4)
                          {
                            //echo (date("Y")-$value1['batch_name']); exit;
                          }
                          else if(($curyear-$value1['batch_name'])<=4)
                          {
                            $bat_map_val=Yii::$app->db->createCommand('SELECT coe_bat_deg_reg_id FROM coe_bat_deg_reg WHERE coe_batch_id="'.$value1['coe_batch_id'].'"')->queryScalar();
                            
                            $sem_verify = ConfigUtilities::SemCaluclation($curyear,$curmon,$bat_map_val);

                            $tpaper_type_id=''; $ppaper_type_id=''; $tsubject_type_id=''; $tpsubject_type_id='';


                            if($value1['batch_name']>=2021)
                            {
                                $tpaper_type_id=' AND paper_type_id NOT IN (10,11,12,22,61,68,100,123,137,105)';

                                $ppaper_type_id=' AND paper_type_id IN (10,11,12,22,61,68,100,123,137,105)';

                                $tsubject_type_id=' AND type_id IN (143)'; 
                                $tpsubject_type_id=' AND type_id IN (140,141,142)';
                            }
                            else
                            {
                                $tpaper_type_id=' AND paper_type_id NOT IN (10,11,12,22,61,68,100,123,105)';

                                $ppaper_type_id=' AND paper_type_id IN (10,11,12,22,61,68,100,123,137,105)';
                            }
                                              

                            $regrejoin=''; $rejoin_stu_count=$t_count_rejoin=$p_count_rejoin=$th_count_rejoin=$tp_count_rejoin=0;

                            
                            $detain_arr=Yii::$app->db->createCommand('SELECT SM.coe_student_mapping_id,B.register_number FROM coe_student_mapping SM JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=SM.course_batch_mapping_id JOIN coe_student B ON B.coe_student_id=SM.student_rel_id JOIN coe_student_mapping DE ON DE.previous_reg_number=B.register_number WHERE SM.status_category_type_id NOT IN('.$det_disc_type.') AND F.coe_batch_id="'.$value1['coe_batch_id'].'" AND SM.status_category_type_id =4')->queryAll();

                            $detain_arr_stu='';
                            if(!empty($detain_arr))
                            {
                              
                              $notIn = array_filter(['']);
                                    foreach ($detain_arr as $valu) 
                                    {
                                       
                                        $notIn[$valu['coe_student_mapping_id']] = $valu['coe_student_mapping_id'];
                                    }      

                                $notIn=implode(",",$notIn);
                               
                                $notIn=rtrim($notIn,",");
                                $notIn=ltrim($notIn,",");
                                $detain_arr_stu=' AND A.student_map_id IN ('.$notIn.')';  
                                                          

                              $t_count_rejoin=Yii::$app->db->createCommand('SELECT count(distinct A.subject_map_id) FROM coe_mark_entry_master as A 
                                JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                                JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                                JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id 
                                JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                                where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                                where F.coe_batch_id="'.$value1['coe_batch_id'].'" AND B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.$detain_arr_stu.') AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$tpaper_type_id.$detain_arr_stu)->queryScalar();

                              $th_count_rejoin=Yii::$app->db->createCommand('SELECT count(distinct A.subject_map_id) FROM coe_mark_entry_master as A 
                                JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                                JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                                JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id 
                                JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                                where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                                where F.coe_batch_id="'.$value1['coe_batch_id'].'" AND B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.$detain_arr_stu.') AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$tpaper_type_id.$detain_arr_stu.$tsubject_type_id)->queryScalar();

                              $tp_count_rejoin=Yii::$app->db->createCommand('SELECT count(distinct A.subject_map_id) FROM coe_mark_entry_master as A 
                                JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                                JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                                JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id 
                                JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                                where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                                where F.coe_batch_id="'.$value1['coe_batch_id'].'" AND B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.$detain_arr_stu.') AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$tpaper_type_id.$detain_arr_stu.$tpsubject_type_id)->queryScalar();

                                 $p_count_rejoin=Yii::$app->db->createCommand('SELECT count(distinct A.subject_map_id) FROM coe_mark_entry_master as A 
                                JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                                JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                                JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id 
                                JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                                where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                                where F.coe_batch_id="'.$value1['coe_batch_id'].'" AND B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.$detain_arr_stu.') AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$ppaper_type_id.$detain_arr_stu)->queryScalar(); 
                            }
                           


                             $stu_count_gen=Yii::$app->db->createCommand('SELECT count(distinct A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')')->queryScalar();
                        
                            $t_count=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$tpaper_type_id)->queryScalar();

                            $p_count=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$ppaper_type_id)->queryScalar();

                            $th_count=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$tpaper_type_id.$tsubject_type_id)->queryScalar();

                           
                            $tp_count=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (3,7,6) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$tpaper_type_id.$tpsubject_type_id)->queryScalar();

                            $stu_count_detain=Yii::$app->db->createCommand('SELECT count(distinct A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id  
                          JOIN coe_student as S ON S.coe_student_id=B.student_rel_id
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$rejoin)->queryScalar();

                            $t_count_detain=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$tpaper_type_id.$rejoin)->queryScalar();

                            $th_count_detain=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id 
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$tpaper_type_id.$rejoin.$tsubject_type_id)->queryScalar();

                            $p_count_detain=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$ppaper_type_id.$rejoin)->queryScalar();

                            $tp_count_detain=Yii::$app->db->createCommand('SELECT count(A.student_map_id) FROM coe_mark_entry_master as A 
                          JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.student_map_id
                          JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.subject_map_id and B.course_batch_mapping_id=C.batch_mapping_id 
                          JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                          JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                          where degree_type="PG" AND G.degree_code!="Ph.D" AND year_of_passing="" and subject_map_id NOT IN(select B.subject_map_id from coe_mark_entry_master B
                          where B.student_map_id=A.student_map_id and B.result like "%Pass%" AND B.year='.$year.' AND B.month='.$month.') AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.year='.$year.' AND A.month='.$month.' AND B.status_category_type_id IN (4) AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$tpaper_type_id.$rejoin.$tpsubject_type_id)->queryScalar();


                             $q_th_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="PG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.') '.$tpaper_type_id; 

                              $th_abs=Yii::$app->db->createCommand($q_th_abs)->queryScalar();
                             
                              $q_tpt_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="PG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.') AND A.exam_date is NULL '.$thppaper_type_id;
                           
                             $tpt_abs=Yii::$app->db->createCommand($q_tpt_abs)->queryScalar();

                             $q_tpp_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="PG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.') AND A.exam_date is NOT NULL '.$thppaper_type_id;                           
                           
                             $tpp_abs=Yii::$app->db->createCommand($q_tpp_abs)->queryScalar();

                             $q_p_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="PG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.')'.$ppaper_type_id;                             
                           
                             $p_abs=Yii::$app->db->createCommand($q_p_abs)->queryScalar();

                             $qry_tot_abs='SELECT count(A.absent_student_reg) FROM coe_absent_entry as A 
                              JOIN coe_student_mapping as B ON B.coe_student_mapping_id=A.absent_student_reg 
                              JOIN coe_subjects_mapping as C ON C.coe_subjects_mapping_id=A.exam_subject_id and B.course_batch_mapping_id=C.batch_mapping_id 
                              JOIN coe_bat_deg_reg as F ON F.coe_bat_deg_reg_id=B.course_batch_mapping_id and F.coe_bat_deg_reg_id=C.batch_mapping_id  
                              JOIN coe_degree as G ON G.coe_degree_id=F.coe_degree_id
                              where A.exam_type=28 AND G.degree_type="PG" AND F.coe_batch_id='.$value1['coe_batch_id'].' AND A.exam_year='.$curyear.' AND A.exam_month='.$curmon.' AND B.status_category_type_id NOT IN('.$det_disc_type.') ';
                           
                             $tot_abs=Yii::$app->db->createCommand($qry_tot_abs)->queryScalar();

                            $stu_count_gen=$stu_count_gen;
                            $t_count=$t_count;
                            $p_count=$p_count;

                            if($value1['batch_name']>=2021 && $stu_count_gen>0)
                            {
                              $p_count=$p_count+$p_count_rejoin+($tp_count);
                                $body .= '<tr>';
                              $body .='<td>'.$value1['batch_name'].'</td>';
                              $body .='<td align="center">'.$stu_count_gen.'</td>';
                              $body .='<td align="center">'.$t_count.' (T:'.($th_count).' TP:'.($tp_count).')</td>';
                              $body .='<td align="center">'.($p_count).' (P:'.($p_count-($tp_count)).' TPP:'.($tp_count).')</td>';
                              $body .='<td align="center">'.($t_count+$p_count).'</td>';
                              
                              $body .='<td align="center">'.$stu_count_detain.'</td>';
                              $body .='<td align="center">'.$t_count_detain.' (T:'.$th_count_detain.' TP:'.$tp_count_detain.')</td>';
                              $p_count_detain=$p_count_detain+$tp_count_detain;
                              $body .='<td align="center">'.$p_count_detain.' (P:'.($p_count_detain-$tp_count_detain).' TPP:'.$tp_count_detain.')</td>';
                              $body .='<td align="center">'.($t_count_detain+$p_count_detain).'</td>';

                              $body .='<td align="center">'.($stu_count_gen+$stu_count_detain).'</td>';
                              $body .='<td align="center">'.($t_count+$t_count_detain).'</td>';
                              $body .='<td align="center">'.($p_count+$p_count_detain).'</td>';
                             
                              //$body .='<td align="center">'.(($t_count+$p_count)+($t_count_detain+$p_count_detain)).'</td>';

                              $ot=($t_count+($p_count-($tp_count))+$t_count_detain+($p_count_detain-$tp_count_detain));
                              $poveralltotal=$poveralltotal+$ot;
                              $body .='<td align="center">'.$ot.'</td>';
                              $body .='<td align="center">'.$th_abs.'</td>';
                              $body .='<td align="center">'.$tpt_abs.'</td>';
                              $body .='<td align="center">'.$tpt_abs.'</td>';
                              $body .='<td align="center">'.$p_abs.'</td>';
                              $body .='<td align="center">'.($tot_abs).'</td>';
                              $body .= '</tr>';

                              $ptot_tabs=$ptot_tabs+$th_abs;
                              $ptot_tptabs=$ptot_tptabs+$tpt_abs;
                              $ptot_tppabs=$ptot_tppabs+$tpt_abs;
                              $ptot_pabs=$ptot_pabs+$p_abs;
                              $pgrand_tot_abs=$pgrand_tot_abs+$tot_abs;
                            }
                            else if($stu_count_gen>0)
                            {
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
                              $ot=(($t_count+$p_count)+($t_count_detain+$p_count_detain));
                              $poveralltotal=$poveralltotal+$ot;
                              $body .='<td align="center">'.$ot.'</td>';
                              $body .='<td align="center">'.$th_abs.'</td>';
                              $body .='<td align="center">'.$tpt_abs.'</td>';
                              $body .='<td align="center">'.$tpt_abs.'</td>';
                              $body .='<td align="center">'.$p_abs.'</td>';
                              $body .='<td align="center">'.($tot_abs).'</td>';
                              $body .= '</tr>';

                              $ptot_tabs=$ptot_tabs+$th_abs;
                              $ptot_tptabs=$ptot_tptabs+$tpt_abs;
                              $ptot_tppabs=$ptot_tppabs+$tpt_abs;
                              $ptot_pabs=$ptot_pabs+$p_abs;
                              $pgrand_tot_abs=$pgrand_tot_abs+$tot_abs;
                            }
                            
                            
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
                       <td align="center">'.$pgtot_stu.'</td><td align="center">'.$pgtot_t.'</td><td align="center">'.$pgtot_p.'</td><td align="center">'.$poveralltotal.'</td>
                       <td align="center">'.$ptot_tabs.'</td><td align="center">'.$ptot_tptabs.'</td><td align="center">'.$ptot_tppabs.'</td><td align="center">'.$ptot_pabs.'</td><td align="center">'.$pgrand_tot_abs.'</td></tr>';

                       $body.='<tr><td>Grand Total</td>
                       <td align="center">'.$tot_gstu.'</td><td align="center">'.$tot_gt.'</td><td align="center">'.$tot_gp.'</td><td align="center">'.$grand_gtot.'</td>
                       <td align="center">'.$tot_dstu.'</td><td align="center">'.$tot_dt.'</td><td align="center">'.$tot_dp.'</td><td align="center">'.$grand_dtot.'</td>
                       <td align="center">'.$tot_stu.'</td><td align="center">'.$tot_t.'</td><td align="center">'.$tot_p.'</td><td align="center">'.($overalltotal+$poveralltotal).'</td>

                       <td align="center">'.($tot_tabs+$ptot_tabs).'</td><td align="center">'.($tot_tptabs+$ptot_tptabs).'</td><td align="center">'.($tot_tppabs+$ptot_tppabs).'</td><td align="center">'.($tot_pabs+$ptot_pabs).'</td><td align="center">'.($grand_tot_abs+$pgrand_tot_abs).'</td>

                       </tr></tbody> </table>';


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