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
use app\models\HallAllocate;
use yii\db\Query;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="Subject Range";

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
                <?= $form->field($model, 'year')->textInput(['id'=>'course_year','name'=>'year','value'=>date("Y")]) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),   
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id'=>'exam_month1',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                    ?>
            </div>
            
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>


        
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <br />
            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">

                <?= Html::submitButton( 'Submit', ['class' =>  'btn btn-success']) ?>
                <?= Html::a("Reset", Url::toRoute(['reports/subject-range']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">&nbsp;</div>
    </div>


    <?php ActiveForm::end(); ?>

    

    </div>
    </div>
    </div>


<?php 
if(!empty($content_data))
{

?>


    
<div class="box box-primary" >
    <div class="box-body">
        <div class="row" >
            <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-3 col-sm-10 col-lg-10">
        </div>    
        <div class="col-xs-3 col-sm-2 col-lg-2">
            <?php 
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('excel-subject-range'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-info', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/reports/subject-range-pdf'], [
                'class'=>'pull-right btn btn-primary', 
                'target'=>'_blank', 
                'data-toggle'=>'tooltip', 
                'title'=>'Will open the generated PDF file in a new window'
                ]);
            ?>
        </div>
    </div>


            <div class="col-xs-12" style="padding-top: 30px;" >
                <div class="col-lg-12" style="text-align:right !important;"> 
                   
                    <?php echo $print_excel; ?> 
                   
                    <?php echo $print_pdf; ?> 
                    
                </div>
                <div class="col-lg-12"  style="overflow-x:auto;"> 
                    <?php 
                    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                    $html = $body ='';
                    $monthname = Yii::$app->db->createCommand("select category_type from coe_category_type where coe_category_type_id='" . $_POST['month'] . "'")->queryScalar();

                    $explode=explode("/", $monthname);

                    if($explode[1]=='Nov')
                    {
                        $explode='Nov/Dec';
                    }
                    else
                    {
                        $explode=$monthname;
                    }

                    $html .= '<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" ><tr>
                                    <td align="center" style="border-right:0px; border-bottom:0px" >  
                                            <img width="100" height="100" class="img-responsive" src="' . Yii::getAlias("@web") . '/images/skacas.png" alt="College Logo 2">
                                    </td>
                                    <td colspan="3" align="center" style="color: #2173bc; border-left:0px; border-right:0px; border-bottom:0px; font-size: 14px !important;"> 
                                                <center><b><font size="6px">' . $org_name . '</font></b></center>
                                                <center><b>(An Autonomous Insitution)</b></center>
                                                <center><b>Approved By AICTE and Affiliated to Anna University, Chennai</b></center>
                                                <center><b>Accredited by NAAC with "A" Grade</b></center>
                                                <center><b>' . $org_address . '</b></center>
                                                 
                                     </td>

                                    <td align="center" style="border-left:0px; border-bottom:0px"> 
                                                    <img class="img-responsive" width="100" height="100" src="' . Yii::getAlias("@web") . '/images/main_logo.png" alt="College Logo">
                                    </td>
                                           
                            </tr>';
                    $html .= '<tr><td colspan="5" align="center" style=" color: #2173bc; border-top:0px; border-bottom:0px; padding-bottom:10px; font-size: 10px !important;"><b>7278 - SUBJECT WISE GRADES AND GRADES RANGE - '.strtoupper($explode).' '.$_POST['year'].' EXAMINATIONS</b></td></tr></table>';
                      
                    $body .= '<table  style="overflow-x:auto; " width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >';  
                    $i=0;  
                    
                    foreach ($content_data as $key => $value1) 
                    {
                        //print_r($value1["content"]); exit;
                        if(!empty($value1["content"]))
                        {
                            $yrs=$value1["year"];
                           
                            $j=0; $pagebreak=1; $pagebreakout=0;
                            foreach ($value1["content"] as $key => $value) 
                            {
                                $semester = ConfigUtilities::SemCaluclation($_POST['year'],$_POST['month'],$value['batch_mapping_id']);
                                
                                $reg_year = Yii::$app->db->createCommand("select regulation_year from coe_bat_deg_reg where coe_batch_id='" . $value['coe_batch_id'] . "' and coe_bat_deg_reg_id='". $value['batch_mapping_id']."'   ")->queryScalar();
                                
                                $query_gr = new Query();
                                $query_gr->select('grade_name')
                                    ->from('coe_regulation')
                                    ->where(['regulation_year' => $reg_year])
                                    ->andWhere(['NOT', ['grade_name' => 'U']])->andWhere(['NOT', ['grade_name' => '']])->groupBy('grade_name')->orderBy('grade_point DESC');
                                $grade_name = $query_gr->createCommand()->queryAll();

                                $sub_list = Yii::$app->db->createCommand("select a.subject_code,c.coe_subjects_mapping_id from coe_grade_range a JOIN coe_subjects b on b.subject_code=a.subject_code JOIN coe_subjects_mapping c on c.subject_id=b.coe_subjects_id where a.year='" . $_POST['year'] . "' AND a.month='" . $_POST['month'] . "' AND c.batch_mapping_id='" . $value['batch_mapping_id'] . "' AND c.semester='".$semester."' group by a.subject_code,c.paper_no ORDER BY paper_no")->queryAll();

                                 $colspan=count($sub_list)*count($grade_name);

                                if($j==0)
                                {
                                    $body .=' <tr>
                                    <td colspan="'.$colspan.'" align="center" style="background-color: #2173bc; color: #fff;"><b>BATCH: '.$value1["year"].' SEMESTER: '.$semester.'</b></td>
                                    </tr> ';
                                    $pagebreakout=4;
                                    $pagebreak=1;
                                }

                                if($pagebreak==$pagebreakout)
                                {
                                    $body.='</table>';
                                    $body.='<pagebreak />';
                                    $body .= '<table  style="overflow-x:auto; " width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >'; 
                                    $pagebreakout=5;
                                    $pagebreak=1;
                                }

                                if($j>0 && !empty($sub_list))
                                {
                                    $body .=' <tr>
                                        <td colspan="'.$colspan.'" >&nbsp;</td>
                                    </tr> ';
                                }

                                if(($value['programme_code']!='P201' && $value['programme_code']!='P203' && $value['programme_code']!='P204' && $value['programme_code']!='P205'))
                                {
                                     $sub_list = Yii::$app->db->createCommand("select a.subject_code,c.coe_subjects_mapping_id from coe_grade_range a JOIN coe_subjects b on b.subject_code=a.subject_code JOIN coe_subjects_mapping c on c.subject_id=b.coe_subjects_id where a.year='" . $_POST['year'] . "' AND a.month='" . $_POST['month'] . "' AND c.batch_mapping_id='" . $value['batch_mapping_id'] . "' AND c.semester='".$semester."' group by a.subject_code ORDER BY paper_no")->queryAll();
                                     
                                if(!empty($sub_list))
                                {
                                    $body .=' <tr>
                                        <td colspan="'.$colspan.'" align="center" style="font-size: 13px !important; background-color: #2173bc; color: #fff;"><b>'.strtoupper($value['degree_code']).' '.strtoupper($value['programme_name']).'</b></td>
                                    </tr> ';

                                $body.='<tr><td align="center" style="border: 1px solid #2173bc !important;">';

                                $body .= '<table width="100%" border=1 align="center">
                                        <tr><td align="center" rowspan="2"><b>Grade Name</b></td>';

                                foreach ($sub_list as $sub) 
                                {
                                    $getSubsInfo = new Query();
                                    $getSubsInfo->select(['A.*'])
                                                ->from('coe_mark_entry_master_temp as A')
                                                ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=A.subject_map_id')
                                                ->join('JOIN', 'coe_bat_deg_reg as C', 'C.coe_bat_deg_reg_id=B.batch_mapping_id')
                                                ->join('JOIN', 'coe_degree as D', 'D.coe_degree_id=C.coe_degree_id')
                                      
                                               ->where(['A.year' => $_POST['year'], 'A.month' => $_POST['month'],'A.mark_type'=>27,'A.subject_map_id'=>$sub['coe_subjects_mapping_id']]);                   
                                    $check_data_exists = $getSubsInfo->createCommand()->queryAll(); 
                                    if(!empty($check_data_exists))
                                    {
                                        $body .= '<td colspan="2" align="center"><b>'. $sub['subject_code'] . '</b></td>';
                                    }
                                }
                                
                                $body.='</tr><tr>';

                                foreach ($sub_list as $sub) 
                                {
                                     $getSubsInfo = new Query();
                                    $getSubsInfo->select(['A.*'])
                                                ->from('coe_mark_entry_master_temp as A')
                                                ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=A.subject_map_id')
                                                ->join('JOIN', 'coe_bat_deg_reg as C', 'C.coe_bat_deg_reg_id=B.batch_mapping_id')
                                                ->join('JOIN', 'coe_degree as D', 'D.coe_degree_id=C.coe_degree_id')
                                      
                                               ->where(['A.year' => $_POST['year'], 'A.month' => $_POST['month'],'A.mark_type'=>27,'A.subject_map_id'=>$sub['coe_subjects_mapping_id']]);                   
                                    $check_data_exists = $getSubsInfo->createCommand()->queryAll(); 
                                    if(!empty($check_data_exists))
                                    {
                                    $body .= '<td align="center"><b>Min Mark</b></td>';
                                    $body .= '<td align="center"><b>Max Mark</b></td>';
                                    }
                                }
                                $body.='</tr>';

                               //$body.='<tr>';
                                $temp_grade='';
                                foreach ($grade_name as $grade) 
                                {
                                    $body .= '<tr><td align="center"><b>'. $grade['grade_name'] . '</b></td>';
                                    foreach ($sub_list as $sub) 
                                    {
                                        $getSubsInfo = new Query();
                                        $getSubsInfo->select(['A.*'])
                                                ->from('coe_mark_entry_master_temp as A')
                                                ->join('JOIN', 'coe_subjects_mapping as B', 'B.coe_subjects_mapping_id=A.subject_map_id')
                                                ->join('JOIN', 'coe_bat_deg_reg as C', 'C.coe_bat_deg_reg_id=B.batch_mapping_id')
                                                ->join('JOIN', 'coe_degree as D', 'D.coe_degree_id=C.coe_degree_id')
                                               ->where(['A.year' => $_POST['year'], 'A.month' => $_POST['month'],'A.mark_type'=>27,'A.subject_map_id'=>$sub['coe_subjects_mapping_id']]);                   
                                        $check_data_exists = $getSubsInfo->createCommand()->queryAll(); 
                                        if(!empty($check_data_exists))
                                        {

                                        $sub_list1 = Yii::$app->db->createCommand("select min_mark, max_mark from coe_grade_range where year='" . $_POST['year'] . "' AND month='" . $_POST['month'] . "' AND subject_code='".$sub['subject_code']."' AND grade_name='" . $grade['grade_name'] . "'")->queryAll();

                                        if(!empty($sub_list1))
                                        {
                                            foreach ($sub_list1 as $sub1) 
                                            {
                                                $body .= '<td align="center"><b>'. $sub1['min_mark'].'</b></td>';
                                                $body .= '<td align="center"><b>'. $sub1['max_mark'].'</b></td>';
                                            }
                                        }
                                        else
                                        {
                                            $body .= '<td align="center"></td>';
                                            $body .= '<td align="center"></td>';
                                        }
                                        }
                                        
                                    }
                                    $body.='</tr>';
                                }
                                $body.='</tr></table>';
                                 $body.='</tb></tr>';
                                }
                                }
                                $j++;
                                $pagebreak++;
                            }
                           
                        }
                        $semester=$semester+2;
                        $i++;
                       
                    }

                  
                    

                        echo $html .='<div style="font-weight:bold !important"> '.$body.'</table></div> '; 

                  
                        if(isset($_SESSION['subject_range_printtemp']))
                        {
                            unset($_SESSION['subject_range_printtemp']);
                        }
                        $_SESSION['subject_range_printtemp'] = $html;
                   

                    ?>


                </div>
            </div>
         </div>
    </div>
</div>

<?php 
}
?>