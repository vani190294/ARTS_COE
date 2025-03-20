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
use yii\db\Query;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Female Result Report";

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
        <div class="col-xs-12 col-sm-12 col-lg-12">

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
                    'data'=>ConfigUtilities::getDegreedetails(),

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
                <?= $form->field($model, 'year')->textInput(['id'=>'course_year','name'=>'year','value'=>date("Y")]) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
	            <?php echo $form->field($model,'month')->widget(
	                Select2::classname(), [
	                    'options' => [
	                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
	                        'id'=>'exam_month',
	                        'name'=>'month',
	                    ],
	                    'pluginOptions' => [
	                        'allowClear' => true,
	                    ],
	                ]) 
	                ?>
        	</div> 
       

            
 </div>
        
        <div class="col-xs-12 col-sm-12 col-lg-12">
       		 <br />
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform">

                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/caste']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>

            <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
       
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
if(isset($courseanalysis))
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

                    $html .='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <tr>
                         
                          <td colspan=45 align="center"> 
                              <center><b><font size="6px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font></center>
                              <center> Phone : <b>'.$org_phone.'</b></center>
                              <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                         </td>
                        
                        </tr> 
                       
                        <tr class="table-danger">
                            
                            <th rowspan="2">SNO</th>
                                           
                            <th rowspan="2">Total</th>
                            <th rowspan="2">Male</th>
                            <th rowspan="2">Female</th>
                            <th rowspan="2">Degree</th>
                            <th rowspan="2">Programme</th>
                            
                            <th colspan="2" style="text-align:center;">BC</th>
                             <th colspan="2" style="text-align:center;">MBC</th>
                             <th colspan="2" style="text-align:center;">OBC</th>
                             <th colspan="2" style="text-align:center;">OC</th>
                             <th colspan="2" style="text-align:center;">SC</th>
                              <th colspan="2" style="text-align:center;">ST</th>

                              <th colspan="2" style="text-align:center;">Total student passed BC</th>
                              <th colspan="2" style="text-align:center;">Total student passed MBC</th>
                               <th colspan="2" style="text-align:center;">Total student passed OBC</th>
                                 <th colspan="2" style="text-align:center;">Total student passed OC</th>
                                    <th colspan="2" style="text-align:center;">Total student passed SC</th>
                                     <th colspan="2" style="text-align:center;">Total student passed ST</th>
                                      <th colspan="2" style="text-align:center;">Total student BC 60 percent above</th>
                                      <th colspan="2" style="text-align:center;">Total student MBC 60 percent above</th>
                                       <th colspan="2" style="text-align:center;">Total student OBC 60 percent above</th>
                                       <th colspan="2" style="text-align:center;">Total student OC 60 percent above</th>

                                       <th colspan="2" style="text-align:center;">Total student SC 60 percent above</th>
                                       <th colspan="2" style="text-align:center;">Total student ST 60 percent above</th>



                            
                        </tr>   
                        <tr>
                            
                            <th>M</th>
                            <th>F</th>
                           

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>
                           
                           <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                             <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>

                            <th>M</th>
                            <th>F</th>


                            
                        </tr>
                        ';

                        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                        
                         $men = new Query();
        $men->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.gender'=>'M','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $analysis = $men->createCommand()->queryAll();



         $women = new Query();
        $women->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.gender'=>'F','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $Female = $women->createCommand()->queryAll();





         $BC = new Query();
        $BC->select('count(register_number) as  totalBC,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'BC','a.gender'=>'M','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteBC = $BC->createCommand()->queryAll();


        


 

          $BCF = new Query();
      $BCF->select('count(register_number) as  totalBCF,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'BC','a.gender'=>'F','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteBCF = $BCF->createCommand()->queryAll();

       // print_r($casteBCF);exit;
        //echo "<br>".$BCF->createCommand()->getRawSql(); exit;

         
        


        $MBC = new Query();
        $MBC->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'MBC','a.gender'=>'M','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteMBC = $MBC->createCommand()->queryAll();


         $MBCF = new Query();
        $MBCF->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'MBC','a.gender'=>'F','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteMBCF = $MBCF->createCommand()->queryAll();


        $OBC = new Query();
        $OBC->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OBC','a.gender'=>'M','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteOBC = $OBC->createCommand()->queryAll();


         $OBCF = new Query();
        $OBCF->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OBC','a.gender'=>'F','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteOBCF = $OBCF->createCommand()->queryAll();

        $SC = new Query();
        $SC->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'SC','a.gender'=>'M','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteSC = $SC->createCommand()->queryAll();


        $SCF = new Query();
        $SCF->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'SC','a.gender'=>'F','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteSCF = $SCF->createCommand()->queryAll();

        $ST = new Query();
        $ST->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'ST','a.gender'=>'M','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteST = $ST->createCommand()->queryAll();


         $STF = new Query();
        $STF->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'ST','a.gender'=>'F','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
        $casteSTF = $STF->createCommand()->queryAll();


        $OC = new Query();
        $OC->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OC','a.gender'=>'M','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
         $casteoc = $OC->createCommand()->queryAll();


         $OCF = new Query();
        $OCF->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OC','a.gender'=>'F','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
         $casteocf = $OCF->createCommand()->queryAll();

      // $get_stu_query = "SELECT count(distinct A.register_number) as count FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id  JOIN coe_mark_entry_master as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_subjects_mapping as G ON G.coe_subjects_mapping_id=F.subject_map_id where  B.coe_student_mapping_id NOT IN(select student_map_id from coe_mark_entry_master where year_of_passing='' and subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where student_map_id=F.student_map_id and result like '%pass%')) and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') AND F.grade_name not IN ('U','wh','AB','ab','WH','ra','wd','WD') and A.caste='BC' and A.gender='M'"; 


        //$final_student_M = Yii::$app->db->createCommand($get_stu_query)->queryAll(); 


        $get_stu_query= new Query();
         $get_stu_query->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'BC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
        $final_student_M =  $get_stu_query->createCommand()->queryAll();

          $get_stu_query_F= new Query();
          $get_stu_query_F->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'BC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
        $final_student_F =   $get_stu_query_F->createCommand()->queryAll();


        

        

         $get_stu_query_MBC = new Query();
         $get_stu_query_MBC ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'MBC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
        $final_student_MBC =  $get_stu_query_MBC ->createCommand()->queryAll();

       $get_stu_query_MBCF = new Query();
         $get_stu_query_MBCF ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'MBC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
            $final_student_MBCF =  $get_stu_query_MBCF ->createCommand()->queryAll();

            $get_stu_query_OBCM = new Query();
         $get_stu_query_OBCM ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OBC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
           $final_student_OBCM =  $get_stu_query_OBCM ->createCommand()->queryAll();

       
       $get_stu_query_OBCF = new Query();
          $get_stu_query_OBCF ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OBC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
           $final_student_OBCF =  $get_stu_query_OBCF ->createCommand()->queryAll();
       
  
       $get_stu_query_OCM = new Query();
         $get_stu_query_OCM ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
          $final_student_OCM=$get_stu_query_OCM ->createCommand()->queryAll();

          $get_stu_query_OCF= new Query();
         $get_stu_query_OCF ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
          $final_student_OCF=$get_stu_query_OCF ->createCommand()->queryAll();
        


         $get_stu_query_SC= new Query();
         $get_stu_query_SC ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'SC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
          $final_student_SC=$get_stu_query_SC ->createCommand()->queryAll();



         $get_stu_query_SCF= new Query();
         $get_stu_query_SCF ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'SC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
          $final_student_SCF=$get_stu_query_SCF->createCommand()->queryAll();



          $get_stu_query_ST= new Query();
         $get_stu_query_ST ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'ST','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
          $final_student_ST=$get_stu_query_ST->createCommand()->queryAll();


       $get_stu_query_STF= new Query();
         $get_stu_query_STF ->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'ST','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']]);
            
          $final_student_STF=$get_stu_query_STF->createCommand()->queryAll();
       

        

         

         

        $BCP= new Query();
         $BCP->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'BC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
            ->andWhere(['>','x.percentage','60']);
        $Bcpercent =  $BCP->createCommand()->queryAll();
        //print_r($Bcpercent);exit;

         $BCPF= new Query();
         $BCPF->select('count(register_number) as  count,d.coe_student_mapping_id,register_number,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')

            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'BC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
            ->andWhere(['>','x.percentage','60']);
        $BcpercentF =  $BCPF->createCommand()->queryAll();  


        $MBCPM = new Query();
        $MBCPM->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')      
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'MBC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
             ->andWhere(['>','x.percentage','60']);
        $casteMBCPM =  $MBCPM->createCommand()->queryAll();


        $MBCPMF = new Query();
        $MBCPMF->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
            ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')      
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'MBC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
             ->andWhere(['>','x.percentage','60']);
        $casteMBCPMF =  $MBCPMF->createCommand()->queryAll();

         $OBCMP = new Query();
        $OBCMP->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
             ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')          
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OBC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
             ->andWhere(['>','x.percentage','60']);
        $casteOBCMP =  $OBCMP->createCommand()->queryAll();

         $OBCMPF = new Query();
        $OBCMPF->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
             ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')          
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OBC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
             ->andWhere(['>','x.percentage','60']);
        $casteOBCMPF =  $OBCMPF->createCommand()->queryAll();

        $OCMP = new Query();
        $OCMP->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id') 
             ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')           
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
             ->andWhere(['>','x.percentage','60']);
         $casteocmp = $OCMP->createCommand()->queryAll();

          $OCMPF = new Query();
        $OCMPF->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id') 
             ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')           
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'OC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
             ->andWhere(['>','x.percentage','60']);
         $casteocmpf = $OCMPF->createCommand()->queryAll();


         $SCMP = new Query();
        $SCMP->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')   
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'SC','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
            ->andWhere(['>','x.percentage','60']);
        $casteSCMP = $SCMP->createCommand()->queryAll();


        $SCMPF = new Query();
        $SCMPF->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')   
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'SC','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
            ->andWhere(['>','x.percentage','60']);
        $casteSCMPF = $SCMPF->createCommand()->queryAll();


        $STMP = new Query();
        $STMP->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
             ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')      
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'ST','a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
             ->andWhere(['>','x.percentage','60']);
        $casteSTMP = $STMP->createCommand()->queryAll();


        $STMPF = new Query();
        $STMPF->select('count(register_number) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
             ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')      
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.Caste'=>'ST','a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$_POST['bat_map_val']])
             ->andWhere(['>','x.percentage','60']);
        $casteSTMPF = $STMPF->createCommand()->queryAll();




         

        //print_r($final_student);exit;

          



        



         foreach ($courseanalysis as $values) 
                {




                    foreach ($analysis as $key => $men) 
                    {
                        foreach ($Female as $key => $fem) 
                        {
                        
                        foreach ($casteBC as $key => $bc)
                         {


                            foreach ($casteBCF as $key => $bcf) {
                                

                        foreach ($casteMBC as $key => $mbc)
                         {
                            foreach ($casteMBCF as $key => $mf) 
                            {
                                
                            

                        foreach ($casteOBC as $key => $obc)
                         {
                            foreach ($casteOBCF as $key => $obcf) 
                            {
                              
                        foreach ($casteSC as $key => $sc)
                         {

                            foreach ($casteSCF as $key => $scf) 
                            {
                             


                            foreach ($casteST as $key => $st)
                         {

                            foreach ($casteSTF as $key => $stf) 
                            {
                              
                            
                            foreach ($casteoc as $key => $oc)
                         {

                            foreach ($casteocf as $key => $ocf)
                         {
                            foreach ($final_student_M as $key => $m) 
                            {

                                foreach ($final_student_F as $key => $f) 
                            {
                                foreach ( $final_student_MBC  as $key => $M_mbc)
                                 {
                                    foreach ($final_student_MBCF as $key => $mbcf) 
                                    {
                                     foreach ($final_student_OBCM as $key => $obcm) {
                                         foreach ($final_student_OBCF as $key => $obcfm) 
                                         {
                                           
                                         foreach ($final_student_OCM as $key => $ocm) 
                                         {
                                            foreach ($final_student_OCF as $key => $ocfm) 
                                            {

                                                foreach ($final_student_SC as $key => $scm) 
                                                {

                                               foreach ($final_student_SCF as $key => $scfm) 
                                               {
                                              foreach ($final_student_ST  as $key => $stm) 
                                              {
                                                         # code...
                                            foreach ($final_student_STF as $key => $stfm) 
                                            {
                                                                   
                                                        
                                            foreach ($Bcpercent as $key => $perbcm) 

                                            {
                                                foreach ($BcpercentF as $key =>$perbcf) 
                                                
                                            {
                                                foreach ($casteMBCPM as $key => $mbcpm) 
                                                {
                                                    
                                               foreach ($casteMBCPMF as $key => $mbcpmf) 

                                               {
                                                
                                                foreach ($casteOBCMP as $key => $obcmp) 
                                                {
                                                    foreach ($casteOBCMPF as $key => $obcmpf) 
                                                    {

                                                      foreach ($casteocmp as $key => $ocmp) 
                                                      {
                                                    
                                                    foreach ($casteocmpf as $key => $ocmpf) 
                                                    {
                                                   
                                                   foreach ($casteSCMP as $key => $scmp) 
                                                   {
                                                              
                                                  foreach ($casteSCMPF as $key => $scmpf) 
                                                  {
                                                               
                                                foreach ($casteSTMP as $key => $stmp) 
                                                {

                                                     foreach ($casteSTMPF as $key => $stmpf) 
                                                {
                                                                         
                                                                
                                                         
                                                
                                                      
                                                  
                                                   
                                           


                                  
                               

                           
                        
                        
                         $sno = 1;
                    $body .='<tr>
                           <td>'.$sno.'</td>
                           
                           <td>'.$values["total"].'</td>
                           <td>'.$men["total"].'</td>
                           <td>'.$fem["total"].'</td>
                           <td>'.$values["degree_code"].'</td>
                           <td>'.$values["programme_name"].'</td>
                           <td>'.$bc["totalBC"].'</td>
                           <td>'.$bcf["totalBCF"].'</td>
                            <td>'.$mbc["total"].'</td>
                             <td>'.$mf["total"].'</td>
                             <td>'.$obc["total"].'</td>
                             <td>'.$obcf["total"].'</td>
                             <td>'.$oc["total"].'</td>
                              <td>'.$ocf["total"].'</td>
                          <td>'.$sc["total"].'</td>
                           <td>'.$scf["total"].'</td>
                          <td>'.$st["total"].'</td>
                          <td>'.$stf["total"].'</td>
                          <td>'.$m["count"].'</td>
                          <td>'.$f["count"].'</td>
                          <td>'.$M_mbc["count"].'</td>
                          <td>'.$mbcf["count"].'</td>
                          <td>'.$obcm["count"].'</td>
                          <td>'.$obcfm["count"].'</td>
                          <td>'.$ocm["count"].'</td>
                          <td>'.$ocfm["count"].'</td>
                          <td>'.$scm["count"].'</td>
                           <td>'.$scfm["count"].'</td>
                            <td>'.$stm["count"].'</td>
                            <td>'.$stfm["count"].'</td>
                            <td>'.$perbcm["count"].'</td>
                            <td>'.$perbcf["count"].'</td>
                             <td>'.$mbcpm["count"].'</td>
                             <td>'.$mbcpmf["count"].'</td>
                             <td>'.$obcmp["count"].'</td>
                             <td>'.$obcmpf["count"].'</td>
                              <td>'.$ocmp["count"].'</td>
                                <td>'.$ocmpf["count"].'</td>
                                  <td>'.$scmp["count"].'</td>
                                    <td>'.$scmpf["count"].'</td>
                                     <td>'.$stmp["count"].'</td>
                                      <td>'.$stmpf["count"].'</td>

                           </tr>';
 }
}
}
}
}
 }
}
 }
 }                          $sno++;
}
}
}
}
}
}
}
}
}
}
}
}
}
}
}
} 
}
}
}
}
}
}
}
}
}
}
}
}
}
}                        echo $html .='<tbody id="show_dummy_numbers" style="font-weight:bold !important"> '.$body.'</tbody> </table>'; 
                         if(isset($_SESSION['student_count_repo']))
                        {
                            unset($_SESSION['student_count_repo']);
                        }
                        $_SESSION['student_count_repo'] = $html;




                    }