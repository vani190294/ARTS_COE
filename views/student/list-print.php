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
$this->title = "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." List";
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
<h1><?php echo "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." List"; ?></h1>
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
                        <div class="col-xs-4">                 
                        
                            <div class="col-xs-12 col-sm-4 col-lg-4"><br />
                                <?= Html::submitButton('Get', ['onClick'=>"spinner();",'name'=>'get_students','class' => 'btn btn-block btn-success' ]) ?>
                                <br />
                            </div> 
                            <div class="col-xs-12 col-sm-4 col-lg-4"><br />
                            <?= Html::a("Reset", Url::toRoute(['student/list-print']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
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
                                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel ",array('excel-list-print'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-block btn-info', 'style'=>'color:#fff'));
                                
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/student/list-print-pdf'], [
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

                        $head .='<table  style="overflow-x:auto;" cellspacing="0" cellpadding="0" border="1" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                           
                            <tr>
                                <td>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).': '.$deg["batch_name"].'</td>
                                <td>
                                    <b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DEGREE).': '.$deg["degree_code"].'</b> 
                                </td>
                                <td colspan=2 >
                                    <b>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' : '.$deg["programme_name"].'</b>
                                </td> 
                            </tr>
                        </table>';

                        $body = '<table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="1" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                    <thead class="thead-inverse">
                        <tr class="table-danger">
                            <th>Reg No</th>                        
                            <th>Name</th>
                            
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        
                        </tr>               
                    </thead> 
                    <tbody>';    
                    $supported_extensions = ConfigUtilities::ValidFileExtension(); 
                    $stu_directory = Yii::getAlias("@web")."/resources/stu_photos/";
                    $absolute_dire = Yii::getAlias("@webroot")."/resources/stu_photos/";   
                    foreach ($stu_data as $key => $value) {   
                            $stu_id = $value['coe_student_id'];
                           $form_name = "bulk_edit_of[$stu_id]";  
                            $files = glob($absolute_dire.$value['register_number'].".*"); // Will find 2.JPG, 2.php, 2.gif
                            // Process through each file in the list
                            // and output its extension

                            if (count($files) > 0)
                            foreach ($files as $file)
                             {
                                $info = pathinfo($file);
                                $extension = ".".$info["extension"];
                             }
                             else
                             {
                                $extension="";
                             }
                        $photo_extension = ConfigUtilities::match($supported_extensions,$value['register_number'].$extension); 

                        $body .= "<tr>
                            <td>".$value['register_number']."</td>
                            <td>".$value['name']."</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>

                        </tr>";
                    }   // End the foreach to finish of the student records display 

                $body .= "</tbody>              
                    </table>";
                    echo $head.$body;
                    $_SESSION['stu_bio_data_download'] = $head.$body;

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