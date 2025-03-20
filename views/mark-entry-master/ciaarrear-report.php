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
use app\models\Categorytype;
use app\models\MarkEntryMaster;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */
$batch_id = isset($_POST['bat_val']) ? $_POST['bat_val'] : '';
$batch_map_id = isset($_POST['bat_map_val']) ? $_POST['bat_map_val'] : '';

$this->title = 'CIA WISE ARREAR REPORT';
$this->params['breadcrumbs'][] = ['label' => 'ARREAR REPORT', 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>

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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
         </div>  

         <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => ConfigUtilities::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',                            
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
            </div>   
          
         <div class="col-lg-2 col-sm-2">
            <?php
                        echo $form->field($model, 'stu_batch_id')->widget(
                                Select2::classname(), [
                            'data' => ConfigUtilities::getBatchDetails(),
                            'options' => [
                                'placeholder' => '-----Select ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH) . ' ----',
                                'id' => 'stu_batch_id_selected',
                                'name' => 'bat_val',
                                'value' => $batch_id,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH));
                        ?>
        </div>
          
           <br>
            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Download', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/ciaarrear-report']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

<?php
if(isset($arrearreport))
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
            
           // $countStuVal = count($arrearreport);
            $stu_print_vals = 0;
              foreach($arrearreport as $va) 
              { 
                $batch = $va["batch_name"];
                $deg_code =  $va["degree_code"].' ('.$va["degree_name"].')';
                break;
              }
               $header .='<table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive bulk_edit_table table-hover" id = "student_import_results" >';
                    $header .= '<tr>
                                    <th style="border: none;" colspan=10>
                                          <center class="tag_line">'.$org_name.'</center>
                                          <center class="tag_line">'.$org_address.' Phone : '.$org_phone.' </center>
                                          <center class="tag_line">'.$org_tagline.'</center>
                                          <center class="tag_line"><b>CIA WISE ARREAR REPORT FOR '.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).'-'.$batch.' </b></center>
                                          <center class="tag_line">'.$org_address.' Phone : '.$org_phone.' </center>


                                    </th>

                                </tr>';
                    $header .="
                    <tr>
                      <th>SNO</th>
                      <th>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH))."</th>
                     
                      <th>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE))."</th>
                        <th>SEMESTER</th>
                      <th>REGISTER NUMBER</th>
                      <th>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT))." NAME</th>
                      <th>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." CODE </th>
                      
                      <th>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." NAME</th>
                    <th>CIA MIN</th>
                    <th>CIA</th>
                    
                     
                    </tr>";
                    $i=1;
                   $footer .='</table>';
                    
                  foreach($arrearreport as $rows) 
                  { 
                    $query_check = MarkEntryMaster::find()->where(['student_map_id'=>$rows["student_map_id"],'subject_map_id'=>$rows["subject_map_id"],'result'=>'pass'])->all();
                    if(empty($query_check))
                    {
                        if($rows['register_number']!=$previous_reg_number && $previous_subject_code!=$rows['subject_code'])
                        {

                            if($rows["CIA_min"]>$rows["CIA"])
                            {
                             $body .='<tr>
                                        <td align="center">'.$i.'</td>
                                        <td align="center">'.$rows["batch_name"].'</td>

                                        <td align="center">'.$rows["degree_code"].'</td>
                                          <td align="center">'.$rows["semester"].'</td>
                                        <td align="center">'.$rows["register_number"].'</td>
                                        <td align="center">'.$rows["name"].'</td>
                                        <td align="center">'.$rows["subject_code"].'</td>
                                        <td align="center">'.$rows["subject_name"].'</td>
                                      
                                     <td align="center">'.$rows["CIA_min"].'</td>
                                     <td align="center">'.$rows["CIA"].'</td>
                                      
                                      
                                    </tr>';
                                       $i++; 
                                }
                                else
                                {


                                }
                            }
                           
                        }
                        else
                        {
                            if($rows["CIA_min"]>$rows["CIA"])
                            {
                            $body .='<tr>
                                <td align="center">'.$i.'</td>
                                <td align="center">'.$rows["batch_name"].'</td>
                                <td align="center">'.$rows["degree_code"].'</td>
                                  <td align="center">'.$rows["semester"].'</td>                            
                                <td align="center">'.$rows["register_number"].'</td>
                                <td align="center">'.$rows["name"].'</td>
                                <td align="center">'.$rows["subject_code"].'</td>
                                <td align="center">'.$rows["subject_name"].'</td>

                                <td align="center">'.$rows["CIA_min"].'</td>
                                <td align="center">'.$rows["CIA"].'</td>
                                 
                                                          
                            </tr>';
                               $i++; 
                        }

                         
                    }


                        
                    }


                
                $html = $header .$body.$footer; 
                $print_stu_data .= $html;

                if(isset($_SESSION['arrearreport'])){ unset($_SESSION['arrearreport']);}
                $_SESSION['arrearreport'] = $print_stu_data;
                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('arrear-report-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('arrear-report-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$print_stu_data.'</div>
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
