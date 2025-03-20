<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\models\Batch;
use app\models\StudentMapping;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
echo Dialog::widget();
$batch_id = isset($subjects->batch_mapping_id)?$subjects->batchMapping->coeBatch->coe_batch_id:"";
$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." COUNT REPORT";
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

                <?= Html::a('Reset', ['student-count-report'], ['class' => 'btn btn-default']) ?> 
        </div>
        </div>

    <?php ActiveForm::end(); ?>

<?php $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/subjects-mapping/student-count-report-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]);  
$print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/subjects-mapping/student-count-report-excel'], [
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

                          <td colspan=9 align="center"> 
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
                            <th>COUNT</th>
                            <th>DETAIN/DEBAR</th>
                            <th>DISCONTINUED</th>
                            <th>REJOIN</th>
                            <th>TRANSFER</th>
                            <th>OTHERS</th>
                        </tr>   
                        ';
                        $det_cat_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%detain%'")->queryScalar();

                        $det_disc_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%Discontinued%'")->queryScalar();
                        $transfer = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%TRANSFER%'")->queryScalar();

                        $rejoin_type = Yii::$app->db->createCommand("select coe_category_type_id from coe_category_type where category_type like '%rejoin%'")->queryScalar();

                        $sn_no = 1;
                        $detain_total = $others_count_total = $transfer_total = $rejoin_total = $disc_total = $total_strength = 0;
                        foreach ($content_1 as $key => $value) 
                        {
                            $body .='<tr>';
                            $body .='<td>'.$sn_no.'</td>';
                            $body .='<td>'.$value['batch_name'].'</td>';
                            $body .='<td>'.$value['degree_code'].'</td>';
                            $body .='<td>'.strtoupper($value['programme_name']).'</td>';
                            $body .='<td>'.$value['count'].'</td>';

                            $detain_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_cat_type])->all();
                            $detain_total +=count($detain_count); 
                            $detain_disp = count($detain_count)==0?'-':count($detain_count);
                            $body .='<td>'.$detain_disp.'</td>';

                            $disc_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$det_disc_type])->all();
                            $disc_total +=count($disc_count); 
                            $disc_disp = count($disc_count)==0?'-':count($disc_count);
                            $body .='<td>'.$disc_disp.'</td>';

                            $rejoin_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$rejoin_type])->all();
                            $rejoin_total +=count($rejoin_count); 
                            $rejoin_dip = count($rejoin_count)==0?'-':count($rejoin_count);
                            $body .='<td>'.$rejoin_dip.'</td>';

                            $transfer_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id'],'status_category_type_id'=>$transfer])->all();
                            $transfer_total +=count($transfer_count); 
                            $transfer_count_dip = count($transfer_count)==0?'-':count($transfer_count);
                            $body .='<td>'.$transfer_count_dip.'</td>';

                            $others_count = StudentMapping::find()->where(['course_batch_mapping_id'=>$value['course_batch_mapping_id']])->andWhere(['NOT IN','status_category_type_id',[$transfer,$rejoin_type,$det_disc_type,$det_cat_type]])->all();
                            $others_count_total +=count($others_count); 
                            $other_disp = count($others_count)==0?'-':count($others_count);
                            $body .='<td>'.$other_disp.'</td>';
                            $total_strength +=$value['count'];
                            $sn_no++;
                        }
                        $body .='<tr><td colspan=4 algin="center" >TOTAL STRENGTH </td><td>'.$total_strength.'</td><td>'.$detain_total.'</td><td>'.$disc_total.'</td><td>'.$rejoin_total.'</td><td>'.$transfer_total.'</td><td>'.$others_count_total.'</td></tr>';
                        echo $html .='<tbody id="show_dummy_numbers"> '.$body.'</tbody> </table>'; 

                        if(isset($_SESSION['student_count_repo']))
                        {
                            unset($_SESSION['student_count_repo']);
                        }
                        $_SESSION['student_count_repo'] = $html;

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