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

$this->title="ESE Moderation ";

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
            <!--div class="col-lg-2 col-sm-2">
                <?php 
                echo $form->field($model, 'section')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getSectionnames(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id'=>'stu_section_select',
                            //'value'=>$sec,
                            'name'=>'sec',
                            'class'=>'form-control',                                    
                        ],
                                                             
                    ]); 
                ?>
            </div-->

            
                <?php 
                $model->mark_type='27';
                
                ?>
            
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <br />
            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">

                <!--input type="button" id="esemoderation" name="esemoderation" class="btn btn-success" value="Submit"-->

                <input type="Submit" class="btn btn-success" value="Submit">
                <?= Html::a("Reset", Url::toRoute(['mark-entry/esemoderation']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        &nbsp;
    </div>
 <?php //print_r($subject_list); exit;

 if(!empty($subject_list))
{ 
    ?>   
    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-programmeanalysistemp1','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mark-entry/programme-analysistemp-pdf1'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12" style="overflow-x: auto;">
        <?php 
                $batch_name = Yii::$app->db->createCommand("select batch_name from coe_batch where coe_batch_id='" . $_POST['bat_val'] . "'")->queryScalar();
       
            $degree_name = Yii::$app->db->createCommand("select concat(degree_code,'  ',programme_code) as degree_name from coe_degree a,coe_programme b,coe_bat_deg_reg c where a.coe_degree_id=c.coe_degree_id and b.coe_programme_id=c.coe_programme_id and c.coe_bat_deg_reg_id='" . $_POST['bat_map_val'] . "'")->queryScalar();
            $month = Yii::$app->db->createCommand("select description from coe_category_type where coe_category_type_id='" . $_POST['month'] . "'")->queryScalar();
            $mark_type_name = Yii::$app->db->createCommand("select description from coe_category_type where coe_category_type_id='27' ")->queryScalar();
            $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();
            $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
            $reg_year = Yii::$app->db->createCommand("select regulation_year from coe_bat_deg_reg where coe_batch_id='" . $_POST['bat_val'] . "' and coe_bat_deg_reg_id='". $_POST['bat_map_val']."'   ")->queryScalar();

            $sem = ConfigUtilities::semCaluclation($_POST['year'], $_POST['month'], $_POST['bat_map_val']);

                require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                $course_result_table = '';
                $course_result_table .= '<table border=1  width="100%" class="table table-striped table-responsive table-hover table-bordered"  align="center">';
                $course_result_table .= '<tr>
                                            <td colspan=29 align="center"> 
                                                <center><b><font size="6px">' . $org_name . '</font></b></center>
                                                <center>' . $org_address . '</center>
                                                <center>' . $org_tagline . '</center> 
                                            </td>
                                           
                                        </tr>';
                $course_result_table .= '<tr><td colspan=29 align="center"><b>' .  "ESE ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . ' Result Analysis</b> - ' . strtoupper($month . ' ' . $_POST['year']) . '</td></tr>';
                $course_result_table .= '<tr><td colspan=29 align="center"><b>Batch - ' . $batch_name . ' ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . '</b> - ' . $degree_name . '</td></tr>';
                $colspan = 0; // is the number of columns
                 $course_result_table .= '<tr>                                                                                                                                
                                <th> S. NO </th> 
                                <th>' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)) . ' CODE </th> 
                                <th>CIA Max</th> 
                                <th colspan="' . $colspan . '" >' . strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)) . ' NAME </th>
                                <th>ENR</th>
                                <th>APP</th>
                                <th>ABS</th>
                                <th>WH</th>
                                <th>PA</th>
                                <th>FA</th>
                                <th>PA%</th>
                                <th>FA%</th>
                                <th>MEAN CIA (out of Max)</th>
                                <th>MEAN CIA 100</th>
                                <th>MEAN ESE (out of 100)</th>
                                <th>SD</th>
                                <th>91-100</th>
                                <th>81-90</th>
                                <th>71-80</th>
                                <th>61-70</th>
                                <th>50-60</th>
                                <th>45-49</th>
                                <th>40-44</th>
                                <th>35-39</th>
                                <th>30-34</th>
                                <th>0-29</th>
                                <th>Common Sub Pass Stu/App Stu</th>
                                <th>Common Sub Stu Pass Percent</th>
                                <th>Moderation</th>';
                $old_grade = '';
                
                $course_result_table .= '</tr>';
                //return $course_result_table;exit;
                $sn = 1; $total_appered_stu=0; $total_pass_stu=0; $total_enroll_stu=0; $tot_stu ='';
                foreach ($subject_list as $subject) 
                {

                    $course_result_table .= '<tr><td align="left">' . $sn . '</td><td align="left">' . $subject['subject_code'] . '</td><td align="left">'.$subject['CIA_max'].'</td><td colspan="' . $colspan . '"  align="left">' . $subject['subject_name'] . '</td>';
                
                     $query_enroll = new Query();
                    $query_enroll->select('count(DISTINCT student_map_id)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                        ->join('JOIN', 'coe_dummy_number b', 'b.student_map_id=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id and c.batch_mapping_id=a.course_batch_mapping_id')
                        ->where(['b.subject_map_id' => $subject['coe_subjects_mapping_id'],'b.year' => $_POST['year'], 'b.month' => $_POST['month'],'student_status'=>'Active']);
                    if(!empty($_POST['section']) && $_POST['section']!='All')
                    {
                         $query_enroll->andWhere(['=', 'section_name', $_POST['section']]);
                    }
                    //$query_enroll->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                    $student_enrol = $query_enroll->createCommand()->queryScalar();
                    $course_result_table .= '<td>' . $student_enrol . '</td>';

                    $query_appeared = new Query();
                    $query_appeared->select('count(DISTINCT student_map_id)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                        ->join('JOIN', 'coe_dummy_number b', 'b.student_map_id=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                        ->where(['b.subject_map_id' => $subject['coe_subjects_mapping_id'], 'b.year' => $_POST['year'], 'b.month' => $_POST['month'],'student_status'=>'Active']);

                    if(!empty($_POST['section']) && $_POST['section']!='All')
                    {
                         $query_appeared->andWhere(['=', 'section_name', $_POST['section']]);
                    }

                    //$query_appeared->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                    $student_appeared = $query_appeared->createCommand()->queryScalar();
                   
                    
                      $query_absent = new Query();
                    $query_absent->select('count(absent_student_reg)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_student as REJ', 'REJ.coe_student_id=a.student_rel_id')
                        ->join('JOIN', 'coe_absent_entry b', 'b.absent_student_reg=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.exam_subject_id')
                        ->where(['b.exam_subject_id' => $subject['coe_subjects_mapping_id'], 'b.exam_year' => $_POST['year'],'student_status'=>'Active', 'b.exam_month' => $_POST['month']]);
                    if(!empty($_POST['section']) && $_POST['section']!='All')
                    {
                         $query_absent->andWhere(['=', 'section_name', $_POST['section']]);
                    }

                    //$query_absent->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                    $student_absent = $query_absent->createCommand()->queryScalar();

                    $student_appeared=$student_appeared-$student_absent;
                    $course_result_table .= '<td>' . $student_appeared . '</td>';
                    $course_result_table .= '<td>' . $student_absent . '</td>';

                    $query_withheld = new Query();
                    $query_withheld->select('count(student_map_id)')
                        ->from('coe_student_mapping a')
                        ->join('JOIN', 'coe_mark_entry_master_temp b', 'b.student_map_id=a.coe_student_mapping_id')
                        ->join('JOIN', 'coe_subjects_mapping c', 'c.coe_subjects_mapping_id=b.subject_map_id')
                        ->where(['subject_map_id' => $subject['coe_subjects_mapping_id'], 'year' => $_POST['year'], 'month' => $_POST['month'], 'withheld' => 'w']);
                    if(!empty($_POST['section']) && $_POST['section']!='All')
                    {
                         $query_withheld->andWhere(['=', 'section_name', $_POST['section']]);
                    }
                        //$query_withheld->andWhere(['<>', 'status_category_type_id', $det_disc_type]);
                    $student_withheld = $query_withheld->createCommand()->queryScalar();
                    $course_result_table .= '<td align="center">' . $student_withheld . '</td>';

                    $query = new  Query();
                    $query->select('BAR.grand_total as tmark, G.type_id, P.out_of_100 as pmark,H.*')
                        ->from('coe_dummy_number as DUM')
                        ->join('JOIN', 'coe_val_barcode_verify_details as BAR', 'BAR.dummy_number=DUM.dummy_number')
                        ->join('JOIN', 'coe_subjects_mapping as G', 'G.coe_subjects_mapping_id=DUM.subject_map_id')
                        ->join('JOIN', 'coe_practical_entry as P','DUM.student_map_id=P.student_map_id AND DUM.subject_map_id=P.subject_map_id')
                        ->join('JOIN', 'coe_subjects H', 'H.coe_subjects_id=G.subject_id');
                    $query->Where(['DUM.year' => $_POST['year'], 'G.batch_mapping_id' => $_POST['bat_map_val'], 'DUM.subject_map_id' => $subject['coe_subjects_mapping_id'], 'DUM.month' => $_POST['month']]);
                    //echo $query->createCommand()->getrawsql(); exit;
                    $ese_list= $query->createCommand()->queryAll();

                                if($rows['CIA_max']==0 && $rows['ESE_max']==0)
                                {
                                      if($stus['result']=='Pass')
                                      {
                                           $subject_pass= $subject_pass+1;
                                      }
                                      else
                                      {
                                          $subject_fail= $subject_fail+1; 
                                      }
                                }
                                else if($rows['type_id']=='143')
                                {
                                    if($stus['total']<$rows['total_minimum_pass'] || $stus['ese']<$rows['ESE_min'])
                                    {
                                        $subject_fail= $subject_fail+1;                                      
                                    }
                                    else
                                    {
                                        $subject_pass= $subject_pass+1;
                                    }
                                  }
                                  else
                                  {
                                      $prac_mark = Yii::$app->db->createCommand("SELECT out_of_100 as mark FROM coe_practical_entry WHERE subject_map_id=".$rows['subject_map_id']." AND student_map_id='".$stus['student_map_id']."'  AND year='".$year."'  AND month='".$month."' ")->queryScalar();

                                      $esemark=$stus['grand_total'];
                                      $tpesemark=0;
                                      if($rows['type_id']=='140')
                                      {
                                          $tpesemark = round(($esemark*0.25) + ($prac_mark*0.25));
                                      }
                                      else if($rows['type_id']=='141')
                                      {
                                           $tpesemark = round(($esemark*0.35) + ($prac_mark*0.15));
                                      }
                                      else if($rows['type_id']=='142')
                                      {
                                         $tpesemark = round(($esemark*0.15) + ($prac_mark*0.35));
                                      } 
                                      else if($rows['type_id']=='144')
                                      {
                                          $tpesemark =round($prac_mark*0.50);
                                      }   
                                      else
                                      {
                                          $tpesemark =round($esemark*0.50);
                                      } 

                                       $ese_total=$stus['CIA']+$tpesemark;

                                      if($ese_total<$rows['total_minimum_pass'] || $tpesemark<$rows['ESE_min'])
                                      {
                                        $subject_fail= $subject_fail+1;
                                      }
                                      else
                                      {
                                        $subject_pass= $subject_pass+1;
                                      }
                                            
                                  }

                    $sn++;
                }
                
                 $tot=0;

                $course_result_table .= '<tr><td colspan="13" style="text-align:center;"><b>Over All Pass: '.$tot.' &nbsp; Over All Pass Percent: '.round((($tot / $total_enroll_stu) * 100),2).'% </b></td></tr>';
                $course_result_table .= '</table>';
                if (isset($_SESSION['programme_analysis_printtemp1'])) {
                    unset($_SESSION['programme_analysis_printtemp1']);
                }
              echo  $_SESSION['programme_analysis_printtemp1'] = $course_result_table;
                

        ?>
    </div>
<?php }?>
    <?php ActiveForm::end(); ?>

    

    </div>
    </div>
    </div>