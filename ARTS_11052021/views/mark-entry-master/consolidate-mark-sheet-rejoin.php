<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use app\models\ExamTimetable;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use kartik\date\DatePicker;

$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(),'register_number','register_number');
$register_numbers = empty($model->register_number) ? $numbers : Student::findOne($model->register_number); 

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title="RE-JOIN CONSOLIDATED MARK SHEET";
$year= isset($_POST['mark_year'])?$_POST['mark_year']:date('Y');
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">
<div class="box box-success">
<div class="box-body"> 
<div>&nbsp;</div>

<?php Yii::$app->ShowFlashMessages->showFlashes();?>

    <?php $form = ActiveForm::begin([
                    'id' => 'mark-entry-form',
                    'fieldConfig' => [
                    'template' => "{label}{input}{error}",
                    ],
            ]); ?>

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
                    'data'=>ConfigUtilities::getDegreedetails(),
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
                               
                            <?php 

                                echo $form->field($student, 'register_number_from')->widget(Select2::classname(), [
                                'initValueText' => $register_numbers, // set the initial display text
                                'options' => ['placeholder' => 'Search for a register_number ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 1,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => $url,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:$("#stu_section_select").val()}; }')
                                    ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                                ],
                            ]);


                            ?>

                        </div> 
                        <div class="col-lg-2 col-sm-2">

                            <?php 

                                echo $form->field($student, 'register_number_to')->widget(Select2::classname(), [
                                'initValueText' => $register_numbers, // set the initial display text
                                'options' => ['placeholder' => 'Search for a register_number ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 1,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:$("#stu_section_select").val()}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                            ]);


                            ?>
                        </div>

        <div class="col-lg-2 col-sm-2">
            <?php echo $form->field($model, 'result')->widget(
                Select2::classname(), [
                'data'=>['A4'=>'A4','A3'=>'A3'],
                    'options' => [
                        'placeholder' => '-----Select Layout----',
                        'id' => 'layout_type',
                        'name'=>'layout_type',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label("Layout"); 
            ?>
            </div>
            <div class="col-xs-12 col-sm-1 col-sm-1">
                <?php 
                    echo '<label>Date</label>';
                    echo DatePicker::widget([
                        'name' => 'created_at',
                        'value'=>date('m/d/Y'),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ['placeholder' => 'Select Mark Statement Date ...',],
                         'pluginOptions' => [
                            'autoclose'=>true,
                        ],
                    ]);
                 ?>
            </div>
            <div class="col-xs-12 col-lg-1 col-sm-1"> 
            <?php $model->mark_type = 0; ?> 
                    <?= $form->field($model, 'mark_type')->checkbox(array(
                        'label'=>'',
                        'name'=>'Transcript',
                        'labelOptions'=>array('style'=>'padding:5px;'),                    
                        ))
                        ->label('Is Transcript'); ?>
                    
            </div>
        </div>
           
                <input type="hidden" value="All" name="section" id='stu_section_select' class='form-control student_disable' />
               
        
        
        <div class="col-xs-12 col-sm-12 col-lg-12">            
           
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Print Consolidate', ['onClick'=>"spinner(); ", 'name'=>'get_marks','class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/consolidate-mark-sheet']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
                    
                
                
        </div>  
    <?php ActiveForm::end(); ?>



</div>
</div>
</div>

<?php
require(Yii::getAlias('@webroot/includes/use_institute_info.php'));
if($org_email=='coe@skasc.ac.in')
{
    if(isset($get_console_list) && !empty($get_console_list))
    {
        if($degree_type=="PG")
        {
            if($layout_type=='A4')
            {
                include_once("consolidate-mark-sheet-pg.php");    
            }
            else
            {
                include_once("consolidate_mark_sheet_pdf_a3.php");
            }
            
        }
        else
        {
            if($layout_type=='A4')
            {
                include_once("consolidate-mark-sheet-pg.php");   
            }
            else
            {
                include_once("arts_consolidate_markstatement.php");
            }
            
        }
        
    }
}
else if($org_email=='coe@skcet.in' && isset($get_console_list) && !empty($get_console_list))
    {
        if($degree_type=="PG")
        {
            if($layout_type=='A4')
            {
                include_once("skcet_consolidate-mark-sheet-pg.php");    
            }
            else
            {
                include_once("skcet_consolidate_mark_sheet_pdf_a3_rejoin.php");
            }
            
        }
        else
        {
            if($layout_type=='A4')
            {
                include_once("skcet_consolidate-mark-sheet-pg.php");   
            }
            else
            {
                include_once("skcet_consolidate_mark_sheet_pdf_a3_rejoin.php");
            }
            
        }
        
    }
else if(isset($get_console_list) && !empty($get_console_list))
    {
        if($degree_type=="PG")
        {
            if($layout_type=='A4')
            {
                include_once("consolidate-mark-sheet-pg.php");    
            }
            else
            {
                include_once("skct_consolidate_mark_sheet_pdf_a3_rejoin.php");
            }
            
        }
        else
        {
            if($layout_type=='A4')
            {
                include_once("consolidate-mark-sheet-pg.php");   
            }
            else
            {
                include_once("skct_consolidate_mark_sheet_pdf_a3_rejoin.php");
            }
            
        }
        
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found');
    }
    ?>