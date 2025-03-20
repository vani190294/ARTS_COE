<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\ExamTimetable;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;


echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="BORDER LINE MARKS";
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
                <?= $form->field($model, 'year')->textInput(['value'=>$year,'id'=>'mark_year']) ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'data' => $model::getMonth(),
                        'options' => [                            
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'name'=>'month',
                            'value'=>$month
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'result')->textInput(['value'=>$border_marks])->label('Border Marks') ?>
            </div>
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform"> <br />
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/borderline-marks']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        
        </div>
       
    <?php ActiveForm::end(); ?>
    
    <?php 

        if(isset($fetched_data))
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
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('border-line-marks-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));

                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' .strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE))." WISE ",array('border-line-marks-coursewise'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-primary', 'style'=>'color:#fff'));

                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' .strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE))." WISE ",array('border-line-marks-coursewise-excel'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-success', 'style'=>'color:#fff'));

                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('excel-border-line-marks','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '
                            </div>
                        </div>
                      ';
$header .='<table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" id = "student_import_results">';
               
                $prev_subject_code = '';
                
                foreach ($fetched_data as $value) 
                {
                    $exam_year = $value['year'];
                    $border = $value['borderLine'];
                    $ESE_min = $value['ESE_min'];
                    $month = $value['month'];
                   if($prev_subject_code!=$value['subject_code'])
                   {
                        $prev_subject_code=$value['subject_code'];
                        $subject_codes[$value['subject_code']] = $value['subject_code'];
                        
                        
                   }
                }
                    array_unique($subject_codes);

                    
            $count_of_sub_codes = count($subject_codes); 
            
            $header_1 .= '<thead> <tr>
                    <td style="border: none;" colspan='.($count_of_sub_codes+2).'>
                    <table width="100%" align="center" border="0" >                    
                    <tr>
                      <td align="center"> 
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan='.($count_of_sub_codes).' align="center"> 
                          <center><b><font size="6px">'.$org_name.'</font></b></center>
                          <center> <font size="3px">'.$org_address.'</font></center>
                          <center> Phone : <b>'.$org_phone.'</b></center>
                          <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>
                    
                    </table></td></tr></thead>';
            $footer .='<thead> 
            <tr>
                <th>SNO</th>
                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." Name").'</th>';
            foreach ($subject_codes as $print) 
            {
                $footer .= "<th>".$print."</th>";
            } 
            $footer .='</tr></thead>'; 

            $body .="<tbody>";
          
            $sno=1;
            foreach ($fetched_data as $values) 
            {

                $body .="<tr><td>".$sno."</td>";
                $body .= "<td><input type='hidden' id='get_count_degree_".$sno."' value='".$values['batch_mapping_id']."' />".$values['Degree']."</td>";
                foreach ($subject_codes as $subjes) 
                { 
                   if($values['subject_code']==$subjes)
                   {
                        $body .= "<td>
                        <input type='hidden' id='get_count_subject_".$sno."' value='".$values['subject_map_id']."' />
                        <input type='hidden' id='count_eseMin_".$sno."' value='".$values['ESE_min']."' />
                        <a href='#' class='btn btn-danger' id='anchor_".$sno."' onmouseover='return getArrearStudetails(this.id); '  ><b>".$values['count']."</b></a>
                        </td>";
                   }
                   else
                   {
                        $body .= "<td>--</td>";
                   }
                }
                $body .= "</tr>"; $sno++;
            }
            $body .='</tbody></table>';
            $html = $header.$footer.$body;
            $html_1 = $header.$header_1.$footer.$body;
            if(isset($_SESSION['borderlinemarks']))
            {
                unset($_SESSION['borderlinemarks']);
                unset($_SESSION['border_year']);
                unset($_SESSION['border_month']);
                unset($_SESSION['border_marks']);
            }
            
            $_SESSION['borderlinemarks'] = $html_1;
            echo $html;
            $_SESSION['border_year'] = $exam_year;
            $_SESSION['border_month'] = $month;
            $_SESSION['border_marks'] = $border;
                echo '<input type="hidden" id="count_mark_year" name="border_year" value="'.$exam_year.'" />
            <input type="hidden" id="count_exam_month" name="border_month" value="'.$month.'" />
            <input type="hidden" id="count_boderLine" value="'.$border.'" name="border_marks" />'; 
                 
        }


    ?>
            
</div>
</div>
</div>

<?php 


$this->registerJsFile(    
    '@web/style/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/style/bower_components/datatables.net/js/jquery.dataTables.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJs(<<<JS
    $(function () {
    $('#student_import_results').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
       'scrollY': '300',
       "scrollX": true,
       "responsive": "true",
       "pageLength": "200",
       
       
    })
  })
JS
);

?>
