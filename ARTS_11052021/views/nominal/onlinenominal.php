<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use yii\helpers\Url;

echo Dialog::widget();

$section_name = isset($stuMapping->section_name)?$stuMapping->section_name:"";

/* @var $this yii\web\View */
/* @var $model app\models\Nominal */
/* @var $form yii\widgets\ActiveForm */
$this->registerCssFile("@web/style/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
    'media' => 'print',
], 'css-print-theme');

?>

<div class="nominal-form">
<div class="box box-success">
<div class="box-body">      
    <?php Yii::$app->ShowFlashMessages->showFlashes();?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($batch, 'batch_name')->widget(
                    Select2::classname(), [
                        'data' => $batch->getBatch(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH));  ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($programme,'programme_code')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME));  ?>
        </div> 
        
        <div class="col-xs-12 col-sm-2 col-lg-2">
           <?= $form->field($model, 'semester')->textInput() ?>
        </div>    
    </div>

    <div>&nbsp;</div>
    <div class="col-xs-12 col-sm-5 col-lg-5">        
            <div class="form-group col-xs-12 col-sm-3 col-lg-3 nominal_submit">
                <?= Html::submitButton('Get', ['onClick'=>"spinner();",'name'=>'get_nominal','class' => 'btn btn-block btn-success' ]) ?>
                                <br />
            </div>
            <div class="col-xs-12 col-sm-3 col-lg-3">
                <?= Html::a("Reset", Url::toRoute(['nominal/onlinenominal']), ['class' => 'btn btn-warning  btn-block']) ?>
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
                                echo Html::a('<i class="fa fa-file-excel-o"></i> ' ."Excel",array('student-nominal-export-excel'),array('title'=>'Export to Excel','target'=>'_blank','class'=>'btn btn-block btn-info', 'style'=>'color:#fff'));
                                
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-lg-2 pull-right">
                            <?php 
                                echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/nominal/student-nominal-export-pdf'], [
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
                                <tr class="table-danger">
                                    <th>Subject Code(Course_code)</th>
                                    <th>Subject_name (Course_name)</th>                        
                                    <th>Email_id</th>
                                    <th>Registration Number</th>
                                   
                                </tr> 
                                ';
                    foreach ($stu_data as $key => $value) 
                    {
                        $body .= "<tr>
                          
                            <td>".$value['subject_code']."</td>
                            <td>".$value['subject_name']."</td>
                            <td>".$value['email_id']."</td>
                            <td>".$value['register_number']."</td>
                            </tr>";
                    }   // End the foreach to finish of the student records display 
                    
                $body .= "</table>";
                    echo $head.$body;
                    if(isset($_SESSION['stu_nominal_data']))
                    {
                        unset($_SESSION['stu_nominal_data']);
                    }
                    $_SESSION['stu_nominal_data'] = $head.$body;

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
    $('#student_bulk_edit').dataTable({
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


$this->registerJs(<<<JS
    $(function () {
    $('#student_bulk_edit_1').dataTable({
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
  $('#student_bulk_edit_1').removeClass( 'display' ).addClass('table table-striped table-bordered');
JS
);
?>
