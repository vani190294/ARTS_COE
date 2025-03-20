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
    /* 
    *   Already Defined Variables from the above included file
    *   $org_name,$org_email,$org_phone,$org_web,$org_address,$org_tagline, 
    *   use these variables for application
    *   use $file_content_available="Yes" for Content Status of the Organisation
    */
    if($file_content_available=="Yes")
        {
            
            $previous_subject_code= "";
            $previous_programme_name= "";
            $previous_reg_number = "";
            $html = "";
            $header = "";
            $body ="";
            $footer = "";
            $print_subjects =1;
            $print_register_number="";
            $new_stu_flag=0;
            $print_stu_data="";
            
            //$countStuVal = count($coursewisearrear);
            $stu_print_vals = 0;
                
              $header .= '<table border=1  width="100%" class="table table-striped table-responsive table-hover table-bordered"  align="center">';
                   
                    $header .="
                    <tr height=40px>
                    
                      <th align='center'>S.no</th>
                     <th align='center'>RegisterNumber</th>
                       <th align='center'>Branch</th>
                      <th align='center'>CGPA</th></tr>";
                      $i=1;
                   
                    $year=2024;
                    $month=29;
                     $failed_stus = Yii::$app->db->createCommand('SELECT student_map_id FROM coe_mark_entry_master where year="'.$year.'" and month="'.$month.'" and mark_type="27" and year_of_passing="" ')->queryAll();
        $notIn = array_filter(['']);
        foreach ($failed_stus as $key => $fails) {
            $notIn[$fails['student_map_id']]=$fails['student_map_id'];
        }
                  foreach($stu1 as $value) 
                { 
             $query_map_id = new Query();
            $query_map_id->select(['distinct (b.register_number) as register_number','programme_code','degree_code','batch_name','degree_type','programme_name'])
                ->from('coe_student_mapping a')
                ->join('JOIN', 'coe_student b', 'a.student_rel_id=b.coe_student_id')
                ->join('JOIN', 'coe_bat_deg_reg c', 'c.coe_bat_deg_reg_id=a.course_batch_mapping_id')
                ->join('JOIN', 'coe_batch d', 'd.coe_batch_id=c.coe_batch_id')
                ->join('JOIN', 'coe_degree f', 'f.coe_degree_id=c.coe_degree_id')
                ->join('JOIN', 'coe_programme g', 'g.coe_programme_id=c.coe_programme_id')
                ->join('JOIN', 'coe_mark_entry_master e', 'e.student_map_id=a.coe_student_mapping_id')
                ->where(['d.coe_batch_id' => $batch,'c.coe_batch_id' => $batch, 'b.student_status' => 'Active','e.month'=>$month,'e.mark_type'=>27,'e.year'=>$year,'result'=>'Pass','a.course_batch_mapping_id'=>$value['course_batch_mapping_id']])
                ->andWhere(['<>', 'status_category_type_id', $det_cat_type])
                ->andWhere(['<>', 'status_category_type_id', $det_disc_type])
                ->andWhere(['<>', 'status_category_type_id', $det_long_absent])
                ->andWhere(['NOT IN', 'student_map_id', $notIn])
               
                ->orderBy('degree_code,programme_code,register_number');
            $students_map_id_pass = $query_map_id->createCommand()->queryAll();
            foreach($students_map_id_pass as $rows)
            {
                    $body .='<tr height=40px>
                    <td align="center">'.$i.'</td>
                    <td align="center">'.$rows["register_number"].'</td>
                    <td align="center">'.$rows["degree_code"].'-'.$rows["programme_name"].'</td>
                    <td align="center"></td>';
                    

                    
                
                     $body .='</tr></table>';
                     $i++;
                 }
             }
              

              $html = $header .$body; 
              //print_r($html );exit;
                $print_stu_data .= $html;
                 if(isset($_SESSION['cia_mark_list'])){ unset($_SESSION['cia_mark_list']);}
                $_SESSION['cia_mark_list'] = $print_stu_data;

                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('nad-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('nad-web-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" style="overflow-x: auto; overflow-y: auto; height: 500px !important;">'.$print_stu_data.'</div>
                            </div>
                        </div>
                      </div>'; 
                
               
        }// If no Content found for Institution
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');            
        }
    } 
?>
