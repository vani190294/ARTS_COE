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


echo Dialog::widget();
$batch_id = isset($subjects->batch_mapping_id)?$subjects->batchMapping->coeBatch->coe_batch_id:"";
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." COUNT REPORT";
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
    $condition = $model->isNewRecord?true:false;
    $form = ActiveForm::begin([
                    'id' => 'categories-form',
                    'enableAjaxValidation' => $condition,
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",

                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'coe_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'batch_id',
                            'value'=> $batch_id_value,
                            'name'=>'bat_val',
                             $batch_id_value => ["Selected"=>'selected']
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-sm-2">
                <?php if(!empty($semester)){ $semester=$semester;}else{$semester='';}    ?>
                        <?= $form->field($model, 'semester1')->textInput(['value' => "$semester"]) ?>

                    </div>
           
            
            <div class="col-lg-4 col-sm-4">
                <br />
                <?= Html::submitButton( 'Get', ['class' =>  'btn btn-success']) ?>

                <?= Html::a('Reset', ['student-count-report'], ['class' => 'btn btn-default']) ?> 
        </div>
        </div>

    <?php ActiveForm::end(); ?>

<?php $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/subjects-mapping/student-count-report-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]);  
$print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/subjects-mapping/student-count-report-excel'], [
                    'class'=>'pull-right btn btn-block btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
?>

 
</div>
</div>
</div>

