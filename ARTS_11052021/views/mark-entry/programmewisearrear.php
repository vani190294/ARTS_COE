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

$this->title = STRTOUPPER(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME))." WISE ARREAR";
$this->params['breadcrumbs'][] = ['label' => strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME))." WISE ARREAR", 'url' => ['create']];
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
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>date('Y'),'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
            </div>
            
            <div class="col-sm-3 col-lg-3"><br />
                <div class="form-group col-lg-12 col-sm-12">
                    <?= Html::submitButton('Show Me', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                    <?= Html::a("Reset", Url::toRoute(['mark-entry/programmewisearrear']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                </div>
            </div>
        </div>
        
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>






<?php
if(isset($programmewisearrear))
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
                    $header .= '<tr>
                    <td style="border: none;" colspan=8>
                    <table width="100%" align="center" border="0">                    
                    <tr>
                      <td> 
                        <img class="img-responsive"  width="80" height="80" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=6 align="center"> 
                          <center><b><font size="4px">'.$org_name.'</font></b></center>
                     </td>
                      <td align="center">  
                        <img width="80" height="80" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>
                    <tr>
                        <td colspan=8><center> <font size="3px">'.$org_address.'</font> Phone : <b>'.$org_phone.'</b> </center></td>
                    </tr>
                     <tr>
                        <td colspan=8><center class="tag_line"><b>'.$org_tagline.'</b></center></td>
                    </tr>
                    </table></td></tr><tr><td height="30px" colspan=8>&nbsp;</td></tr>';
                    
                $body = $full_html ='';
                $prev_deg_code ='';
                $old_name = $old_batch_name = '';
                  foreach($programmewisearrear as $rows) 
                  { 
                    if($rows['subject_code']!=$previous_subject_code)
                    {       
                        $disp_name = $rows["degree_code"]==$old_name?',,':$rows["degree_code"];
                        $disp_batch_name = $rows["batch_name"]==$old_batch_name?',,':$rows["batch_name"];
                        $body .='<tr>
                             <td>'.$disp_batch_name.'</td>
                             <td colspan=2>'.$disp_name.'</td>
                             <td colspan=4 >[ <b>'.$rows["subject_code"].'</b> ] '.$rows["subject_name"].' <b>[ SEMESTER '.$rows["semester"].'</b> ]</td>
                             <td><b>'.$rows["count"].'</b></td>
                        </tr>';
                        $previous_subject_code=$rows['subject_code'];
                        $old_name = $rows["degree_code"];
                        $old_batch_name = $rows["batch_name"];
                    } 
                    
                }

                $full_html =$header.$body.'</table>';
                if(isset($_SESSION['programmewisearrear'])){ unset($_SESSION['programmewisearrear']);}
                $_SESSION['programmewisearrear'] = $full_html;

                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('programme-wise-arrear-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('programme-wise-arrear-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
                echo '<div class="col-xs-12" >'.$full_html.'</div>
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
