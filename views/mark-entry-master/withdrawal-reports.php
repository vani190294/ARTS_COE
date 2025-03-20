<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\Categorytype;
use app\models\ExamTimetable;
use app\models\MarkEntryMaster;
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

$this->title="WITHDRAWAL REPORTS";
$year= isset($_POST['MarkEntryMaster']['year'])?$_POST['MarkEntryMaster']['year']:date('Y');
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
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform"> <br />
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/withdrawal-reports']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
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
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('withdrawal-reports-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('withdrawal-reports-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '
                            </div>
                        </div>
                      ';
$header .='<table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" id = "student_import_results">';            
            
            $header .= '              
                    <tr>
                      <td align="center"> 
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=8 align="center"> 
                          <center><b><font size="6px">'.$org_name.'</font></b></center>
                          <center> <font size="3px">'.$org_address.'</font></center>
                          <center> Phone : <b>'.$org_phone.'</b></center>
                          <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>';
            $header .=' <tr>
                      <th colspan=10 align="center"><h2>
                        WITHDRAWAL REPORT FOR '.$_POST['MarkEntryMaster']['year'].'</h2>
                      </th>
                    </tr>
            <tr>
                <th>SNO</th>
                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)." Name").'</th>
                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)." Name").'</th>
                <th>REGISTER NUMBER</th>
                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").'</th>
                <th colspan=2>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME").'</th>
                <th>SEMESTER</th>
                <th>YEAR</th>
                <th>MONTH</th>
                
                ';
            
            $header .='</tr></thead>'; 

            $body .="<tbody>";
          
                $sno=1;
                    foreach ($fetched_data as $values) 
                    {
                        $body .="<tr><td>".$sno."</td>";
                        $body .= "<td>".$values['batch_name']."</td>";
                        $body .= "<td>".$values['Degree']."</td>";
                        $body .= "<td>".$values['register_number']."</td>";
                        $body .= "<td>".$values['subject_code']."</td>";
                        $body .= "<td colspan=2>".$values['subject_name']."</td>";
                        $body .= "<td>".$values['semester']."</td>";
                        $body .= "<td>".$values['year']."</td>";
                        $body .= "<td>".$values['month']."</td>";
                        
                        $body .= "</tr>"; $sno++;
                    }
                $body .='</tbody></table>';
                $html = $header.$body;
                $html_1 = $html;
                if(isset($_SESSION['withdrawa_report_sem']))
                {
                    unset($_SESSION['withdrawa_report_sem']);
                }               
                $_SESSION['withdrawa_report_sem'] = $html_1;
                

                echo $html;
                 
        }


    ?>
</div>
</div>
</div>