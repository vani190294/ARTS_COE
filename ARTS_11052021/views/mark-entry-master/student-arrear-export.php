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

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title=ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Arrear Subjects";
$reg_num = '';
if(isset($_SESSION['stu_arrear_subject']))
{
    $reg_num = $_SESSION['stu_arrear_subject'];
}


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
            <div class="col-lg-3 col-sm-3">
            	<?= $form->field($model, 'register_number')->textInput(['id'=>'stu_arrear_subjects','required'=>'required','value'=>$reg_num]) ?>
            </div>
       		<div class="form-group col-lg-3 col-sm-3"><br />
                <input type="submit" id="student_arrear_data" name="markviewbutton" class="btn btn-success" value="Submit">
            </div>       	
        </div>
    </div>

     <?php ActiveForm::end(); ?>
         <?php 

        if(isset($fetched_data) && !empty($fetched_data))
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
            echo '<div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('student-arrear-reports-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('student-arrear-reports-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '</div>
                        </div>
                      ';
$header .='<table style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" id = "student_import_results">';            
            
            $header .= '              
                    <tr>
                      <td align="center"> 
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=5 align="center"> 
                          <center><b><font size="6px">'.$org_name.'</font></b></center>
                          <center> <font size="3px">'.$org_address.'</font></center>
                          <center> Phone : <b>'.$org_phone.'</b></center>
                          <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>';
            $header .='<tr>
                      <th colspan=7 align="center" >
                      <h2>
                        '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)).' PENDING ARREAR REPORT </h2>
                      </th>
                    </tr>
            <tr>
                <th>SNO</th>
                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).'   </th>
                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).'  </th>
                <th>REGISTER NUMBER</th>
                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE").' </th>
                <th colspan=2> '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME").'</th>';
            
            $header .='</tr></thead>'; 

            $body .="<tbody>";
          
                $sno=1;
                    foreach ($fetched_data as $values) 
                    {
                        $body .="<tr style='padding-top: 20px;'><td>".$sno."</td>";
                        $body .= "<td>".$values['batch_name']."</td>";
                        $body .= "<td>".$values['degree_name']."</td>";
                        $body .= "<td>".$values['register_number']."</td>";
                        $body .= "<td>".$values['subject_code']."</td>";
                        $body .= "<td colspan=2>".$values['subject_name']."</td>";                        
                        $body .= "</tr>"; $sno++;
                    }
                $body .='</tbody></table>';
                $html = $header.$body;
                $html_1 = $html;
                if(isset($_SESSION['student_arrear_report']))
                {
                    unset($_SESSION['student_arrear_report']);
                }
               $_SESSION['student_arrear_report'] = $html_1;
                echo $html;
        }
        else
        {
          Yii::$app->ShowFlashMessages->setMsg('Error','No Arrears Found');
        }
    ?>
    </div>
	</div>
	</div>