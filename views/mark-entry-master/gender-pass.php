<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use app\models\HallAllocate;
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

$this->title="Batch Wise Pass Count Report";

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
                <?= $form->field($model, 'year')->textInput(['id'=>'course_year','name'=>'year','value'=>date("Y")]) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
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
       


            
 </div>
        
        <div class="col-xs-12 col-sm-12 col-lg-12">
       		 <br />
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform">

                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/gender-pass']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
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
                            <th rowspan="2">Degree</th>
                                           
                            <th rowspan="2">Particulars</th>
                           


                            
                            <th colspan="2" style="text-align:center;">Appeared</th>
                             <th colspan="2" style="text-align:center;">Passed</th>
                              <th rowspan="2">Pass Percentage</th>
                            


                            
                        </tr>   
                        <tr>
                            
                            <th>M</th>
                            <th>F</th>
                           

                            <th>M</th>
                            <th>F</th>

                           

                            
                        </tr>
                        ';

            
                         $sno = 1;
                        foreach ($courseanalysis as $key => $value) 
                        {

                             $men = new Query();
        $men->select('count(register_number) as  total,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.gender'=>'M','d.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);
        $appmen = $men->createCommand()->queryAll();

        $female = new Query();
        $female->select('count(register_number) as  female,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')    
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.gender'=>'F','d.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);
        $appfem = $female->createCommand()->queryAll();

        $malepass = new Query();
        $malepass->select('count(x.student_map_id) as  count,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
             ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')

           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.gender'=>'M','x.part_no'=>'3','d.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);
        $passm = $malepass->createCommand()->queryAll();

         $femalepass = new Query();
         $femalepass->select('count(register_number) as  countf,course_batch_mapping_id,degree_code')
            ->from('coe_student a')
            ->join('JOIN', 'coe_student_mapping d', 'd.student_rel_id=a.coe_student_id')
             ->join('JOIN', 'coe_consolidate_marks x', 'x.student_map_id=d.coe_student_mapping_id')      
           
            ->join('JOIN', 'coe_bat_deg_reg e', 'd.course_batch_mapping_id=e.coe_bat_deg_reg_id')
            ->join('JOIN', 'coe_degree f', 'e.coe_degree_id=f.coe_degree_id')
            ->join('JOIN', 'coe_programme g', 'e.coe_programme_id=g.coe_programme_id')
            ->join('JOIN', 'coe_batch h', 'h.coe_batch_id=e.coe_batch_id')
            ->where([ 'h.coe_batch_id' => $_POST['bat_val'],'a.gender'=>'F','x.part_no'=>'3','d.course_batch_mapping_id'=>$value['course_batch_mapping_id']]);
        $femalepass = $femalepass->createCommand()->queryAll();
         foreach ($appmen as $key => $men) 
                    {
                        foreach ($appfem as $key => $fem) 
                    {
                        foreach ($passm as $key => $malep) 
                        {
                            foreach ($femalepass as $key => $fpass) 
                            {
                                
                                $percent_pass=$malep['count']+$fpass['countf'];
                                $appered=$men['total']+$fem['female'];

                            
                    $body .='<tr>
                           <td>'.$sno.'</td>
                            <td>'.$value['degree_code'].'</td>
                             <td>'.$value['programme_name'].'</td>
                              <td>'.$men['total'].'</td>
                              <td>'.$fem['female'].'</td>
                               <td>'.$malep['count'].'</td>
                               <td>'.$fpass['countf'].'</td>
                               <td>'.ceil( (($percent_pass / $appered) * 100)).'</td>
                              

                              </tr>';
                              $sno++;
                        }
                    }
}
}
}

                        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                        
                      echo $html .='<tbody id="show_dummy_numbers" style="font-weight:bold !important"> '.$body.'</tbody> </table>'; 
                         if(isset($_SESSION['student_count_repo']))
                        {
                            unset($_SESSION['student_count_repo']);
                        }
                        $_SESSION['student_count_repo'] = $html;




                    }