<?php 
if(isset($content_1))
{

?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row" >
            <div class="col-xs-12" >
                <div class="col-lg-12" > 
                    <div class="col-lg-2" > 
                    <?php echo $print_excel.$print_pdf; ?> </div>
                    </div>
                <div class="col-lg-12"  style="overflow-x:auto;"> 
                    <?php 
                    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                    $html = $body ='';
                    if($semester==1 || $semester==2)
                    {

                    $html .='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <tr>
                         
                          <td colspan=16 align="center"> 
                             

                              <center><b><font size="6px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font></center>
                              <center> Phone : <b>'.$org_phone.'</b></center>
                              <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                         </td>
                        
                        </tr> 
                        <tr>
                            <td colspan=16 align="center"><b>SEMESTER : '.$semester.'
                            </b></td>
                        </tr>
                        <tr class="table-danger">
                            
                            <th rowspan="2">SNO</th>                
                            <th rowspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).'</th>
                            <th rowspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).'</th>
                            <th rowspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)).'</th>
                            <th rowspan="2"> COUNT</th>
                           
                            <th colspan="4" style="text-align:center;">CURRENT SEM</th>
                             <th colspan="4" style="text-align:center;">TOTAL</th>
                          
                            <th rowspan="2">GENERAL (D)</th>
                            <th rowspan="2">ACTIVE COUNT (A+B+C+D)</th>
                             <th rowspan="2"> MALE</th>
                            <th rowspan="2"> FEMALE</th>
                        </tr>   
                        <tr>
                            
                            <th>DETAIN/DEBAR</th>
                            <th>DISCONTINUED</th>
                            <th>REJOIN</th>
                            <th>TRANSFER</th>

                            <th>DETAIN/DEBAR</th>
                            <th>DISCONTINUED</th>
                            <th>REJOIN (A)</th>
                            <th>TRANSFER (B)</th>
                        </tr>
                        ';

                        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                        $transfer = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%TRANSFER%'")->queryScalar();

                        $rejoin_type =6;// Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%rejoin%'")->queryScalar();
                         $latearl = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Lateral Entry%'")->queryScalar();
                         

                        $sn_no = 1;
                        $detain_total = $ug_count = $pg_count = $others_count_total = $transfer_total = $rejoin_total =$latearl_total= $disc_total=$first_year_count = $GRAND_TOTAL = $total_strength = 0;

                        $total_strengthm = 0; $total_strengthf = 0;
                         $detain_total_sem = $transfer_total_sem = $rejoin_total_sem = $disc_total_sem=0;

                        $detain_total_phd = $ug_count_phd = $pg_count_phd = $others_count_total_phd = $transfer_total_phd = $rejoin_total_phd = $ACTIVE_count_php = $disc_total_phd =$latearl_total_phd=$first_year_count_phd  = $GRAND_TOTAL_phd = $total_strength_phd = $total_strengthm_phd = $total_strengthf_phd = $printed = $phd_printed  = 0;
                        $body_phd= '';

                        $pg_general_count=0;
                       
                        foreach ($content_1 as $key => $value) 
                        {
                            $semester_detain = $value['semester_detain'];
                           //$value['course_batch_mapping_id']=142;    
                            
                           
                            $query_detain_count_sem = new Query();
                            $query_detain_count_sem->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                           //->join('JOIN', 'coe_mark_entry_master e', 'e.student_map_id=a.coe_student_mapping_id')
                            ->where(['a.semester_detain'=>$semester])
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $det_cat_type]);
                            //echo "<br>".$query_detain_count_sem->createCommand()->getRawSql();
                            $stu_detain_count_sem = $query_detain_count_sem->createCommand()->queryAll();

                             $detain_count_sem=$stu_detain_count_sem[0]['count']; 
                             $detain_total_sem +=$detain_count_sem;
                             $detain_disp_sem = $detain_count_sem;

                            $detain_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();
                           
                            $detain_total +=count($detain_count); 
                            $detain_disp = count($detain_count)==0?'-':count($detain_count);

                           
                             $query_disc_count_sem = new Query();
                            $query_disc_count_sem->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                            //->join('JOIN', 'coe_mark_entry_master e', 'e.student_map_id=a.coe_student_mapping_id')
                            ->where(['a.semester_detain'=>$semester])
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $det_disc_type]);
                            
                            
                            $stu_disc_count_sem = $query_disc_count_sem->createCommand()->queryAll();

                             $disc_count_sem=$stu_disc_count_sem[0]['count']; 
                             $disc_total_sem +=$disc_count_sem;
                             $disc_disp_sem = $disc_count_sem;

                            $disc_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();
                            
                            
                            $disc_total +=count($disc_count); 
                            $disc_disp = count($disc_count)==0?'-':count($disc_count);

                            
                            $rejoin_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$rejoin_type])->andWhere(['<=', 'semester_detain', $semester])->all();
                            $rejoin_total +=count($rejoin_count); 
                            $rejoin_dip = count($rejoin_count)==0?'-':count($rejoin_count);

                            $query_rejoin_count_sem = new Query();
                            $query_rejoin_count_sem->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                            ->join('JOIN', 'coe_mark_entry_master e', 'e.student_map_id=a.coe_student_mapping_id')
                            ->where(['a.semester_detain'=>$semester])
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $rejoin_type])
                              ->andWhere(['<=', 'semester_detain', $semester]);

                            //   if($value['programme_code']=='U102')
                            // {
                            //      echo "<br>".$query_rejoin_count_sem->createCommand()->getRawSql();
                            //      exit;
                            // }

                             
                           $stu_rejoin_count_sem = $query_rejoin_count_sem->createCommand()->queryAll();

                             $rejoin_count_sem=$stu_rejoin_count_sem[0]['count']; 
                             $rejoin_total_sem +=$rejoin_count_sem;
                             $rejoin_dip_sem = $rejoin_count_sem;
                            

                            $transfer_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$transfer])
                            ->andWhere(['<=', 'semester_detain', $semester])->all();
                            $transfer_total +=count($transfer_count); 
                            $transfer_count_dip = count($transfer_count)==0?'-':count($transfer_count);

                             $query_transfer_count_sem = new Query();
                            $query_transfer_count_sem->select(['count(distinct student_map_id) as count'])
                            ->from('coe_student_mapping a')
                            ->join('JOIN', 'coe_mark_entry_master e', 'e.student_map_id=a.coe_student_mapping_id')
                            ->where(['a.semester_detain'=>$semester])
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $transfer])
                              ->andWhere(['<=', 'semester_detain', $semester]);
                            
                            $stu_transfer_count_sem = $query_transfer_count_sem->createCommand()->queryAll();

                             $transfer_count_sem=$stu_transfer_count_sem[0]['count']; 
                             $transfer_total_sem +=$transfer_count_sem;
                             $transfer_count_dip_sem = $transfer_count_sem;


                            if($semester>2)
                            {
                                $lateral_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$latearl])->all();
                                $latearl_total +=count($lateral_count); 
                                $lateral_count_dip = count($lateral_count)==0?'-':count($lateral_count);
                            }
                            else
                            {
                                $latearl_total = $lateral_count_dip =0;
                            }


                            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
                            if($semester=1)
                            {
                           
                           
                           $others_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['NOT IN','status_category_type_id',[$transfer,$rejoin_type,$latearl]])->andWhere(['NOT IN','coe_student_mapping_id',['13852','13871','13878']])->all();
                            }

                       else
                       {
                             $others_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['NOT IN','status_category_type_id',[$transfer,$rejoin_type,$latearl]])->all();

                       }

                            

                           
                               //print_r(count( $others_count));exit;

                            $detain_countbelowsem= StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();

                            $detain_countbelowsem2=StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['>', 'semester_detain', $semester])->all();

                            $detain_countbelowsem1= StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type])->andWhere(['!=', 'rejoin_migrated', 'NO'])->all();

                            $disc_countsem=($semester==1)?$disc_disp_sem:$disc_disp;
                            $detain_sem=($semester==1)?$detain_disp_sem:$detain_disp_sem;
                           
                            
                            $others_count = count($others_count) - ($disc_countsem+$detain_sem+count($detain_countbelowsem1));

                                // print_r()
                            
                           

                       

                            $ott=$others_count;

                            $others_count_total +=($ott==0 || $ott=='' )?0:$ott; 
                            $other_disp = $ott==0?'-':$ott;

                            if($disc_count_sem!=0 || $detain_count_sem!=0)
                            {
                                $ACTIVE_count = ($other_disp+$transfer_count_dip+$rejoin_dip+$lateral_count_dip);
                                //+($detain_count_sem+$disc_count_sem);
                            }
                            else
                            {
                                $ACTIVE_count = ($other_disp+$transfer_count_dip+$rejoin_dip+$lateral_count_dip);
                            }
                           //print_r( $other_disp);exit;
                           
                             
                            $active_final_disp = count($ACTIVE_count)==0?'-':$ACTIVE_count;

                            $checkStuInfo = new Query();
                            $checkStuInfo->select(['count(coe_student_mapping_id) as count,register_number'])
                                ->from('coe_student_mapping as A')            
                                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.course_batch_mapping_id')
                                 ->JOIN('JOIN','coe_student as x','x.coe_student_id=A.student_rel_id')
                                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                                ->Where(['B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value,'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['semester_detain' => null]);

                            if($semester<=2)
                            {
                                 $checkStuInfo->andwhere(['<>','status_category_type_id',$latearl]);
                            }
                            // echo $checkStuInfo->createCommand()->getrawsql(); exit;   
                            
                            $strength = $checkStuInfo->createCommand()->queryAll();

                            $detain_count_sem = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();

                            $disc_count_sem = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();

                          

                           if($semester=1)
                           {
                              //$detain_count_sem1 = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type])->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO'])->all

                              $sem = new Query();
                             $sem->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                            ->join('JOIN', 'coe_student e', 'e.coe_student_id=a.student_rel_id')
                           ->join('JOIN', 'coe_mark_entry_master x', 'x.student_map_id=a.coe_student_mapping_id')
                           ->join('JOIN', 'coe_subjects_mapping y', 'y.coe_subjects_mapping_id=x.subject_map_id')
                            
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $det_cat_type])
                              ->andWhere(['=', 'a.rejoin_migrated', 'NO'])
                                ->andWhere(['=', 'y.semester', $semester])
                                ->andWhere(['>', 'semester_detain', $semester]);
                            
                            $detain_count_sem1 = $sem->createCommand()->queryAll();
                             //print_r($detain_count_sem1);exit;


                         $sem1 = new Query();
                             $sem1->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                            ->join('JOIN', 'coe_student e', 'e.coe_student_id=a.student_rel_id')
                            ->join('JOIN', 'coe_mark_entry_master x', 'x.student_map_id=a.coe_student_mapping_id')
                           ->join('JOIN', 'coe_subjects_mapping y', 'y.coe_subjects_mapping_id=x.subject_map_id')
                            
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $det_disc_type])
                              ->andWhere(['=', 'a.rejoin_migrated', 'NO'])
                               ->andWhere(['=', 'y.semester', $semester])
                                ->andWhere(['>', 'semester_detain', $semester]);
                            
                            $disc_count_sem1 = $sem1->createCommand()->queryAll();
                           }
                           else
                           {
                              $detain_count_sem1 = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type])->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO'])->all();

                           $disc_count_sem1 = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type]) ->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO'])->all();


                           }



                             $strength_count=$strength[0]['count']+(count($detain_count_sem) + ($detain_count_sem1[0]['count']))+(count($disc_count_sem)+($disc_count_sem1[0]['count']));
                            //print_r($strength_count);exit;
                             if($semester=1)
                            {
                           
                           
                          

                             $checkStuInfom = new Query();
                            $checkStuInfom->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')            
                                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.course_batch_mapping_id')
                                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'M', 'B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value,'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);


                            if($semester<=2)
                            {
                                 $checkStuInfom->andwhere(['<>','status_category_type_id',$latearl]);
                                  $checkStuInfom->andwhere(['<>','status_category_type_id',$rejoin_type]);
                                  $checkStuInfom->andwhere(['<>','coe_student_mapping_id','13852']);
                                   $checkStuInfom->andwhere(['<>','coe_student_mapping_id','13871']);
                                    $checkStuInfom->andwhere(['<>','coe_student_mapping_id','13878']);
                                 
                            }
                           // echo $checkStuInfom->createCommand()->getrawsql(); exit;   

                            $strengthm = $checkStuInfom->createCommand()->queryAll();


                            }
                      else
                      {
                     

                             $checkStuInfom = new Query();
                            $checkStuInfom->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')            
                                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.course_batch_mapping_id')
                                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'M', 'B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value,'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);

                            if($semester<=2)
                            {
                                 $checkStuInfom->andwhere(['<>','status_category_type_id',$latearl]);
                                  $checkStuInfom->andwhere(['<>','status_category_type_id',$rejoin_type]);
                            }
                           // echo $checkStuInfom->createCommand()->getrawsql(); exit;   

                            $strengthm = $checkStuInfom->createCommand()->queryAll();
                        }

                            $detain_count_semm = new Query();
                            $detain_count_semm->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'M', 'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['=', 'semester_detain', $semester]);

                            if($semester<=2)
                            {
                                 $detain_count_semm->andwhere(['<>','status_category_type_id',$latearl]);
                            }
                          $detain_count_semmm = $detain_count_semm->createCommand()->queryScalar();
                          //echo $detain_count_semm->createCommand()->getrawsql(); exit;  


                            $detain_count_semm1 = new Query();
                            $detain_count_semm1->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'M', 'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO']);

                            if($semester<=2)
                            {
                                 $detain_count_semm1->andwhere(['<>','status_category_type_id',$latearl]);
                            }

                            $detain_count_semmm1 = $detain_count_semm1->createCommand()->queryScalar();
                            // print_r($detain_count_semmm);exit;


                             $strengthm_count=$strengthm[0]['count']- ($detain_count_semmm);
                            // print_r($strengthm[0]['count']);exit;
                            


                              $checkStuInfof = new Query();
                            $checkStuInfof->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')            
                                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.course_batch_mapping_id')
                                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'F', 'B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value,'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);

                            if($semester<=2)
                            {
                                 $checkStuInfof->andwhere(['<>','status_category_type_id',$latearl]);
                                  $checkStuInfof->andwhere(['<>','status_category_type_id',$rejoin_type]);
                            }
                           //echo $checkStuInfof->createCommand()->getrawsql(); exit;   

                            $strengthf = $checkStuInfof->createCommand()->queryAll();

                            $detain_count_semf = new Query();
                            $detain_count_semf->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'F', 'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['=', 'semester_detain', $semester]);

                            if($semester<=2)
                            {
                                 $detain_count_semf->andwhere(['<>','status_category_type_id',$latearl]);
                            }
                                 //echo $detain_count_semf->createCommand()->getrawsql(); exit; 
                            $detain_count_semmf = $detain_count_semf->createCommand()->queryScalar();

                            $detain_count_semf1 = new Query();
                            $detain_count_semf1->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'F', 'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO']);

                            if($semester<=2)
                            {
                                 $detain_count_semf1->andwhere(['<>','status_category_type_id',$latearl]);
                            }
                          // echo $detain_count_semf1->createCommand()->getrawsql(); exit;   

                            $detain_count_semmf1 = $detain_count_semf1->createCommand()->queryScalar();
                            //print_r($detain_count_semmf1);exit;

                             $strengthf_count=$strengthf[0]['count']- ($detain_count_semmf);
                          // print_r($strengthf[0]['count']);exit;
                            
                            if($value['degree_type']=='PG' && $printed==0 &&$value['degree_code']!=='Ph.D' )
                            {
                                $others_count_total_disp = $others_count_total-(($ott==0 || $ott=='' )?0:$ott);
                                $body .='<tr><td colspan=4 algin="right" ><strong>TOTAL STRENGTH UG</strong></td>';
                                $body .='<td>'.$total_strength.'</td>';
                               
                                $body .='<td>'.$detain_total_sem.'</td>';
                                 $body .='<td>'.$disc_total_sem.'</td>';
                                 $body .='<td>'.$rejoin_total_sem.'</td>';
                                $body .='<td>'.$transfer_total_sem.'</td>';
                                $body .='<td>'.$detain_total.'</td>';
                                $body .='<td>'.$disc_total.'</td>';
                                $body .='<td>'.$rejoin_total.'</td>';
                                $body .='<td>'.$transfer_total.'</td>';
                               // $body .='<td>'.$latearl_total.'</td>';
                                $body .='<td>'.$others_count_total_disp.'</td>';
                                $body .='<td>'.$GRAND_TOTAL.'</td>';
                                 $body .='<td>'.$total_strengthm.'</td>';
                                $body .='<td>'.$total_strengthf.'</td></tr>';

                                $detain_total = $ug_count = $pg_count = $others_count_total = $transfer_total =$latearl_total= $rejoin_total = $disc_total = $GRAND_TOTAL = $total_strengthm =$total_strengthf =$total_strength = 0;
                                $others_count_total +=(($ott==0 || $ott=='' )?0:$ott);
                                $printed = 1; $pg_general_count=0;
                                $detain_total_sem = $transfer_total_sem = $rejoin_total_sem = $disc_total_sem=0;
                            }
                            
                            if($value['degree_type']=='PG' && ($value['degree_code']=='Ph.D' || $value['degree_code']=='Ph.d') )
                            {
                                $body_phd .='<tr>';
                                $body_phd .='<td>'.$sn_no.'</td>';
                                $body_phd .='<td>'.$value['batch_name'].'</td>';
                                $body_phd .='<td>'.$value['degree_code'].'</td>';
                                $body_phd .='<td>'.strtoupper($value['programme_name']).'</td>';
                                $body_phd .='<td>'.$strength_count.'</td>';
                              
                                 $body_phd .='<td>'.$detain_disp_sem.'</td>';
                                 $body_phd .='<td>'.$disc_disp_sem.'</td>';
                                 $body_phd .='<td>'.$rejoin_dip_sem.'</td>';
                                 $body_phd .='<td>'.$transfer_count_dip_sem.'</td>';
                                $body_phd .='<td>'.$detain_disp.'</td>';
                                $body_phd .='<td>'.$disc_disp.'</td>';
                                $body_phd .='<td>'.$rejoin_dip.'</td>';
                                $body_phd .='<td>'.$transfer_count_dip.'</td>';
                               // $body_phd .='<td>'.$lateral_count_dip.'</td>';
                                $body_phd .='<td>'.$other_disp.'</td>';
                                $body_phd .='<td>'.$active_final_disp.'</td>';
                                $body_phd .='<td>'.$strengthm_count.'</td>';
                                $body_phd .='<td>'.$strengthf_count.'</td></tr>';

                                $detain_total_phd +=count($detain_count); 
                                $disc_total_phd +=count($disc_count); 
                                $rejoin_total_phd +=count($rejoin_count); 
                                $transfer_total_phd +=count($transfer_count); 
                                $latearl_total_phd+=$lateral_count_dip;
                                $ACTIVE_count_php = $other_disp+$transfer_count_dip+$rejoin_dip+$lateral_count_dip; 

                                $others_count_total_phd +=($ott==0 || $ott=='' )?0:$ott; 
                                $GRAND_TOTAL_phd +=$active_final_disp;
                                $total_strength_phd +=$strength_count;
                                $total_strengthm_phd +=$strengthm_count;
                                $total_strengthm_phd +=$strengthf_count;
                                $phd_printed = 1;
                            }
                            
                           
                            if($value['degree_type']=='UG')
                            {
                                $body .='<tr>';
                                $body .='<td>'.$sn_no.'</td>';
                                $body .='<td>'.$value['batch_name'].'</td>';
                                $body .='<td>'.$value['degree_code'].'</td>';
                                $body .='<td>'.strtoupper($value['programme_name']).'</td>';
                                $body .='<td>'.$strength_count.'</td>';
                               
                                $body .='<td>'.$detain_disp_sem.'</td>';
                                 $body .='<td>'.$disc_disp_sem.'</td>';
                                 $body .='<td>'.$rejoin_dip_sem.'</td>';
                                 $body .='<td>'.$transfer_count_dip_sem.'</td>';
                                $body .='<td>'.$detain_disp.'</td>';
                                $body .='<td>'.$disc_disp.'</td>';
                                $body .='<td>'.$rejoin_dip.'</td>';
                                $body .='<td>'.$transfer_count_dip.'</td>';
                               //  $body .='<td>'.$lateral_count_dip.'</td>';
                                $body .='<td>'.$other_disp.'</td>';
                                $body .='<td>'.$active_final_disp.'</td>';
                                 $body .='<td>'.$strengthm_count.'</td>';
                                $body .='<td>'.$strengthf_count.'</td></tr>';
                                $sn_no++;

                                 $GRAND_TOTAL +=$active_final_disp;
                                $total_strength +=$strength_count;
                                $total_strengthm +=$strengthm_count;
                                $total_strengthf +=$strengthf_count;
                                 
                            }
                            //echo $value['degree_code'].$value['semester']."<br>";
                            if(($value['degree_type']=='PG') && ($value['degree_code']!='Ph.D') && ($value['semester']<=$semester && $value['degree_total_semesters']>=$semester) && ($semester<5))
                            {
                                $body .='<tr>';
                                $body .='<td>'.$sn_no.'</td>';
                                $body .='<td>'.$value['batch_name'].'</td>';
                                $body .='<td>'.$value['degree_code'].'</td>';
                                $body .='<td>'.strtoupper($value['programme_name']).'</td>';
                                $body .='<td>'.$strength_count.'</td>';
                                 
                                $body .='<td>'.$detain_disp_sem.'</td>';
                                 $body .='<td>'.$disc_disp_sem.'</td>';
                                 $body .='<td>'.$rejoin_dip_sem.'</td>';
                                 $body .='<td>'.$transfer_count_dip_sem.'</td>';
                                $body .='<td>'.$detain_disp.'</td>';
                                $body .='<td>'.$disc_disp.'</td>';
                                $body .='<td>'.$rejoin_dip.'</td>';
                                $body .='<td>'.$transfer_count_dip.'</td>';
                              //   $body .='<td>'.$lateral_count_dip.'</td>';
                                $body .='<td>'.$other_disp.'</td>';
                                $body .='<td>'.$active_final_disp.'</td>';
                                $body .='<td>'.$strengthm_count.'</td>';
                                $body .='<td>'.$strengthf_count.'</td></tr>';
                                $sn_no++;

                                 $GRAND_TOTAL +=$active_final_disp;
                                $total_strength +=$strength_count;
                                $pg_general_count+=$other_disp;
                                 $total_strengthm +=$strengthm_count;
                                $total_strengthf +=$strengthf_count;
                                 
                            }
                        }

                        if($total_strength>0)
                       {
                            $body .='<tr><td colspan=4 algin="center" ><strong>TOTAL STRENGTH PG</strong> </td>';
                            $body .='<td>'.$total_strength.'</td>';
                         
                            $body .='<td>'.$detain_total_sem.'</td>';
                                 $body .='<td>'.$disc_total_sem.'</td>';
                                 $body .='<td>'.$rejoin_total_sem.'</td>';
                                $body .='<td>'.$transfer_total_sem.'</td>';
                                $body .='<td>'.$detain_total.'</td>';
                                $body .='<td>'.$disc_total.'</td>';
                                $body .='<td>'.$rejoin_total.'</td>';
                                $body .='<td>'.$transfer_total.'</td>';
                          //  $body .='<td>'.$latearl_total.'</td>';
                            $body .='<td>'.$pg_general_count.'</td>';
                            $body .='<td>'.$GRAND_TOTAL.'</td>';
                            $body .='<td>'.$total_strengthm.'</td>';
                            $body .='<td>'.$total_strengthf.'</td></tr>';
                      }
                      /*  if($phd_printed==1)
                        {
                            $body .=$body_phd.'<tr><td colspan=4 algin="center" ><strong>TOTAL STRENGTH PH.D</strong> </td><td>'.$total_strength_phd.'</td><td>'.$detain_total_phd.'</td><td>'.$disc_total_phd.'</td><td>'.$rejoin_total_phd.'</td><td>'.$transfer_total_phd.'</td><td>'.$latearl_total_phd.'</td><td>'.$others_count_total_phd.'</td><td>'.$GRAND_TOTAL_phd.'</td></tr>';    
                        }*/
                        echo $html .='<tbody id="show_dummy_numbers" style="font-weight:bold !important"> '.$body.'</tbody> </table>'; 
                    }

                    else
                    {

                         $html .='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <tr>
                         
                          <td colspan=16 align="center"> 
                             

                              <center><b><font size="6px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font></center>
                              <center> Phone : <b>'.$org_phone.'</b></center>
                              <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                         </td>
                        
                        </tr> 
                        <tr>
                            <td colspan=16 align="center"><b>SEMESTER : '.$semester.'
                            </b></td>
                        </tr>
                        <tr class="table-danger">
                            
                            <th rowspan="2">SNO</th>                
                            <th rowspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).'</th>
                            <th rowspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).'</th>
                            <th rowspan="2">'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)).'</th>
                            <th rowspan="2"> COUNT</th>
                           
                            <th colspan="4" style="text-align:center;">CURRENT SEM</th>
                             <th colspan="4" style="text-align:center;">TOTAL</th>
                            <th rowspan="2">LATERAL ENTRY <br>(C)</th>
                            <th rowspan="2">GENERAL (D)</th>
                            <th rowspan="2">ACTIVE COUNT (A+B+C+D)</th>
                             <th rowspan="2"> MALE</th>
                            <th rowspan="2"> FEMALE</th>
                        </tr>   
                        <tr>
                            
                            <th>DETAIN/DEBAR</th>
                            <th>DISCONTINUED</th>
                            <th>REJOIN</th>
                            <th>TRANSFER</th>

                            <th>DETAIN/DEBAR</th>
                            <th>DISCONTINUED</th>
                            <th>REJOIN (A)</th>
                            <th>TRANSFER (B)</th>
                        </tr>
                        ';

                        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                        $transfer = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%TRANSFER%'")->queryScalar();

                        $rejoin_type =6;// Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%rejoin%'")->queryScalar();
                         $latearl = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Lateral Entry%'")->queryScalar();
                         

                        $sn_no = 1;
                        $detain_total = $ug_count = $pg_count = $others_count_total = $transfer_total = $rejoin_total =$latearl_total= $disc_total=$first_year_count = $GRAND_TOTAL = $total_strength = 0;

                        $total_strengthm = 0; $total_strengthf = 0;
                         $detain_total_sem = $transfer_total_sem = $rejoin_total_sem = $disc_total_sem=0;

                        $detain_total_phd = $ug_count_phd = $pg_count_phd = $others_count_total_phd = $transfer_total_phd = $rejoin_total_phd = $ACTIVE_count_php = $disc_total_phd =$latearl_total_phd=$first_year_count_phd  = $GRAND_TOTAL_phd = $total_strength_phd = $total_strengthm_phd = $total_strengthf_phd = $printed = $phd_printed  = 0;
                        $body_phd= '';

                        $pg_general_count=0;
                       
                        foreach ($content_1 as $key => $value) 
                        {
                            $semester_detain = $value['semester_detain'];
                           //$value['course_batch_mapping_id']=142;    
                            
                           
                            $query_detain_count_sem = new Query();
                            $query_detain_count_sem->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                           //->join('JOIN', 'coe_mark_entry_master e', 'e.student_map_id=a.coe_student_mapping_id')
                            ->where(['a.semester_detain'=>$semester])
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $det_cat_type]);
                            //echo "<br>".$query_detain_count_sem->createCommand()->getRawSql();
                            $stu_detain_count_sem = $query_detain_count_sem->createCommand()->queryAll();

                             $detain_count_sem=$stu_detain_count_sem[0]['count']; 
                             $detain_total_sem +=$detain_count_sem;
                             $detain_disp_sem = $detain_count_sem;

                            $detain_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();
                           
                            $detain_total +=count($detain_count); 
                            $detain_disp = count($detain_count)==0?'-':count($detain_count);

                           
                             $query_disc_count_sem = new Query();
                            $query_disc_count_sem->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                            //->join('JOIN', 'coe_mark_entry_master e', 'e.student_map_id=a.coe_student_mapping_id')
                            ->where(['a.semester_detain'=>$semester])
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $det_disc_type]);
                            
                            
                            $stu_disc_count_sem = $query_disc_count_sem->createCommand()->queryAll();

                             $disc_count_sem=$stu_disc_count_sem[0]['count']; 
                             $disc_total_sem +=$disc_count_sem;
                             $disc_disp_sem = $disc_count_sem;

                            $disc_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();
                            
                            
                            $disc_total +=count($disc_count); 
                            $disc_disp = count($disc_count)==0?'-':count($disc_count);

                            
                            $rejoin_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$rejoin_type])->andWhere(['<=', 'semester_detain', $semester])->all();
                            $rejoin_total +=count($rejoin_count); 
                            $rejoin_dip = count($rejoin_count)==0?'-':count($rejoin_count);

                            $query_rejoin_count_sem = new Query();
                            $query_rejoin_count_sem->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                            ->join('JOIN', 'coe_mark_entry_master e', 'e.student_map_id=a.coe_student_mapping_id')
                            ->where(['a.semester_detain'=>$semester])
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $rejoin_type])
                              ->andWhere(['<=', 'semester_detain', $semester]);

                            //   if($value['programme_code']=='U102')
                            // {
                            //      echo "<br>".$query_rejoin_count_sem->createCommand()->getRawSql();
                            //      exit;
                            // }

                             
                           $stu_rejoin_count_sem = $query_rejoin_count_sem->createCommand()->queryAll();

                             $rejoin_count_sem=$stu_rejoin_count_sem[0]['count']; 
                             $rejoin_total_sem +=$rejoin_count_sem;
                             $rejoin_dip_sem = $rejoin_count_sem;
                            

                            $transfer_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$transfer])
                            ->andWhere(['<=', 'semester_detain', $semester])->all();
                            $transfer_total +=count($transfer_count); 
                            $transfer_count_dip = count($transfer_count)==0?'-':count($transfer_count);

                             $query_transfer_count_sem = new Query();
                            $query_transfer_count_sem->select(['count(distinct student_map_id) as count'])
                            ->from('coe_student_mapping a')
                            ->join('JOIN', 'coe_mark_entry_master e', 'e.student_map_id=a.coe_student_mapping_id')
                            ->where(['a.semester_detain'=>$semester])
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $transfer])
                              ->andWhere(['<=', 'semester_detain', $semester]);
                            
                            $stu_transfer_count_sem = $query_transfer_count_sem->createCommand()->queryAll();

                             $transfer_count_sem=$stu_transfer_count_sem[0]['count']; 
                             $transfer_total_sem +=$transfer_count_sem;
                             $transfer_count_dip_sem = $transfer_count_sem;


                            if($semester>2)
                            {
                                $lateral_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$latearl])->all();
                                $latearl_total +=count($lateral_count); 
                                $lateral_count_dip = count($lateral_count)==0?'-':count($lateral_count);
                            }
                            else
                            {
                                $latearl_total = $lateral_count_dip =0;
                            }


                            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
                            if($semester=1)
                            {
                           
                           
                           $others_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['NOT IN','status_category_type_id',[$transfer,$rejoin_type,$latearl]])->andWhere(['NOT IN','coe_student_mapping_id',['13852','13871','13878']])->all();
                            }

                       else
                       {
                             $others_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['NOT IN','status_category_type_id',[$transfer,$rejoin_type,$latearl]])->all();

                       }

                            

                           
                               //print_r(count( $others_count));exit;

                            $detain_countbelowsem= StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();

                            $detain_countbelowsem2=StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['>', 'semester_detain', $semester])->all();

                            $detain_countbelowsem1= StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type])->andWhere(['!=', 'rejoin_migrated', 'NO'])->all();

                            $disc_countsem=($semester==1)?$disc_disp_sem:$disc_disp;
                            $detain_sem=($semester==1)?$detain_disp_sem:$detain_disp_sem;
                           
                            
                            $others_count = count($others_count) - ($disc_countsem+$detain_sem+count($detain_countbelowsem1));

                                // print_r()
                            
                           

                       

                            $ott=$others_count;

                            $others_count_total +=($ott==0 || $ott=='' )?0:$ott; 
                            $other_disp = $ott==0?'-':$ott;

                            if($disc_count_sem!=0 || $detain_count_sem!=0)
                            {
                                $ACTIVE_count = ($other_disp+$transfer_count_dip+$rejoin_dip+$lateral_count_dip);
                                //+($detain_count_sem+$disc_count_sem);
                            }
                            else
                            {
                                $ACTIVE_count = ($other_disp+$transfer_count_dip+$rejoin_dip+$lateral_count_dip);
                            }
                           //print_r( $other_disp);exit;
                           
                             
                            $active_final_disp = count($ACTIVE_count)==0?'-':$ACTIVE_count;

                            $checkStuInfo = new Query();
                            $checkStuInfo->select(['count(coe_student_mapping_id) as count,register_number'])
                                ->from('coe_student_mapping as A')            
                                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.course_batch_mapping_id')
                                 ->JOIN('JOIN','coe_student as x','x.coe_student_id=A.student_rel_id')
                                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                                ->Where(['B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value,'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['semester_detain' => null]);

                            if($semester<=2)
                            {
                                 $checkStuInfo->andwhere(['<>','status_category_type_id',$latearl]);
                            }
                            // echo $checkStuInfo->createCommand()->getrawsql(); exit;   
                            
                            $strength = $checkStuInfo->createCommand()->queryAll();

                            $detain_count_sem = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();

                            $disc_count_sem = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type]) ->andWhere(['<=', 'semester_detain', $semester])->all();

                          

                           if($semester=1)
                           {
                              //$detain_count_sem1 = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type])->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO'])->all

                              $sem = new Query();
                             $sem->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                            ->join('JOIN', 'coe_student e', 'e.coe_student_id=a.student_rel_id')
                           ->join('JOIN', 'coe_mark_entry_master x', 'x.student_map_id=a.coe_student_mapping_id')
                           ->join('JOIN', 'coe_subjects_mapping y', 'y.coe_subjects_mapping_id=x.subject_map_id')
                            
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $det_cat_type])
                              ->andWhere(['=', 'a.rejoin_migrated', 'NO'])
                                ->andWhere(['=', 'y.semester', $semester])
                                ->andWhere(['>', 'semester_detain', $semester]);
                            
                            $detain_count_sem1 = $sem->createCommand()->queryAll();
                             //print_r($detain_count_sem1);exit;


                         $sem1 = new Query();
                             $sem1->select(['count(distinct coe_student_mapping_id) as count'])
                            ->from('coe_student_mapping a')
                            ->join('JOIN', 'coe_student e', 'e.coe_student_id=a.student_rel_id')
                            ->join('JOIN', 'coe_mark_entry_master x', 'x.student_map_id=a.coe_student_mapping_id')
                           ->join('JOIN', 'coe_subjects_mapping y', 'y.coe_subjects_mapping_id=x.subject_map_id')
                            
                            ->andwhere(['a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                              ->andWhere(['=', 'a.status_category_type_id', $det_disc_type])
                              ->andWhere(['=', 'a.rejoin_migrated', 'NO'])
                               ->andWhere(['=', 'y.semester', $semester])
                                ->andWhere(['>', 'semester_detain', $semester]);
                            
                            $disc_count_sem1 = $sem1->createCommand()->queryAll();
                           }
                           else
                           {
                              $detain_count_sem1 = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type])->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO'])->all();

                           $disc_count_sem1 = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type]) ->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO'])->all();


                           }



                             $strength_count=$strength[0]['count']+(count($detain_count_sem) + ($detain_count_sem1[0]['count']))+(count($disc_count_sem)+($disc_count_sem1[0]['count']));
                            //print_r($strength_count);exit;
                             if($semester=1)
                            {
                           
                           
                          

                             $checkStuInfom = new Query();
                            $checkStuInfom->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')            
                                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.course_batch_mapping_id')
                                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'M', 'B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value,'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);


                            if($semester<=2)
                            {
                                 $checkStuInfom->andwhere(['<>','status_category_type_id',$latearl]);
                                  $checkStuInfom->andwhere(['<>','status_category_type_id',$rejoin_type]);
                                  $checkStuInfom->andwhere(['<>','coe_student_mapping_id','13852']);
                                   $checkStuInfom->andwhere(['<>','coe_student_mapping_id','13871']);
                                    $checkStuInfom->andwhere(['<>','coe_student_mapping_id','13878']);
                                 
                            }
                           // echo $checkStuInfom->createCommand()->getrawsql(); exit;   

                            $strengthm = $checkStuInfom->createCommand()->queryAll();


                            }
                      else
                      {
                     

                             $checkStuInfom = new Query();
                            $checkStuInfom->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')            
                                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.course_batch_mapping_id')
                                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'M', 'B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value,'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);

                            if($semester<=2)
                            {
                                 $checkStuInfom->andwhere(['<>','status_category_type_id',$latearl]);
                                  $checkStuInfom->andwhere(['<>','status_category_type_id',$rejoin_type]);
                            }
                           // echo $checkStuInfom->createCommand()->getrawsql(); exit;   

                            $strengthm = $checkStuInfom->createCommand()->queryAll();
                        }

                            $detain_count_semm = new Query();
                            $detain_count_semm->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'M', 'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['=', 'semester_detain', $semester]);

                            if($semester<=2)
                            {
                                 $detain_count_semm->andwhere(['<>','status_category_type_id',$latearl]);
                            }
                          $detain_count_semmm = $detain_count_semm->createCommand()->queryScalar();
                          //echo $detain_count_semm->createCommand()->getrawsql(); exit;  


                            $detain_count_semm1 = new Query();
                            $detain_count_semm1->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'M', 'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO']);

                            if($semester<=2)
                            {
                                 $detain_count_semm1->andwhere(['<>','status_category_type_id',$latearl]);
                            }

                            $detain_count_semmm1 = $detain_count_semm1->createCommand()->queryScalar();
                            // print_r($detain_count_semmm);exit;


                             $strengthm_count=$strengthm[0]['count']- ($detain_count_semmm);
                            // print_r($strengthm[0]['count']);exit;
                            


                              $checkStuInfof = new Query();
                            $checkStuInfof->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')            
                                ->JOIN('JOIN','coe_bat_deg_reg as B','B.coe_bat_deg_reg_id=A.course_batch_mapping_id')
                                ->JOIN('JOIN','coe_batch as C','C.coe_batch_id=B.coe_batch_id')
                                ->JOIN('JOIN','coe_degree as D','D.coe_degree_id=B.coe_degree_id')
                                ->JOIN('JOIN','coe_programme as E','E.coe_programme_id=B.coe_programme_id')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'F', 'B.coe_batch_id' => $batch_id_value, 'C.coe_batch_id' => $batch_id_value,'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);

                            if($semester<=2)
                            {
                                 $checkStuInfof->andwhere(['<>','status_category_type_id',$latearl]);
                                  $checkStuInfof->andwhere(['<>','status_category_type_id',$rejoin_type]);
                            }
                           //echo $checkStuInfof->createCommand()->getrawsql(); exit;   

                            $strengthf = $checkStuInfof->createCommand()->queryAll();

                            $detain_count_semf = new Query();
                            $detain_count_semf->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'F', 'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['=', 'semester_detain', $semester]);

                            if($semester<=2)
                            {
                                 $detain_count_semf->andwhere(['<>','status_category_type_id',$latearl]);
                            }
                                 //echo $detain_count_semf->createCommand()->getrawsql(); exit; 
                            $detain_count_semmf = $detain_count_semf->createCommand()->queryScalar();

                            $detain_count_semf1 = new Query();
                            $detain_count_semf1->select(['count(distinct coe_student_mapping_id) as count'])
                                ->from('coe_student_mapping as A')
                                ->JOIN('JOIN','coe_student as F','F.coe_student_id=A.student_rel_id')
                                ->Where(['F.gender' => 'F', 'A.course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['>', 'semester_detain', $semester])->andWhere(['=', 'rejoin_migrated', 'NO']);

                            if($semester<=2)
                            {
                                 $detain_count_semf1->andwhere(['<>','status_category_type_id',$latearl]);
                            }
                          // echo $detain_count_semf1->createCommand()->getrawsql(); exit;   

                            $detain_count_semmf1 = $detain_count_semf1->createCommand()->queryScalar();
                            //print_r($detain_count_semmf1);exit;

                             $strengthf_count=$strengthf[0]['count']- ($detain_count_semmf);
                          // print_r($strengthf[0]['count']);exit;
                            
                            if($value['degree_type']=='PG' && $printed==0 &&$value['degree_code']!=='Ph.D' )
                            {
                                $others_count_total_disp = $others_count_total-(($ott==0 || $ott=='' )?0:$ott);
                                $body .='<tr><td colspan=4 algin="right" ><strong>TOTAL STRENGTH UG</strong></td>';
                                $body .='<td>'.$total_strength.'</td>';
                               
                                $body .='<td>'.$detain_total_sem.'</td>';
                                 $body .='<td>'.$disc_total_sem.'</td>';
                                 $body .='<td>'.$rejoin_total_sem.'</td>';
                                $body .='<td>'.$transfer_total_sem.'</td>';
                                $body .='<td>'.$detain_total.'</td>';
                                $body .='<td>'.$disc_total.'</td>';
                                $body .='<td>'.$rejoin_total.'</td>';
                                $body .='<td>'.$transfer_total.'</td>';
                                $body .='<td>'.$latearl_total.'</td>';
                                $body .='<td>'.$others_count_total_disp.'</td>';
                                $body .='<td>'.$GRAND_TOTAL.'</td>';
                                 $body .='<td>'.$total_strengthm.'</td>';
                                $body .='<td>'.$total_strengthf.'</td></tr>';

                                $detain_total = $ug_count = $pg_count = $others_count_total = $transfer_total =$latearl_total= $rejoin_total = $disc_total = $GRAND_TOTAL = $total_strengthm =$total_strengthf =$total_strength = 0;
                                $others_count_total +=(($ott==0 || $ott=='' )?0:$ott);
                                $printed = 1; $pg_general_count=0;
                                $detain_total_sem = $transfer_total_sem = $rejoin_total_sem = $disc_total_sem=0;
                            }
                            
                            if($value['degree_type']=='PG' && ($value['degree_code']=='Ph.D' || $value['degree_code']=='Ph.d') )
                            {
                                $body_phd .='<tr>';
                                $body_phd .='<td>'.$sn_no.'</td>';
                                $body_phd .='<td>'.$value['batch_name'].'</td>';
                                $body_phd .='<td>'.$value['degree_code'].'</td>';
                                $body_phd .='<td>'.strtoupper($value['programme_name']).'</td>';
                                $body_phd .='<td>'.$strength_count.'</td>';
                              
                                 $body_phd .='<td>'.$detain_disp_sem.'</td>';
                                 $body_phd .='<td>'.$disc_disp_sem.'</td>';
                                 $body_phd .='<td>'.$rejoin_dip_sem.'</td>';
                                 $body_phd .='<td>'.$transfer_count_dip_sem.'</td>';
                                $body_phd .='<td>'.$detain_disp.'</td>';
                                $body_phd .='<td>'.$disc_disp.'</td>';
                                $body_phd .='<td>'.$rejoin_dip.'</td>';
                                $body_phd .='<td>'.$transfer_count_dip.'</td>';
                                $body_phd .='<td>'.$lateral_count_dip.'</td>';
                                $body_phd .='<td>'.$other_disp.'</td>';
                                $body_phd .='<td>'.$active_final_disp.'</td>';
                                $body_phd .='<td>'.$strengthm_count.'</td>';
                                $body_phd .='<td>'.$strengthf_count.'</td></tr>';

                                $detain_total_phd +=count($detain_count); 
                                $disc_total_phd +=count($disc_count); 
                                $rejoin_total_phd +=count($rejoin_count); 
                                $transfer_total_phd +=count($transfer_count); 
                                $latearl_total_phd+=$lateral_count_dip;
                                $ACTIVE_count_php = $other_disp+$transfer_count_dip+$rejoin_dip+$lateral_count_dip; 

                                $others_count_total_phd +=($ott==0 || $ott=='' )?0:$ott; 
                                $GRAND_TOTAL_phd +=$active_final_disp;
                                $total_strength_phd +=$strength_count;
                                $total_strengthm_phd +=$strengthm_count;
                                $total_strengthm_phd +=$strengthf_count;
                                $phd_printed = 1;
                            }
                            
                           
                            if($value['degree_type']=='UG')
                            {
                                $body .='<tr>';
                                $body .='<td>'.$sn_no.'</td>';
                                $body .='<td>'.$value['batch_name'].'</td>';
                                $body .='<td>'.$value['degree_code'].'</td>';
                                $body .='<td>'.strtoupper($value['programme_name']).'</td>';
                                $body .='<td>'.$strength_count.'</td>';
                               
                                $body .='<td>'.$detain_disp_sem.'</td>';
                                 $body .='<td>'.$disc_disp_sem.'</td>';
                                 $body .='<td>'.$rejoin_dip_sem.'</td>';
                                 $body .='<td>'.$transfer_count_dip_sem.'</td>';
                                $body .='<td>'.$detain_disp.'</td>';
                                $body .='<td>'.$disc_disp.'</td>';
                                $body .='<td>'.$rejoin_dip.'</td>';
                                $body .='<td>'.$transfer_count_dip.'</td>';
                                 $body .='<td>'.$lateral_count_dip.'</td>';
                                $body .='<td>'.$other_disp.'</td>';
                                $body .='<td>'.$active_final_disp.'</td>';
                                 $body .='<td>'.$strengthm_count.'</td>';
                                $body .='<td>'.$strengthf_count.'</td></tr>';
                                $sn_no++;

                                 $GRAND_TOTAL +=$active_final_disp;
                                $total_strength +=$strength_count;
                                $total_strengthm +=$strengthm_count;
                                $total_strengthf +=$strengthf_count;
                                 
                            }
                            //echo $value['degree_code'].$value['semester']."<br>";
                            if(($value['degree_type']=='PG') && ($value['degree_code']!='Ph.D') && ($value['semester']<=$semester && $value['degree_total_semesters']>=$semester) && ($semester<5))
                            {
                                $body .='<tr>';
                                $body .='<td>'.$sn_no.'</td>';
                                $body .='<td>'.$value['batch_name'].'</td>';
                                $body .='<td>'.$value['degree_code'].'</td>';
                                $body .='<td>'.strtoupper($value['programme_name']).'</td>';
                                $body .='<td>'.$strength_count.'</td>';
                                 
                                $body .='<td>'.$detain_disp_sem.'</td>';
                                 $body .='<td>'.$disc_disp_sem.'</td>';
                                 $body .='<td>'.$rejoin_dip_sem.'</td>';
                                 $body .='<td>'.$transfer_count_dip_sem.'</td>';
                                $body .='<td>'.$detain_disp.'</td>';
                                $body .='<td>'.$disc_disp.'</td>';
                                $body .='<td>'.$rejoin_dip.'</td>';
                                $body .='<td>'.$transfer_count_dip.'</td>';
                                 $body .='<td>'.$lateral_count_dip.'</td>';
                                $body .='<td>'.$other_disp.'</td>';
                                $body .='<td>'.$active_final_disp.'</td>';
                                $body .='<td>'.$strengthm_count.'</td>';
                                $body .='<td>'.$strengthf_count.'</td></tr>';
                                $sn_no++;

                                 $GRAND_TOTAL +=$active_final_disp;
                                $total_strength +=$strength_count;
                                $pg_general_count+=$other_disp;
                                 $total_strengthm +=$strengthm_count;
                                $total_strengthf +=$strengthf_count;
                                 
                            }
                        }

                        if($total_strength>0)
                       {
                            $body .='<tr><td colspan=4 algin="center" ><strong>TOTAL STRENGTH PG</strong> </td>';
                            $body .='<td>'.$total_strength.'</td>';
                         
                            $body .='<td>'.$detain_total_sem.'</td>';
                                 $body .='<td>'.$disc_total_sem.'</td>';
                                 $body .='<td>'.$rejoin_total_sem.'</td>';
                                $body .='<td>'.$transfer_total_sem.'</td>';
                                $body .='<td>'.$detain_total.'</td>';
                                $body .='<td>'.$disc_total.'</td>';
                                $body .='<td>'.$rejoin_total.'</td>';
                                $body .='<td>'.$transfer_total.'</td>';
                            $body .='<td>'.$latearl_total.'</td>';
                            $body .='<td>'.$pg_general_count.'</td>';
                            $body .='<td>'.$GRAND_TOTAL.'</td>';
                            $body .='<td>'.$total_strengthm.'</td>';
                            $body .='<td>'.$total_strengthf.'</td></tr>';
                      }
                      /*  if($phd_printed==1)
                        {
                            $body .=$body_phd.'<tr><td colspan=4 algin="center" ><strong>TOTAL STRENGTH PH.D</strong> </td><td>'.$total_strength_phd.'</td><td>'.$detain_total_phd.'</td><td>'.$disc_total_phd.'</td><td>'.$rejoin_total_phd.'</td><td>'.$transfer_total_phd.'</td><td>'.$latearl_total_phd.'</td><td>'.$others_count_total_phd.'</td><td>'.$GRAND_TOTAL_phd.'</td></tr>';    
                        }*/
                        echo $html .='<tbody id="show_dummy_numbers" style="font-weight:bold !important"> '.$body.'</tbody> </table>'; 

                      


                    }

                        if(isset($_SESSION['student_count_repo']))
                        {
                            unset($_SESSION['student_count_repo']);
                        }
                        $_SESSION['student_count_repo'] = $html;

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