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
<h1><?php echo "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Bio Data"; ?></h1>
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
                            <?php echo $form->field($model, 'stu_programme_id')->widget(
                                Select2::classname(), [
                                    'data'=>ConfigUtilities::getDegreedetails(),                                    
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id' => 'stu_programme_selected',
                                    'value'=>$degree_batch_mapping_id,
                                    'class'=>'form-control student_disable',                                   
                                    
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
                            <?= Html::a("Reset", Url::toRoute(['student/dob-verify']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
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
                                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('student-dob-export-excel'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-block btn-info', 'style'=>'color:#fff'));
                                
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/student/student-dob-export-pdf'], [
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
                        foreach ($stu_data as $key => $value) 
                        {
                              $degree_name =  strtoupper($value['degree_name']);
                              $batch_name = strtoupper($value['batch_name']);                             
                              $programme_name = strtoupper($value['programme_name']);
                        } 

                        $table_open ="<table style='text-align: left;' border=1 width='100%' >";
                        $table_close = "</table>";
                        $print_data_again =$table_open.'
                        <tr>
                            <th colspan=2>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)).'</th>
                           <th colspan=3>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE)).' NAME </th>
                           <th colspan=2>'.strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)).' CODE </th>
                         </tr>
                          <tr>
                           <td colspan=2>'.$batch_name.'</td>
                           <td colspan=3>'.$degree_name.'</td>
                           <td colspan=2>'.$programme_name.'</td>
                         </tr>
                        '; 
$i=1;
$html .= $print_data_again;

                        $bottom_data = '
                            <tr class="table-danger">
                                <th>S.NO</th>                                
                                <th>REGISTER NUMBER</th>                        
                                <th>NAME</th>
                                <th>OLD DOB (DD/MM/YYYY)</th>
                                <th>DOB IN WORDS</th>
                                <th>DOB (DD/MM/YYYY)</th>
                                <th>SIGNATURE</th>
                            </tr>';    
                  $sn_no = 1;  $body=''; $continue_serial = 1;
                    foreach ($stu_data as $key => $values) 
                    { 
                        if(($sn_no%23)==0)
                        {
                          $sn_no=1;
                          $html .=$bottom_data.$body.$table_close."<pagebreak />".$print_data_again;
                          $$print_data_again = $body = '';

                        }  
                        $body .= "
                            <tr>
                                <td>".$continue_serial."</td>
                                <td>".$values['register_number']."</td>
                                <td style='width: 350px' >".$values['name']."</td>
                                <td>".date('d-m-Y',strtotime($values['dob']))."</td>
                                <td>".date('d-M-Y',strtotime($values['dob']))."</td>
                                <td>&nbsp;</td>                           
                                <td> &nbsp; </td>
                            </tr>";
                        $sn_no++;
                        $continue_serial++;
                    }   // End the foreach to finish of the student records display 
                    $html .=$bottom_data.$body."<tr><td height='45px' colspan='5'>&nbsp;</td></tr><tr><td colspan='3'>Class Tutors</td><td colspan='4'>HOD</td></tr><tr><td colspan='3'> 1. <br /> <br /> 2. </td><td colspan='4'>&nbsp; </td></tr>".$table_close;
                    echo $html;
                    $_SESSION['stu_dob_bio_data'] = $html;

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