<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\Student;
use app\models\Batch;
use app\models\CoeBatDegReg;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use app\models\ExamTimetable;
use app\models\Categorytype;

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = " ARREAR REPORT ";
$this->params['breadcrumbs'][] = ['label' => " ARREAR REPORT ", 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
$month= isset($_POST['exam_month'])?$_POST['exam_month']:'';
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
        ]); 
    ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-3 col-sm-3">
                <?= $form->field($model, 'year')->textInput(['id'=>'mark_year','value'=>$year,'name'=>'mark_year'])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM)." Year") ?>
            </div>
            <br />
            <div class="form-group col-lg-3 col-sm-3">
                <?= Html::submitButton('Submit', ['onClick'=>"spinner();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['mark-entry/arrearreport']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
</div>
</div>
</div>


<?php
if(isset($arrear))
{
    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
    $data ='<table id="checkAllFeet" class="table table-responsive table-striped" align="center" ><tbody align="center">';                 
    $data.='<tr>
                <td>
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                </td>
                <td colspan=2> 
                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                    <center>'.$org_address.'</center>
                    <center>'.$org_tagline.'</center> 
                </td>
                <td>  
                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                </td>
            </tr>';

            $data.=
            '<tr>
                <th>S.No</th>
                <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).'</th>     
                <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' Code</th>
                <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code</th>
                <th> No. of Arrears</th>
            </tr>';
            
            $prgm_code='';
            $sn=1;
            $i=1;
            foreach($arrear as $arrear1){
                $data.='<tr>';
                    $coeBatd = CoeBatDegReg::findOne($arrear1['batch_mapping_id']);
                    $batchMapVal = $arrear1['batch_mapping_id'];
                    $batchName = Batch::findOne($coeBatd['coe_batch_id']);
                    if($prgm_code!=$arrear1['programme_code'])
                    {
                        $prgm_code = $arrear1['programme_code'];
                        $data.='<td align="left">'.$sn.'</td>';
                        $data.='<td align="left">'.$batchName->batch_name.'</td>';
                        $data.='<td align="left"><input type="hidden" id=pgm_code_'.$i.' value="'.$batchMapVal.'">'.$prgm_code.'</td>';
                        $sn++;
                    }
                    else
                    {
                        $data.= '<td align="left"> </td>';
                        $data.='<td align="left">'.$batchName->batch_name.'</td>';
                        $data.='<td align="left"><input type="hidden" id=pgm_code_'.$i.' value="'.$batchMapVal.'">'.$prgm_code.'</td>';
                    }
                        $data.='<td align="left"><input type="hidden" id=sub_code_'.$i.' value="'.$arrear1['subject_code'].'">'.$arrear1['subject_code'].'</td>';
                        /*$data.='<td align="left"><a href="#" onmouseover="ArrearList(this.id);" id=code_'.$i.'><b>'.$arrear1['count'].'</b></a></td>';*/
                        $data.='<td align="left"><b>'.$arrear1['count'].'</b></td>';
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