<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Student;
use kartik\date\DatePicker;
use app\models\ExamTimetable;
use yii\web\JsExpression;
use app\components\ConfigConstants;
use app\components\ConfigUtilities;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\dialog\Dialog;
use yii\db\Query;

$url = Url::to(['student/register-numbers']);
$numbers = ArrayHelper::map(Student::find('register_number')->all(), 'register_number', 'register_number');
$register_numbers = empty($student->register_number) ? $numbers : Student::findOne($student->register_number);

$batch_id = isset($_POST['bat_val']) ? $_POST['bat_val'] : '';
$batch_map_id = isset($_POST['bat_map_val']) ? $_POST['bat_map_val'] : '';
$from_reg_no = isset($_POST['from_reg']) ? $_POST['from_reg'] : '';
$to_reg_no = isset($_POST['to_reg']) ? $_POST['to_reg'] : '';

echo Dialog::widget();

/* @var $this yii\web\View */
/* @var $model app\models\MarkEntry */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Activity Points Marksheet";
?>
<h1><?= Html::encode($this->title) ?></h1>
<div>&nbsp;</div>
<div class="mark-entry-form">

     <?php 
        if(empty($stu_data))
        {
    ?>
    <div class="box box-success">
        <div class="box-body"> 
            <div>&nbsp;</div>

            <?php Yii::$app->ShowFlashMessages->showFlashes(); ?>

            <?php
            $form = ActiveForm::begin([
                        'id' => 'mark-entry-form',
                        'fieldConfig' => [
                            'template' => "{label}{input}{error}",
                        ],
            ]);

           ?>

            <div class="row">
                <div class="col-xs-12 col-lg-12 col-sm-12">
                    <div class="col-sm-2">
                        <?php
                        echo $form->field($model, 'batch')->widget(
                                Select2::classname(), [
                            'data' => ConfigUtilities::getBatchDetails(),
                            'options' => [
                                'placeholder' => '-----Select ----',
                                'id' => 'stu_batch_id_selected',
                                'name' => 'bat_val',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]);
                        ?>
                    </div>

                    <div class="col-sm-2">
                        <?php
                        echo $form->field($model, 'programme')->widget(
                                Select2::classname(), [
                            'options' => [
                                'placeholder' => '-----Select ----',
                                'id' => 'stu_programme_selected',
                                'name' => 'bat_map_val',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]);
                        ?>
                    </div>

                   
                    <!-- </div>
                
                    <div class="col-xs-12 col-lg-12 col-sm-12"> -->
                    <div class="col-sm-2">
                        <?php
                        echo $form->field($student, 'register_number_from')->widget(Select2::classname(), [
                            'initValueText' => $register_numbers, // set the initial display text
                            'options' => ['placeholder' => 'Search for a register_number ...', 'name' => 'from_reg', 'value' => $from_reg_no],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:"All"}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                        ]);
                        ?>
                    </div>

                    <div class="col-sm-2">
                        <?php
                        echo $form->field($student, 'register_number_to')->widget(Select2::classname(), [
                            'initValueText' => $register_numbers, // set the initial display text
                            'options' => ['placeholder' => 'Search for a register_number ...', 'name' => 'to_reg', 'id' => 'to_reg', 'value' => $to_reg_no],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $url,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params,programme,section) { return {q:params.term,programme:$("#stu_programme_selected").val(),section:"All"}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(register_number) { return register_number.text; }'),
                                'templateSelection' => new JsExpression('function (register_number) { return register_number.text; }'),
                            ],
                        ]);
                        ?>
                    </div> 

                    <div class="col-xs-12 col-sm-2 col-sm-2">
                        <?php 
                            echo '<label>Print Date</label>';
                            echo DatePicker::widget([
                                'name' => 'activity_print_date',
                                'value'=>date('m/d/Y'),
                                'type' => DatePicker::TYPE_INPUT,
                                'options' => ['placeholder' => 'Print Date ...',],
                                 'pluginOptions' => [
                                    'autoclose'=>true,
                                ],
                            ]);
                         ?>
                    </div>
                
                    <div class="btn-group col-lg-2 col-sm-2" role="group" aria-label="Actions to be Perform">
                        <br/>
                        <?= Html::submitButton('Print', ['name' => 'get_marks', 'class' => 'btn btn-success', 'formtarget'=>'_blank']) ?>
                           <?= Html::a("Reset", Url::toRoute(['coe-activity-marks/activity-marksheet']), ['onClick' => "spinner();", 'class' => 'btn btn-group btn-group-lg btn-warning ']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>


            
        </div>
    </div>

    <?php 
        }
    ?>

    <div class="box box-success">
        <div class="box-body"> 
            <div>&nbsp;</div>
            <div class="row">
                <div class="col-lg-12" >
                    <?php 
                    if(!empty($stu_data))
                    {
                        echo Html::a('<i class="fa fa-file-pdf-o"></i> PDF', ['/coe-activity-marks/activitymarksheetpdf'], [
                            'class' => 'pull-right btn btn-primary',
                            'target' => '_blank',
                            'data-toggle' => 'tooltip',
                            'title' => 'Will open the generated PDF file in a new window'
                        ]);
                        include_once("activity-marksheet-pdf.php");
                    }

                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

