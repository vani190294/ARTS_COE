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
$this->title = "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Full Info Export";
$this->params['breadcrumbs'][] = ['label' => ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$batch_id = isset($model->coe_batch_id)?$model->coe_batch_id:"";

?>
<h1><?php echo "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." Full Data"; ?></h1>
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
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <?php echo $form->field($model, 'coe_batch_id')->widget(
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

                        <div class="col-xs-12 col-sm-2 col-lg-2"><br />
                                <?= Html::submitButton('Get', ['onClick'=>"spinner();",'name'=>'get_students','class' => 'btn btn-block btn-success' ]) ?>
                                <br />
                            </div> 
                            <div class="col-xs-12 col-sm-2 col-lg-2"><br />
                            <?= Html::a("Reset", Url::toRoute(['mandatory-subjects/mandatory-subjects-full']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
                            <br />
                            
                        </div>                 
                                               
                    </div>                     
                </div>
                
                <?php 
                if(isset($man_su_info) && !empty($man_su_info))
                {  $body = '';
                   ?>   
                    
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-lg-8">
                            &nbsp;
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel ",array('man-subject-export-info-excel'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-block btn-info', 'style'=>'color:#fff'));
                                
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/mandatory-subjects/man-subject-info-export-pdf'], [
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

                        <?php $body = '<table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="1" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                    <thead class="thead-inverse">
                    <tr class="table-danger">
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).'</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).'</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).'</th>
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' CODE </th>                          
                        <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' NAME</th>   
                        <th>SUB CATEGORY NAME</th>                     
                        <th>CIA MINIMUM</th>
                        <th>ESE MINIMUM</th>
                        <th>CIA MAXIMUM</th>
                        <th>ESE MAXIMUM</th>
                        <th>MINIMUM PASS</th>
                        <th>TOTAL MARKS</th>                        
                        <th>PAPER TYPE</th>
                        <th>CREDITS</th>  
                    </tr>               
                    </thead> 
                    <tbody>';       
                    foreach ($man_su_info as $key => $value) {   
                            $stu_id = $value['coe_mandatory_subjects_id'];
                           $form_name = "bulk_edit_of[$stu_id]";  
                        
                        $body .= "<tr>
                            <td>".$value['batch_name']."</td>
                            <td>".$value['degree_name']."</td>
                            <td>".$value['programme_name']."</td>
                            <td>".$value['subject_code']."</td>
                            <td>".$value['subject_name']."</td>
                            <td>".$value['sub_category_name']."</td>
                            <td>".$value['CIA_min']."</td>                            
                            <td>".$value['ESE_min']."</td>
                            <td>".$value['CIA_max']." </td>
                            <td>".$value['ESE_max']."</td>
                            <td>".$value['total_minimum_pass']."</td>
                            <td>".$value['final_marks']."</td>
                            <td>".$value['paper_type']."</td>
                            <td>".$value['credit_points']."</td>                            

                        </tr>";
                    }   // End the foreach to finish of the student records display 

                $body .= "</tbody>              
                    </table>";
                    echo $body;
                    $_SESSION['mandatory_sub_info'] = $body;

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
            searchPlaceholder : "Search Here..."
        },
    })
  })
  $('#student_bulk_edit').removeClass( 'display' ).addClass('table table-striped table-bordered');
JS
);


?>

<!-- "sPaginationType": "full_numbers",
       'dom': 'Bfrtip',  "dom": '<"top"flp<"clear">>rt<"bottom"i<"clear">>', -->