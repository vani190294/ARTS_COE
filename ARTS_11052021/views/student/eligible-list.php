<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\dialog\Dialog;
use kartik\widgets\Select2;
use yii\helpers\Url;
use app\models\HallAllocate;
use app\models\Categorytype;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;

$this->registerCssFile("@web/style/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    'media' => 'print',
], 'css-print-theme');



$formatter = \Yii::$app->formatter;
echo Dialog::widget();
$this->title = "Download Eligible ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." List";
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
<h1><?php echo "Download Eligible ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." List"; ?></h1>
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
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($model, 'stu_section_name')->widget(
                                Select2::classname(), [
                                    'data'=>ConfigUtilities::getSectionnames(),                                    
                                'options' => [
                                    'placeholder' => '-----Select ----',
                                    'id'=>'stu_section_select',
                                    'value'=>$section_name,
                                    'class'=>'form-control student_disable',                                    
                                ],
                                 'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                                             
                            ]); 
                        ?>
                        </div> 
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?php echo $form->field($MarkEntry, 'year')->textInput(['value'=>date('Y')]); 
                        ?>
                        </div> 
                        <div class="col-xs-12 col-sm-2 col-lg-2">
                            <?= $form->field($MarkEntry, 'month')->widget(
                                Select2::classname(), [  
                                    'data' => HallAllocate::getMonth(),                      
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
                        <div class="col-xs-4">                 
                        
                            <div class="col-xs-12 col-sm-4 col-lg-4"><br />
                                <?= Html::submitButton('Get', ['onClick'=>"spinner();",'name'=>'get_students','class' => 'btn btn-block btn-success' ]) ?>
                                <br />
                            </div> 
                            <div class="col-xs-12 col-sm-4 col-lg-4"><br />
                            <?= Html::a("Reset", Url::toRoute(['student/eligible-list']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
                            <br />
                            
                        </div> 
                        
                    </div>
                    </div> 
                    
                </div>
                <div class="row">
                   
                </div>


                <?php 
                if(isset($stu_data) && !empty($stu_data))
                {   $head = '';
                    $body = '';
                   ?>   
                    
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-lg-8">
                            &nbsp;
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel ",array('eligible-excel-list-print'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-block btn-info', 'style'=>'color:#fff'));
                                
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/student/eligible-list-print-pdf'], [
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
                        require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
                        $months = Categorytype::findOne($_POST['MarkEntry']['month']);
                        $month_name = $months['description'];
                        $head .='<table style="overflow-x:auto;" cellspacing="0" cellpadding="0" border="1" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                            <tr>
                                <td colspan=4 align="center"> 
                                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                                    <center>OFFICE OF THE CONTROLLER OF EXAMINATIONS</center>
                                    <center> LIST OF STUDENTS ELIGIBLE FOR '.$month_name.' '.$_POST['MarkEntry']['year'].' EXAMINATIONS</center> 
                                </td>
                            </tr>
                            <tr>                                
                                <td colspan=4 align="center" >
                                    <b>'.$deg["degree_code"].' '.$deg["programme_name"].' ('.$deg["batch_name"].')</b>
                                </td> 
                            </tr>
                            <tr class="table-danger">
                                    <th>SNO</th>    
                                    <th>Reg No</th>                        
                                    <th colspan=2>Name</th>
                                </tr>';     
                                   
                    $body_1 = '';
                    $td_close = $sno=1;

                    foreach ($stu_data as $key => $value) 
                    { 
                        if($sno%46==0)
                        {                            
                            $body_1 .='</table><pagebreak />'.$head;
                        } 
                        $body_1 .= "<tr>
                                    <td width='50px'>".$sno."</td>
                                    <td width='150px' >".$value['register_number']."</td>
                                    <td colspan=2>".$value['name']."</td>
                                </tr>";  
                        $sno++;
                        
                    }   
                    $body .=$head.$body_1.'</table>';
                    echo '<div class="col-xs-12 col-sm-12 col-lg-12">'.$head.$body_1.'</table></div>';
                    $_SESSION['stu_eligible_bio_data_download'] = $body;

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


<?php 

$this->registerJsFile(
    '@web/style/bower_components/datatables.net/js/jquery.dataTables.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(    
    '@web/style/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);


$this->registerJs(<<<JS
    $(function () {
    $('#student_bulk_edit').DataTable({
      'paging'      : true,
      "dom": '<lf<t>ip>',
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true,
       'scrollY': '400',
       "scrollX": true,
       'buttons': [
        'copy', 'excel', 'pdf'
    ],
       "responsive": true,
       "pageLength": "60",
       "language" : {
            searchPlaceholder : "Register Number to filter"
        },
    })
  })
  $('#student_bulk_edit').removeClass( 'display' ).addClass('table table-striped table-bordered');
JS
);


?>

<!-- "sPaginationType": "full_numbers",
       'dom': 'Bfrtip',  "dom": '<"top"flp<"clear">>rt<"bottom"i<"clear">>', -->