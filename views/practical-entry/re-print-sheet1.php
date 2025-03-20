<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\assets\AppAsset;
use kartik\widgets\Select2;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use app\models\HallAllocate;
use app\models\ExamTimetable;
use kartik\dialog\Dialog;
echo Dialog::widget();
/* @var $this yii\web\View */
/* @var $model app\models\ExamTimetable */
/* @var $form yii\widgets\ActiveForm */

$this->title ="Re Print Practical Mark Entry";
$this->params['breadcrumbs'][] = $this->title;


?>
<h1><?php echo "Re Print Practical Mark Entry"; ?></h1>

<div>&nbsp;</div>
<div class="exam-timetable-form">
<div class="box box-success">
<div class="box-body"> 
<?php Yii::$app->ShowFlashMessages->showFlashes();?> 
<?php 

$form = ActiveForm::begin(); ?>
<div>&nbsp;</div>
<div class="row">
<div class="col-xs-12 col-sm-12 col-lg-12">
   <div class="col-xs-12 col-sm-12 col-lg-12"> 
         <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry,'stu_batch_id')->widget(
                    Select2::classname(), [
                        'data' => ConfigUtilities::getBatchDetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH).' ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_BATCH)); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry, 'stu_programme_id')->widget(
                    Select2::classname(), [
                    //'data'=>ConfigUtilities::getDegreedetails(),
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME).' ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label(ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_PROGRAMME)); 
                ?>
            </div>
         <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year']) ?>
         </div>
           
         <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'data' => HallAllocate::getMonth(),  
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '-----Select '.ConfigUtilities::getConfigValue(ConfigConstants::CONFIG_EXAM).' Month ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'onchange' => 'getPracExamDatesAllocat(this.value);',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
         </div>  
       

        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php echo $form->field($model, 'exam_date')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'class'=>'form-control student_disable', 
                            'id'=>'prac_exam_date',                    
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">

            <?php echo $form->field($model, 'exam_session')->widget(
                    Select2::classname(), [
                        'data'=>ConfigUtilities::getPracExamSessions(),                                    
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'class'=>'form-control student_disable',     
                            'id'=>'prac_exam_sess',  
                            'onchange'=>'getPracticalSubsOnly1(this.value);',
                                                            
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); 
                ?>
        </div>


        <div class="col-lg-2 col-sm-2">
             
                <?php echo $form->field($model, 'unique_prac_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select Code----',
                            'id' => 'unique_prac_id',
                            'name'=>'unique_prac_id', 
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Practical Batch'); 
                ?>
        </div>
        <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform"><br>
                <?= Html::Button('Show', ['onClick'=>'getExaminerNamesRePrint1()','class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['practical-entry/re-print-sheet1']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                
            </div> 
        <!--div class="col-xs-12 col-sm-2 col-lg-2">
            <?php // $form->field($model, 'examiner_name')->textInput(['name'=>'register_num_from','required'=>'required','id'=>'register_num_from','onBLur'=>'getStudentInfoPracts(this.value); '])->label('Reg Num From'); ?>
                     
        </div>
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php // $form->field($model, 'examiner_name')->textInput(['required'=>'required','id'=>'examiner_name'])->label('Examiner Name'); ?>
                     
        </div> 
        <div class="col-xs-12 col-sm-2 col-lg-2">
            <?php // $form->field($model, 'chief_exam_name')->textInput(['required'=>'required','id'=>'chief_exam_name'])->label('Chief Examiner Name'); ?>
        </div--> 
    </div>
</div>

</div>

<div class="row">
    
   
    <div id='hide_dum_data_send' class="row">
        <div  class="col-xs-12"> <br /><br />
            <div class="col-xs-2"> <?php echo $print_pdf = Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/practical-entry/reprint-sheet-practical-pdf'], [
                    'class'=>'pull-right btn btn-block btn-primary', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                echo "<br />";
                echo  $print_excel = Html::a('<i class="fa fa-file-pdf-o"></i> EXCEL', ['/practical-entry/excel-reprint-practical-sheet'], [
                    'class'=>'pull-right btn btn-block btn-warning', 
                    'target'=>'_blank', 
                    'data-toggle'=>'tooltip', 
                    'title'=>'Will open the generated PDF file in a new window'
                ]); 
                ?> </div>
            <div class="col-xs-10" id="pract_show_dummy_numbers">

            </div>
        </div>
    </div>


<?php ActiveForm::end(); ?>

</div>
</div>
</div>