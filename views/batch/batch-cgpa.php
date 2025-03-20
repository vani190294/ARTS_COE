<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\HallAllocate;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use yii\db\Query;
use app\models\MarkEntry;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="CGPA REPORT";

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
        ]); 
    ?>

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
  <?= $form->field($degree, 'degree_type')->dropDownList($degree->getDegreeType(), ['prompt' => Yii::t('app', '--- Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).' Type ---')]) ?>
        </div>

        <div class="col-sm-2"><br>
                        <label class="control-label">
                            <input type="checkbox" name="with_umis">With Arrear
                        </label>

                    </div>
      </div>
        
       <div class="col-xs-12 col-sm-12 col-lg-12">

            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Show Me', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['batch/reval-grade-report']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
   


    <div class="col-xs-12 col-sm-12 col-lg-12 reval_batch_report">
        
        <div id = "reval_batch_report_ex" >                
           
        </div>
      
    </div>

<?php ActiveForm::end(); ?>

</div>
</div>
</div>


<?php
if(isset($stu1))
{
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $data ='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';                 
    $data.='<tr>
                <td>
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan=3> 
                    <center><b><font size="3px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    <center>'.$org_tagline.'</center> 
                </td>
                <td>  
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
            </tr>';

            $data.=
            '<tr>
                <th>S.No</th>
                <th>RegisterNumber</th>     
                <th>BranchCode</th>
                <th>CGPA</th>
               
            </tr>';
            $year=2024;
                    $month=29;
                     $failed_stus = Yii::$app->db->createCommand('SELECT student_map_id FROM coe_mark_entry_master where  year_of_passing="" ')->queryAll();
        $notIn = array_filter(['']);
        foreach ($failed_stus as $key => $fails) {
            $notIn[$fails['student_map_id']]=$fails['student_map_id'];
        }
            
            $prgm_code='';
            $sn=1;
          
           foreach($stu1 as $value) 
           {
               
                

            if( $with_arrear=='on')
            {
                 $get_stu_query = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,G.semester,H.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance,round (sum(H.credit_points*F.grade_point)/sum(H.credit_points),5) as total FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mark_entry_master as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_subjects_mapping as G ON G.coe_subjects_mapping_id=F.subject_map_id JOIN coe_subjects as H ON H.coe_subjects_id=G.subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id where C.coe_batch_id='".$batch."' AND B.coe_student_mapping_id  IN(select student_map_id from coe_mark_entry_master where year_of_passing='' and subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where student_map_id=F.student_map_id and result like '%pass%') and subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where student_map_id=F.student_map_id and result like '%COMPLETED%')) and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') AND F.grade_name not IN ('U','wh','AB','ab','WH','ra','wd','WD') and B.course_batch_mapping_id='".$value['course_batch_mapping_id']."'  group by A.register_number order by A.register_number, G.semester, paper_no";
              $students_cgpa = Yii::$app->db->createCommand($get_stu_query)->queryAll();


            }
            else
            {

                 $get_stu_query = "SELECT F.student_map_id,A.register_number,A.dob,A.name,A.gender,D.degree_code,D.degree_name,E.programme_name,H.subject_code,C.regulation_year,H.subject_name,G.semester,H.credit_points,H.CIA_max,H.ESE_max,F.grade_point,F.grade_name,F.year,F.month,B.course_batch_mapping_id,F.year_of_passing,paper_no,F.result,max(F.year_of_passing) as last_appearance,round (sum(H.credit_points*F.grade_point)/sum(H.credit_points),5) as total FROM coe_student as A JOIN coe_student_mapping as B ON B.student_rel_id=A.coe_student_id JOIN coe_bat_deg_reg as C ON C.coe_bat_deg_reg_id=B.course_batch_mapping_id JOIN coe_degree as D ON D.coe_degree_id=C.coe_degree_id JOIN coe_programme as E ON E.coe_programme_id=C.coe_programme_id JOIN coe_mark_entry_master as F ON F.student_map_id=B.coe_student_mapping_id JOIN coe_subjects_mapping as G ON G.coe_subjects_mapping_id=F.subject_map_id JOIN coe_subjects as H ON H.coe_subjects_id=G.subject_id JOIN coe_batch as I ON I.coe_batch_id=C.coe_batch_id where C.coe_batch_id='".$batch."' AND B.coe_student_mapping_id NOT IN(select student_map_id from coe_mark_entry_master where year_of_passing='' and subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where student_map_id=F.student_map_id and result like '%pass%') and subject_map_id NOT IN(select subject_map_id FROM coe_mark_entry_master where student_map_id=F.student_map_id and result like '%COMPLETED%')) and status_category_type_id NOT IN('".$det_cat_type."','".$det_disc_type."') AND F.grade_name not IN ('U','wh','AB','ab','WH','ra','wd','WD') and B.course_batch_mapping_id='".$value['course_batch_mapping_id']."'  group by A.register_number order by A.register_number, G.semester, paper_no";
               $students_cgpa = Yii::$app->db->createCommand($get_stu_query)->queryAll();


            }
            foreach($students_cgpa as $rows)
            {
                        $sem_verify = ConfigUtilities::SemCaluclation($year,$month,$rows['course_batch_mapping_id']);
                        $cgpa_calc = ConfigUtilities::getCgpaCaluclation( $year,$month,$rows['course_batch_mapping_id'],$rows['student_map_id'],$sem_verify);
                         $data.='<tr>';
                        $data.= '<td align="left">'.$sn.' </td>';
                        $data.='<td align="left">'.$rows["register_number"].'</td>';
                        $data.='<td align="left">'.$rows["degree_code"].'-'.$rows["programme_name"].'</td>';
                        $data.='<td align="center">'.$cgpa_calc['part_3_cgpa'].'</td>';
                          $sn++;
                    
                        
                       
                $data.='</tr>';
            }
    }
    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['sub_arrear_count']))
    {
        unset($_SESSION['sub_arrear_count']);
    }
    $_SESSION['sub_arrear_count'] = $data;
    echo '<div class="box box-primary">
            <div class="box-body">
                <div class="row" >';
    echo '<div class="col-xs-12">';
    echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('cgpa-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
    echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('cgpa-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
    echo '<div class="col-xs-12" >'.$data.'</div>
                </div>
            </div>
          </div>'; 
}
?>
