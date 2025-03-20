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
                <input type="button" class="btn btn-block btn-success" name="viewnominal" value="Submit" id="button_view_nominal">
            </div>
            <div class="col-xs-12 col-sm-3 col-lg-3">
                <?= Html::a("Reset", Url::toRoute(['nominal/create']), ['class' => 'btn btn-warning  btn-block']) ?>
            </div>       
    </div>

    <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-12 col-lg-12">
           <!-- <div id = "nominal_tbl" >                
            </div> -->
        <!-- <table style="overflow-x: auto;" cellspacing="0" cellpadding="0" border="0" id="nominal_tbl" class="table table-bordered table-responsive bulk_edit_table table-hover" align="right">
            </table> -->

            <table  style="overflow-x:auto; "  cellspacing="0" cellpadding="0" border="0" id="student_bulk_edit" class="table table-bordered table-responsive bulk_edit_table table-hover" >
                
            </table>

        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
           <!-- <div id = "view_nominal_tbl" >                
            </div> -->
    <!-- <table  style="overflow-x: auto;" cellspacing="0" cellpadding="0" border="0" id="view_nominal_tbl" class="table table-bordered table-responsive bulk_edit_table table-hover" align="right"> -->

        <table  style="overflow-x:auto; "  cellspacing="0" cellpadding="0" border="0" id="student_bulk_edit_1" class="table table-bordered table-responsive bulk_edit_table table-hover" >

            </table>
        </div>

    </div> 

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
