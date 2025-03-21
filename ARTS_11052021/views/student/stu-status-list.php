<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\dialog\Dialog;
use kartik\widgets\Select2;
use yii\helpers\Url;

use app\components\ConfigConstants;
use app\components\ConfigUtilities;

$this->registerCssFile("@web/style/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    'media' => 'print',
], 'css-print-theme');


$admission_status = isset($model->admission_status) && $model->admission_status!=""?$model->admission_status:"";
$formatter = \Yii::$app->formatter;
echo Dialog::widget();
$this->title = "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Bio Data";
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(isset($model->stu_section_name))
{
    $this->registerJs("$(document).ready(function() { $('.student_disable').attr('disabled',true)});");
}
$batch_id = isset($model->stu_batch_id)?$model->stu_batch_id:"";
$section_name = isset($model->stu_section_name)?$model->stu_section_name:"";
$degree_batch_mapping_id = isset($model->stu_programme_id)?$model->stu_programme_id:"";

?>
<h1><?php echo "DOWNLOAD ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." STASTUS LIST "; ?></h1>
<br /><br />
<div id="student_update_edit_page" class="configuration-form">
    <div class="box box-primary">
        <div class="box-body">          
               
            <?php Yii::$app->ShowFlashMessages->showFlashes();?> 
            <?php 
                        $condition = $model->isNewRecord?true:false;
                        $form = ActiveForm::begin(); ?>
            
            <div class="row">
                <div  class="col-xs-12">                 
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'stu_batch_id')->widget(
                                Select2::classname(), [
                                'data' => ConfigUtilities::getBatchDetails(),
                                
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_batch_id_selected', 
                                    'value'=> $batch_id,    
                                    'class'=>'form-control student_disable',                              
                                ],

                               
                                
                            ]); 
                        ?>
                        </div>                  
                                        
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                             <?php echo $form->field($model, 'admission_status')->widget(
                                Select2::classname(), [
                                'data' => $model->StudentStatus,                                
                                'options' => [
                                    'placeholder' => '-----Select Status----',
                                    'id' => 'stu_status_select',                                    
                                    'value' => $admission_status,
                                    'onChange'=>'addFields(this.id);',
                                    'class'=>'form-control', 
                                ],
                                ]); 
                            ?>
                        </div> 
                        
                    
                   <div class="col-xs-12 col-sm-3 col-lg-3">                 
                        
                            <div class="col-xs-12 col-sm-6 col-lg-6"><br />
                                <?= Html::submitButton('Get', ['onClick'=>"spinner();",'name'=>'get_students','class' => 'btn btn-block btn-success' ]) ?>
                                <br />
                            </div> 
                            <div class="col-xs-12 col-sm-6 col-lg-6"><br />
                            <?= Html::a("Reset", Url::toRoute(['student/stu-status-list']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
                            <br />
                            
                        </div> 
                        
                    </div>
                </div>


                <?php 
                if(isset($stu_data) && !empty($stu_data))
                {  $body = '';
                   ?>   
                    
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-lg-8">
                            &nbsp;
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('student-status-export-excel'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-block btn-info', 'style'=>'color:#fff'));
                                
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/student/student-status-export-pdf'], [
                                'class'=>'pull-right btn btn-block btn-primary', 
                                'target'=>'_blank', 
                                'data-toggle'=>'tooltip', 
                                'title'=>'Will open the generated PDF file in a new window'
                                ]);
                            ?>
                        </div>
                    </div>
                  <div class="row">
                    <div class="col-xs-12">
                    <div class="box">
                        
                        <!-- /.box-header -->
                        <div class="box-body">

                        <?php 
                        $html='';
                        
                        $table_open ="<table style='text-align: left;' border=1 width='100%' >";
                        $table_close = "</table>";
                        $print_data_again =$table_open.''; 
$i=1;
$html .= $print_data_again;

                        $bottom_data = '
                            <tr class="table-danger">

                                <th>S.NO</th> 
                                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).'</th>
                                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).' </th>
                                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)).' CODE </th>  
                                <th>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)).' </th>                               
                                <th>REGISTER NUMBER</th>                        
                                <th>NAME</th>
                                <th>CURRENT STATUS</th>
                            </tr>';    
                  $sn_no = 1;  $body='';
                    foreach ($stu_data as $key => $values) 
                    { 
                        $body .= "
                            <tr>
                                <td>".$sn_no."</td>
                                <td>".$values['batch_name']."</td>
                                <td>".$values['degree_code']."</td>
                                <td>".$values['programme_code']."</td>
                                <td>".$values['programme_name']."</td>
                                <td>".$values['register_number']."</td>
                                <td>".$values['name']."</td>
                                <td>".$values['admission_status']."</td>
                            </tr>";
                        $sn_no++;
                    }   // End the foreach to finish of the student records display 
                    $html .=$bottom_data.$body.$table_close;
                    echo $html;
                    $_SESSION['stu_status_list'] = $html;

?>

                </div>
            </div>
        </div>
    </div>
</section>
 <?php  } // If not empty of Students Data Display Records to update ?>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>