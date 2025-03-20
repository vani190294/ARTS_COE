<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\Batch;
use app\models\SubjectsMapping;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
$batch_id = isset($subjects->batch_mapping_id)?$subjects->batchMapping->coeBatch->coe_batch_id:"";
$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." COUNT REPORT";
?>
<h1><?php echo $this->title;     ?></h1>
<style type="text/css">
.left-padding
{
    margin-left: -10px; 
    padding-right: -0px;
}
.righh-padding
{
    padding-right: -0px;
}
</style>

<div class="subjects-form">
<div class="box box-success">
<div class="box-body"> 
   
     <?php Yii::$app->ShowFlashMessages->showFlashes();?>
<div>&nbsp;</div>

    <?php 
    $condition = $model->isNewRecord?true:false;
    $form = ActiveForm::begin([
                    'id' => 'categories-form',
                    'enableAjaxValidation' => $condition,
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",

                    ],
            ]); ?>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'coe_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'batch_id',
                            'value'=> $batch_id,
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div> 
            <div class="col-lg-4 col-sm-4">
                <br />
                <?= Html::submitButton( 'Get', ['class' =>  'btn btn-success']) ?>

                <?= Html::a('Reset', ['subject-count-report'], ['class' => 'btn btn-default']) ?> 
        </div>
        </div>

    <?php ActiveForm::end(); ?>

<?php $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/subjects-mapping/subject-count-report-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]);  
$print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/subjects-mapping/subject-count-report-excel'], [
                    'class'=>'pull-right btn btn-block btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
?>

 
</div>
</div>
</div>

<?php 
if(isset($content_1))
{

?>
<div class="box box-primary">
    <div class="box-body">
        <div class="row" >
            <div class="col-xs-12" >
                <div class="col-lg-2" > <?php echo $print_excel.$print_pdf; ?> </div>
                <div class="col-lg-10" > 
                    <?php 
                    require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                    $html = $body ='';

                    $html .='<table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0" class="table table-bordered table-responsive dum_edit_table table-hover" >
                        <tr>
                          <td> 
                            <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                          </td>

                          <td colspan=7 align="center"> 
                              <center><b><font size="6px">'.$org_name.'</font></b></center>
                              <center> <font size="3px">'.$org_address.'</font></center>
                              <center> Phone : <b>'.$org_phone.'</b></center>
                              <center class="tag_line"><b>'.$org_tagline.'</b></center> 
                         </td>
                          <td align="center">  
                            <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                          </td>
                        </tr> 
                        <tr class="table-danger">
                            
                            <th>SNO</th>                
                            <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).'</th>
                            <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).'</th>
                            <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)).'</th>
                            <th>SEMESTER</th>
                            <th>COUNT</th>
                            <th>OPTIONAL</th>
                            <th>ELECTIVE</th>
                            <th>COMMON</th>
                        </tr>   
                        ';
                        $sn_no = 1;
                        $optional = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Optional%'")->queryScalar();

                        $elective = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Elective%'")->queryScalar();
                        $Common = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Common%'")->queryScalar();
                        $optional_total = $elective_total = $Common_total = $grand_total = 0;
                        foreach ($content_1 as $key => $value) 
                        {
                            $body .='<tr>';
                            $body .='<td>'.$sn_no.'</td>';
                            $body .='<td>'.$value['batch_name'].'</td>';
                            $body .='<td>'.$value['degree_code'].'</td>';
                            $body .='<td>'.strtoupper($value['programme_name']).'</td>';
                            $body .='<td>'.$value['semester'].'</td>';
                            $body .='<td>'.$value['count'].'</td>';
                            $grand_total +=$value['count'];
                            $optional_count = SubjectsMapping::find()->where(['batch_mapping_id'=>$value['batch_mapping_id'],'subject_type_id'=>$optional,'semester'=>$value['semester']])->all();
                            $optional_total +=count($optional_count); 
                            $optional_disp = count($optional_count)==0?'-':count($optional_count);
                            $body .='<td>'.$optional_disp.'</td>';

                            $elective_count = SubjectsMapping::find()->where(['batch_mapping_id'=>$value['batch_mapping_id'],'subject_type_id'=>$elective,'semester'=>$value['semester']])->all();
                            $elective_total +=count($elective_count); 
                            $elective_disp = count($elective_count)==0?'-':count($elective_count);
                            $body .='<td>'.$elective_disp.'</td>';

                            $Common_count = SubjectsMapping::find()->where(['batch_mapping_id'=>$value['batch_mapping_id'],'subject_type_id'=>$Common,'semester'=>$value['semester']])->all();
                            $Common_total +=count($Common_count); 
                            $Common_dip = count($Common_count)==0?'-':count($Common_count);
                            $body .='<td>'.$Common_dip.'</td>';

                            $sn_no++;
                        }
                        $body .='<tr><td colspan=5 algin="center" >TOTAL COUNT </td><td>'.$grand_total.'</td><td>'.$optional_total.'</td><td>'.$elective_total.'</td><td>'.$Common_total.'</td></tr>';

                        echo $html .='<tbody id="show_dummy_numbers"> '.$body.'</tbody> </table>'; 

                        if(isset($_SESSION['sub_report_count']))
                        {
                            unset($_SESSION['sub_report_count']);
                        }
                        $_SESSION['sub_report_count'] = $html;

                    ?>


                </div>
            </div>
         </div>
    </div>
</div>

<?php 
}
?>

</div>