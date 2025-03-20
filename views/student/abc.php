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
$this->title = "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." ABC Data";
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
<h1><?php echo "Download ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." ABC"; ?></h1>
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
                            <?= Html::a("Reset", Url::toRoute(['student/abc']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
                            <br />
                        </div> 
                      </div>  
                    </div>
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
                                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('student-bio-export-excel'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-block btn-info', 'style'=>'color:#fff'));
                                
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/student/student-bio-export-pdf'], [
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

                     
                        $head .='<table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="1" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                            <tr>
                                <td colspan=2> 
                                    <img class="img-responsive"  width="100" height="100" src="'.Yii::getAlias("@web").'/images/main_logo.png" alt="College Logo">
                                </td>
                                <td colspan=8 align="center"> 
                                    <center><b><font size="4px">'.$org_name.'</font></b></center>
                                    <center>'.$org_address.'</center>
                                    
                                    <center>'.$org_tagline.'</center> 
                                </td>
                                <td  colspan=2 align="center">  
                                    <img width="100" height="100" class="img-responsive" src="'.Yii::getAlias("@web").'/images/skacas.png" alt="College Logo 2">
                                </td>   
                            </tr>
                           
                        </table>';

                      /*  $body = '<table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="1" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                    <thead class="thead-inverse">
                        <tr class="table-danger">
                            <th>Reg No</th>                        
                            <th>Name</th>
                            <th>Medium</th>
                            <th>Hosteler/Day Scholar</th>
                            <th>'.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SECTION).'</th>
                            <th>DOB</th>
                            <th>E-Mail</th>
                            <th>Mobile</th>
                            <th>Aadhar</th>
                            <th>Religion</th>                        
                            <th>Gender</th>
                            <th>Nationality</th>
                            <th>Caste</th>
                            <th>Sub Caste</th>
                            <th>Bloodgroup</th>                        
                            <th>Guardian / Parent</th>
                            <th>Guardian Mobile</th>
                            <th>Photo</th>
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

                        
                     $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg"; 
                        
                        


                        $body .= "<tr>
                            <td>".$value['register_number']."</td>
                            <td>".$value['name']."</td>
                            <td>".$value['medium']."</td>
                            <td>".$value['is_hostlier']."</td>
                            <td>".$value['section_name']."</td>
                            <td>".date('d-m-Y',strtotime($value['dob']))."</td>
                            <td>".$value['email_id']."</td>
                            <td>".$value['mobile_no']." </td>
                            <td>".$value['aadhar_number']."</td>
                            <td>".$value['religion']."</td>
                            <td>".$value['gender']."</td>
                            <td>".$value['nationality']."</td>
                            <td>".$value['caste']."</td>
                            <td>".$value['sub_caste']."</td>                            
                            <td>".$value['bloodgroup']."</td>
                           
                            <td>".$value['guardian_name']."</td>
                            <td>".$value['guardian_mobile_no']."</td>
                            <td><img class='img-responsive' width=80 height=80 src=".$stu_photo." alt='".$stu_photo." Photo' > </td>
                            

                        </tr>";*/
                        //changes  11032021
                         $body = '<table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="1" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                    <thead class="thead-inverse">
                        <tr class="table-danger">
                            <th>Reg No</th> 
                            <th>Programme</th>                        
                            <th>Name</th>
                           
                           <th>ABC Account ID</th>                         
                            
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

                        
                     $stu_photo = $photo_extension!="" ? $stu_directory.$value['register_number'].".".$photo_extension:$stu_directory."stu_sample.jpg"; 
                        
                        


                        $body .= "<tr>
                            <td>".$value['register_number']."</td>
                             <td>".$value['programme_code']."</td>
                            <td>".$value['name']."</td>
                           
                           <td>".$value['abc_account_id']."</td>
                          

                        </tr>";

                    }   // End the foreach to finish of the student records display 

                $body .= "</tbody>              
                    </table>";
                    echo $head.$body;
                    $_SESSION['stu_bio_data'] = $head.$body;

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