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
$this->title = "Bulk ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT);
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
<h1><?php echo "Bulk ".ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_STUDENT)." Update"; ?></h1>
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
                        <div class="col-xs-12 col-sm-3 col-lg-3">
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
                        
                        
                    </div> 
                    
                </div>
                <div class="row">
                   <div class="col-xs-10">                 
                        <?php 
                        if(!isset($_POST['get_students']))
                        {
                            ?>
                            <div class="col-xs-12 col-sm-2 col-lg-2"><br />
                                <?= Html::submitButton('Get', ['onClick'=>"spinner();",'name'=>'get_students','class' => 'btn btn-block btn-success' ]) ?>
                                <br />
                            </div> 
                            <div class="col-xs-12 col-sm-2 col-lg-2"><br />
                            <?= Html::a("Reset", Url::toRoute(['student/bulkupdate']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
                            <br />
                            
                        </div> 
                            <?php    
                        } else {
                        ?>   
                        <div class="col-xs-12 col-sm-2 col-lg-2"><br />
                            <?= Html::submitButton('Update', ['onClick'=>"spinner();",'name'=>'get_edit_bulk','class' => 'btn btn-block  btn-primary']) ?>
                            <br />
                        </div> 
                        <div class="col-xs-12 col-sm-2 col-lg-2"><br />
                            <?= Html::a("Reset", Url::toRoute(['student/bulkupdate']), ['onClick'=>"spinner();",'class' => 'btn btn-warning  btn-block']) ?>
                            <br />
                        </div>                  
                        <?php } ?>
                    </div>
                </div>


                <?php 
                if(isset($stu_data) && !empty($stu_data))
                {  
                   ?>   
                    
                <section class="content">
                  <div class="row">
                    <div class="col-xs-12">
                    <div class="box">
                       
                        <!-- /.box-header -->
                        <div class="box-body">

                        <table  style="overflow-x:auto;"  cellspacing="0" cellpadding="0" border="0" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                    <thead class="thead-inverse">
                    <tr class="table-danger">
                        
                        <th>Reg No</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>E-Mail</th>
                        <th>Mobile</th>
                        <th>Aadhar</th>
                        <th>Religion</th>                        
                        <th>Guardian</th>
                        <th>Guardian Mobile</th>
                        <th>Guardian Email</th>
                    </tr>               
                    </thead> 
                    <tbody>       
                    <?php  foreach ($stu_data as $key => $value) {   
                            $stu_id = $value['coe_student_id'];
                           $form_name = "bulk_edit_of[$stu_id]";  
                        ?>
                        <tr>
                            <td valign="top"><?php echo $value['register_number'] ?></td>
                            <td>
                                <input type="text" pattern="^[a-zA-Z\s._-]+$" title="Maximum 45 Letters are allowed" name="<?php echo $form_name; ?>[name]" value='<?php echo $value['name'] ?>'>
                            </td>
                            <td>
                                <?php 
                                    $dob = $value['dob'];
                                    
                                    echo DatePicker::widget([
                                        'name' => $form_name."[dob]",
                                         'type' => DatePicker::TYPE_INPUT,
                                        'value' => $dob,
                                        'options' => [                                            
                                            'placeholder' => '-- Select Date of Birth ...',
                                            'id'=>'stu_dob'.$stu_id,
                                            'required'=>'required',                                            
                                            'onChange' => "isDateBulk(this.id); ", 

                                        ],
                                        'removeButton' => [
                                            'icon'=>'trash',
                                        ],
                                        'pickerButton' => [
                                            'icon'=>'ok',
                                        ],
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'yyyy/mm/dd'
                                        ],
                                       
                                    ]);
                                 ?>
                                
                            </td>
                            <td>
                                <input type="email" title="Enter the Valid Email Address" name="<?php echo $form_name; ?>[email_id]" value='<?php echo $value['email_id'] ?>'>
                            </td>
                            <td>
                                <input type="text" title="Only 10 Digists are allowed" maxlength="10" pattern="[0-9]{10}" name="<?php echo $form_name; ?>[mobile_no]" value='<?php echo $value['mobile_no'] ?>'>
                            </td>
                            <td>
                                <input type="text" title="Maximum 12 Numbers are allowed" maxlength="12" pattern="[0-9]{12}" name="<?php echo $form_name; ?>[aadhar_number]" value='<?php echo $value['aadhar_number'] ?>'>
                            </td>
                            <td>
                                <input type="text" title="Enter Valid Religion" pattern="^[a-zA-Z\s._-]+$" name="<?php echo $form_name; ?>[religion]" value='<?php echo $value['religion'] ?>'>
                            </td>
                            <td>
                                <input type="text" title="Maximum 45 Letters are allowed" pattern="^[a-zA-Z\s._-]+$" name="<?php echo $form_name; ?>[guardian_name]" value='<?php echo $value['guardian_name'] ?>'>
                            </td>
                            <td>
                                <input type="text" title="Valid Mobile number Maximum 10 digists" maxlength="10" pattern="[0-9]{10}" name="<?php echo $form_name; ?>[guardian_mobile_no]" value='<?php echo $value['guardian_mobile_no'] ?>'>
                            </td>
                            <td>
                                <input type="email" title="Valid Email Address is required" name="<?php echo $form_name; ?>[guardian_email]" value='<?php echo $value['guardian_email'] ?>'>
                            </td>
                            

                        </tr>
                    <?php }   // End the foreach to finish of the student records display  ?>

                </tbody>
                <!-- <tfoot>
                    <tr>
                      <th>Reg No</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>E-Mail</th>
                        <th>Mobile</th>
                        <th>Aadhar</th>
                        <th>Religion</th>
                        
                        <th>Guardian</th>
                        <th>Guardian Mobile</th>
                        <th>Guardian Email</th>
                    </tr>
                </tfoot> -->
                    </table>

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