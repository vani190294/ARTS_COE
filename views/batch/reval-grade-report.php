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
use app\models\MarkEntry;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="REVALUATION REPORT";

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
                        'id' => 'stu_cs_batch_id',
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
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'batch_year','name'=>'withdraw_year']) ?>
        </div>

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'consolidate_month',   
                            'name' => 'consolidate_month',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>

        <br />
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
if(isset($sub_list))
{
   //print_r($sub_list);exit;
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
                    $header .= '<tr>
                    <td style="border: none;" colspan=9>
                    <table width="100%" align="center" border="0">                    
                    <tr>
                      <td> 
                        <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                      </td>

                      <td colspan=12 align="center"> 
                          <center><b><font size="4px">'.$org_name.'</font></b></center>
                     </td>
                      <td align="center">  
                        <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                      </td>
                    </tr>
                    <tr>
                        <td colspan=14><center> <font size="3px">'.$org_address.'</font> Phone : <b>'.$org_phone.'</b> </center></td>
                    </tr>
                     <tr>
                        <td colspan=14><center class="tag_line"><b>'.$org_tagline.'</b></center></td>
                    </tr>
                    </table></td></tr>';
                    $header .="
                    <tr height=40px>
                      <th align='center'>SNO</th>
                      <th align='center'>".strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH))."</th>
                       <th align='center'>Department</th>
                       <th align='center'>Register Number</th>
                        <th align='center'>subject code</th>
                       <th align='center'>Mark Before Revaluation</th>
                       <th align='center'>Mark After Revaluation</th>
                        <th align='center'>Grade Before Revaluation</th>
                        <th align='center'>Grade After Revaluation</th>
                      
                    </tr>";
                    $i=1;
                    $footer .='</table>';
                  foreach($sub_list as $rows) 
                  { 
                    
                    if($i%45==0 && $i!=1)
                    {
                        $html = $header .$body.$footer; 
                        $print_stu_data .= $html;
                        $html = "";
                        $body ="";
                        $i=1;
                    }

                    if($rows['result']=="Pass")
                    {

                     //$getgradename = Yii::$app->db->createCommand("SELECT A.grade_name FROM `coe_grade_range` as A join coe_mark_entry_master_temp  as B on B.year =A.year  WHERE B.total='".$rows['temptotal']."' between `min_mark` and `max_mark` and `subject_code`='".$rows["subject_code"]."' and semester='".$rows["semester"]."'")->queryScalar();
                        $total=$rows['temptotal'];
                        $getgradename=Yii::$app->db->createCommand("SELECT   grade_name FROM `coe_grade_range`   WHERE '".$total."'  between `min_mark` and `max_mark` and `subject_code`='".$rows["subject_code"]."' and semester='".$rows["semester"]."'")->queryScalar();

                       // $getgradename="vani";

                 }

                 else
                 {

                    $getgradename="U";
                     
                 }
         
                    $body .='<tr height=40px>
                                        <td align="center">'.$i.'</td>
                                        <td align="center">'.$rows["batch_name"].'</td>
                                        <td align="center">'.$rows["degree_code"].'-'.$rows["programme_code"].'</td>
                                         <td align="center">'.$rows["register_number"].'</td>
                                          <td align="center">'.$rows["subject_code"].'</td>
                                          <td align="center">'.$rows["temptotal"].'</td>
                                              <td align="center">'.$rows["total"].'</td>
                                               <td align="center">'.$getgradename.'</td>
                                               <td align="center">'.$rows["grade_name"].'</td>
                                         
                                               
                                    </tr>';
                           
                        $i++;
                    

                }

                
                $html = $header .$body.$footer; 
                $print_stu_data .= $html;

                if(isset($_SESSION['coursewisearrear'])){ unset($_SESSION['coursewisearrear']);}
                $_SESSION['coursewisearrear'] = $print_stu_data;

                
                echo "</div>";
                echo '<div class="box box-primary">
                        <div class="box-body">
                            <div class="row" >';
                echo '<div class="col-xs-12">';
                echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('course-wise-arrear-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
                echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('course-wise-arrear-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
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