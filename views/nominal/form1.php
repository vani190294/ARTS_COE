<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use app\models\Student;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
echo Dialog::widget();

$section_name = isset($stuMapping->section_name)?$stuMapping->section_name:"";



$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(), 'register_number', 'register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number);
$from_reg_no = isset($_POST['from_reg']) ? $_POST['from_reg'] : '';
$to_reg_no = isset($_POST['to_reg']) ? $_POST['to_reg'] : '';

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
    
        <?php $form = ActiveForm::begin(); ?>
       
            <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
                

                <?= $form->field($model, 'coe_student_id')->textInput(['maxlength'=>1,'placeholder'=>'Only Numbers', 'value' => $model->coeStudent->register_number,]) ?>

            </div>

<div class="col-lg-2 col-sm-2">
                 
                 <?= $form->field($model, 'coe_subjects_id')->textInput(['maxlength'=>1,'placeholder'=>'Only Numbers', 'value' => $model->coeSubjects->subject_code]) ?>
                   
            </div>


<div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'semester')->textInput(['maxlength'=>1,'placeholder'=>'Only Numbers']) ?>
            </div>




    <div>&nbsp;</div>
    <div class="col-xs-12 col-sm-5 col-lg-5">        
            <div class="form-group col-xs-12 col-sm-3 col-lg-3 nominal_submit">
                <input type="button" class="btn btn-block btn-success" name="viewnominal" value="Update" id="button_view_nominal">
            </div>
            <div class="col-xs-12 col-sm-3 col-lg-3">
                <?= Html::a("Reset", Url::toRoute(['nominal/create']), ['class' => 'btn btn-warning  btn-block']) ?>
            </div>       
    </div>

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






