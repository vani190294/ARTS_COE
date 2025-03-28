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

$this->title = "Elective Count";
$this->params['breadcrumbs'][] = ['label' =>"Elective Count", 'url' => ['create']];
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
                <?php
                echo $form->field($model, 'stu_programme_id')->widget(
                        Select2::classname(), [
                    'data' => ConfigUtilities::getDegreedetails(),
                    'options' => [
                        'placeholder' => '-----Select ' . ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME) . ' ----',
                        'id' => 'stu_programme_selected',
                        'name' => 'bat_map_val',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME));
                ?>
            </div>
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
            <br />
            <div class="form-group col-lg-2 col-sm-2">
                <?= Html::submitButton('Show Me', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry/elective-count']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>






<?php
if(isset($programmewisearrearnominal))
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
            
            //$countStuVal = count($programmewisearrear);
            $stu_print_vals = 0;
                
               $header .="<table border=1 align='center' class='table table-striped '>";
               $header .= '
                    <tr>
                        <td>
                            <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                        </td>
                        <td  colspan=7 align="center"> 
                              <center><b><font size="4px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font> Phone : <b>'.$org_phone.'</b> </center><br /><center class="tag_line"><b>'.$org_tagline.'</b></center>
                        </td>
                        <td align="center">  
                            <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                        </td>
                    </tr>';
                    $header .="
                    <tr>
					  <th align='center'>S.NO</th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH))."</th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE))." CODE</th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME))." NAME</th>
                      <th align='center'>SEMESTER</th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." CODE</th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT))." NAME</th>
                      <th align='center'>COUNT</th>
					  <th align='center'>DOWNLOAD</th>
                    </tr>";
				  $sn=1;
                  foreach($programmewisearrearnominal as $rows) 
                  { 
                    
                    if(empty($query_check))
                    {
                        $header .='<tr>
							<td align="center">'.$sn.'</td>
                            <td align="center">'.$rows["batch_name"].'</td>
                             <td align="center">'.$rows["degree_code"].'</td>
                             <td align="center">'.$rows["programme_name"].'</td>
                             <td align="center"><b>'.$rows["semester"].'</b></td>
                             <td align="center"><b>'.$rows["subject_code"].'</b></td>
                             <td align="center"><b>'.$rows["subject_name"].'</b></td>
                             <td align="center">'.$rows["count"].'</td>
							 <td align="center">  
							 <input type="hidden" id="sub_id_'.$sn.'" value="'.$rows["coe_subjects_id"].'"  />
							 <input type="hidden" id="batch_map_id_'.$sn.'" value="'.$rows["course_batch_mapping_id"].'" />
							 <input type="hidden" id="semester_'.$sn.'" value="'.$rows["semester"].'" />							 
							 <input type="button" class="btn btn-success" id="subexpor_'.$sn.'" onclick="ExportStuList(this.id,this.value);" value="DOWNLOAD" /> 
							 </td>
                        </tr>';
						$sn++;
                    }
                }

                $header .='</table>';
                if(isset($_SESSION['elective-count'])){ unset($_SESSION['elective-count']);}
                $_SESSION['elective-count'] = $header;

                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('programme-wise-arrear-pdf-elective','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('programme-wise-arrear-elective-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$header.'</div>
					 <div class="hide_elec_div" >&nbsp;</div><br /><br />
                            </div>
                        </div>
                      </div>';
					  
				echo '<div class="hide_elec_div" ><div class="hide_elec_div_con" >&nbsp;</div></div>';
						
                
               
        }// If no Content found for Institution
        else
        {
            Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found for Institution');            
        }
    } 
?>
