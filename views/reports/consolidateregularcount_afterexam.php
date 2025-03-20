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


$this->title = "CONSOLIDATE REGULAR COUNT REPORT AFTER EXAM";
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
.scriptbckgrd
{
    background-color: #c4cacf !important;
}
.totalscriptbckgrd
{
    background-color: #c3baba !important;
    font-weight: bold;
}
.contenttable
{
    overflow-x:auto;
}
.contenttable td
{
   padding:5px !important;
}
.contenttable th
{
   padding:2px !important;
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
                         
                          <td align="center"> 
                              <center><h2><b>'.$org_name.'</b></h2></center>
                              <center><h6>('.$org_address.')</h6></center>
                              <center><h5>'.$org_tagline.'</h5></center> 
                         </td>
                        
                        </tr> ';
                        
                    $html .= '<tr>
                            <td align="center"><b>EXAMINATIONS: ' . strtoupper($month) . ' ' . $Year1 .'
                            </b></td>
                        </tr></table>';
                        
                    $det_cat_type = 4;

                    $det_disc_type = 93;
                    
                    $transfer = 5;

                    $rejoin_type = 6;

                    $latearl =7;

                    $i=0; 

                    $body='<table class="contenttable" width="100%"  cellspacing="0" cellpadding="0" border="0">
                        <tbody>';
                    $n=count($content_data)-1;
                    foreach ($content_data as $key => $value1) 
                    {


                        if(!empty($value1["content"]))
                        {
                            $yrs=$value1["year"];
                            
                            $body .=' <tr>
                            <td colspan=45 align="center" style="background-color: #2173bc; color: #fff;">Batch: '.$value1["year"].' Semester: '.$semester.'</td>
                            </tr>
                            <tr class="table-danger">
                                <th colspan=3 class="scriptbckgrd"></th>
                                <th colspan=4 align=center>Common Courses</th>
                                <th colspan=5 class="scriptbckgrd" align=center>Scripts</th>
                                <th colspan=4 align=center>Elective Courses</th>
                                <th colspan=5 class="scriptbckgrd" align=center>Scripts</th>
                                <th colspan=4 align=center>Additional Courses</th>
                                <th colspan=5 class="scriptbckgrd" align=center>Scripts</th>
                                <th colspan=4 align=center>Rejoin Additional Courses</th>
                                <th colspan=5 class="scriptbckgrd" align=center>Scripts</th>
                                <th colspan=5 align=center>Total</th>
                                <th rowspan=2 class="scriptbckgrd" align=center>Final Total (A+B+C+D)</th>
                            </tr>
                            <tr class="table-danger">
                                
                                <th class="scriptbckgrd">S.No.</th>                
                                <th class="scriptbckgrd">Class</th>
                                <th class="scriptbckgrd">No of Students</th>';

                            for ($l=0; $l <4 ; $l++) 
                            { 
                                $body .='<th>No of Theory</th>
                                <th>No of T&P</th>
                                <th>No of Practical</th>
                                <th>No of Other</th>';
                                $body .='<th class="scriptbckgrd">T Scripts</th>
                                <th class="scriptbckgrd">TPT Scripts</th>                                
                                <th class="scriptbckgrd">P Scripts</th>
                                <th class="scriptbckgrd">TPP Scripts</th>
                                <th class="scriptbckgrd">Other Scripts</th>';
                            }
                            

                            $body .='<th>T Scripts (A)</th>
                            <th>TPT Scripts (B)</th>
                            <th>P Scripts (C)</th>
                            <th>TPP Scripts</th>
                            <th>Other Scripts (D)</th>
                            </tr>';
                            

                            $sn_no = 1;

                            $ug_t_script=0;
                            $ug_tpt_script=0;
                            $ug_pp_script=0;
                            $ug_tpp_script=0;
                            $ug_othr_script=0;
                            $ug_final_total=0;
                            
                            foreach ($value1["content"] as $value) 
                            {
                                $commonstu_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['NOT IN','status_category_type_id',[$transfer,$rejoin_type,$det_disc_type,$det_cat_type,$latearl]])->all();
                                $commonstu_count_dip = count($commonstu_count)==0?'0':count($commonstu_count);

                                $lateral_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$latearl])->all();
                                $lateral_count_dip = count($lateral_count)==0?'0':count($lateral_count);

                                $transfer_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$transfer])->andWhere(['>', 'semester_detain', $semester])->all();
                                $transfer_count_dip = count($transfer_count)==0?'0':count($transfer_count);

                                $rejoin_query = new Query();
                                $rejoin_query->select(['distinct (student_rel_id)'])
                                ->from('coe_student_mapping a')
                                ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                  ->andWhere(['=', 'a.status_category_type_id', $rejoin_type])
                                   ->andWhere(['<=', 'semester_detain', $semester]);
                                $rejoin_result = $rejoin_query->createCommand()->queryAll();
                                $rejoin_dip = count($rejoin_result)==0?'0':count($rejoin_result);

                                $disc_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type]) ->andWhere(['>', 'semester_detain', $semester])->all();
                                $disc_disp = count($disc_count)==0?'0':count($disc_count);

                                $det_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['>', 'semester_detain', $semester])->all();
                                $detain_disp = count($det_count)==0?'0':count($det_count);

                                $ott=0;//count($disc_disp)+count($detain_disp);

                                $commonstu_count_dip = $commonstu_count_dip + $ott;

                                $stu_active_count =$commonstu_count_dip+$transfer_count_dip+$rejoin_dip+$lateral_count_dip; 
                                $stu_active_count_disp = count($stu_active_count)==0?'0':$stu_active_count;

                                
                                if($value['degree_type']=='UG')
                                {
                                    $tot_t_script=0;
                                    $tot_tpt_script=0;
                                    $tot_pp_script=0;
                                    $tot_tpp_script=0;
                                    $tot_othr_script=0;

                                    $final_total=0;

                                    if($value1['year']>=2020)
                                    {
                                        $body .='<tr>';
                                        $body .='<td class="scriptbckgrd">'.$sn_no.'</td>';
                                        $body .='<td class="scriptbckgrd">'.$value['degree_code'].' '.strtoupper($value['programme_name']).'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$stu_active_count_disp.'</td>';

                                        //common courses

                                        $t_script=0;
                                        $tpt_script=0;
                                        $pp_script=0;
                                        $tpp_script=0;
                                        $othr_script=0;
                                        
                                        $th_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (8) AND  course_type_id NOT IN (230,231,232)")->queryScalar();
                                        
                                        if($value1['year']==2020)
                                        {
                                            $tpt_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (121) AND type_id IN (143) AND  course_type_id NOT IN (230,231,232)")->queryScalar();
                                        }
                                        else
                                        {
                                            $tpt_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (121) AND type_id NOT IN (143,144) AND course_type_id NOT IN (230,231,232)")->queryScalar();
                                        }

                                        $pp_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (10,11,123) AND  course_type_id NOT IN (230,231,232)")->queryScalar();

                                        $tpp_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (121) AND type_id IN (144) AND  course_type_id NOT IN (230,231,232)")->queryScalar();

                                        $other_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (105,106,122,139,218) AND type_id IN (143,105) AND  course_type_id NOT IN (230,231,232)")->queryScalar();
                                        
                                        $body .='<td align=center>'.($th_sub).'</td>';
                                        $body .='<td align=center>'.($tpt_sub).'</td>';
                                        $body .='<td align=center>'.($pp_sub+$tpp_sub).'</td>';
                                        $body .='<td align=center>'.$other_sub.'</td>';
                                            
                                        $t_script=$th_sub*$stu_active_count_disp;
                                        $tpt_script=$tpt_sub*$stu_active_count_disp;
                                        $pp_script=($pp_sub*$stu_active_count_disp)+($tpp_sub*$stu_active_count_disp);
                                        $tpp_script=$tpt_sub*$stu_active_count_disp;
                                        $othr_script=$other_sub*$stu_active_count_disp;

                                        $tot_t_script=$tot_t_script+$t_script;
                                        $tot_tpt_script=$tot_tpt_script+$tpt_script;
                                        $tot_pp_script=$tot_pp_script+$pp_script;
                                        $tot_tpp_script=$tot_tpp_script+$tpt_script;
                                        $tot_othr_script=$tot_othr_script+$othr_script;

                                        $body .='<td class="scriptbckgrd" align=center>'.$t_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$pp_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$othr_script.'</td>';

                                        //elective courses

                                        $t_script=0;
                                        $tpt_script=0;
                                        $pp_script=0;
                                        $tpp_script=0;
                                        $othr_script=0;

                                        $th_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (8) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                        $new_t = array();

                                        foreach ($th_sube as $val) 
                                        {
                                            $new_t[]=$val['subject_id'];
                                        }
                                    
                                        $tpt_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (121) AND type_id NOT IN (143,144) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                        $new_tpt = array();

                                        foreach ($tpt_sube as $val) 
                                        {
                                            $new_tpt[]=$val['subject_id'];
                                        }

                                        $pp_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (10,11,123) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                        $new_pp = array();

                                        foreach ($pp_sube as $val) 
                                        {
                                            $new_pp[]=$val['subject_id'];
                                        }

                                        $tpp_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (121) AND type_id IN (144) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                        $new_tpp = array();

                                        foreach ($tpp_sube as $val) 
                                        {
                                            $new_tpp[]=$val['subject_id'];
                                        }

                                        $other_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (105,106,122,139,218) AND type_id IN (143,105) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                        $new_othr = array();

                                        foreach ($other_sube as $val) 
                                        {
                                            $new_othr[]=$val['subject_id'];
                                        }
                                        
                                        $body .='<td align=center>'.count($th_sube).'</td>';
                                        $body .='<td align=center>'.count($tpt_sube).'</td>';
                                        $body .='<td align=center>'.(count($pp_sube)+count($tpp_sube)).'</td>';
                                        $body .='<td align=center>'.count($other_sube).'</td>';
                                        //print_r($new_t);  exit();
                                        $elect_qt = new Query();
                                        $elect_qt->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_t]);
                                        //echo "<br>".$elect_qt->createCommand()->getRawSql(); exit();
                                        $t_script = $elect_qt->createCommand()->queryScalar();

                                        $elect_tpt = new Query();
                                        $elect_tpt->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_tpt]);
                                        //echo "<br>".$elect_qt->createCommand()->getRawSql(); 
                                        $tpt_script = $elect_tpt->createCommand()->queryScalar();

                                        $elect_pp = new Query();
                                        $elect_pp->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_pp]);
                                        //echo "<br>".$elect_qt->createCommand()->getRawSql(); 
                                        $pp_script = $elect_pp->createCommand()->queryScalar();

                                        $elect_tpp = new Query();
                                        $elect_tpp->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                       ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_tpp]);
                                        //echo "<br>".$elect_qt->createCommand()->getRawSql(); 
                                        $tpp_script = $elect_tpp->createCommand()->queryScalar();

                                        $elect_othr = new Query();
                                        $elect_othr->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_othr]);
                                        //echo "<br>".$elect_qt->createCommand()->getRawSql(); 
                                        $othr_script = $elect_othr->createCommand()->queryScalar();
                                        
                                        $body .='<td class="scriptbckgrd" align=center>'.$t_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.($pp_script+$tpp_script).'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$othr_script.'</td>';
                                       
                                        $tot_t_script=$tot_t_script+$t_script;
                                        $tot_tpt_script=$tot_tpt_script+$tpt_script;
                                        $tot_pp_script=$tot_pp_script+($pp_script+$tpp_script);
                                        $tot_tpp_script=$tot_tpp_script+$tpt_script;
                                        $tot_othr_script=$tot_othr_script+$othr_script;

                                        //additional courses

                                        $t_script=0;
                                        $tpt_script=0;
                                        $p_script=0;
                                        $tpp_script=0;
                                        $othr_script=0;

                                        $th_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (8) AND type_id NOT IN (105) AND additional_course=0")->queryAll();

                                        $new_t = array();

                                        foreach ($th_suba as $val) 
                                        {
                                            $new_t[]=$val['subject_id'];
                                        }

                                        $tpt_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (121) AND type_id NOT IN (143,144) AND additional_course=0")->queryAll();

                                        $new_tpt = array();

                                        foreach ($tpt_suba as $val) 
                                        {
                                            $new_tpt[]=$val['subject_id'];
                                        }

                                        $pp_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (10,11,123) AND additional_course=0")->queryAll();

                                        $new_pp = array();

                                        foreach ($pp_suba as $val) 
                                        {
                                            $new_pp[]=$val['subject_id'];
                                        }

                                        $tpp_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (121) AND type_id IN (144) AND additional_course=0")->queryAll();

                                        $new_tpp = array();

                                        foreach ($tpp_suba as $val) 
                                        {
                                            $new_tpp[]=$val['subject_id'];
                                        }

                                        $other_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (8,105,106,122,139,218) AND type_id IN (143,105) AND course_type_id NOT IN (231,232) AND additional_course=0")->queryAll();

                                        $new_othr = array();

                                        foreach ($other_suba as $val) 
                                        {
                                            $new_othr[]=$val['subject_id'];
                                        }
                                            
                                        $body .='<td align=center>'.count($th_suba).'</td>';
                                        $body .='<td align=center>'.count($tpt_suba).'</td>';
                                        $body .='<td align=center>'.(count($pp_suba)+count($tpp_suba)).'</td>';
                                        $body .='<td align=center>'.count($other_suba).'</td>';
                                        
                                        $add_qt = new Query();
                                        $add_qt->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_t]); 
                                        $t_script = $add_qt->createCommand()->queryScalar();

                                        $add_tpt = new Query();
                                        $add_tpt->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_tpt]);
                                        $tpt_script = $add_tpt->createCommand()->queryScalar();

                                        $add_pp = new Query();
                                        $add_pp->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_pp]);
                                        $pp_script = $add_pp->createCommand()->queryScalar();

                                        $add_tpp = new Query();
                                        $add_tpp->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_tpp]);
                                        $tpp_script = $add_tpp->createCommand()->queryScalar();

                                        $add_othr = new Query();
                                        $add_othr->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_othr]);
                                        $othr_script = $add_othr->createCommand()->queryScalar();
                                        
                                        $body .='<td class="scriptbckgrd" align=center>'.$t_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.($pp_script+$tpp_script).'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$othr_script.'</td>';

                                        $tot_t_script=$tot_t_script+$t_script;
                                        $tot_tpt_script=$tot_tpt_script+$tpt_script;
                                        $tot_pp_script=$tot_pp_script+($pp_script+$tpp_script);
                                        $tot_tpp_script=$tot_tpp_script+$tpt_script;
                                        $tot_othr_script=$tot_othr_script+$othr_script;
                                        
                                        //rejoin studnet addtional course
                                        
                                        $th_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (8) AND course_type_id NOT IN (230,231,232) AND additional_course=1")->queryAll();

                                        $new_t = array();

                                        foreach ($th_subc as $val) 
                                        {
                                            $new_t[]=$val['subject_id'];
                                        }

                                        $tpt_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (121) AND type_id NOT IN (143,144) AND course_type_id NOT IN (230,231,232) AND additional_course=1")->queryAll();

                                        $new_tpt = array();

                                        foreach ($tpt_subc as $val) 
                                        {
                                            $new_tpt[]=$val['subject_id'];
                                        }

                                        $pp_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (10,11,123) AND course_type_id NOT IN (230,231,232) AND additional_course=1")->queryAll();

                                        $new_pp = array();

                                        foreach ($pp_subc as $val) 
                                        {
                                            $new_pp[]=$val['subject_id'];
                                        }

                                        $tpp_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (121) AND type_id IN (144) AND course_type_id NOT IN (230,231,232) AND additional_course=1")->queryAll();

                                        $new_tpp = array();

                                        foreach ($tpp_subc as $val) 
                                        {
                                            $new_tpp[]=$val['subject_id'];
                                        }

                                        $other_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (105,106,122,139,218) AND type_id IN (143,105) AND course_type_id NOT IN (230,231,232) AND additional_course=1")->queryAll();

                                        $new_othr = array();

                                        foreach ($other_subc as $val) 
                                        {
                                            $new_othr[]=$val['subject_id'];
                                        }
                                            
                                        $body .='<td align=center>'.count($th_subc).'</td>';
                                        $body .='<td align=center>'.count($tpt_subc).'</td>';
                                        $body .='<td align=center>'.(count($pp_subc)+count($tpp_subc)).'</td>';
                                        $body .='<td align=center>'.count($other_subc).'</td>';
                                        
                                        $t_script=0;
                                        $tpt_script=0;
                                        $p_script=0;
                                        $tpp_script=0;
                                        $othr_script=0;
                                        
                                        $add_qt = new Query();
                                        $add_qt->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_t]); 
                                        $t_script = $add_qt->createCommand()->queryScalar();

                                        $add_tpt = new Query();
                                        $add_tpt->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_tpt]);
                                        $tpt_script = $add_tpt->createCommand()->queryScalar();

                                        $add_pp = new Query();
                                        $add_pp->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_pp]);
                                        $pp_script = $add_pp->createCommand()->queryScalar();

                                        $add_tpp = new Query();
                                        $add_tpp->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_tpp]);
                                        $tpp_script = $add_tpp->createCommand()->queryScalar();

                                        $add_othr = new Query();
                                        $add_othr->select(['count(coe_student_id)'])
                                        ->from('coe_nominal a')
                                        ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                        ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                        ->andWhere(['a.semester'=>$semester])
                                        ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                        ->andWhere(['IN','a.coe_subjects_id',$new_othr]);
                                        $othr_script = $add_othr->createCommand()->queryScalar();

                                        $body .='<td class="scriptbckgrd" align=center>'.$t_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.($pp_script+$tpp_script).'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                        $body .='<td class="scriptbckgrd" align=center>'.$othr_script.'</td>';

                                        $tot_t_script=$tot_t_script+$t_script;
                                        $tot_tpt_script=$tot_tpt_script+$tpt_script;
                                        $tot_pp_script=$tot_pp_script+($pp_script+$tpp_script);
                                        $tot_tpp_script=$tot_tpp_script+$tpt_script;
                                        $tot_othr_script=$tot_othr_script+$othr_script;

                                        $final_total=$tot_t_script+$tot_tpt_script+$tot_pp_script+$tot_othr_script;

                                        $ug_t_script=$ug_t_script+$tot_t_script;
                                        $ug_tpt_script=$ug_tpt_script+$tot_tpt_script;
                                        $ug_pp_script=$ug_pp_script+$tot_pp_script;
                                        $ug_tpp_script=$ug_tpp_script+$tot_tpp_script;
                                        $ug_othr_script=$ug_othr_script+$tot_othr_script;

                                        $ug_final_total=$ug_final_total+$final_total;

                                        $body .='<td align=center>'.$tot_t_script.'</td>';
                                        $body .='<td align=center>'.$tot_tpt_script.'</td>';
                                        $body .='<td align=center>'.$tot_pp_script.'</td>';
                                        $body .='<td align=center>'.$tot_tpp_script.'</td>';
                                        $body .='<td align=center>'.$tot_othr_script.'</td>';

                                        $body .='<td class="scriptbckgrd" align=center>'.$final_total.'</td>';

                                        $body .='</tr>';
                                    }

                                    $sn_no++;
                                }

                                
                            }

                            $body .='<tr>';
                            $body .='<td class="totalscriptbckgrd" colspan=39 align=right><b>TOTAL UG  </b></td>';
                            $body .='<td class="totalscriptbckgrd" align=center>'.$ug_t_script.'</td>';
                            $body .='<td class="totalscriptbckgrd" align=center>'.$ug_tpt_script.'</td>';
                            $body .='<td class="totalscriptbckgrd" align=center>'.$ug_pp_script.'</td>';
                            $body .='<td class="totalscriptbckgrd" align=center>'.$ug_tpp_script.'</td>';
                            $body .='<td class="totalscriptbckgrd" align=center>'.$ug_othr_script.'</td>';
                            $body .='<td class="totalscriptbckgrd" align=center>'.$ug_final_total.'</td>';
                            $body .='</tr>';

                            $pg_t_script=0;
                            $pg_tpt_script=0;
                            $pg_pp_script=0;
                            $pg_tpp_script=0;
                            $pg_othr_script=0;
                            $pg_final_total=0;

                            foreach ($value1["content"] as $value) 
                            {
                                $commonstu_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['NOT IN','status_category_type_id',[$transfer,$rejoin_type,$det_disc_type,$det_cat_type,$latearl]])->all();
                                $commonstu_count_dip = count($commonstu_count)==0?'0':count($commonstu_count);

                                $lateral_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$latearl])->all();
                                $lateral_count_dip = count($lateral_count)==0?'0':count($lateral_count);

                                $transfer_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$transfer])->andWhere(['>', 'semester_detain', $semester])->all();
                                $transfer_count_dip = count($transfer_count)==0?'0':count($transfer_count);

                                $rejoin_query = new Query();
                                $rejoin_query->select(['distinct (student_rel_id)'])
                                ->from('coe_student_mapping a')
                                ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                  ->andWhere(['=', 'a.status_category_type_id', $rejoin_type])
                                   ->andWhere(['<=', 'semester_detain', $semester]);
                                $rejoin_result = $rejoin_query->createCommand()->queryAll();
                                $rejoin_dip = count($rejoin_result)==0?'0':count($rejoin_result);

                                $disc_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type]) ->andWhere(['>', 'semester_detain', $semester])->all();
                                $disc_disp = count($disc_count)==0?'0':count($disc_count);

                                $det_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['>', 'semester_detain', $semester])->all();
                                $detain_disp = count($det_count)==0?'0':count($det_count);

                                $ott=0;//count($disc_disp)+count($detain_disp);

                                $commonstu_count_dip = $commonstu_count_dip + $ott;

                                $stu_active_count =$commonstu_count_dip+$transfer_count_dip+$rejoin_dip+$lateral_count_dip; 
                                $stu_active_count_disp = count($stu_active_count)==0?'0':$stu_active_count;

                                
                                if(($value['degree_type']=='PG') && ($value['degree_code']!='Ph.D') && ($value['semester']<=$semester && $value['degree_total_semesters']>=$semester  && ($semester<5) ))
                                {
                                    $tot_t_script=0;
                                    $tot_tpt_script=0;
                                    $tot_pp_script=0;
                                    $tot_tpp_script=0;
                                    $tot_othr_script=0;

                                    $final_total=0;

                                    $degree_code='';
                                    if($value['degree_code']=='MBABISEM')
                                    {
                                        $degree_code='MBA';
                                    }
                                    else
                                    {
                                        $degree_code=$value['degree_code'];
                                    }

                                    
                                    $body .='<tr>';
                                    $body .='<td class="scriptbckgrd">'.$sn_no.'</td>';
                                    $body .='<td class="scriptbckgrd">'.$degree_code.' '.strtoupper($value['programme_name']).'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$stu_active_count_disp.'</td>';

                                    //common courses

                                    $t_script=0;
                                    $tpt_script=0;
                                    $pp_script=0;
                                    $tpp_script=0;
                                    $othr_script=0;
                                    
                                    $th_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (8) AND  course_type_id NOT IN (230,231,232)")->queryScalar();
                                
                                    $tpt_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (121) AND type_id NOT IN (143,144) AND  course_type_id NOT IN (230,231,232)")->queryScalar();

                                    $pp_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (10,11,123) AND  course_type_id NOT IN (230,231,232)")->queryScalar();

                                    $tpp_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (121) AND type_id IN (144) AND  course_type_id NOT IN (230,231,232)")->queryScalar();

                                    $other_sub = Yii::$app->db->createCommand("SELECT count(coe_subjects_mapping_id) FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=13 AND paper_type_id IN (105,106,122,139,218) AND type_id IN (143,105) AND  course_type_id NOT IN (230,231,232)")->queryScalar();
                                    
                                    $body .='<td align=center>'.($th_sub).'</td>';
                                    $body .='<td align=center>'.($tpt_sub).'</td>';
                                    $body .='<td align=center>'.($pp_sub+$tpp_sub).'</td>';
                                    $body .='<td align=center>'.$other_sub.'</td>';
                                        
                                    $t_script=$th_sub*$stu_active_count_disp;
                                    $tpt_script=$tpt_sub*$stu_active_count_disp;
                                    $pp_script=($pp_sub*$stu_active_count_disp)+($tpp_sub*$stu_active_count_disp);
                                    $tpp_script=$tpt_sub*$stu_active_count_disp;
                                    $othr_script=$other_sub*$stu_active_count_disp;

                                    $tot_t_script=$tot_t_script+$t_script;
                                    $tot_tpt_script=$tot_tpt_script+$tpt_script;
                                    $tot_pp_script=$tot_pp_script+$pp_script;
                                    $tot_tpp_script=$tot_tpp_script+$tpt_script;
                                    $tot_othr_script=$tot_othr_script+$othr_script;

                                    $body .='<td class="scriptbckgrd" align=center>'.$t_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$pp_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$othr_script.'</td>';

                                    //elective courses

                                    $t_script=0;
                                    $tpt_script=0;
                                    $pp_script=0;
                                    $tpp_script=0;
                                    $othr_script=0;

                                    $th_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (8) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_t = array();

                                    foreach ($th_sube as $val) 
                                    {
                                        $new_t[]=$val['subject_id'];
                                    }
                                
                                    $tpt_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (121) AND type_id NOT IN (143,144) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_tpt = array();

                                    foreach ($tpt_sube as $val) 
                                    {
                                        $new_tpt[]=$val['subject_id'];
                                    }

                                    $pp_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (10,11,123) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_pp = array();

                                    foreach ($pp_sube as $val) 
                                    {
                                        $new_pp[]=$val['subject_id'];
                                    }

                                    $tpp_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (121) AND type_id IN (144) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_tpp = array();

                                    foreach ($tpp_sube as $val) 
                                    {
                                        $new_tpp[]=$val['subject_id'];
                                    }

                                    $other_sube = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=15 AND paper_type_id IN (105,106,122,139,218) AND type_id IN (143,105) AND  course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_othr = array();

                                    foreach ($other_sube as $val) 
                                    {
                                        $new_othr[]=$val['subject_id'];
                                    }
                                    
                                    $body .='<td align=center>'.count($th_sube).'</td>';
                                    $body .='<td align=center>'.count($tpt_sube).'</td>';
                                    $body .='<td align=center>'.(count($pp_sube)+count($tpp_sube)).'</td>';
                                    $body .='<td align=center>'.count($other_sube).'</td>';

                                    $elect_qt = new Query();
                                    $elect_qt->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_t]);
                                    //echo "<br>".$elect_qt->createCommand()->getRawSql(); 
                                    $t_script = $elect_qt->createCommand()->queryScalar();

                                    $elect_tpt = new Query();
                                    $elect_tpt->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_tpt]);
                                    //echo "<br>".$elect_qt->createCommand()->getRawSql(); 
                                    $tpt_script = $elect_tpt->createCommand()->queryScalar();

                                    $elect_pp = new Query();
                                    $elect_pp->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_pp]);
                                    //echo "<br>".$elect_qt->createCommand()->getRawSql(); 
                                    $pp_script = $elect_pp->createCommand()->queryScalar();

                                    $elect_tpp = new Query();
                                    $elect_tpp->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_tpp]);
                                    //echo "<br>".$elect_qt->createCommand()->getRawSql(); 
                                    $tpp_script = $elect_tpp->createCommand()->queryScalar();

                                    $elect_othr = new Query();
                                    $elect_othr->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_othr]);
                                    //echo "<br>".$elect_qt->createCommand()->getRawSql(); 
                                    $othr_script = $elect_othr->createCommand()->queryScalar();
                                    
                                    $body .='<td class="scriptbckgrd" align=center>'.$t_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.($pp_script+$tpp_script).'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$othr_script.'</td>';
                                   
                                    $tot_t_script=$tot_t_script+$t_script;
                                    $tot_tpt_script=$tot_tpt_script+$tpt_script;
                                    $tot_pp_script=$tot_pp_script+($pp_script+$tpp_script);
                                    $tot_tpp_script=$tot_tpp_script+$tpt_script;
                                    $tot_othr_script=$tot_othr_script+$othr_script;

                                    //additional courses

                                    $t_script=0;
                                    $tpt_script=0;
                                    $p_script=0;
                                    $tpp_script=0;
                                    $othr_script=0;

                                    $th_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (8) AND  course_type_id IN (230,231,232)")->queryAll();

                                    $new_t = array();

                                    foreach ($th_suba as $val) 
                                    {
                                        $new_t[]=$val['subject_id'];
                                    }

                                    $tpt_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (121) AND type_id NOT IN (143,144) AND  course_type_id IN (230,231,232)")->queryAll();

                                    $new_tpt = array();

                                    foreach ($tpt_suba as $val) 
                                    {
                                        $new_tpt[]=$val['subject_id'];
                                    }

                                    $pp_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (10,11,123) AND  course_type_id IN (230,231,232)")->queryAll();

                                    $new_pp = array();

                                    foreach ($pp_suba as $val) 
                                    {
                                        $new_pp[]=$val['subject_id'];
                                    }

                                    $tpp_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (121) AND type_id IN (144) AND course_type_id IN (230,231,232)")->queryAll();

                                    $new_tpp = array();

                                    foreach ($tpp_suba as $val) 
                                    {
                                        $new_tpp[]=$val['subject_id'];
                                    }

                                    $other_suba = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (105,106,122,139,218) AND type_id IN (143,105) AND  course_type_id IN (230,231,232)")->queryAll();

                                    $new_othr = array();

                                    foreach ($other_suba as $val) 
                                    {
                                        $new_othr[]=$val['subject_id'];
                                    }
                                        
                                    $body .='<td align=center>'.count($th_suba).'</td>';
                                    $body .='<td align=center>'.count($tpt_suba).'</td>';
                                    $body .='<td align=center>'.(count($pp_suba)+count($tpp_suba)).'</td>';
                                    $body .='<td align=center>'.count($other_suba).'</td>';
                                    
                                    $add_qt = new Query();
                                    $add_qt->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_t]); 
                                    $t_script = $add_qt->createCommand()->queryScalar();

                                    $add_tpt = new Query();
                                    $add_tpt->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_tpt]);
                                    $tpt_script = $add_tpt->createCommand()->queryScalar();

                                    $add_pp = new Query();
                                    $add_pp->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_pp]);
                                    $pp_script = $add_pp->createCommand()->queryScalar();

                                    $add_tpp = new Query();
                                    $add_tpp->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_tpp]);
                                    $tpp_script = $add_tpp->createCommand()->queryScalar();

                                    $add_othr = new Query();
                                    $add_othr->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_othr]);
                                    $othr_script = $add_othr->createCommand()->queryScalar();
                                    
                                    $body .='<td class="scriptbckgrd" align=center>'.$t_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.($pp_script+$tpp_script).'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$othr_script.'</td>';

                                    $tot_t_script=$tot_t_script+$t_script;
                                    $tot_tpt_script=$tot_tpt_script+$tpt_script;
                                    $tot_pp_script=$tot_pp_script+($pp_script+$tpp_script);
                                    $tot_tpp_script=$tot_tpp_script+$tpt_script;
                                    $tot_othr_script=$tot_othr_script+$othr_script;
                                    
                                    //rejoin studnet addtional course
                                    
                                    $th_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (8) AND course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_t = array();

                                    foreach ($th_subc as $val) 
                                    {
                                        $new_t[]=$val['subject_id'];
                                    }

                                    $tpt_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (121) AND type_id NOT IN (143,144) AND course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_tpt = array();

                                    foreach ($tpt_subc as $val) 
                                    {
                                        $new_tpt[]=$val['subject_id'];
                                    }

                                    $pp_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (10,11,123) AND course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_pp = array();

                                    foreach ($pp_subc as $val) 
                                    {
                                        $new_pp[]=$val['subject_id'];
                                    }

                                    $tpp_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (121) AND type_id IN (144) AND course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_tpp = array();

                                    foreach ($tpp_subc as $val) 
                                    {
                                        $new_tpp[]=$val['subject_id'];
                                    }

                                    $other_subc = Yii::$app->db->createCommand("SELECT subject_id FROM coe_subjects_mapping WHERE batch_mapping_id=".$value['course_batch_mapping_id']." AND semester=".$semester." AND subject_type_id=233 AND paper_type_id IN (105,106,122,139,218) AND type_id IN (143,105) AND course_type_id NOT IN (230,231,232)")->queryAll();

                                    $new_othr = array();

                                    foreach ($other_subc as $val) 
                                    {
                                        $new_othr[]=$val['subject_id'];
                                    }
                                        
                                    $body .='<td align=center>'.count($th_subc).'</td>';
                                    $body .='<td align=center>'.count($tpt_subc).'</td>';
                                    $body .='<td align=center>'.(count($pp_subc)+count($tpp_subc)).'</td>';
                                    $body .='<td align=center>'.count($other_subc).'</td>';
                                    
                                    $t_script=0;
                                    $tpt_script=0;
                                    $p_script=0;
                                    $tpp_script=0;
                                    $othr_script=0;
                                    
                                    $add_qt = new Query();
                                    $add_qt->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_t]); 
                                    $t_script = $add_qt->createCommand()->queryScalar();

                                    $add_tpt = new Query();
                                    $add_tpt->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_tpt]);
                                    $tpt_script = $add_tpt->createCommand()->queryScalar();

                                    $add_pp = new Query();
                                    $add_pp->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_pp]);
                                    $pp_script = $add_pp->createCommand()->queryScalar();

                                    $add_tpp = new Query();
                                    $add_tpp->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_tpp]);
                                    $tpp_script = $add_tpp->createCommand()->queryScalar();

                                    $add_othr = new Query();
                                    $add_othr->select(['count(coe_student_id)'])
                                    ->from('coe_nominal a')
                                    ->join('JOIN','coe_student_mapping as b','b.student_rel_id=a.coe_student_id')
                                    ->Where(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                                    ->andWhere(['a.semester'=>$semester])
                                    ->andWhere(['NOT IN','b.status_category_type_id',[$det_disc_type,$det_cat_type]])
                                    ->andWhere(['IN','a.coe_subjects_id',$new_othr]);
                                    $othr_script = $add_othr->createCommand()->queryScalar();

                                    $body .='<td class="scriptbckgrd" align=center>'.$t_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.($pp_script+$tpp_script).'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$tpt_script.'</td>';
                                    $body .='<td class="scriptbckgrd" align=center>'.$othr_script.'</td>';

                                    $tot_t_script=$tot_t_script+$t_script;
                                    $tot_tpt_script=$tot_tpt_script+$tpt_script;
                                    $tot_pp_script=$tot_pp_script+($pp_script+$tpp_script);
                                    $tot_tpp_script=$tot_tpp_script+$tpt_script;
                                    $tot_othr_script=$tot_othr_script+$othr_script;

                                    $final_total=$tot_t_script+$tot_tpt_script+$tot_pp_script+$tot_othr_script;

                                    $pg_t_script=$pg_t_script+$tot_t_script;
                                    $pg_tpt_script=$pg_tpt_script+$tot_tpt_script;
                                    $pg_pp_script=$pg_pp_script+$tot_pp_script;
                                    $pg_tpp_script=$pg_tpp_script+$tot_tpp_script;
                                    $pg_othr_script=$pg_othr_script+$tot_othr_script;

                                    $pg_final_total=$pg_final_total+$final_total;

                                    $body .='<td align=center>'.$tot_t_script.'</td>';
                                    $body .='<td align=center>'.$tot_tpt_script.'</td>';
                                    $body .='<td align=center>'.$tot_pp_script.'</td>';
                                    $body .='<td align=center>'.$tot_tpp_script.'</td>';
                                    $body .='<td align=center>'.$tot_othr_script.'</td>';

                                    $body .='<td class="scriptbckgrd" align=center>'.$final_total.'</td>';

                                    $body .='</tr>';
                                    

                                    $sn_no++;
                                }

                                
                            }

                            if($pg_final_total>0)
                            {
                                $body .='<tr>';
                                $body .='<td class="totalscriptbckgrd" colspan=39 align=right><b>TOTAL PG  </b></td>';
                                $body .='<td class="totalscriptbckgrd" align=center>'.$pg_t_script.'</td>';
                                $body .='<td class="totalscriptbckgrd" align=center>'.$pg_tpt_script.'</td>';
                                $body .='<td class="totalscriptbckgrd" align=center>'.$pg_pp_script.'</td>';
                                $body .='<td class="totalscriptbckgrd" align=center>'.$pg_tpp_script.'</td>';
                                $body .='<td class="totalscriptbckgrd" align=center>'.$pg_othr_script.'</td>';
                                $body .='<td class="totalscriptbckgrd" align=center>'.$pg_final_total.'</td>';
                                $body .='</tr>';

                                if($i<$n)
                                {
                                    $body .='</tbody> </table><pagebreak>';
                                    $body.='<table class="contenttable" width="100%"  cellspacing="0" cellpadding="0" border="0">
                                        <tbody>';
                                }
                                else
                                {
                                    $body .='</tbody> </table>';
                                }
                            }
                            else
                            {
                                if($i<$n)
                                {
                                    $body .='</tbody> </table><pagebreak>';
                                    $body.='<table class="contenttable" width="100%"  cellspacing="0" cellpadding="0" border="0">
                                        <tbody>';
                                }
                                else
                                {
                                    $body .='</tbody> </table>';
                                }
                            }
                          
                            
                        }
                    
                        $semester=$semester+2;
                        $i++;
                    }

               

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