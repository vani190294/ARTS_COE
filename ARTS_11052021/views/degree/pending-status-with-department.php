<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\Categorytype;
use app\models\ExamTimetable;
use app\models\HallAllocate;
use app\models\AbsentEntry;
use app\models\MarkEntry;
use app\models\Nominal;
use app\models\MarkEntryMaster;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use yii\db\Query;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="COE STATUS";
$year= isset($_POST['MarkEntryMaster']['year'])?$_POST['MarkEntryMaster']['year']:date('Y');
$month= isset($_POST['month'])?$_POST['month']:'';
$border_marks= isset($_POST['MarkEntryMaster']['result'])?$_POST['MarkEntryMaster']['result']:'';
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>
<input type="hidden" value="All" name="section" id='stu_section_select' class='form-control student_disable' />
<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
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
                <?= $form->field($model, 'year')->textInput(['name'=>'exam_year','value'=>date('Y'),'id'=>'mark_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => ConfigUtilities::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',      
                            'name'=>'exam_month',                      
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'mark_type')->widget(
                    Select2::classname(), [  
                        'data' => [1,2,3,4,5,6,7,8],                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Semester ----',
                            'name'=>'semester',                      
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('Semester') ?>
        </div>
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform"> <br />
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['degree/pending-status-with-department']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        
        </div>
       
    <?php ActiveForm::end(); ?>
    
    <?php 

        if(isset($content_1))
        {
            $html = $header_1 = $body = $header = $footer = '';
           
            require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
            if($file_content_available=="Yes")
            {

            }
            else
            {
                Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');
            }
            echo '
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                // echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('pending-count-report-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('pending-count-report-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '
                            </div>
                        </div>
                      ';
$header .='<table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" id = "student_import_results">';            
            
            $header .= '              
                    <tr>
                      <td  colspan=2 align="center"> 
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=9 align="center"> 
                          <center><b><font size="6px">'.$org_name.'</font></b></center>
                          <center> <font size="3px">'.$org_address.'</font></center>
                          <center> Phone : <b>'.$org_phone.'</b></center>
                          <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                     </td>
                      <td width="100" height="100" align="center">  
                        <img class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>';
            $month_name = Categorytype::findOne($_POST['exam_month']);
            $header .=' <tr>
                      <th style="padding: 10px;" colspan=12 align="center"><h2>
                        COE STATUS REPORT '.strtoupper($month_name->description).' '.$_POST['exam_year'].'</h2>
                      </th>
                    </tr>
            <tr>
                <th width="50px" >SNO</th>
                <th width="100px" >'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).'</th>
                <th width="100px" >'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).'</th>
                <th width="100px" >SEMESTER</th>
                <th width="100px" >'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").'</th>
                <th width="200px"  colspan=4>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME").'</th>
                <th width="150px"  colspan=3>
                    ENTRY STATUS
                    <table width="30%" style="overflow-x:auto; color: #000 !important;" border="1"  class="table table-bordered table-responsive table-hover table-danger" >
                        <tr>
                            <td width="100px" >CIA</td>
                            <td width="100px" >ESE</td>
                            <td width="100px" >'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)).' </td>
                            <td width="100px" >GALLEY</td>
                            <td width="100px" >'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_ABSENT)).' </td> 
                            <td width="100px" >ELECTIVE COUNT</td> 
                            <td width="100px" >MISSING ENTRY</td>                           
                        </tr>
                    </table>
                </th>
                ';
            
            $header .='</tr></thead>'; 

            $body .="<tbody>";
            $cia_cat_id = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Internal%' OR category_type like '%CIA%'")->queryScalar();
            $elective = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%elective%'")->queryScalar();
                $sno=1;
                    foreach ($content_1 as $values) 
                    {   
                        $ele_count = '-';
                        if($values['subject_type_id']==$elective)
                        {
                            $ele_count = Nominal::find()->where(['coe_subjects_id'=>$values['subject_id'],'semester'=>$values['semester'],'course_batch_mapping_id'=>$values['batch_mapping_id']])->count();
                            $checkStuInfo = new Query();
                            $checkStuInfo->select('*')
                                ->from('coe_subjects_mapping as A')   
                                ->JOIN('JOIN','coe_subjects as F','F.coe_subjects_id=A.subject_id')
                                ->JOIN('JOIN','coe_student_mapping as D','D.course_batch_mapping_id=A.batch_mapping_id')
                                ->JOIN('JOIN','coe_student as E','E.coe_student_id=D.student_rel_id')
                                ->JOIN('JOIN','coe_nominal as C','C.coe_subjects_id=A.subject_id and C.semester=A.semester and C.coe_student_id=E.coe_student_id and C.course_batch_mapping_id=A.batch_mapping_id and C.course_batch_mapping_id=D.course_batch_mapping_id')
                                ->JOIN('JOIN','coe_mark_entry as B','B.subject_map_id=A.coe_subjects_mapping_id and B.student_map_id=D.coe_student_mapping_id')
                                ->Where(['subject_map_id'=>$values['sub_id'],'year'=>$_POST['exam_year'],'category_type_id'=>$cia_cat_id,'A.semester'=>$values['semester'],'C.semester'=>$values['semester']])->groupBy('register_number');
                            $count_int = $checkStuInfo->createCommand()->queryAll();
                        }
                        else
                        {
                            $count_int = MarkEntry::find()->where(['subject_map_id'=>$values['sub_id'],'year'=>$_POST['exam_year'],'category_type_id'=>$cia_cat_id])->all();
                        }

                        $int_count = count($count_int);
                        $ese_count = MarkEntryMaster::find()->where(['subject_map_id'=>$values['sub_id'],'year'=>$_POST['exam_year'],'month'=>$_POST['exam_month']])->count();
                        $exam_stat = ExamTimetable::find()->where(['subject_mapping_id'=>$values['sub_id'],'exam_year'=>$_POST['exam_year'],'exam_month'=>$_POST['exam_month']])->one();
                        $galley_count = HallAllocate::find()->where(['exam_timetable_id'=>$exam_stat['coe_exam_timetable_id'],'year'=>$_POST['exam_year'],'month'=>$_POST['exam_month']])->count();
                        $ab_stat = AbsentEntry::find()->where(['exam_subject_id'=>$values['sub_id'],'exam_year'=>$_POST['exam_year'],'exam_month'=>$_POST['exam_month']])->all();
                        $ab_val = !empty($ab_stat) ? count($ab_stat) : "-";
                        $g_val = !empty($galley_count)?$galley_count:"-";
                        $e_stat = !empty($exam_stat)?'YES':'NO';
                        $ese_c = !empty($ese_count)?$ese_count:"-"; $i_con = !empty($int_count)?$int_count:"-";
                        
                        $markEntryNotList = Yii::$app->db->createCommand('SELECT * FROM stu_info where stu_map_id in(SELECT student_map_id FROM coe_mark_entry where subject_map_id="'.$values['sub_id'].'" and student_map_id NOT IN(SELECT student_map_id FROM coe_mark_entry_master where subject_map_id="'.$values['sub_id'].'")) ')->queryAll();
                        $missingEntryList = '-';
                        if(count($markEntryNotList)<=10)
                        {
                            if(!empty($markEntryNotList))
                            {
                                $missingEntryList = '';
                                foreach ($markEntryNotList as $key => $missingRec) 
                                {
                                    $missingEntryList .=$missingRec['reg_num'].", ";
                                }
                                $missingEntryList =trim($missingEntryList,", ");
                            }
                        }

                        $body .="<tr><td width='50px' >".$sno."</td>";
                        $body .= "<td width='100px' >".$values['batch_name']."</td>";
                        $body .= "<td width='100px' >".$values['degree_code']."-".$values['programme_code']."</td>";
                        $body .= "<td width='100px' >".$values['semester']."</td>";
                        $body .= "<td width='100px' >".$values['subject_code']."</td>";
                        $body .= "<td  width='100px'  colspan=4>".$values['subject_name']."</td>";
                        $body .= "
                        <td colspan=3>
                            <table  width='30%'  style='overflow-x:auto;' border='1'  class='table table-bordered table-responsive bulk_edit_table table-hover' >
                                 <tr>
                                    <td width='100px' >$i_con</td>
                                    <td width='100px' >$ese_c</td>
                                    <td width='100px' >$e_stat</td>
                                    <td width='100px' >$g_val</td>
                                    <td width='150px' >$ab_val</td>
                                    <td width='150px' >$ele_count</td>
                                    <td width='150px' >$missingEntryList</td>             
                                </tr>
                            </table>
                        </td>";
                        $body .= "</tr>"; $sno++;
                        
                    }
                $body .='</tbody></table>';
                $html = $header.$body;
                $html_1 = $html;
                if(isset($_SESSION['PENDING_status']))
                {
                    unset($_SESSION['PENDING_status']);
                }
                $_SESSION['PENDING_status'] = $html_1;
                echo $html;
                 
        }


    ?>
</div>
</div>
</div>