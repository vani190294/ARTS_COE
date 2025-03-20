<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\CoeBatDegReg;
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

$this->title="Mandatory Report";

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
                <?php echo $form->field($model_1,'stu_batch_id')->widget(
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
                <?php echo $form->field($model_1, 'stu_programme_id')->widget(
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
                <?= $form->field($model_1, 'year')->textInput(['id'=>'course_year','name'=>'year','value'=>date("Y")]) ?>
            </div>

            <div class="col-lg-2 col-sm-2">
	            <?php echo $form->field($model_1,'month')->widget(
	                Select2::classname(), [
	                    'options' => [
	                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
	                        'id'=>'exam_month',
	                        'name'=>'month',
	                    ],
	                    'pluginOptions' => [
	                        'allowClear' => true,
	                    ],
	                ]) 
	                ?>
        	</div> 
          <br />
            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mandatory-stu-marks/manreport']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>
<?php ActiveForm::end(); ?>
</div>
</div>
</div>
<?php
if(isset($get_console_list_man))
{
     $month_1 = Yii::$app->db->createCommand("select description from coe_category_type where coe_category_type_id='" . $_POST['month'] . "'")->queryScalar();
    //print_r($get_console_list_man);exit;
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $data ='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';                 
    $data.='<tr>
                <td>
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan=8> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    <center>'.$org_tagline.'</center> 
                </td>
                <td>  
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
                
            </tr>';
            $data .= '<tr><td colspan=8 align="center"><b>MANDATORY MARKS REPORT</b> - ' . strtoupper($month_1 . ' ' . $_POST['year']) . '</td></tr>';
            
                   
                  

            $data.=
            '<tr>
                <th>S.No</th>
                <th>REGISTER NUMBER</th>     
               <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code</th>
               <th>SUBJECTNAME</th>
                <th>SUBCATCODE</th>
                <th>SUBCATNAME</th>
                <th>MARKS</th>
                <th>RESULT</th>
            </tr>';
            
            $prgm_code='';
            $sn=1;
            $i=1;
            foreach($get_console_list_man as $arrear1){
                $data.='<tr>';
                    //$coeBatd = CoeBatDegReg::findOne($arrear1['batch_mapping_id']);
                    //$batchMapVal = $arrear1['batch_mapping_id'];
                    //$batchName = Batch::findOne($coeBatd['coe_batch_id']);
                    if($prgm_code!=$arrear1['programme_code'])
                    {
                        
                        $data.='<td align="left">'.$sn.'</td>';
                        $data.='<td align="left">'. $arrear1['register_number'].'</td>';
                        $data.='<td align="left">'. $arrear1['subject_code'].'</td>';
                        $data.='<td align="left">'. $arrear1['subject_name'].'</td>';
                        $data.='<td align="left">'. $arrear1['sub_cat_code'].'</td>';
                        $data.='<td align="left">'. $arrear1['sub_cat_name'].'</td>';
                        $data.='<td align="left">'. $arrear1['total'].'</td>';
                        $data.='<td align="left">'. $arrear1['result'].'</td>';
                        $sn++;
                    }
                    else
                    {
                         $data.='<td align="left">'.$sn.'</td>';
                        $data.='<td align="left">'. $arrear1['register_number'].'</td>';
                        $data.='<td align="left">'. $arrear1['subject_code'].'</td>';
                        $data.='<td align="left">'. $arrear1['subject_name'].'</td>';
                        $data.='<td align="left">'. $arrear1['sub_cat_code'].'</td>';
                        $data.='<td align="left">'. $arrear1['sub_cat_name'].'</td>';
                        $data.='<td align="left">'. $arrear1['total'].'</td>';
                        $data.='<td align="left">'. $arrear1['result'].'</td>';
                    }
                        //$data.='<td align="left"><input type="hidden" id=sub_code_'.$i.' value="'.$arrear1['subject_code'].'">'.$arrear1['subject_code'].'</td>';
                        /*$data.='<td align="left"><a href="#" onmouseover="ArrearList(this.id);" id=code_'.$i.'><b>'.$arrear1['count'].'</b></a></td>';*/
                        //$data.='<td align="left"><b>'.$arrear1['count'].'</b></td>';
                        $i++;
                $data.='</tr>';
            }

    $data.='</tbody>';        
    $data.='</table>';
    if(isset($_SESSION['sub_arrear_count']))
    {
        unset($_SESSION['sub_arrear_count']);
    }
    $_SESSION['sub_arrear_count'] = $data;
    echo '<div class="box box-primary">
            <div class="box-body">
                <div class="row" >';
    echo '<div class="col-xs-12">';
    echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('sub-arrear-count-pdf','exportPDF'=>'PDF'),array('title'=>'Export to PDF','target'=>'_blank','class'=>'pull-right btn btn-warning', 'style'=>'color:#fff'));
    echo Html::a('<i class="fa fa-file-excel-o"></i> ' . "EXCEL ",array('sub-arrear-count-excel','exportPDF'=>'PDF'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'pull-right btn btn-info', 'style'=>'color:#fff')); 
    echo '<div class="col-xs-12" >'.$data.'</div>
                </div>
            </div>
          </div>'; 
}
?>