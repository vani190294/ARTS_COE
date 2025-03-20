<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use kartik\dialog\Dialog;
use app\models\HallAllocate;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title = ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_DUMMY)." Entry";
$this->params['breadcrumbs'][] = $this->title;

?>
<div>&nbsp;</div>
<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 

$form = ActiveForm::begin(); ?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'exam_year']) ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?= $form->field($model, 'month')->widget(
                    Select2::classname(), [  
                        'data' => HallAllocate::getMonth(),                      
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select Month ----',
                            'id' => 'exam_month',    
                            'onchange' => 'bringYearMonthSubs(this.value,$("#exam_year").val());',                        
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ]) ?>
        </div>
         <div class="col-xs-12 col-sm-3 col-lg-3">
             <?php 

             echo $form->field($model,'subject_map_id')->widget(
                Select2::classname(), [
                    'options' => [
                        'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT).' Code ----',
                        'id' => 'dummy_exam_subject_code',
                        'name'=>'exam_subject_code',
                        'onchange' => 'get_numbers_info(this.value,$("#exam_year").val(), $("#exam_month").val());',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);


            ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?php 
                  $examtimetable->exam_type = '27'; // Hard Coded for Auto Selection
                  $examtimetable->exam_term = '34'; // Hard Coded for Auto Selection
             ?>
            <?= $form->field($examtimetable, 'exam_type')->radioList( $examtimetable->getExamType(),
         [ 'item' => function($index, $label, $name, $checked, $value) {
         $return = '<label class="left-padding">';
         $return .= Html::radio($name, $checked, ['value' => $value,'required'=>'required']);
         $return .= '<i></i>';
         $return .= '<span>&nbsp;&nbsp;&nbsp;' . ucwords($label) . '</span>';
         $return .= '</label>';
         return $return;

           }])?>
                     
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($examtimetable, 'exam_term')->radioList( $examtimetable->getExamTerm(),
         [ 'item' => function($index, $label, $name, $checked, $value) {
         $return = '<label class="left-padding">';
         $return .= Html::radio($name, $checked, ['value' => $value,'required'=>'required']);
         $return .= '<i></i>';
         $return .= '<span>&nbsp;&nbsp;&nbsp;' . ucwords($label) . '</span>';
         $return .= '</label>';
         return $return;

           }])?>
                     
        </div> 
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'start_number')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'start_number','getLastNumber(this.value);','autocomplete'=>"off",'min'=>4,'max'=>10,'required'=>'required']); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'end_number')->textInput(['onkeypress'=>'numbersOnly(event)','id'=>'end_number','onchange'=>'compareNumbers(this.value);','autocomplete'=>"off",'min'=>4,'max'=>10,'required'=>'required']); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-3 col-lg-3">
            <?= $form->field($model, 'last_dummy_number')->textInput(['required'=>'required','id'=>'examiner_name','autocomplete'=>"off"])->label('Examiner Name'); ?>
                     
        </div>      
    </div>
    <div id='hide_dum_sub_data' class="row">
    <div  class="col-xs-12"> <br /><br />
        <div class="col-xs-1"> &nbsp; </div>
            <div class="col-xs-10">
                <table  style="overflow-x:auto;" width="100%"  cellspacing="0" cellpadding="0" border="0"  class="table table-bordered table-responsive dum_edit_table table-hover" >
                    <thead class="thead-inverse">
                        <tr class="table-danger">
                            
                            <th>SNO</th>
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." CODE"); ?></th>
                            
                            <th><?php echo strtoupper(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_SUBJECT)." NAME"); ?></th>
                            <th><?php echo strtoupper("Minimum"); ?></th>
                            <th><?php echo strtoupper("Maximum"); ?></th>
                            <th><?php echo strtoupper("Min Pass"); ?></th>
                        </tr>               
                    </thead> 
                    <tbody id="show_dummy_entry">     

                    </tbody>
                </table> 
            </div>
        <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div> <!-- Row Closed -->
</div>
</div>

<div class="row">
    
   <div class="col-xs-12 col-sm-12 col-lg-12">
        <div class="col-xs-12 col-sm-6 col-lg-6">            
            <br />

            <div class="btn-group col-lg-12 col-sm-12" role="group" aria-label="Actions to be Perform">
                <?= Html::Button('Show' ,['onClick'=>"verify_marks();",'class' => 'btn btn-success' ]) ?>

                <?= Html::a("Reset", Url::toRoute(['dummy-numbers/verify-marks']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                <?php 
                    echo Html::a('<i class="fa fa-file-pdf-o"></i> ' . "PDF",array('verify-marks','exportPDF'=>'PDF'),array('target'=>'_blank','title'=>'Export to PDF','target'=>'_blank','id' =>'submit_dummy','class'=>'btn btn-group btn-group-lg btn-primary ', 'style'=>'color:#fff'));
                ?>
               
                
            </div>           

            
        </div>
        

       
    </div>
    <div id='hide_dum_data' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-1"> &nbsp; </div>
            <div id='show_dummy_numbers' class="col-xs-10">
            
        
            </div>
    <div class="col-xs-1"> &nbsp; </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

</div>
</div>
</div>