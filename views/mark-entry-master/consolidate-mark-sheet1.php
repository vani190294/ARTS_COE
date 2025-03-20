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

$this->title="CONSOLIDATED MARK SHEET";
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
                            'placeholder' => '-----Select ----',
                            'id' => 'stu_batch_id_selected',
                            'name'=>'bat_val',
                        ],
                       'pluginOptions' => [
                           'allowClear' => true,
                        ],
                    ])->label('Batch'); 
                ?>
            </div>

            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($markEntry, 'stu_programme_id')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'stu_programme_selected',
                            'name'=>'bat_map_val',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ])->label('Programme'); 
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
            <div class="col-xs-12 col-sm-2 col-sm-2">
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
            

            
        </div>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?= $form->field($model, 'year')->textInput(['name'=>'top_margin','placeholder'=>'-5 to push up +5 for to push down'])->label('Top Margin') ?>
            </div>
            <div class="col-xs-12 col-sm-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['placeholder'=>'-5 to push up +5 for to push down','name'=>'bottom_margin'])->label('Bottom Margin') ?>
            </div>

            <div class="col-lg-2 col-sm-2">
           <?= $form->field($model, 'year')->textInput(['name'=>'semester','placeholder'=>''])->label("semester <span style='color: #F00;' >  (OPTIONAL) </span>" ) ?>
             </div>

              <div class="col-lg-2 col-sm-2">
                <?= $form->field($model, 'year')->textInput(['value'=>date('Y'),'id'=>'mark_year','name'=>'year']) ?>
            </div>
           
            <div class="col-lg-2 col-sm-2">
                <?php echo $form->field($model,'month')->widget(
                    Select2::classname(), [
                        'options' => [
                            'placeholder' => '-----Select ----',
                            'id' => 'exam_month',
                            'class'=>'student_disable',
                            'name'=>'month',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
            </div>
            <div class="col-xs-12 col-lg-2 col-sm-2"> 
            <?php $model->mark_type = 0; ?> 
                    <?= $form->field($model, 'mark_type')->checkbox(array(
                        'label'=>'',
                        'name'=>'Transcript',
                        'labelOptions'=>array('style'=>'padding:5px;'),                    
                        ))
                        ->label('Transcript'); ?>
                    
            </div>
            <div class="btn-group col-lg-3 col-sm-3" role="group" aria-label="Actions to be Perform">
                <?= Html::submitButton('Print Consolidate', ['onClick'=>"spinner(); ", 'name'=>'get_marks','class' => 'btn btn-success' ]) ?>
                <?= Html::a("Reset", Url::toRoute(['mark-entry-master/consolidate-mark-sheet']), ['onClick'=>"spinner();",'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
            </div>
        </div>   
                <input type="hidden" value="All" name="section" id='stu_section_select' class='form-control student_disable' />
    <?php ActiveForm::end(); ?>



</div>
</div>
</div>

<?php

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
                include_once("consolidate_mark_sheet_pdf_a3_more_subs.php");
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
                include_once("consolidate_mark_sheet_pdf_a3_more_subs_pp.php");
            }
            
        }
        
    }
    else
    {
        Yii::$app->ShowFlashMessages->setMsg('Error','No Data Found');
    }
    ?>